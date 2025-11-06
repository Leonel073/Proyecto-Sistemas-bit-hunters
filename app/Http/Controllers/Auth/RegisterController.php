<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Muestra el formulario de registro
     */
    public function show()
    {
        // Vista Blade del formulario de registro (usa tu vista actual)
        return view('sign_up');
    }

    /**
     * Procesa y guarda el registro del usuario
     */
    public function store(Request $request)
    {
        // ✅ Validación de campos con políticas de contraseña seguras
        $request->validate([
            'primerNombre' => 'required|string|max:100',
            'segundoNombre' => 'nullable|string|max:100',
            'apellidoPaterno' => 'required|string|max:100',
            'apellidoMaterno' => 'nullable|string|max:100',
            'ci' => 'required|string|max:20|unique:usuarios,ci',
            'numeroCelular' => 'required|string|max:20|unique:usuarios,numeroCelular',
            'email' => 'nullable|email|max:255|unique:usuarios,email',
            'direccionTexto' => 'nullable|string|max:255',

            // 🔒 Políticas de seguridad para contraseñas:
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()   // Mayúsculas y minúsculas
                    ->numbers()     // Números
                    ->symbols()     // Caracteres especiales
            ],
        ], [
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.mixedCase' => 'Debe incluir letras mayúsculas y minúsculas.',
            'password.numbers' => 'Debe incluir al menos un número.',
            'password.symbols' => 'Debe incluir al menos un carácter especial.',
        ]);

        // ✅ Crear el nuevo usuario
        $usuario = Usuario::create([
            'primerNombre' => $request->primerNombre,
            'segundoNombre' => $request->segundoNombre,
            'apellidoPaterno' => $request->apellidoPaterno,
            'apellidoMaterno' => $request->apellidoMaterno,
            'ci' => $request->ci,
            'numeroCelular' => $request->numeroCelular,
            'email' => $request->email,
            'passwordHash' => Hash::make($request->password), // 🔐 Cifrado seguro con bcrypt
            'direccionTexto' => $request->direccionTexto,
            'estado' => 'Activo',
        ]);

        // ✅ Redirección con mensaje de éxito
        return redirect()
            ->route('login')
            ->with('success', '¡Registro exitoso! Tu contraseña fue cifrada de forma segura.');
    }
}