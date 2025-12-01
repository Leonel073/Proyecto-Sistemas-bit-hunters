<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar Sesión - Nexora Bolivia</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-900 min-h-screen flex items-center justify-center p-4">

  <!-- Background Decoration -->
  <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
      <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-800 via-slate-900 to-black opacity-80"></div>
      <div class="absolute -top-24 -right-24 w-96 h-96 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-blob"></div>
      <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-blob animation-delay-2000"></div>
  </div>

  <div class="w-full max-w-md z-10 relative">
    <!-- Logo & Header -->
    <div class="text-center mb-8">
      <a href="{{ route('home') }}" class="inline-flex flex-col items-center gap-4 group mb-6">
        <img src="{{ asset('images/LogoClaro.png') }}" alt="Nexora" class="h-20 w-20 object-cover rounded-full group-hover:scale-105 transition-transform duration-300 shadow-2xl shadow-indigo-500/20">
        <span class="text-4xl font-bold text-white tracking-tight group-hover:text-indigo-400 transition-colors">Nexora</span>
      </a>
      <h2 class="text-2xl font-bold text-black">Bienvenido de nuevo</h2>
      <p class="text-slate-400 mt-2">Ingresa a tu cuenta para continuar</p>
    </div>

    <!-- Login Card -->
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-slate-700/50">
      <div class="p-8">
        
        <!-- Alerts -->
        @if (session('success'))
          <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center shadow-sm text-sm">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
          </div>
        @endif

        @if ($errors->any())
          <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg shadow-sm text-sm">
            <div class="flex items-center mb-1 font-semibold">
              <i class="fas fa-exclamation-circle mr-2"></i> Error
            </div>
            <ul class="list-disc list-inside ml-1">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
          @csrf

          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-envelope text-gray-400"></i>
              </div>
              <input 
                type="email" 
                id="email" 
                name="email" 
                class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out" 
                placeholder="nombre@ejemplo.com" 
                value="{{ old('email') }}"
                required 
              />
            </div>
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-lock text-gray-400"></i>
              </div>
              <input 
                type="password" 
                id="password" 
                name="password" 
                class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out" 
                placeholder="••••••••" 
                required 
              />
            </div>
          </div>

          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <input id="togglePasswordCheckbox" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded cursor-pointer">
              <label for="togglePasswordCheckbox" class="ml-2 block text-sm text-gray-600 cursor-pointer">
                Mostrar contraseña
              </label>
            </div>
            {{-- <div class="text-sm">
              <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">
                ¿Olvidaste tu contraseña?
              </a>
            </div> --}}
          </div>

          <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform hover:-translate-y-0.5">
            Iniciar Sesión
          </button>
        </form>
      </div>
      
      <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 text-center">
        <p class="text-sm text-gray-600">
          ¿No tienes una cuenta? 
          <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500 hover:underline">
            Regístrate aquí
          </a>
        </p>
      </div>
    </div>
    
    <div class="text-center mt-8">
      <a href="{{ route('home') }}" class="text-slate-400 hover:text-white text-sm font-medium transition-colors">
        <i class="fas fa-arrow-left mr-1"></i> Volver al inicio
      </a>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const toggleCheckbox = document.getElementById('togglePasswordCheckbox');
      const passwordInput = document.getElementById('password');
      
      if (toggleCheckbox && passwordInput) {
        toggleCheckbox.addEventListener('change', () => {
          passwordInput.type = toggleCheckbox.checked ? 'text' : 'password';
        });
      }
    });
  </script>
</body>
</html>