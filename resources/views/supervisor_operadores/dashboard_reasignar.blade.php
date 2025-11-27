<!DOCTYPE html>

<html lang="es">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Panel de Supervisor - Nexora Bolivia</title>
@vite(['resources/css/app.css','resources/css/operador.css'])
</head>
<body>
<!-- NAVBAR -->
<header class="navbar">
<div class="container">
<div class="nav-left">
<div class="logo-container">
<div class="logo-icon">
<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
</svg>
</div>
<div class="logo-text">
<div class="site-name">Nexora Bolivia</div>
<div class="site-role">Supervisor Operadores</div>
</div>
</div>
</div>

        <div class="nav-right">
            {{-- Botón para ir al CRUD de Operadores --}}
            <a href="{{ route('supervisor.operadores.index') }}" class="btn-nav" style="background-color: #f7941d;">
                Gestión de Operadores
            </a>
            
            {{-- Botón para ir al Panel de Reasignación de Reclamos --}}
            <a href="{{ route('supervisor.operadores.dashboard') }}" class="btn-nav" style="background-color: #007bff;">
                Reasignar Reclamos
            </a>
            
            {{-- Botón para ir al Mapa de Reclamos --}}
            <a href="{{ route('supervisor.operadores.mapa') }}" class="btn-nav" style="background-color: #28a745;">
                Mapa de Reclamos
            </a>

            <button class="btn-nav" id="notificationsBtn">Notificaciones</button>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button class="btn-nav">Cerrar Sesión</button>
            </form>
        </div>
    </div>
</header>

<div class="container">
    <header class="page-header">
        <h1>Panel de Supervisor</h1>
        <p>Bienvenido, <strong id="operadorNombre">
            @auth('empleado')
                {{ auth('empleado')->user()->primerNombre ?? 'Supervisor' }} {{ auth('empleado')->user()->apellidoPaterno ?? '' }}
            @else
                Supervisor
            @endauth
        </strong></p>
    </header>

    @if(session('success'))
        <div class="alert alert-success" style="padding: 15px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 4px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <!-- ESTADÍSTICAS -->
    <div class="stats-grid" id="statsContainer"></div>

    <!-- RECLAMOS PENDIENTES PARA REASIGNAR -->
    <div class="card">
        <div class="card-header">
            <h2>Reclamos Pendientes - Reasignación</h2>
            <p class="card-description">Reclamos nuevos o abiertos que requieren asignación o reasignación de operador</p>
        </div>
        <div class="card-content">
            <div id="reclamosTable"></div>
        </div>
    </div>
</div>

<!-- MODAL (Mantener la estructura del modal) -->
<div id="modal" class="modal hidden">
    <div class="modal-overlay" onclick="closeModal()"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Gestionar Caso</h3>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div id="modalBody" class="modal-body"></div>
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- Inyectar datos del servidor al JavaScript --}}
<script>
    // Convertir los datos de PHP a JavaScript
    window.reclamosData = @json($reclamos);
    window.operadoresData = @json($operadores);
</script>

{{-- Usar el JavaScript específico del supervisor --}}
@vite(['resources/js/nav.js','resources/js/supervisor-reasignar.js']) 


</body>
</html>