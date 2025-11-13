<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar Sesión - Nexora Bolivia</title>

  <!-- VITE: Carga CSS + JS -->
  @vite([
    'resources/css/app.css',
    'resources/css/login.css',
    'resources/css/btns.css'
  ])

  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gray-50">

  <!-- Botón Volver -->
  <a href="{{ route('home') }}" class="btn-top-left">
    ← Volver al Sitio
  </a>

  <!-- CARD DE LOGIN -->
  <div class="min-h-screen flex items-center justify-center px-4">
    <div class="card max-w-md w-full">
      <div class="card-header text-center">
        <div class="icon-container">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 3h4a2 2 0 0 1 2 2v4m0 0L10 21l-7-7L17 5z" />
          </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900">Iniciar Sesión</h2>
        <p class="text-gray-600">Ingresa tus credenciales para acceder a Nexora Bolivia</p>
      </div>

      <!-- ALERTA DE ÉXITO -->
      @if (session('success'))
        <div class="alert alert-success text-green-700 bg-green-100 border border-green-300 rounded p-2 mb-3">
          {{ session('success') }}
        </div>
      @endif

      <!-- ALERTA DE ERROR -->
      @if ($errors->any())
        <div class="alert alert-error text-red-700 bg-red-100 border border-red-300 rounded p-2 mb-3">
          <ul class="list-disc pl-5 text-sm">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <!-- FORMULARIO -->
      <form id="loginForm" action="{{ route('login.post') }}" method="POST" class="space-y-5">
        @csrf

        <div class="form-group">
          <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
          <input 
            type="email" 
            id="email" 
            name="email" 
            placeholder="tu@email.com" 
            value="{{ old('email') }}"
            class="mt-1 w-full" 
            required 
          />
        </div>

        <div class="form-group">
          <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
          <!-- ⛔️ CAMBIO: Se eliminó el div.password-wrapper y el botón del "ojito" ⛔️ -->
          <input 
            type="password" 
            id="password" 
            name="password" 
            placeholder="••••••••" 
            class="mt-1 w-full" 
            required 
          />
        </div>

        <!-- ✅ CAMBIO: Checkbox para mostrar contraseña (como en sign_up) ✅ -->
        <div class="flex items-center">
            <input id="togglePasswordCheckbox" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
            <label for="togglePasswordCheckbox" class="ml-2 block text-sm text-gray-900">
              Mostrar contraseña
            </label>
        </div>

        {{-- <div class="flex items-center justify-between">
          <label class="flex items-center">
            <input type="checkbox" name="remember" class="h-4 w-4 text-indigo-600 rounded">
            <span class="ml-2 text-sm text-gray-600">Recordarme</span>
          </label>
        </div> --}}

        <button type="submit" class="btn w-full" id="submitBtn">
          Iniciar Sesión
        </button>
      </form>

      <div class="card-footer text-center mt-6">
        <span class="text-gray-600">¿No tienes una cuenta?</span>
        <a href="{{ route('register') }}" class="text font-medium text-indigo-600 hover:text-indigo-500">
          Regístrate aquí
        </a>
      </div>
    </div>
  </div>

  <!-- ✅ CAMBIO: Nuevo script para el checkbox ✅ -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const toggleCheckbox = document.getElementById('togglePasswordCheckbox');
      const passwordInput = document.getElementById('password');
      
      if (toggleCheckbox && passwordInput) {
        toggleCheckbox.addEventListener('change', () => {
          // Si el checkbox está marcado, muestra el texto
          if (toggleCheckbox.checked) {
            passwordInput.type = 'text';
          } else {
            // Si no, lo oculta
            passwordInput.type = 'password';
          }
        });
      }
    });
  </script>
</body>
</html>