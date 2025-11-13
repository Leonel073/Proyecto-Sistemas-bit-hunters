<!DOCTYPE html> 
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro - Nexora Bolivia</title>

  @vite([
    'resources/css/app.css',
    'resources/css/register.css',
    'resources/css/btns.css',
    'resources/js/register.js'
  ])

  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

  <style>
    .error-message {
      color: #DC2626; /* Este es el color 'red-600' de Tailwind */
      font-size: 0.75rem; /* text-xs */
      margin-top: 0.25rem; /* mt-1 */
      font-weight: 500; /* font-medium */
    }
  </style>
</head>
<body class="bg-gray-50">

  <a href="{{ route('home') }}" class="btn-top-left">
    ← Volver al Sitio
  </a>

  <div class="register-container min-h-screen flex items-center justify-center px-4 py-8">
    <div class="card max-w-2xl w-full bg-white rounded-xl shadow-lg p-6 md:p-8">

      <div class="card-header text-center mb-6">
        <div class="icon inline-flex items-center justify-center w-16 h-16 bg-linear-to-br from-indigo-500 to-purple-600 text-white rounded-full text-3xl font-bold">
          +
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mt-4">Crear Cuenta</h2>
        <p class="text-gray-600">Regístrate en Nexora Bolivia</p>
      </div>

      @if (session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
      @endif

      <form id="registerForm" action="{{ route('register.store') }}" method="POST" class="space-y-5" novalidate>
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="input-group">
            <label for="primerNombre" class="block text-sm font-medium text-gray-700">Primer Nombre *</label>
            <input type="text" id="primerNombre" name="primerNombre" value="{{ old('primerNombre') }}" placeholder="Juan" class="mt-1 w-full" required 
                   pattern="^[\pL\s\-]+$" title="Solo debe contener letras y espacios." />
            <span id="primerNombre-error" class="error-message empty:hidden"></span>
            @error('primerNombre')
              <span class="error-message">{{ $message }}</span>
            @enderror
          </div>
          <div class="input-group">
            <label for="apellidoPaterno" class="block text-sm font-medium text-gray-700">Apellido Paterno *</label>
            <input type="text" id="apellidoPaterno" name="apellidoPaterno" value="{{ old('apellidoPaterno') }}" placeholder="Pérez" class="mt-1 w-full" required 
                   pattern="^[\pL\s\-]+$" title="Solo debe contener letras y espacios." />
            <span id="apellidoPaterno-error" class="error-message empty:hidden"></span>
            @error('apellidoPaterno')
              <span class="error-message">{{ $message }}</span>
            @enderror
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="input-group">
            <label for="segundoNombre" class="block text-sm font-medium text-gray-700">Segundo Nombre *</label>
            <input type="text" id="segundoNombre" name="segundoNombre" value="{{ old('segundoNombre') }}" placeholder="Carlos" class="mt-1 w-full" required
                   pattern="^[\pL\s\-]+$" title="Solo debe contener letras y espacios." />
            <span id="segundoNombre-error" class="error-message empty:hidden"></span>
            @error('segundoNombre')
              <span class="error-message">{{ $message }}</span>
            @enderror
          </div>
          <div class="input-group">
            <label for="apellidoMaterno" class="block text-sm font-medium text-gray-700">Apellido Materno *</label>
            <input type="text" id="apellidoMaterno" name="apellidoMaterno" value="{{ old('apellidoMaterno') }}" placeholder="Gómez" class="mt-1 w-full" required
                   pattern="^[\pL\s\-]+$" title="Solo debe contener letras y espacios." />
            <span id="apellidoMaterno-error" class="error-message empty:hidden"></span>
            @error('apellidoMaterno')
              <span class="error-message">{{ $message }}</span>
            @enderror
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="input-group">
            <label for="ci" class="block text-sm font-medium text-gray-700">Cédula (CI) *</label>
            <input type="tel" id="ci" name="ci" value="{{ old('ci') }}" placeholder="1234567" class="mt-1 w-full" required 
                   pattern="\d{7,10}" title="Debe tener entre 7 y 10 números." maxlength="10" />
            <span id="ci-error" class="error-message empty:hidden"></span>
            @error('ci')
              <span class="error-message">{{ $message }}</span>
            @enderror
          </div>
          <div class="input-group">
            <label for="numeroCelular" class="block text-sm font-medium text-gray-700">Celular *</label>
            <input type="tel" id="numeroCelular" name="numeroCelular" value="{{ old('numeroCelular') }}" placeholder="70123456" class="mt-1 w-full" required 
                   pattern="\d{8,}" title="Debe ser un número válido (ej: 70123456)." maxlength="15" />
            <span id="numeroCelular-error" class="error-message empty:hidden"></span>
            @error('numeroCelular')
              <span class="error-message">{{ $message }}</span>
            @enderror
          </div>
        </div>

        <div class="input-group">
          <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico *</label>
          <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="tu@email.com" class="mt-1 w-full" required 
                 title="Debe ser un correo electrónico válido." />
          <span id="email-error" class="error-message empty:hidden"></span>
          
          @error('email')
            <span class="error-message">{{ $message }}</span>
          @enderror
        </div>

        <div class="input-group">
          <label for="direccionTexto" class="block text-sm font-medium text-gray-700">Dirección *</label>
          <input type="text" id="direccionTexto" name="direccionTexto" value="{{ old('direccionTexto') }}" placeholder="Calle, zona..." class="mt-1 w-full" required />
          <span id="direccionTexto-error" class="error-message empty:hidden"></span>
          @error('direccionTexto')
            <span class="error-message">{{ $message }}</span>
          @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="input-group">
            <label for="password" class="block text-sm font-medium text-gray-700">Contraseña *</label>
            <input 
              type="password" 
              id="password" 
              name="password" 
              placeholder="••••••••" 
              class="mt-1 w-full" 
              required 
              minlength="8"
              pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$"
              title="Debe tener al menos 8 caracteres, incluir mayúsculas, minúsculas, números y un símbolo especial."
            />
            <p id="password-hint" class="text-xs text-gray-500 mt-1">
              Mínimo 8 caracteres, con mayúsculas, minúsculas, números y símbolos.
            </p> <span id="password-error" class="error-message empty:hidden"></span>
            @error('password')
              <span class="error-message">{{ $message }}</span>
            @enderror
          </div>

          <div class="input-group">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Contraseña *</label>
            <input 
              type="password" 
              id="password_confirmation" 
              name="password_confirmation" 
              placeholder="••••••••" 
              class="mt-1 w-full" 
              required 
              minlength="8"
            />
            <span id="password_confirmation-error" class="error-message empty:hidden"></span>
          </div>
        </div>

        <div class="flex items-center">
            <input id="togglePasswordCheckbox" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
            <label for="togglePasswordCheckbox" class="ml-2 block text-sm text-gray-900">
              Mostrar contraseñas
            </label>
        </div>


        <button type="submit" class="submit-btn w-full bg-linear-to-r from-indigo-600 to-purple-700 text-white font-semibold py-3 rounded-lg hover:from-indigo-700 hover:to-purple-800 transition">
          Crear Cuenta
        </button>

        <p class="switch-text text-center text-gray-600 mt-6">
          ¿Ya tienes una cuenta?
          <a href="{{ route('login') }}" class="text font-medium text-indigo-600 hover:text-indigo-500">
            Inicia sesión aquí
          </a>
        </p>
      </form>
    </div>
  </div>
</body>
</html>