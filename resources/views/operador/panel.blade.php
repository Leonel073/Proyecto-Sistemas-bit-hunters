<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Panel de Operador - Nexora Bolivia</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  @vite(['resources/css/app.css','resources/css/operador.css'])
</head>
<body>

  <header class="navbar" role="banner">
    <div class="container">
      <div class="flex items-center gap-4">
        <a href="/" class="logo-container" aria-label="Ir al inicio">
          <div class="logo-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
          </div>
          <div class="logo-text">
            <div class="site-name">Nexora Bolivia</div>
            <div class="site-role">Centro de Operaciones</div>
          </div>
        </a>

        

        <nav id="navCenter" class="nav-center hidden md:flex items-center gap-2" aria-label="Navegación principal">
          <a href="#" class="nav-item active" data-section="dashboard"> <i class="fas fa-chart-line"></i> <span>Dashboard</span></a>
          <a href="#" class="nav-item" data-section="casos"> <i class="fas fa-clipboard-list"></i> <span>Casos</span></a>
          <a href="#" class="nav-item" data-section="reportes"> <i class="fas fa-file-chart-column"></i> <span>Reportes</span></a>
        </nav>
      </div>

      <div class="nav-right">
        <button class="btn-nav" id="notificationsBtn" aria-label="Notificaciones"><i class="fas fa-bell"></i></button>
        <div class="user-info">
          <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'O',0,1)) }}</div>
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
          <button type="submit" class="btn-nav btn-logout" aria-label="Cerrar sesión">Salir</button>
        </form>
      </div>
    </div>
  </header>

  <main class="page-wrapper">
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

      <div id="statsContainer" class="stats-grid"></div>

      <section id="dashboardSection">
        <div class="sections-container">
          <div class="card card-primary">
            <div class="card-header">
              <div class="header-info">
                <div class="header-icon"><i class="fas fa-bell"></i></div>
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

          <div class="card card-secondary">
            <div class="card-header">
              <div class="header-info">
                <div class="header-icon"><i class="fas fa-tasks"></i></div>
                <div class="header-text">
                  <h2>Mis Casos</h2>
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
      </section>

      <section id="casosSection" class="hidden">
        <div class="card">
          <div class="card-header">
            <h2>Vista: Casos</h2>
            <p class="card-description">Lista completa de casos (nuevos y asignados).</p>
          </div>
          <div class="card-content grid-2">
            <div>
              <h3>Casos Nuevos</h3>
              <div id="nuevosCasosContainer"></div>
            </div>
            <div>
              <h3>Mis Casos</h3>
              <div id="misCasosContainer"></div>
            </div>
          </div>
        </div>
      </section>

      <section id="reportesSection" class="hidden">
        <div class="card">
          <div class="card-header">
            <h2>Reportes</h2>
            <p class="card-description">Reportes y métricas del servicio.</p>
          </div>
          <div class="card-content">
            <p>Próximamente: gráficas, export CSV y filtros avanzados.</p>
            <div class="empty-state">
              <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg>
              <p>No hay reportes configurados aún.</p>
            </div>
          </div>
        </div>
      </section>

    </div>
  </main>

  <!-- MODAL -->
  <div id="modal" class="modal hidden" aria-hidden="true">
    <div class="modal-overlay" onclick="closeModal()"></div>
    <div class="modal-content" role="dialog" aria-modal="true">
      <div class="modal-header">
        <div>
          <h3 id="modalTitle">Gestionar Caso</h3>
          <p id="modalSubtitle" class="modal-subtitle"></p>
        </div>
        <button class="modal-close" onclick="closeModal()" aria-label="Cerrar modal"><i class="fas fa-times"></i></button>
      </div>
      <div id="modalBody" class="modal-body"></div>
    </div>
  </div>

  <meta name="csrf-token" content="{{ csrf_token() }}">
  @vite(['resources/js/nav.js','resources/js/operador.js'])
</body>
</html>