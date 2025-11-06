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

      <!-- ALERTAS -->
      @if (session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
      @endif

      @if ($errors->any())
        <div class="alert alert-error">
          <ul class="list-disc pl-5 text-sm">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form id="registerForm" action="{{ route('register.store') }}" method="POST" class="space-y-5">
        @csrf

        <!-- NOMBRE Y APELLIDO -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="input-group">
            <label for="primerNombre" class="block text-sm font-medium text-gray-700">Primer Nombre *</label>
            <input type="text" id="primerNombre" name="primerNombre" value="{{ old('primerNombre') }}" placeholder="Juan" class="mt-1 w-full" required />
          </div>
          <div class="input-group">
            <label for="apellidoPaterno" class="block text-sm font-medium text-gray-700">Apellido Paterno *</label>
            <input type="text" id="apellidoPaterno" name="apellidoPaterno" value="{{ old('apellidoPaterno') }}" placeholder="Pérez" class="mt-1 w-full" required />
          </div>
        </div>

        <!-- SEGUNDO NOMBRE Y APELLIDO MATERNO -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="input-group">
            <label for="segundoNombre" class="block text-sm font-medium text-gray-700">Segundo Nombre</label>
            <input type="text" id="segundoNombre" name="segundoNombre" value="{{ old('segundoNombre') }}" placeholder="Carlos" class="mt-1 w-full" />
          </div>
          <div class="input-group">
            <label for="apellidoMaterno" class="block text-sm font-medium text-gray-700">Apellido Materno</label>
            <input type="text" id="apellidoMaterno" name="apellidoMaterno" value="{{ old('apellidoMaterno') }}" placeholder="Gómez" class="mt-1 w-full" />
          </div>
        </div>

        <!-- CI Y CELULAR -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="input-group">
            <label for="ci" class="block text-sm font-medium text-gray-700">Cédula (CI) *</label>
            <input type="text" id="ci" name="ci" value="{{ old('ci') }}" placeholder="1234567 LP" class="mt-1 w-full" required />
          </div>
          <div class="input-group">
            <label for="numeroCelular" class="block text-sm font-medium text-gray-700">Celular *</label>
            <input type="text" id="numeroCelular" name="numeroCelular" value="{{ old('numeroCelular') }}" placeholder="70123456" class="mt-1 w-full" required />
          </div>
        </div>

        <!-- EMAIL -->
        <div class="input-group">
          <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
          <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="tu@email.com" class="mt-1 w-full" />
        </div>

        <!-- DIRECCIÓN -->
        <div class="input-group">
          <label for="direccionTexto" class="block text-sm font-medium text-gray-700">Dirección (opcional)</label>
          <input type="text" id="direccionTexto" name="direccionTexto" value="{{ old('direccionTexto') }}" placeholder="Calle, zona..." class="mt-1 w-full" />
        </div>

        <!-- CONTRASEÑAS -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="input-group">
            <label for="password" class="block text-sm font-medium text-gray-700">Contraseña *</label>
            <div class="password-wrapper relative">
              <input 
                type="password" 
                id="password" 
                name="password" 
                placeholder="••••••••" 
                class="mt-1 w-full pr-10" 
                required 
                minlength="8"
                pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$"
                title="Debe tener al menos 8 caracteres, incluir mayúsculas, minúsculas, números y un símbolo especial."
              />
              <button type="button" class="toggle-pass absolute right-3 top-3" onclick="togglePassword('password')">
                <svg id="eye-password" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5 text-gray-500">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
              </button>
            </div>
            <p class="text-xs text-gray-500 mt-1">
              La contraseña debe tener mínimo 8 caracteres, incluir mayúsculas, minúsculas, números y símbolos especiales.
            </p>
          </div>

          <div class="input-group">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Contraseña *</label>
            <div class="password-wrapper relative">
              <input 
                type="password" 
                id="password_confirmation" 
                name="password_confirmation" 
                placeholder="••••••••" 
                class="mt-1 w-full pr-10" 
                required 
                minlength="8"
              />
              <button type="button" class="toggle-pass absolute right-3 top-3" onclick="togglePassword('password_confirmation')">
                <svg id="eye-password_confirmation" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5 text-gray-500">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
              </button>
            </div>
          </div>
        </div>

        <!-- BOTÓN ENVIAR -->
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