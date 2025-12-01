<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Nexora Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="antialiased bg-gray-50 text-gray-800 font-sans">

    <div class="min-h-screen flex flex-col md:flex-row">
        
        <!-- SIDEBAR -->
        <aside class="w-full md:w-64 glass-sidebar flex-shrink-0 md:fixed md:h-full z-30 flex flex-col transition-all duration-300">
            <!-- Logo -->
            <div class="h-16 flex items-center justify-center border-b border-slate-800 px-6">
                <a href="/" class="flex items-center gap-3 group">
                    <img src="{{ asset('images/LogoClaro.png') }}" alt="Nexora" class="h-10 w-10 object-cover rounded-full group-hover:scale-105 transition-transform">
                    <span class="text-xl font-bold text-white tracking-tight group-hover:text-indigo-400 transition-colors">Nexora</span>
                </a>
            </div>

            <!-- User Profile Summary (Mobile only) -->
            <div class="md:hidden p-4 border-b border-slate-800 bg-slate-900">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-indigo-900/50 flex items-center justify-center text-indigo-400 font-bold border border-indigo-500/30">
                        {{ substr(auth('empleado')->user()->primerNombre ?? 'U', 0, 1) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-white">{{ auth('empleado')->user()->primerNombre ?? 'Usuario' }}</p>
                        <p class="text-xs text-slate-400">{{ auth('empleado')->user()->rol ?? 'Invitado' }}</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="p-4 space-y-1 overflow-y-auto flex-1 custom-scrollbar">
                
                <!-- SUPER ADMIN MENU -->
                @if(auth('empleado')->check() && (auth('empleado')->user()->rol === 'SuperAdmin' || auth('empleado')->user()->rol === 'Gerente'))
                    <div class="mb-6">
                        <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Administración</p>
                        
                        @if(auth('empleado')->user()->rol === 'SuperAdmin')
                            <a href="{{ route('admin.control') }}" class="nav-link {{ request()->routeIs('admin.control') ? 'active' : '' }}">
                                <i class="fas fa-tachometer-alt nav-icon"></i>
                                Dashboard Principal
                            </a>
                        @endif

                        <a href="{{ route('gerente.dashboard') }}" class="nav-link {{ request()->routeIs('gerente.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-chart-line nav-icon"></i>
                            Panel Gerencial
                        </a>
                        <a href="{{ route('gerente.usuarios.index') }}" class="nav-link {{ request()->routeIs('gerente.usuarios.*') ? 'active' : '' }}">
                            <i class="fas fa-users nav-icon"></i>
                            Clientes
                        </a>
                        <a href="{{ route('gerente.empleados.index') }}" class="nav-link {{ request()->routeIs('gerente.empleados.*') ? 'active' : '' }}">
                            <i class="fas fa-user-tie nav-icon"></i>
                            Personal
                        </a>
                        <a href="{{ route('gerente.auditoria') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-slate-800 hover:text-white transition-colors {{ request()->routeIs('gerente.auditoria') ? 'bg-slate-800 text-white border-r-4 border-indigo-500' : '' }}">
                            <i class="fas fa-shield-alt w-6 text-center mr-3"></i>
                            <span class="font-medium">Auditoría</span>
                        </a>
                        <a href="{{ route('gerente.sla-politicas') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-slate-800 hover:text-white transition-colors {{ request()->routeIs('gerente.sla-politicas*') ? 'bg-slate-800 text-white border-r-4 border-indigo-500' : '' }}">
                            <i class="fas fa-clock w-6 text-center mr-3"></i>
                            <span class="font-medium">Políticas SLA</span>
                        </a>
                    </div>
                @endif

                <!-- SUPERVISOR OPERADORES MENU -->
                @if(auth('empleado')->check() && (auth('empleado')->user()->rol === 'SuperAdmin' || auth('empleado')->user()->rol === 'SupervisorOperador'))
                    <div class="mb-6">
                        <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Supervisión Operadores</p>
                        <a href="{{ route('supervisor.operadores.index') }}" class="nav-link {{ request()->routeIs('supervisor.operadores.index') || request()->routeIs('supervisor.operadores.create') || request()->routeIs('supervisor.operadores.edit') ? 'active' : '' }}">
                            <i class="fas fa-headset nav-icon"></i>
                            Gestión Operadores
                        </a>
                        <a href="{{ route('supervisor.operadores.dashboard') }}" class="nav-link {{ request()->routeIs('supervisor.operadores.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-clipboard-list nav-icon"></i>
                            Asignación Reclamos
                        </a>
                    </div>
                @endif

                <!-- SUPERVISOR TÉCNICOS MENU -->
                @if(auth('empleado')->check() && (auth('empleado')->user()->rol === 'SuperAdmin' || auth('empleado')->user()->rol === 'SupervisorTecnico'))
                    <div class="mb-6">
                        <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Supervisión Técnicos</p>
                        <a href="{{ route('supervisor.tecnicos.index') }}" class="nav-link {{ request()->routeIs('supervisor.tecnicos.index') || request()->routeIs('supervisor.tecnicos.create') || request()->routeIs('supervisor.tecnicos.edit') ? 'active' : '' }}">
                            <i class="fas fa-tools nav-icon"></i>
                            Gestión Técnicos
                        </a>
                        <a href="{{ route('supervisor.tecnicos.dashboard') }}" class="nav-link {{ request()->routeIs('supervisor.tecnicos.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tasks nav-icon"></i>
                            Gestión Asignaciones
                        </a>
                        <a href="{{ route('supervisor.tecnicos.mapa') }}" class="nav-link {{ request()->routeIs('supervisor.tecnicos.mapa') ? 'active' : '' }}">
                            <i class="fas fa-map-marked-alt nav-icon"></i>
                            Mapa en Vivo
                        </a>
                    </div>
                @endif

                <!-- OPERATIONAL MENU -->
                @if(auth('empleado')->check() && in_array(auth('empleado')->user()->rol, ['Operador', 'Tecnico', 'SuperAdmin']))
                    <div class="mb-6">
                        <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Operativo</p>
                        
                        @if(auth('empleado')->user()->rol === 'Operador' || auth('empleado')->user()->rol === 'SuperAdmin')
                            <a href="{{ route('operador.panel') }}" class="nav-link {{ request()->routeIs('operador.panel') ? 'active' : '' }}">
                                <i class="fas fa-desktop nav-icon"></i>
                                Panel Operador
                            </a>
                        @endif

                        @if(auth('empleado')->user()->rol === 'Tecnico' || auth('empleado')->user()->rol === 'SuperAdmin')
                            <a href="{{ route('tecnico.dashboard') }}" class="nav-link {{ request()->routeIs('tecnico.dashboard') ? 'active' : '' }}">
                                <i class="fas fa-hard-hat nav-icon"></i>
                                Panel Técnico
                            </a>
                        @endif
                    </div>
                @endif
            </nav>

                <!-- PORTAL CLIENTE (Acceso Admin) -->
                @if(auth('empleado')->check() && auth('empleado')->user()->rol === 'SuperAdmin')
                    <div class="mb-6">
                        <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Portal Cliente</p>
                        <a href="{{ route('formulario') }}" class="nav-link {{ request()->routeIs('formulario') ? 'active' : '' }}">
                            <i class="fas fa-edit nav-icon"></i>
                            Nuevo Reclamo
                        </a>
                        <a href="{{ route('seguimiento') }}" class="nav-link {{ request()->routeIs('seguimiento') ? 'active' : '' }}">
                            <i class="fas fa-search-location nav-icon"></i>
                            Seguimiento Global
                        </a>
                    </div>
                @endif

                <!-- ACCOUNT MENU -->
                <div class="pt-4 border-t border-slate-800">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-3 py-2 text-sm font-medium rounded-lg text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-colors">
                            <i class="fas fa-sign-out-alt w-5 text-center mr-2"></i>
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
        </aside>

        <!-- MAIN CONTENT -->
        <div class="flex-1 flex flex-col md:ml-64 min-h-screen transition-all duration-300">
            
            <!-- TOP BAR -->
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 sticky top-0 z-20 shadow-sm">
                <!-- Page Title -->
                <h1 class="text-xl font-bold text-gray-800">
                    @yield('header', 'Dashboard')
                </h1>

                <!-- Right Actions -->
                <div class="flex items-center gap-4">
                    <!-- Notifications (Placeholder) -->
                    <button class="text-gray-400 hover:text-gray-600 transition-colors relative">
                        <i class="far fa-bell text-lg"></i>
                        <span class="absolute top-0 right-0 block h-2 w-2 rounded-full ring-2 ring-white bg-red-500"></span>
                    </button>

                    <!-- User Dropdown -->
                    <div class="hidden md:flex items-center gap-3 pl-4 border-l border-gray-200">
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-900">{{ auth('empleado')->user()->primerNombre ?? 'Usuario' }}</p>
                            <p class="text-xs text-gray-500">{{ auth('empleado')->user()->rol ?? 'Rol' }}</p>
                        </div>
                        <div class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold border-2 border-white shadow-sm">
                            {{ substr(auth('empleado')->user()->primerNombre ?? 'U', 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- CONTENT AREA -->
            <main class="flex-1 p-6 overflow-x-hidden">
                <div class="max-w-7xl mx-auto">
                    <!-- Alerts -->
                    @if (session('success'))
                        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center shadow-sm" role="alert">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg shadow-sm" role="alert">
                            <div class="flex items-center mb-1">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                <span class="font-bold">Atención:</span>
                            </div>
                            <ul class="list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>

            <!-- FOOTER -->
            <footer class="bg-white border-t border-gray-200 p-4 text-center text-xs text-gray-500">
                &copy; {{ date('Y') }} Nexora Bolivia. Todos los derechos reservados.
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
