<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nexora Bolivia - Apoyo para Reclamos de Internet</title>

  @vite([
      'resources/css/app.css',
      'resources/css/hero.css',
      'resources/css/nav.css',
      'resources/css/footer.css',
      'resources/css/index.css',
      'resources/js/nav.js'
  ])

  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>

  <!-- NAV -->
  <nav>
    <div class="nav-container">
      <!-- LOGO -->
      <div class="logo" role="button" tabindex="0" onclick="navigateTo('inicio')" aria-label="Ir al inicio">
        <div class="logo-icon">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M12 20h.01M2 8.82a15.91 15.91 0 0 1 20 0M5.17 12.25a10.91 10.91 0 0 1 13.66 0M8.31 15.68a5.91 5.91 0 0 1 7.38 0" />
          </svg>
        </div>
        <div class="logo-text">
          <div class="title">Nexora Bolivia</div>
          <div class="subtitle">Apoyo al Usuario</div>
        </div>
      </div>

      <!-- LINKS DESKTOP -->
      <div class="nav-links" id="navLinks">
        <button class="nav-link active" onclick="navigateTo('inicio')" aria-current="page">Inicio</button>
        <button class="nav-link" onclick="scrollToSection('beneficios')">Beneficios</button>
        <button class="nav-link" onclick="navigateTo('seguimiento')">Seguimiento</button>
        <button class="nav-link" onclick="navigateTo('recursos')">Recursos</button>
      </div>

      <!-- MENÚ MÓVIL -->
      <button class="menu-button" id="menuToggle" aria-label="Menú de navegación">
        <svg id="menuIcon" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
    </div>

    <div class="mobile-menu" id="mobileMenu">
      <button class="mobile-link active" onclick="navigateTo('inicio')">Inicio</button>
      <button class="mobile-link" onclick="scrollToSection('beneficios')">Beneficios</button>
      <button class="mobile-link" onclick="navigateTo('seguimiento')">Seguimiento</button>
      <button class="mobile-link" onclick="navigateTo('recursos')">Recursos</button>
    </div>
  </nav>
  <!-- NAV END -->

  <!-- HERO -->
  <section class="hero">
    <div class="hero-overlay"></div>
    <img 
      src="https://images.unsplash.com/photo-1679068008949-12852e5fca5a?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1080" 
      alt="Torre de internet rural" 
      class="hero-bg"
    />

    <div class="hero-content">
      <div class="hero-badge">✨ Nexora Bolivia - Tu Aliado Digital</div>
      <h1>Apoyo para Reclamos de Internet en Zonas Rurales</h1>
      <p>
        En Nexora Bolivia te ayudamos a defender tu derecho a un servicio de internet de calidad. 
        Registra tu reclamo, conoce tus derechos y obtén el apoyo profesional que necesitas.
      </p>
      <div class="hero-buttons">
        <button class="btn btn-primary" onclick="showAuthModal('formulario')">
          <i class="fas fa-pencil-alt"></i> Presentar Reclamo
        </button>
        <button class="btn btn-outline" onclick="showAuthModal('seguimiento')">
          <i class="fas fa-search"></i> Seguimiento
        </button>
      </div>

      <div class="hero-cards">
        <div class="card">
          <div class="card-icon"><i class="fas fa-wifi"></i></div>
          <h3>Velocidad Lenta</h3>
          <p>Reporta problemas de velocidad inferior a la contratada</p>
        </div>    
        <div class="card">
          <div class="card-icon"><i class="fas fa-headset"></i></div>
          <h3>Asesoría Gratuita</h3>
          <p>Orientación sobre cómo proceder con tu reclamo</p>
        </div>
        <div class="card">
          <div class="card-icon"><i class="fas fa-file-download"></i></div>
          <h3>Modelos de Carta</h3>
          <p>Descarga formatos para presentar tu reclamo formalmente</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ESTADÍSTICAS -->
  <section class="stats-section">
    <div class="container">
      <h2>Impacto de Nexora Bolivia</h2>
      <div class="stats-grid">
        <div class="stat-item animate-fade-in">
          <div class="stat-number">1,250+</div>
          <div class="stat-label">Reclamos Resueltos</div>
          <p>En los últimos 12 meses</p>
        </div>
        <div class="stat-item animate-fade-in" style="animation-delay: 0.1s">
          <div class="stat-number">98%</div>
          <div class="stat-label">Satisfacción</div>
          <p>De nuestros usuarios</p>
        </div>
        <div class="stat-item animate-fade-in" style="animation-delay: 0.2s">
          <div class="stat-number">24hrs</div>
          <div class="stat-label">Respuesta Rápida</div>
          <p>Atención prioritaria</p>
        </div>
        <div class="stat-item animate-fade-in" style="animation-delay: 0.3s">
          <div class="stat-number">La Paz</div>
          <div class="stat-label">Departamento</div>
          <p>Cobertura en zonas rurales</p>
        </div>
      </div>
    </div>
  </section>

  <!-- BENEFICIOS -->
  <section class="benefits-section" id="beneficios">
    <div class="container">
      <h2>¿Por qué elegir Nexora Bolivia?</h2>
      <div class="benefits-grid">
        <div class="benefit-card">
          <div class="benefit-icon">
            <i class="fas fa-shield-alt"></i>
          </div>
          <h3>Protección de Derechos</h3>
          <p>Te ayudamos a ejercer tus derechos como consumidor y a reclamar un servicio de calidad.</p>
        </div>
        <div class="benefit-card">
          <div class="benefit-icon">
            <i class="fas fa-clock"></i>
          </div>
          <h3>Seguimiento Transparente</h3>
          <p>Monitorea el estado de tu reclamo en tiempo real con actualizaciones constantes.</p>
        </div>
        <div class="benefit-card">
          <div class="benefit-icon">
            <i class="fas fa-users"></i>
          </div>
          <h3>Equipo Especializado</h3>
          <p>Técnicos y abogados listos para resolver tu problema de forma eficiente.</p>
        </div>
        <div class="benefit-card">
          <div class="benefit-icon">
            <i class="fas fa-map-marker-alt"></i>
          </div>
          <h3>Cobertura Rural</h3>
          <p>Nos enfocamos en zonas rurales donde más se necesita apoyo en conectividad.</p>
        </div>
        <div class="benefit-card">
          <div class="benefit-icon">
            <i class="fas fa-dollar-sign"></i>
          </div>
          <h3>100% Gratuito</h3>
          <p>Nuestros servicios son completamente gratuitos para todos los usuarios.</p>
        </div>
        <div class="benefit-card">
          <div class="benefit-icon">
            <i class="fas fa-lock"></i>
          </div>
          <h3>Datos Seguros</h3>
          <p>Protegemos tu información con los más altos estándares de seguridad.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- PROCESO -->
  <section class="process-section">
    <div class="container">
      <h2>Cómo Funciona el Proceso</h2>
      <div class="process-timeline">
        <div class="process-step">
          <div class="step-number">1</div>
          <h3>Registra tu Reclamo</h3>
          <p>Completa el formulario con los detalles de tu problema de internet. Te tomará menos de 5 minutos.</p>
          <div class="step-icon"><i class="fas fa-file-alt"></i></div>
        </div>
        <div class="process-arrow"><i class="fas fa-arrow-right"></i></div>
        <div class="process-step">
          <div class="step-number">2</div>
          <h3>Asignación Operativa</h3>
          <p>Un operador revisa tu caso y lo asigna a nuestro equipo técnico más capacitado.</p>
          <div class="step-icon"><i class="fas fa-user-check"></i></div>
        </div>
        <div class="process-arrow"><i class="fas fa-arrow-right"></i></div>
        <div class="process-step">
          <div class="step-number">3</div>
          <h3>Investigación</h3>
          <p>Nuestros técnicos investigan la raíz del problema y preparan un análisis completo.</p>
          <div class="step-icon"><i class="fas fa-microscope"></i></div>
        </div>
        <div class="process-arrow"><i class="fas fa-arrow-right"></i></div>
        <div class="process-step">
          <div class="step-number">4</div>
          <h3>Resolución</h3>
          <p>Coordinamos con el proveedor para resolver el problema y te comunicamos los resultados.</p>
          <div class="step-icon"><i class="fas fa-check-circle"></i></div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA SECTION -->
  <section class="cta-section">
    <div class="container">
      <div class="cta-content">
        <h2>¿Tu internet no funciona correctamente?</h2>
        <p>No esperes más. Inicia tu reclamo ahora y nosotros nos encargaremos del resto.</p>
        <button class="btn btn-primary btn-large" onclick="showAuthModal('formulario')">
          <i class="fas fa-bolt"></i> Empezar Ahora
        </button>
      </div>
    </div>
  </section>

  <!-- FAQ SECTION -->
  <section class="faq-section">
    <div class="container">
      <h2>Preguntas Frecuentes</h2>
      <div class="faq-grid">
        <div class="faq-item">
          <div class="faq-question">
            <h4>¿Es realmente gratuito?</h4>
            <i class="fas fa-chevron-down"></i>
          </div>
          <div class="faq-answer">
            Sí, 100% gratuito. Nexora Bolivia es una iniciativa para proteger los derechos de usuarios de internet en zonas rurales.
          </div>
        </div>
        <div class="faq-item">
          <div class="faq-question">
            <h4>¿Cuánto tiempo tarda en resolverse?</h4>
            <i class="fas fa-chevron-down"></i>
          </div>
          <div class="faq-answer">
            Depende de la complejidad del caso, pero la mayoría se resuelven dentro de 24 a 48 horas de presentado el reclamo.
          </div>
        </div>
        <div class="faq-item">
          <div class="faq-question">
            <h4>¿Necesito documentos especiales?</h4>
            <i class="fas fa-chevron-down"></i>
          </div>
          <div class="faq-answer">
            No necesitas documentos especiales. Solo información básica del problema y tus datos de contacto.
          </div>
        </div>
        <div class="faq-item">
          <div class="faq-question">
            <h4>¿Cómo puedo seguir mi reclamo?</h4>
            <i class="fas fa-chevron-down"></i>
          </div>
          <div class="faq-answer">
            Accede a tu cuenta y usa la sección de Seguimiento para ver el estado actualizado de tu caso en tiempo real.
          </div>
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
        @auth('web')
            <!-- Sesión de usuario regular -->
            <div class="bg-green-500 text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg">
                {{ auth('web')->user()->primerNombre }}
            </div>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="btn-floating btn-logout">Cerrar Sesión</button>
            </form>
        @endauth
        @auth('empleado')
            <!-- Sesión de empleado -->
            <div class="bg-blue-500 text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg">
                {{ auth('empleado')->user()->primerNombre }}
            </div>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="btn-floating btn-logout">Cerrar Sesión</button>
            </form>
        @endauth
        @guest('web')
        @guest('empleado')
            <!-- No autenticado -->
            <a href="{{ route('login') }}" class="btn-floating">Iniciar Sesión</a>
            <a href="{{ route('register') }}" class="btn-floating btn-register">Registrarse</a>
        @endguest
        @endguest
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

    function scrollToSection(section) {
      const element = document.getElementById(section);
      if (element) {
        element.scrollIntoView({ behavior: 'smooth' });
        document.getElementById('mobileMenu')?.classList.remove('show');
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

    // FAQ Toggle
    document.querySelectorAll('.faq-question').forEach(question => {
      question.addEventListener('click', function() {
        this.parentElement.classList.toggle('active');
      });
    });
  </script>
</body>
</html>