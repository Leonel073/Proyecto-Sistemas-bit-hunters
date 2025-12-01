<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel - @yield('title', 'Sistema NEXORA')</title>
    
    <style>
        /* ESTILOS BÁSICOS PARA VISUALIZACIÓN EN COLUMNA (DEBE USAR TU CSS/FRAMEWORK) */
        body { margin: 0; font-family: sans-serif; background-color: #f4f7f6; }
        .wrapper { display: flex; min-height: 100vh; }
        .sidebar { 
            width: 250px; 
            background-color: #2c3e50; 
            color: white; 
            padding: 20px 0; /* Padding vertical */
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2); 
            position: fixed; /* Fija el menú en la izquierda */
            height: 100%;
            overflow-y: auto; /* Permite scroll si hay muchos enlaces */
        }
        .content-area { 
            flex-grow: 1; 
            padding: 30px; 
            margin-left: 250px; /* Desplaza el contenido para que no quede detrás del sidebar */
        }
        .sidebar h4 { margin-bottom: 10px; text-align: center; color: #ecf0f1; border-bottom: 1px solid #34495e; padding-bottom: 15px; }
        .sidebar ul { list-style: none; padding: 0; margin: 0; }
        .sidebar li { margin-bottom: 0px; }
        .sidebar a { color: #bdc3c7; text-decoration: none; display: block; padding: 10px 20px; transition: background-color 0.3s; font-size: 0.95em;}
        .sidebar a:hover { background-color: #34495e; color: white; }
        .menu-heading { color: #7f8c8d; font-size: 0.7em; margin-top: 15px; margin-bottom: 5px; padding: 0 20px; text-transform: uppercase; font-weight: bold; }
        .logout-form { padding: 20px; margin-top: 20px; border-top: 1px solid #34495e;}
    </style>
</head>
<body>
    <div class="wrapper">
        
        {{-- ================================================= --}}
        {{-- BARRA LATERAL (SIDEBAR) - AQUÍ ESTÁ EL MENÚ JERÁRQUICO --}}
        {{-- ================================================= --}}
        <aside class="sidebar">
            <h4>Sistema NEXORA</h4>
            <p style="text-align: center; font-size: 0.9em; color: #f39c12; padding-bottom: 10px;">
                Rol: **{{ auth()->guard('empleado')->user()->rol ?? 'Desconocido' }}**
            </p>

            <ul class="sidebar-menu">

                {{-- 0. MÓDULO SUPER ADMINISTRADOR (ROL EXCLUSIVO) --}}
                @role('SuperAdmin')
                    <li class="menu-heading" style="color: #e74c3c;">⭐ SUPER ADMIN CONTROL ⭐</li>
                    <li><a href="{{ route('admin.control') }}" style="font-weight: bold;">⚙️ Panel de Control Técnico</a></li>
                    <li><a href="{{ route('admin.migrations') }}">💾 Registros de Migración</a></li>
                @endrole
                
                {{-- 1. MÓDULO GERENCIAL (Acceso: Gerente, SuperAdmin) --}}
                @role('Gerente|SuperAdmin')
                    <li class="menu-heading">Administración Central</li>
                    <li><a href="{{ route('gerente.dashboard') }}">📊 Dashboard Gerencial (HU-13)</a></li>
                    <li><a href="{{ route('gerente.empleados.index') }}">👥 Gestión de Personal (HU-11)</a></li>
                    <li><a href="{{ route('gerente.sla-politicas') }}">⚖️ Gestión Políticas SLA (HU-12)</a></li>
                    <li><a href="{{ route('gerente.auditoria') }}">🔍 Logs de Auditoría</a></li>
                @endrole

                {{-- 2. MÓDULOS DE SUPERVISIÓN (Acceso: Supervisores, Gerente, SuperAdmin) --}}
                @role('SupervisorOperador|SupervisorTecnico|Gerente|SuperAdmin')
                    <li class="menu-heading">Supervisión y Control</li>
                    <li><a href="{{ route('supervisor.operadores.dashboard') }}">🛠️ Intervención Operativa (HU-7)</a></li>
                    <li><a href="{{ route('supervisor.tecnicos.mapa') }}">📍 Supervisión Geográfica (HU-9)</a></li>
                    <li><a href="{{ route('gerente.zonas') }}">🗺️ ABM Zonas (HU-6)</a></li>
                @endrole
                
                {{-- 3. MÓDULO OPERATIVO (Acceso: Operador, Supervisores, Gerente, SuperAdmin) --}}
                @role('Operador|SupervisorOperador|Gerente|SuperAdmin')
                    <li class="menu-heading">Bandeja de Trabajo</li>
                    <li><a href="{{ route('operador.panel') }}">📥 Gestión de Asignaciones (HU-5)</a></li>
                @endrole

                {{-- 4. MÓDULO TÉCNICO (Acceso: Técnico, Supervisores, Gerente, SuperAdmin) --}}
                @role('Tecnico|SupervisorTecnico|Gerente|SuperAdmin')
                    <li class="menu-heading">Flujo de Campo</li>
                    <li><a href="{{ route('tecnico.dashboard') }}">✅ Mis Asignaciones (HU-8)</a></li>
                @endrole
                
                {{-- 5. ACCESO PÚBLICO (Para Clientes) --}}
                {{-- Esto es si el SuperAdmin quiere saltar a la vista pública --}}
                <li class="menu-heading">Acceso Público</li>
                <li><a href="{{ route('home') }}">🏠 Portal del Cliente</a></li>


            </ul>

            {{-- Formulario de Logout (Siempre visible para empleados logueados) --}}
            <form action="{{ route('logout') }}" method="POST" class="logout-form">
                @csrf
                <button type="submit" style="width: 100%; padding: 10px; background-color: #e74c3c; border: none; color: white; border-radius: 4px; cursor: pointer; font-weight: bold;">
                    Cerrar Sesión ({{ auth()->guard('empleado')->user()->emailCorporativo ?? '' }})
                </button>
            </form>

        </aside>

        {{-- ================================================= --}}
        {{-- ÁREA DE CONTENIDO DINÁMICO --}}
        {{-- ================================================= --}}
        <main class="content-area">
            <h1>@yield('title')</h1>
            <p>Bienvenido, **{{ auth()->guard('empleado')->user()->primerNombre }}**.</p>
            <hr>
            
            {{-- Aquí se inyectará el contenido de la vista específica (Dashboard, CRUD, etc.) --}}
            @yield('content')
        </main>
    </div>

    @stack('scripts')

</body>
</html>