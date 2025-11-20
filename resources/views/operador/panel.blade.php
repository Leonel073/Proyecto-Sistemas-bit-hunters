<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Panel de Operador - Nexora Bolivia</title>
  @vite(['resources/css/app.css','resources/css/operador.css'])
</head>
<body>
  <header class="op-navbar">
    <div class="container op-nav-inner">
      <div class="brand">
        <div class="brand-logo">Nexora</div>
        <div class="brand-sub">Panel de Operador</div>
      </div>
      <div class="op-nav-actions">
        <span class="operador-welcome">Hola, <strong id="operadorNombre">
          @auth('empleado')
            {{ auth('empleado')->user()->primerNombre ?? 'Operador' }}
          @else
            {{ Auth::user()->primerNombre ?? Auth::user()->name ?? 'Operador' }}
          @endauth
        </strong></span>
        <button class="btn small" id="notificationsBtn">Notificaciones</button>
        <form method="POST" action="{{ route('logout') }}" class="inline">
          @csrf
          <button class="btn outline small">Cerrar Sesión</button>
        </form>
      </div>
    </div>
  </header>

  <main class="container op-main">
    <section class="op-row">
      <aside class="op-sidebar">
        <div class="stats-card">
          <h3>Resumen</h3>
          <div id="statsContainer" class="stats-list">
            <!-- JS injecta métricas: Nuevos, Asignados, En Progreso, Resueltos -->
          </div>
        </div>

        <div class="quick-actions">
          <button class="btn primary" onclick="location.reload()">Refrescar</button>
          
        </div>
      </aside>

      <section class="op-content">
        <div class="panel-header">
          <h2>Casos Nuevos</h2>
          <p class="muted">Reclamos recientes pendientes de revisión y asignación</p>
        </div>

        <div class="card list-card">
          <div id="nuevosReclamosTable" class="list-container">
            <!-- JS renderiza tarjetas o tabla de reclamos nuevos -->
          </div>
        </div>

        <div class="panel-subheader">
          <h3>Mis Casos</h3>
          <p class="muted">Casos que has tomado y requieren asignación a técnicos</p>
        </div>

        <div class="card list-card">
          <div id="misCasosTable" class="list-container">
            <!-- JS renderiza tus casos con botones para asignar/abrir -->
          </div>
        </div>
      </section>
    </section>
  </main>

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