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
      'resources/css/btns.css',
      'resources/js/login.js'
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
          <div class="password-wrapper relative">
            <input 
              type="password" 
              id="password" 
              name="password" 
              placeholder="••••••••" 
              class="mt-1 w-full pr-10" 
              required 
            />
            <button 
              type="button" 
              class="toggle-password absolute right-3 top-3 text-gray-500 hover:text-gray-700 transition" 
              id="togglePassword"
              onclick="togglePassword('password', 'eyeIcon')"
            >
              <svg 
                id="eyeIcon" 
                xmlns="http://www.w3.org/2000/svg" 
                fill="none" 
                viewBox="0 0 24 24" 
                stroke="currentColor" 
                class="w-5 h-5"
              >
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
            </button>
          </div>
        </div>

        <div class="flex items-center justify-between">
          <label class="flex items-center">
            <input type="checkbox" name="remember" class="h-4 w-4 text-indigo-600 rounded">
            <span class="ml-2 text-sm text-gray-600">Recordarme</span>
          </label>
        </div>

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

  <!-- Script para mostrar/ocultar contraseña -->
  <script>
    function togglePassword(inputId, iconId) {
      const input = document.getElementById(inputId);
      const icon = document.getElementById(iconId);
      if (input.type === "password") {
        input.type = "text";
        icon.setAttribute("stroke", "indigo");
      } else {
        input.type = "password";
        icon.setAttribute("stroke", "currentColor");
      }
    }
  </script>
</body>
</html>