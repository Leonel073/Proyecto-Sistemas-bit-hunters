<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Super Admin') - NEXORA</title>
    @vite(['resources/css/app.css', 'resources/css/admin-sidebar.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-header">
                <div class="sidebar-brand">
                    <i class="fas fa-network-wired"></i>
                    <span>NEXORA</span>
                </div>
            </div>

            <nav class="sidebar-nav">
                <!-- Grupo: General -->
                <div class="nav-group">
                    <div class="nav-group-title">General</div>
                    <a href="{{ route('admin.control') }}" class="nav-item {{ request()->routeIs('admin.control') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt nav-icon"></i>
                        <span>Dashboard Principal</span>
                    </a>
                    <a href="{{ route('admin.migrations') }}" class="nav-item {{ request()->routeIs('admin.migrations') ? 'active' : '' }}">
                        <i class="fas fa-database nav-icon"></i>
                        <span>Base de Datos</span>
                    </a>
                </div>

                <!-- Grupo: Gerencia -->
                <div class="nav-group">
                    <div class="nav-group-title">Gerencia</div>
                    <a href="{{ route('gerente.dashboard') }}" class="nav-item {{ request()->routeIs('gerente.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-chart-line nav-icon"></i>
                        <span>Panel Gerencial</span>
                    </a>
                    <a href="{{ route('gerente.auditoria') }}" class="nav-item {{ request()->routeIs('gerente.auditoria') ? 'active' : '' }}">
                        <i class="fas fa-history nav-icon"></i>
                        <span>Auditoría</span>
                    </a>
                    <a href="{{ route('gerente.usuarios.index') }}" class="nav-item {{ request()->routeIs('gerente.usuarios.*') ? 'active' : '' }}">
                        <i class="fas fa-users nav-icon"></i>
                        <span>Clientes</span>
                    </a>
                </div>

                <!-- Grupo: Supervisión -->
                <div class="nav-group">
                    <div class="nav-group-title">Supervisión</div>
                    <a href="{{ route('supervisor.operadores.index') }}" class="nav-item {{ request()->routeIs('supervisor.operadores.*') ? 'active' : '' }}">
                        <i class="fas fa-headset nav-icon"></i>
                        <span>Sup. Operadores</span>
                    </a>
                    <a href="{{ route('supervisor.tecnicos.index') }}" class="nav-item {{ request()->routeIs('supervisor.tecnicos.*') ? 'active' : '' }}">
                        <i class="fas fa-tools nav-icon"></i>
                        <span>Sup. Técnicos</span>
                    </a>
                    <a href="{{ route('supervisor.tecnicos.mapa') }}" class="nav-item {{ request()->routeIs('supervisor.tecnicos.mapa') ? 'active' : '' }}">
                        <i class="fas fa-map-marked-alt nav-icon"></i>
                        <span>Mapa en Vivo</span>
                    </a>
                </div>

                <!-- Grupo: Operaciones -->
                <div class="nav-group">
                    <div class="nav-group-title">Operaciones</div>
                    <a href="{{ route('operador.panel') }}" class="nav-item {{ request()->routeIs('operador.panel') ? 'active' : '' }}">
                        <i class="fas fa-desktop nav-icon"></i>
                        <span>Panel Operador</span>
                    </a>
                </div>

                <!-- Grupo: Campo -->
                <div class="nav-group">
                    <div class="nav-group-title">Campo</div>
                    <a href="{{ route('tecnico.dashboard') }}" class="nav-item {{ request()->routeIs('tecnico.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-hard-hat nav-icon"></i>
                        <span>Panel Técnico</span>
                    </a>
                </div>

                <!-- Grupo: Portal Cliente (Acceso Admin) -->
                <div class="nav-group">
                    <div class="nav-group-title">Portal Cliente</div>
                    <a href="{{ route('formulario') }}" class="nav-item {{ request()->routeIs('formulario') ? 'active' : '' }}">
                        <i class="fas fa-edit nav-icon"></i>
                        <span>Nuevo Reclamo</span>
                    </a>
                    <a href="{{ route('seguimiento') }}" class="nav-item {{ request()->routeIs('seguimiento') ? 'active' : '' }}">
                        <i class="fas fa-search-location nav-icon"></i>
                        <span>Seguimiento Global</span>
                    </a>
                </div>
            </nav>

            <div class="sidebar-footer">
                <div class="user-profile">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::guard('empleado')->user()->primerNombre ?? 'A', 0, 1)) }}
                    </div>
                    <div class="user-info">
                        <div class="user-name">{{ Auth::guard('empleado')->user()->primerNombre ?? 'Admin' }} {{ Auth::guard('empleado')->user()->apellidoPaterno ?? '' }}</div>
                        <div class="user-role">Super Administrador</div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer;" title="Cerrar Sesión">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Overlay for mobile -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Main Content -->
        <main class="admin-main">
            <header class="top-bar">
                <button class="toggle-sidebar" id="toggleSidebar">
                    <i class="fas fa-bars fa-lg"></i>
                </button>
                <div class="page-title">
                    <h1 style="font-size: 1.25rem; font-weight: 600; color: #1e293b;">@yield('header', 'Dashboard')</h1>
                </div>
                <div class="top-actions">
                    <!-- Additional top bar actions if needed -->
                </div>
            </header>

            <div class="page-content">
                @if(session('success'))
                    <div style="background-color: #d1fae5; color: #065f46; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; border: 1px solid #a7f3d0;">
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('toggleSidebar');
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            function toggleMenu() {
                sidebar.classList.toggle('open');
                overlay.classList.toggle('show');
            }
            
            if(toggleBtn) toggleBtn.addEventListener('click', toggleMenu);
            if(overlay) overlay.addEventListener('click', toggleMenu);
        });
    </script>
</body>
</html>
