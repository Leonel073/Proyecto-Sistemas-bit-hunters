<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nexora Bolivia - Hero Section</title>

  @vite([
      'resources/css/app.css',
      'resources/css/hero.css',
      'resources/css/nav.css',
      'resources/css/footer.css',
      'resources/js/nav.js'
  ])

  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>

  <!-- NAV -->
  <nav>
    <div class="container">
      <!-- LOGO -->
      <div class="logo" onclick="navigateTo('inicio')">
        <div class="logo-icon">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 20h.01M2 8.82a15.91 15.91 0 0 1 20 0M5.17 12.25a10.91 10.91 0 0 1 13.66 0M8.31 15.68a5.91 5.91 0 0 1 7.38 0" />
          </svg>
        </div>
        <div class="logo-text">
          <div class="title">Nexora Bolivia</div>
          <div class="subtitle">Apoyo al Usuario</div>
        </div>
      </div>

      <!-- BOTONES DESKTOP -->
      <div class="nav-links" id="navLinks">
        <button class="active" onclick="navigateTo('inicio')">Inicio</button>
        <button onclick="showAuthModal('formulario')">Presentar Reclamo</button>
        <button onclick="showAuthModal('seguimiento')">Seguimiento</button>
        <button onclick="navigateTo('recursos')">Recursos</button>
      </div>

      <!-- BOTÓN MENÚ MÓVIL -->
      <button class="menu-button" id="menuToggle">
        <svg id="menuIcon" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
    </div>

    <!-- MENÚ MÓVIL -->
    <div class="mobile-menu" id="mobileMenu">
      <button class="active" onclick="navigateTo('inicio')">Inicio</button>
      <button onclick="showAuthModal('formulario')">Presentar Reclamo</button>
      <button onclick="showAuthModal('seguimiento')">Seguimiento</button>
      <button onclick="navigateTo('recursos')">Recursos</button>
    </div>
  </nav>

  <!-- HERO -->
  <section class="hero">
    <div class="hero-overlay"></div>
    <img 
      src="https://images.unsplash.com/photo-1679068008949-12852e5fca5a?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1080" 
      alt="Torre de internet rural" 
      class="hero-bg"
    />

    <div class="hero-content">
      <div class="hero-badge">Nexora Bolivia - Tu Aliado Digital</div>
      <h1>Apoyo para Reclamos de Internet en Zonas Rurales</h1>
      <p>
        En Nexora Bolivia te ayudamos a defender tu derecho a un servicio de internet de calidad. 
        Registra tu reclamo, conoce tus derechos y obtén el apoyo profesional que necesitas.
      </p>
      <div class="hero-buttons">
        <button class="btn btn-primary" onclick="showAuthModal('formulario')">
          Presentar Reclamo
        </button>
        <button class="btn btn-outline" onclick="showAuthModal('seguimiento')">
          Seguimiento
        </button>
      </div>

      <div class="hero-cards">
        <div class="card">
          <div class="card-icon"></div>
          <h3>Velocidad Lenta</h3>
          <p>Reporta problemas de velocidad inferior a la contratada</p>
        </div>    
        <div class="card">
          <div class="card-icon"></div>
          <h3>Asesoría Gratuita</h3>
          <p>Orientación sobre cómo proceder con tu reclamo</p>
        </div>
        <div class="card">
          <div class="card-icon"></div>
          <h3>Modelos de Carta</h3>
          <p>Descarga formatos para presentar tu reclamo formalmente</p>
        </div>
      </div>
    </div>
  </section>
  
  <!-- FOOTER -->
  <footer class="footer">
    <div class="footer-container">
      <div class="footer-grid">
        <!-- Sobre Nosotros -->
        <div class="footer-section">
          <h3 class="footer-title">Nexora Bolivia</h3>
          <p class="footer-text">
            Plataforma de apoyo para usuarios de internet en zonas rurales de Bolivia. 
            Defendemos tu derecho a una conectividad de calidad y te acompañamos en cada paso.
          </p>
        </div>

        <!-- Enlaces Rápidos -->
        <div class="footer-section">
          <h4 class="footer-subtitle">Enlaces Rápidos</h4>
          <ul class="footer-links">
            <li><a href="#" onclick="showAuthModal('formulario')">Presentar Reclamo</a></li>
            <li><a href="#" onclick="showAuthModal('seguimiento')">Seguimiento</a></li>
            <li><a href="#" onclick="navigateTo('recursos')">Recursos y Guías</a></li>
            <li><a href="{{ route('login') }}">Iniciar Sesión</a></li>
          </ul>
        </div>

        <!-- Legal -->
        <div class="footer-section">
          <h4 class="footer-subtitle">Legal</h4>
          <ul class="footer-links">
            <li><a href="#">Política de Privacidad</a></li>
            <li><a href="#">Términos de Uso</a></li>
            <li><a href="#">Aviso Legal</a></li>
          </ul>
        </div>

        <!-- Contacto -->
        <div class="footer-section">
          <h4 class="footer-subtitle">Contacto</h4>
          <ul class="footer-contact">
            <li><i class="fa-solid fa-envelope"></i> soporte@nexorabolivia.com</li>
            <li><i class="fa-solid fa-phone"></i> 800-10-6394 (Línea gratuita)</li>
            <li><i class="fa-solid fa-map-pin"></i> Cobertura en toda Bolivia</li>
          </ul>

          <div class="footer-socials">
            <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
            <a href="#"><i class="fa-brands fa-instagram"></i></a>
          </div>
        </div>
      </div>

      <!-- BOTONES FLOTANTES -->
      <div class="fixed-buttons">
        @guest
          <a href="{{ route('login') }}" class="btn-floating">Iniciar Sesión</a>
          <a href="{{ route('register') }}" class="btn-floating btn-register">Registrarse</a>
        @endguest

        @auth
          <!-- PRUEBA VISUAL -->
          <div class="bg-green-500 text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg">
            {{ Auth::user()->primerNombre }}
          </div>
          <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="btn-floating btn-logout">Cerrar Sesión</button>
          </form>
        @endauth
      </div>

      <!-- Copyright -->
      <div class="footer-bottom">
        <p>© 2025 Nexora Bolivia. Todos los derechos reservados.</p>
        <p>Servicio profesional de asesoría y apoyo para usuarios de internet en zonas rurales de Bolivia.</p>
      </div>
    </div>
  </footer>

  <!-- MODAL -->
  <div id="authModal" class="modal hidden">
    <div class="modal-content">
      <h3 class="text-lg font-bold text-gray-900">Autenticación Requerida</h3>
      <p class="text-gray-600 mt-2">Debes iniciar sesión o registrarte para continuar.</p>
      <div class="modal-buttons">
        <a href="{{ route('login') }}" class="btn-floating modal-btn-login">Iniciar Sesión</a>
        <a href="{{ route('register') }}" class="btn-floating modal-btn-register">Registrarse</a>
      </div>
    </div>
  </div>

  <!-- JS -->
  <script>
    function navigateTo(page) {
      const routes = {
        inicio: '/',
        recursos: '/recursos'
      };
      if (routes[page]) {
        window.location.href = routes[page];
      }
    }

    function showAuthModal(target) {
      @auth
        const routes = {
          'formulario': '/formulario',
          'seguimiento': '/seguimiento'
        };
        if (routes[target]) {
          window.location.href = routes[target];
        }
      @else
        document.getElementById('authModal').classList.remove('hidden');
      @endauth
    }

    document.getElementById('authModal')?.addEventListener('click', (e) => {
      if (e.target === e.currentTarget) {
        e.currentTarget.classList.add('hidden');
      }
    });

    document.getElementById('menuToggle')?.addEventListener('click', () => {
      document.getElementById('mobileMenu').classList.toggle('show');
    });
  </script>
</body>
</html>