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
  <!-- NAVBAR PREMIUM -->
  <header class="navbar-premium">
    <div class="navbar-wrapper">
      <div class="navbar-brand">
        <div class="brand-logo">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
          </svg>
        </div>
        <div class="brand-info">
          <h1 class="brand-name">Nexora Bolivia</h1>
          <span class="brand-badge">Gestión de Operadores</span>
        </div>
      </div>

      <nav class="navbar-menu">
        <a href="#" class="nav-link active" data-section="dashboard">
          <i class="fas fa-chart-line"></i>
          <span>Dashboard</span>
        </a>
        <a href="#" class="nav-link" data-section="casos">
          <i class="fas fa-tasks"></i>
          <span>Mis Casos</span>
        </a>
        <a href="#" class="nav-link" data-section="reportes">
          <i class="fas fa-file-alt"></i>
          <span>Reportes</span>
        </a>
      </nav>

      <div class="navbar-end">
        <div class="notification-bell">
          <button class="bell-btn" id="notificationsBtn">
            <i class="fas fa-bell"></i>
            <span class="notification-badge" id="notificationCount">0</span>
          </button>
        </div>
        
        <div class="user-profile">
          <div class="profile-avatar">
            <i class="fas fa-user-circle"></i>
          </div>
          <div class="profile-info">
            <p class="profile-name">
              @auth('empleado')
                {{ auth('empleado')->user()->primerNombre ?? 'Operador' }}
              @else
                {{ Auth::user()->primerNombre ?? Auth::user()->name ?? 'Operador' }}
              @endauth
            </p>
            <span class="profile-role">Operador</span>
          </div>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="logout-btn-form">
          @csrf
          <button type="submit" class="logout-btn" title="Cerrar sesión">
            <i class="fas fa-sign-out-alt"></i>
          </button>
        </form>
      </div>
    </div>
  </header>

  <!-- MAIN CONTENT -->
  <main class="main-content">
    <!-- HERO HEADER -->
    <div class="hero-header">
      <div class="hero-content">
        <h2 class="hero-title">Panel de Control</h2>
        <p class="hero-subtitle">Administra y asigna casos a técnicos en tiempo real</p>
      </div>
      <div class="hero-actions">
        <button class="action-btn" id="refreshBtn" title="Refrescar datos">
          <i class="fas fa-sync-alt"></i>
          <span>Refrescar</span>
        </button>
        <button class="action-btn" id="filterBtn" title="Filtros">
          <i class="fas fa-filter"></i>
          <span>Filtros</span>
        </button>
      </div>
    </div>

    <!-- QUICK STATS -->
    <section class="quick-stats">
      <div class="stats-grid" id="statsContainer"></div>
    </section>

    <!-- MAIN SECTIONS -->
    <section class="content-sections">
      <!-- CASOS NUEVOS -->
      <div class="section-card section-nuevos">
        <div class="card-header-premium">
          <div class="header-title">
            <div class="header-icon nuevos-icon">
              <i class="fas fa-inbox"></i>
            </div>
            <div class="header-text">
              <h3>Casos Nuevos</h3>
              <p>Reclamos sin asignar que requieren revisión inmediata</p>
            </div>
          </div>
          <div class="header-stat" id="countNuevos">
            <span class="stat-number">0</span>
            <span class="stat-label">nuevos</span>
          </div>
        </div>
        <div class="card-body">
          <div id="nuevosReclamosTable" class="table-container"></div>
        </div>
      </div>

      <!-- MIS CASOS -->
      <div class="section-card section-misCasos">
        <div class="card-header-premium">
          <div class="header-title">
            <div class="header-icon misCasos-icon">
              <i class="fas fa-check-circle"></i>
            </div>
            <div class="header-text">
              <h3>Mis Casos en Asignación</h3>
              <p>Casos asignados a ti que necesitan ser delegados a técnicos</p>
            </div>
          </div>
          <div class="header-stat" id="countMisCasos">
            <span class="stat-number">0</span>
            <span class="stat-label">asignados</span>
          </div>
        </div>
        <div class="card-body">
          <div id="misCasosTable" class="table-container"></div>
        </div>
      </div>
    </section>
  </main>

  <!-- MODAL PREMIUM -->
  <div id="modal" class="modal-premium hidden">
    <div class="modal-overlay-premium" onclick="closeModal()"></div>
    <div class="modal-window">
      <div class="modal-header-premium">
        <div class="modal-title-group">
          <h3 id="modalTitle" class="modal-title">Gestionar Caso</h3>
          <p id="modalSubtitle" class="modal-subtitle"></p>
        </div>
        <button class="modal-close-btn" onclick="closeModal()">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div id="modalBody" class="modal-body-premium"></div>
    </div>
  </div>

  <meta name="csrf-token" content="{{ csrf_token() }}">
  @vite(['resources/js/nav.js','resources/js/operador.js'])
</body>
</html>