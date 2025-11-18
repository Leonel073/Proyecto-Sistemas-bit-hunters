<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Models\Usuario;
use App\Models\Empleado;
use App\Models\RegistroAuditoria;
// Asegúrate de tener estos modelos si los usas en la redirección de Operador
use App\Models\Reclamo; 

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
        if (in_array($persona->estado, ['Bloqueado', 'Eliminado', 'De Baja'])) {
            $mensaje = match($persona->estado) {
                'Bloqueado' => 'Cuenta bloqueada. Contacte con soporte.',
                'Eliminado', 'De Baja' => 'Cuenta eliminada del sistema.',
            };
            throw ValidationException::withMessages(['email' => $mensaje]);
        }

        // Verificar contraseña usando bcrypt
        if (!Hash::check($request->password, $persona->passwordHash)) {
            // Incrementar intentos fallidos
            $persona->intentosFallidos = ($persona->intentosFallidos ?? 0) + 1;

            if ($persona->intentosFallidos >= 3) {
                $persona->estado = 'Bloqueado';
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

        if ($tipo === 'usuario') {
            Auth::guard('web')->login($persona);
        } else {
            Auth::guard('empleado')->login($persona);
        }

        $request->session()->regenerate();

        // Guardar el rol en sesión para el middleware de roles
        if ($tipo === 'empleado') {
            session(['user_role' => $persona->rol]);
        }

        // Registrar auditoría para empleados
        if ($tipo === 'empleado') {
            try {
                RegistroAuditoria::create([
                    'idEmpleado' => $persona->idEmpleado,
                    'accion' => 'login',
                    'detalleAccion' => 'Inicio de sesión exitoso',
                    'ipOrigen' => $request->ip(),
                ]);
            } catch (\Exception $e) {
                Log::error('Error al crear registro de auditoría: '.$e->getMessage());
            }
        }
        
        // Redirección según tipo y rol
        if ($tipo === 'empleado') {
            $mensajesBienvenida = [
                'Gerente' => 'Bienvenido, Gerente.',
                'Operador' => 'Bienvenido, Operador.',
                'Tecnico' => 'Bienvenido, Técnico.',
                'SupervisorOperador' => 'Bienvenido, Supervisor de Operadores.',
                'SupervisorTecnico' => 'Bienvenido, Supervisor Técnico.',
            ];

            $mensaje = $mensajesBienvenida[$persona->rol] ?? 'Bienvenido al sistema.';
            
            Log::info('Login de empleado', ['rol' => $persona->rol, 'email' => $persona->emailCorporativo, 'id' => $persona->idEmpleado]);

            // --- ✅ BLOQUE DE REDIRECCIÓN ACTUALIZADO ✅ ---
            if ($persona->rol === 'Operador') {
                $empleadoId = $persona->idEmpleado;
                $nuevos = Reclamo::whereNull('idOperador')
                    ->where('estado', 'Nuevo')
                    ->orderBy('fechaCreacion', 'desc')
                    ->get();
                $misCasos = Reclamo::where('idOperador', $empleadoId)
                    ->whereIn('estado', ['Asignado', 'En Proceso'])
                    ->orderBy('fechaCreacion', 'desc')
                    ->get();
                
                return view('operador.panel', compact('nuevos', 'misCasos'))->with('success', $mensaje);
            
            } elseif ($persona->rol === 'Gerente') {
                return redirect()->route('usuarios')->with('success', $mensaje);
            
            } elseif ($persona->rol === 'Tecnico') {
                return redirect('/tecnico/dashboard')->with('success', $mensaje);
            
            // --- ✅ LÓGICA AÑADIDA AQUÍ ✅ ---
            } elseif ($persona->rol === 'SupervisorOperador') {
                // Redirige a la ruta del panel de operadores
                return redirect()->route('supervisor.operadores.index')->with('success', $mensaje);
                
            } elseif ($persona->rol === 'SupervisorTecnico') {
                // Redirige a la ruta del panel de técnicos
                return redirect()->route('supervisor.tecnicos.index')->with('success', $mensaje);
                
            } else {
                return redirect()->route('home')->with('success', $mensaje);
            }
        }

        return redirect()->route('home')->with('success', 'Bienvenido al sistema.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}