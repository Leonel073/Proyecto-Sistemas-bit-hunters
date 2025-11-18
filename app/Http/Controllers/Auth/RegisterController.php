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
        // ✅ Validación de campos actualizada
        $request->validate([
            // Nombres y Apellidos: Solo letras, espacios, y acentos.
            // La regex \pL permite letras con acentos. \s permite espacios.
            'primerNombre' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\-]+$/u'],
            'segundoNombre' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\-]+$/u'],
            'apellidoPaterno' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\-]+$/u'],
            'apellidoMaterno' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\-]+$/u'],
            
            // CI: Requerido, numérico, entre 7 y 10 dígitos, y único.
            'ci' => 'required|numeric|digits_between:7,10|unique:usuarios,ci',
            
            // Celular: Requerido, numérico, y único.
            'numeroCelular' => 'required|numeric|unique:usuarios,numeroCelular',
            
            // Email: Requerido, formato email, y único.
            'email' => 'required|email|max:255|unique:usuarios,email',
            
            // Dirección: Requerida (eliminamos 'nullable').
            'direccionTexto' => 'required|string|max:255',

            // 🔒 Políticas de seguridad para contraseñas:
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()   // Mayúsculas y minúsculas
                    ->numbers()     // Números
                    ->symbols(),    // Caracteres especiales
            ],
        ], [
            // --- Mensajes Personalizados (Ahora 100% en Español) ---

            // Nombres y Apellidos
            'primerNombre.required' => 'El primer nombre es obligatorio.',
            'primerNombre.regex' => 'El primer nombre solo debe contener letras.',
            'segundoNombre.required' => 'El segundo nombre es obligatorio.',
            'segundoNombre.regex' => 'El segundo nombre solo debe contener letras.',
            'apellidoPaterno.required' => 'El apellido paterno es obligatorio.',
            'apellidoPaterno.regex' => 'El apellido paterno solo debe contener letras.',
            'apellidoMaterno.required' => 'El apellido materno es obligatorio.',
            'apellidoMaterno.regex' => 'El apellido materno solo debe contener letras.',

            // CI
            'ci.required' => 'El campo CI es obligatorio.',
            'ci.numeric' => 'El CI debe contener solo números.',
            'ci.digits_between' => 'El CI debe tener entre 7 y 10 dígitos.',
            'ci.unique' => 'Este número de CI ya está registrado.',

            // Celular
            'numeroCelular.required' => 'El número de celular es obligatorio.',
            'numeroCelular.numeric' => 'El celular debe contener solo números.',
            'numeroCelular.unique' => 'Este número de celular ya está registrado.',

            // Email
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debe ingresar un formato de correo válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',

            // Dirección
            'direccionTexto.required' => 'El campo dirección es obligatorio.',

            // Contraseña
            'password.required' => 'La contraseña es obligatoria.',
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
            ->with('success', '¡Registro exitoso! Ya puedes iniciar sesión.');
    }
}