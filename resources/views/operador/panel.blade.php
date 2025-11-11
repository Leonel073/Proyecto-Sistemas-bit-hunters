<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Panel de Operador - Nexora Bolivia</title>
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
            <div class="site-role">Operador</div>
          </div>
        </div>
      </div>

      <div class="nav-right">
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
      <h1>Panel de Operador</h1>
      <p>Bienvenido, <strong id="operadorNombre">
        @auth('empleado')
          {{ auth('empleado')->user()->primerNombre ?? 'Operador' }}
        @else
          {{ Auth::user()->primerNombre ?? Auth::user()->name ?? 'Operador' }}
        @endauth
      </strong></p>
    </header>

    <!-- ESTADÍSTICAS (placeholder) -->
    <div class="stats-grid" id="statsContainer"></div>

    <!-- CASOS NUEVOS -->
    <div class="card">
      <div class="card-header">
        <h2>Casos Nuevos - Requieren Atención</h2>
        <p class="card-description">Reclamos recién ingresados que necesitan ser revisados y asignados</p>
      </div>
      <div class="card-content">
        <div id="nuevosReclamosTable"></div>
      </div>
    </div>

    <!-- MIS CASOS -->
    <div class="card">
      <div class="card-header">
        <h2>Mis Casos - Asignar a Técnicos</h2>
        <p class="card-description">Casos bajo tu responsabilidad que necesitan ser asignados a técnicos de campo</p>
      </div>
      <div class="card-content">
        <div id="misCasosTable"></div>
      </div>
    </div>
  </div>

  <!-- MODAL -->
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
  @vite(['resources/js/nav.js','resources/js/operador.js'])
</body>
</html>