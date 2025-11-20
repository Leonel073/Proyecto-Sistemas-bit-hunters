<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Panel de Operador - Nexora Bolivia</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  @vite(['resources/css/app.css','resources/css/operador.css'])
</head>
<body>
  <!-- NAVBAR MEJORADA -->
  <header class="navbar">
    <div class="navbar-container">
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
            <div class="site-role">Panel de Operador</div>
          </div>
        </div>
      </div>

      <div class="nav-center">
        <a href="#" class="nav-item active">
          <i class="fas fa-dashboard"></i>
          <span>Dashboard</span>
        </a>
        <a href="#" class="nav-item">
          <i class="fas fa-tasks"></i>
          <span>Casos</span>
        </a>
        <a href="#" class="nav-item">
          <i class="fas fa-chart-line"></i>
          <span>Reportes</span>
        </a>
      </div>

      <div class="nav-right">
        <div class="user-info">
          <div class="user-avatar">
            <i class="fas fa-user-circle"></i>
          </div>
          <div class="user-details">
            <div class="user-name">
              @auth('empleado')
                {{ auth('empleado')->user()->primerNombre ?? 'Operador' }}
              @else
                {{ Auth::user()->primerNombre ?? Auth::user()->name ?? 'Operador' }}
              @endauth
            </div>
            <div class="user-role">Operador</div>
          </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="logout-form">
          @csrf
          <button type="submit" class="btn-logout" title="Cerrar sesión">
            <i class="fas fa-sign-out-alt"></i>
          </button>
        </form>
      </div>
    </div>
  </header>

  <div class="main-container">
    <!-- SIDEBAR OPCIONAL -->
    <aside class="sidebar-mini"></aside>

    <main class="panel-content">
      <!-- HEADER DEL PANEL -->
      <div class="panel-header">
        <div class="header-top">
          <h1 class="page-title">Panel de Control</h1>
          <p class="page-subtitle">Gestión de casos y asignación de técnicos en tiempo real</p>
        </div>
        <div class="header-actions">
          <button class="btn-icon" title="Refrescar">
            <i class="fas fa-sync-alt"></i>
          </button>
          <button class="btn-icon" title="Filtros">
            <i class="fas fa-filter"></i>
          </button>
        </div>
      </div>

      <!-- ESTADÍSTICAS -->
      <div class="stats-grid" id="statsContainer"></div>

      <!-- CONTENEDOR DE SECCIONES -->
      <div class="sections-container">
        <!-- CASOS NUEVOS -->
        <div class="card card-primary">
          <div class="card-header">
            <div class="header-info">
              <div class="header-icon">
                <i class="fas fa-bell"></i>
              </div>
              <div class="header-text">
                <h2>Casos Nuevos</h2>
                <p class="card-description">Reclamos recién ingresados que requieren revisión</p>
              </div>
            </div>
            <div class="header-badge" id="countNuevos">0</div>
          </div>
          <div class="card-content">
            <div id="nuevosReclamosTable"></div>
          </div>
        </div>

        <!-- MIS CASOS -->
        <div class="card card-secondary">
          <div class="card-header">
            <div class="header-info">
              <div class="header-icon">
                <i class="fas fa-tasks"></i>
              </div>
              <div class="header-text">
                <h2>Mis Casos en Asignación</h2>
                <p class="card-description">Casos bajo tu responsabilidad a asignar a técnicos</p>
              </div>
            </div>
            <div class="header-badge" id="countMisCasos">0</div>
          </div>
          <div class="card-content">
            <div id="misCasosTable"></div>
          </div>
        </div>
      </div>
    </main>
  </div>

  <!-- MODAL MEJORADO -->
  <div id="modal" class="modal hidden">
    <div class="modal-overlay" onclick="closeModal()"></div>
    <div class="modal-content">
      <div class="modal-header">
        <div>
          <h3 id="modalTitle">Gestionar Caso</h3>
          <p id="modalSubtitle" class="modal-subtitle"></p>
        </div>
        <button class="modal-close" onclick="closeModal()">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div id="modalBody" class="modal-body"></div>
    </div>
  </div>

  <meta name="csrf-token" content="{{ csrf_token() }}">
  @vite(['resources/js/nav.js','resources/js/operador.js'])
</body>
</html>