<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Nexora Bolivia')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Leaflet CSS (solo si se necesita) -->
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans antialiased flex flex-col min-h-screen">

    <!-- Navbar Cliente (Dark Blue Theme) -->
    <nav class="bg-slate-900 shadow-lg border-b border-slate-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <!-- Logo & Links -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center gap-3 group mr-8">
                        <img src="{{ asset('images/LogoClaro.png') }}" alt="Nexora" class="h-11 w-11 object-cover rounded-full group-hover:scale-105 transition-transform">
                        <span class="text-xl font-bold text-white tracking-tight group-hover:text-indigo-400 transition-colors">Nexora</span>
                    </a>
                    <div class="hidden md:flex space-x-6">
                        <a href="{{ route('home') }}" class="text-slate-300 hover:text-white font-medium transition-colors text-sm uppercase tracking-wider {{ request()->routeIs('home') ? 'text-white' : '' }}">
                            Inicio
                        </a>
                        <a href="{{ route('formulario') }}" class="text-slate-300 hover:text-white font-medium transition-colors text-sm uppercase tracking-wider {{ request()->routeIs('formulario') ? 'text-white' : '' }}">
                            Nuevo Reclamo
                        </a>
                        <a href="{{ route('seguimiento') }}" class="text-slate-300 hover:text-white font-medium transition-colors text-sm uppercase tracking-wider {{ request()->routeIs('seguimiento') ? 'text-white' : '' }}">
                            Mis Reclamos
                        </a>
                        <a href="{{ route('notificaciones.index') }}" class="text-slate-300 hover:text-white font-medium transition-colors text-sm uppercase tracking-wider {{ request()->routeIs('notificaciones.index') ? 'text-white' : '' }}">
                            Notificaciones
                        </a>
                    </div>
                </div>

                <!-- Right Side Actions -->
                <div class="flex items-center">
                    <div class="flex-shrink-0 mr-4">
                        <a href="{{ route('formulario') }}" class="relative inline-flex items-center px-4 py-2 border border-transparent text-sm font-bold rounded-full text-white bg-indigo-600 shadow-md hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform hover:scale-105">
                            <i class="fas fa-plus mr-2"></i> CREAR RECLAMO
                        </a>
                    </div>
                    
                    @auth
                        <div class="ml-3 relative" x-data="{ open: false }">
                            <div>
                                <button @click="open = !open" @click.away="open = false" class="max-w-xs bg-slate-800 rounded-full flex items-center text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-slate-900 focus:ring-indigo-500 p-1 pr-3 transition-colors hover:bg-slate-700" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                    <span class="sr-only">Abrir menú de usuario</span>
                                    <div class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold mr-2">
                                        {{ substr(Auth::user()->primerNombre, 0, 1) }}
                                    </div>
                                    <span class="text-slate-200 font-medium hidden md:block">{{ Auth::user()->primerNombre }}</span>
                                    <i class="fas fa-chevron-down text-xs text-slate-400 ml-2"></i>
                                </button>
                            </div>

                            <!-- Dropdown menu -->
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1" style="display: none;">
                                
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="text-sm text-gray-500">Conectado como</p>
                                    <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->email }}</p>
                                </div>

                                <!-- Opciones de Empleado -->
                                @if(auth('empleado')->check())
                                    <a href="{{ route('admin.control') }}" class="block px-4 py-2 text-sm text-indigo-600 hover:bg-indigo-50 font-bold border-b border-slate-100">
                                        <i class="fas fa-tachometer-alt mr-2"></i> Panel de Control
                                    </a>
                                @endif

                                <a href="{{ route('perfil.editar') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">
                                    <i class="fas fa-user-edit mr-2 text-slate-400"></i> Editar Perfil
                                </a>
                                
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-rose-600 hover:bg-rose-50 font-medium">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="ml-4">
                            <a href="{{ route('login') }}" class="text-slate-300 hover:text-white font-medium text-sm uppercase tracking-wider">Iniciar Sesión</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div class="md:hidden border-t border-slate-800 bg-slate-900">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-300 hover:text-white hover:bg-slate-800">INICIO</a>
                <a href="{{ route('formulario') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-300 hover:text-white hover:bg-slate-800">NUEVO RECLAMO</a>
                <a href="{{ route('seguimiento') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-300 hover:text-white hover:bg-slate-800">MIS RECLAMOS</a>
                <a href="{{ route('notificaciones.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-300 hover:text-white hover:bg-slate-800">NOTIFICACIONES</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} Nexora Bolivia. Todos los derechos reservados.
            </p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
