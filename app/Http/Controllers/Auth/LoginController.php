<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\Usuario;
use App\Models\Empleado;

class LoginController extends Controller
{
    public function show()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Buscar en usuarios y empleados
        $usuario = Usuario::where('email', $request->email)->first();
        $empleado = Empleado::where('emailCorporativo', $request->email)->first();

        if (!$usuario && !$empleado) {
            throw ValidationException::withMessages(['email' => 'Credenciales incorrectas.']);
        }

        if ($usuario) {
            return $this->verificarCredenciales($usuario, $request, 'usuario');
        }

        if ($empleado) {
            return $this->verificarCredenciales($empleado, $request, 'empleado');
        }
    }

    private function verificarCredenciales($persona, Request $request, $tipo)
    {
        // Verificar estado
        if (in_array($persona->estado, ['Suspendido', 'Eliminado', 'De Baja'])) {
            $mensaje = match($persona->estado) {
                'Suspendido' => 'Cuenta bloqueada. Contacte con soporte.',
                'Eliminado', 'De Baja' => 'Cuenta eliminada del sistema.',
            };
            throw ValidationException::withMessages(['email' => $mensaje]);
        }

        // Verificar contraseña usando bcrypt
        if (!Hash::check($request->password, $persona->passwordHash)) {
            // Incrementar intentos fallidos
            $persona->intentosFallidos = ($persona->intentosFallidos ?? 0) + 1;

            if ($persona->intentosFallidos >= 3) {
                $persona->estado = 'Suspendido';
                $persona->save();
                throw ValidationException::withMessages([
                    'email' => 'Cuenta bloqueada por múltiples intentos fallidos.'
                ]);
            }

            $intentosRestantes = 3 - $persona->intentosFallidos;
            $persona->save();

            throw ValidationException::withMessages([
                'email' => "Contraseña incorrecta. Intentos restantes: {$intentosRestantes}."
            ]);
        }

        // Contraseña correcta: reiniciar intentos
        $persona->intentosFallidos = 0;
        $persona->save();

        // Login y sesión
        if ($tipo === 'usuario') {
            Auth::login($persona); // Usuarios normales
        } else {
            // Empleados: usamos guard "web" también para mantener sesión
            // ¡OJO! Tu código original tenía un error lógico aquí.
            // Si usas Auth::loginUsingId(), Laravel no sabe qué "guard" usar
            // y puede no autenticar al Empleado correctamente.
            // Es más seguro usar Auth::login() para ambos, ya que son "Authenticatable"
            Auth::login($persona); 
        }

        $request->session()->regenerate();

        // ================================================================
        // ¡¡AQUÍ ESTÁ LA LÓGICA DE REDIRECCIÓN CORREGIDA Y MEJORADA!!
        // ================================================================

        if ($tipo === 'empleado') {
            // Es un empleado, redirigir según su ROL
            
            // ¡¡AÑADE ESTA LÍNEA!!
            // Guardamos el rol en la sesión para que el Middleware lo pueda leer.
            session(['user_role' => $persona->rol]);
            
            if ($persona->rol === 'Gerente') {
                // El Gerente va al panel de admin
                return redirect()->route('admin.empleados.index')->with('success', 'Bienvenido, Gerente.');
            
            } elseif ($persona->rol === 'Tecnico') {
                // El Técnico va a SU dashboard
                return redirect()->route('tecnico.dashboard')->with('success', 'Bienvenido a tu panel.');
            
            } else {
                // Otros roles (Operador, Supervisor) que aún no tienen panel
                // van al 'home' por ahora.
                return redirect()->route('home')->with('success', 'Bienvenido, ' . $persona->primerNombre);
            }

        } else {
            // Es un 'usuario' (Cliente)
            // Lo redirigimos al formulario para crear un reclamo.
            return redirect()->route('formulario')->with('success', 'Bienvenido, ' . $persona->primerNombre);
        }
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
