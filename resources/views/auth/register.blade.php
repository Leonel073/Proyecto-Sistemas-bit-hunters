<!DOCTYPE html> 
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro - Nexora Bolivia</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-900 min-h-screen flex items-center justify-center p-4 py-12">

  <!-- Background Decoration -->
  <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
      <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-800 via-slate-900 to-black opacity-80"></div>
      <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-blob"></div>
      <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-blob animation-delay-2000"></div>
  </div>

  <div class="w-full max-w-2xl z-10 relative">
    <!-- Logo & Header -->
    <div class="text-center mb-8">
      <a href="{{ route('home') }}" class="inline-flex flex-col items-center gap-4 group mb-4">
        <img src="{{ asset('images/LogoClaro.png') }}" alt="Nexora" class="h-20 w-20 object-cover rounded-full group-hover:scale-105 transition-transform duration-300 shadow-2xl shadow-indigo-500/20">
        <span class="text-3xl font-bold text-white tracking-tight group-hover:text-indigo-400 transition-colors">Nexora</span>
      </a>
      <h2 class="text-3xl font-bold text-white">Crear una cuenta</h2>
      <p class="text-slate-400 mt-2">Únete a nosotros y gestiona tus servicios fácilmente</p>
    </div>

    <!-- Register Card -->
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-slate-700/50">
      <div class="p-8">
        
        @if (session('success'))
          <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center shadow-sm text-sm">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
          </div>
        @endif

        <form id="registerForm" action="{{ route('register.store') }}" method="POST" class="space-y-5" novalidate>
          @csrf

          <div class="space-y-5">
            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-100 pb-2 mb-4">Información Personal</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              <div>
                <label for="primerNombre" class="block text-sm font-medium text-gray-700 mb-1">Primer Nombre <span class="text-red-500">*</span></label>
                <input type="text" id="primerNombre" name="primerNombre" value="{{ old('primerNombre') }}" placeholder="Juan" 
                       class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors" required 
                       pattern="^[\pL\s\-]+$" title="Solo debe contener letras y espacios." />
                @error('primerNombre') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
              </div>
              <div>
                <label for="segundoNombre" class="block text-sm font-medium text-gray-700 mb-1">Segundo Nombre <span class="text-red-500">*</span></label>
                <input type="text" id="segundoNombre" name="segundoNombre" value="{{ old('segundoNombre') }}" placeholder="Carlos" 
                       class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors" required
                       pattern="^[\pL\s\-]+$" title="Solo debe contener letras y espacios." />
                @error('segundoNombre') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              <div>
                <label for="apellidoPaterno" class="block text-sm font-medium text-gray-700 mb-1">Apellido Paterno <span class="text-red-500">*</span></label>
                <input type="text" id="apellidoPaterno" name="apellidoPaterno" value="{{ old('apellidoPaterno') }}" placeholder="Pérez" 
                       class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors" required 
                       pattern="^[\pL\s\-]+$" title="Solo debe contener letras y espacios." />
                @error('apellidoPaterno') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
              </div>
              <div>
                <label for="apellidoMaterno" class="block text-sm font-medium text-gray-700 mb-1">Apellido Materno <span class="text-red-500">*</span></label>
                <input type="text" id="apellidoMaterno" name="apellidoMaterno" value="{{ old('apellidoMaterno') }}" placeholder="Gómez" 
                       class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors" required
                       pattern="^[\pL\s\-]+$" title="Solo debe contener letras y espacios." />
                @error('apellidoMaterno') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              <div>
                <label for="ci" class="block text-sm font-medium text-gray-700 mb-1">Cédula (CI) <span class="text-red-500">*</span></label>
                <input type="tel" id="ci" name="ci" value="{{ old('ci') }}" placeholder="1234567" 
                       class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors" required 
                       pattern="\d{7,10}" title="Debe tener entre 7 y 10 números." maxlength="10" />
                @error('ci') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
              </div>
              <div>
                <label for="numeroCelular" class="block text-sm font-medium text-gray-700 mb-1">Celular <span class="text-red-500">*</span></label>
                <input type="tel" id="numeroCelular" name="numeroCelular" value="{{ old('numeroCelular') }}" placeholder="70123456" 
                       class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors" required 
                       pattern="\d{8,}" title="Debe ser un número válido (ej: 70123456)." maxlength="15" />
                @error('numeroCelular') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
              </div>
            </div>
          </div>

          <div class="space-y-5 pt-2">
            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-100 pb-2 mb-4">Información de Contacto</h3>

            <div>
              <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico <span class="text-red-500">*</span></label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <i class="fas fa-envelope text-gray-400"></i>
                </div>
                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="tu@email.com" 
                       class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors" required 
                       title="Debe ser un correo electrónico válido." />
              </div>
              @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
              <label for="direccionTexto" class="block text-sm font-medium text-gray-700 mb-1">Dirección <span class="text-red-500">*</span></label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <i class="fas fa-map-marker-alt text-gray-400"></i>
                </div>
                <input type="text" id="direccionTexto" name="direccionTexto" value="{{ old('direccionTexto') }}" placeholder="Av. Principal #123, Zona Central" 
                       class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors" required />
              </div>
              @error('direccionTexto') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
          </div>

          <div class="space-y-5 pt-2">
            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-100 pb-2 mb-4">Seguridad</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña <span class="text-red-500">*</span></label>
                <input type="password" id="password" name="password" placeholder="••••••••" 
                       class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors" required 
                       minlength="8" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$"
                       title="Debe tener al menos 8 caracteres, incluir mayúsculas, minúsculas, números y un símbolo especial." />
                <p class="text-xs text-gray-500 mt-1">Mín. 8 caracteres, mayús, minús, números y símbolos.</p>
                @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
              </div>

              <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmar Contraseña <span class="text-red-500">*</span></label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" 
                       class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors" required 
                       minlength="8" />
              </div>
            </div>

            <div class="flex items-center">
                <input id="togglePasswordCheckbox" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded cursor-pointer">
                <label for="togglePasswordCheckbox" class="ml-2 block text-sm text-gray-600 cursor-pointer">
                  Mostrar contraseñas
                </label>
            </div>
          </div>

          <div class="pt-4">
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform hover:-translate-y-0.5">
              Crear Cuenta
            </button>
          </div>
        </form>
      </div>
      
      <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 text-center">
        <p class="text-sm text-gray-600">
          ¿Ya tienes una cuenta?
          <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500 hover:underline">
            Inicia sesión aquí
          </a>
        </p>
      </div>
    </div>
    
    <div class="text-center mt-8 pb-8">
      <a href="{{ route('home') }}" class="text-slate-400 hover:text-white text-sm font-medium transition-colors">
        <i class="fas fa-arrow-left mr-1"></i> Volver al inicio
      </a>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const toggleCheckbox = document.getElementById('togglePasswordCheckbox');
      const passwordInput = document.getElementById('password');
      const passwordConfirmInput = document.getElementById('password_confirmation');
      
      if (toggleCheckbox) {
        toggleCheckbox.addEventListener('change', () => {
          const type = toggleCheckbox.checked ? 'text' : 'password';
          if (passwordInput) passwordInput.type = type;
          if (passwordConfirmInput) passwordConfirmInput.type = type;
        });
      }
    });
  </script>
</body>
</html>