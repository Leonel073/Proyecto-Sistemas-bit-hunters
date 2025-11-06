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

        // Redirección según tipo y rol
        if ($tipo === 'empleado' && $persona->rol === 'Gerente') {
            return redirect()->route('usuarios')->with('success', 'Bienvenido, Gerente.');
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