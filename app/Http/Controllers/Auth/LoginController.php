<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Empleado;
use App\Models\Reclamo;
use App\Models\RegistroAuditoria;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $usuario = Usuario::where('email', $request->email)->first();
        $empleado = Empleado::where('emailCorporativo', $request->email)->first();

        if (! $usuario && ! $empleado) {
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
        if (in_array($persona->estado, ['Bloqueado', 'Eliminado', 'De Baja'])) {
            $mensaje = match ($persona->estado) {
                'Bloqueado' => 'Cuenta bloqueada. Contacte con soporte.',
                'Eliminado', 'De Baja' => 'Cuenta eliminada del sistema.',
            };
            throw ValidationException::withMessages(['email' => $mensaje]);
        }

        if (! Hash::check($request->password, $persona->passwordHash)) {
            $persona->intentosFallidos = ($persona->intentosFallidos ?? 0) + 1;

            if ($persona->intentosFallidos >= 3) {
                $persona->estado = 'Bloqueado';
                $persona->save();
                throw ValidationException::withMessages(['email' => 'Cuenta bloqueada por múltiples intentos fallidos.']);
            }

            $intentosRestantes = 3 - $persona->intentosFallidos;
            $persona->save();
            throw ValidationException::withMessages(['email' => "Contraseña incorrecta. Intentos restantes: {$intentosRestantes}."]);
        }

        $persona->intentosFallidos = 0;
        $persona->save();

        if ($tipo === 'usuario') {
            Auth::guard('web')->login($persona);
        } else {
            Auth::guard('empleado')->login($persona);
        }

        $request->session()->regenerate();

        // --- AUDITORÍA DE INICIO DE SESIÓN ---
        try {
            $auditData = [
                'accion' => 'login',
                'detalleAccion' => 'Inicio de sesión exitoso',
                'ipOrigen' => $request->ip(),
            ];

            if ($tipo === 'empleado') {
                $auditData['idEmpleado'] = $persona->idEmpleado;
            } else {
                $auditData['idUsuario'] = $persona->idUsuario;
            }

            RegistroAuditoria::create($auditData);
        } catch (\Exception $e) {
            Log::error('Error al crear auditoría: '.$e->getMessage());
        }

        if ($tipo === 'empleado') {
            session(['user_role' => $persona->rol]);
        }

        // --- REDIRECCIÓN SEGÚN ROL (CORREGIDO) ---
        if ($tipo === 'empleado') {
            $mensajesBienvenida = [
                'Gerente' => 'Bienvenido, Gerente.',
                'Operador' => 'Bienvenido, Operador.',
                'Tecnico' => 'Bienvenido, Técnico.',
                'SupervisorOperador' => 'Bienvenido, Supervisor de Operadores.',
                'SupervisorTecnico' => 'Bienvenido, Supervisor Técnico.',
            ];
            $mensaje = $mensajesBienvenida[$persona->rol] ?? 'Bienvenido al sistema.';

            switch ($persona->rol) {
                case 'Gerente':
                    // CAMBIO: Ahora apunta al dashboard de gerente
                    return redirect()->route('gerente.dashboard')->with('success', $mensaje);

                case 'SuperAdmin':
                    // CAMBIO: Apunta al dashboard de SuperAdmin
                    return redirect()->route('admin.control')->with('success', 'Bienvenido, Super Administrador.');

                case 'SupervisorOperador':
                    // CAMBIO: Apunta a 'supervisor.operadores.index'
                    return redirect()->route('supervisor.operadores.index')->with('success', $mensaje);

                case 'SupervisorTecnico':
                    // CAMBIO: Apunta a 'supervisor.tecnicos.index'
                    return redirect()->route('supervisor.tecnicos.index')->with('success', $mensaje);

                case 'Tecnico':
                    return redirect()->route('tecnico.dashboard')->with('success', $mensaje);

                case 'Operador':
                    $empleadoId = $persona->idEmpleado;
                    $nuevos = Reclamo::whereNull('idOperador')->where('estado', 'Nuevo')->orderBy('fechaCreacion', 'desc')->get();
                    $misCasos = Reclamo::where('idOperador', $empleadoId)->whereIn('estado', ['Asignado', 'En Proceso'])->orderBy('fechaCreacion', 'desc')->get();

                    return view('operador.panel', compact('nuevos', 'misCasos'))->with('success', $mensaje);

                default:
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
