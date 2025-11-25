<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nexora Bolivia - Apoyo para Reclamos de Internet</title>

  @vite([
      'resources/css/app.css',
      'resources/css/nav.css',
      'resources/css/footer.css',
      'resources/css/btns.css',
      'resources/css/index.css',
      'resources/css/hero.css',
      'resources/js/nav.js'
  ])


  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

</head>
<body>

  <!-- NAV -->
  <nav>
    <div class="nav-container">
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

      <div class="nav-links" id="navLinks">
        <button class="nav-link active" onclick="navigateTo('inicio')" aria-current="page">Inicio</button>
        <button class="nav-link" onclick="scrollToSection('beneficios')">Beneficios</button>
        <button class="nav-link" onclick="navigateTo('seguimiento')">Seguimiento</button>
        <button class="nav-link" onclick="navigateTo('recursos')">Recursos</button>
      </div>

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

  <!-- HERO SECTION -->
  <section class="hero">
    <!-- Fondo animado con patrón -->
    <div class="hero-bg-animated">
      <div class="hero-circle-1"></div>
      <div class="hero-circle-2"></div>
    </div>

    <div class="hero-content">
      <div class="hero-badge">✨ Nexora Bolivia - Tu Aliado Digital</div>
      
      <h1>
        Apoyo para Reclamos de Internet en <span>Zonas Rurales</span>
      </h1>
      
      <p>
        En Nexora Bolivia te ayudamos a defender tu derecho a un servicio de internet de calidad. 
        Registra tu reclamo, conoce tus derechos y obtén el apoyo profesional que necesitas.
      </p>
      
      <div class="hero-buttons">
        <button class="hero-btn hero-btn-primary" onclick="showAuthModal('formulario')">
          <i class="fas fa-pencil-alt"></i> Presentar Reclamo
        </button>
        <button class="hero-btn hero-btn-outline" onclick="showAuthModal('seguimiento')">
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
    <div class="stats-container">
      <h2>Impacto de Nexora Bolivia</h2>
      <div class="stats-grid">
        <div class="stat-item">
          <div class="stat-number">1,250+</div>
          <div class="stat-label">Reclamos Resueltos</div>
          <p>En los últimos 12 meses</p>
        </div>
        <div class="stat-item">
          <div class="stat-number">98%</div>
          <div class="stat-label">Satisfacción</div>
          <p>De nuestros usuarios</p>
        </div>
        <div class="stat-item">
          <div class="stat-number">24hrs</div>
          <div class="stat-label">Respuesta Rápida</div>
          <p>Atención prioritaria</p>
        </div>
        <div class="stat-item">
          <div class="stat-number">La Paz</div>
          <div class="stat-label">Departamento</div>
          <p>Cobertura en zonas rurales</p>
        </div>
      </div>
    </div>
  </section>

  <!-- BENEFICIOS -->
  <section class="benefits-section" id="beneficios">
    <div class="benefits-container">
      <h2>¿Por qué elegir Nexora Bolivia?</h2>
      <p class="benefits-subtitle">Somos el aliado confiable en tu defensa de derechos</p>
      
      <div class="benefits-grid">
        <div class="benefit-card">
          <div class="benefit-icon"><i class="fas fa-shield-alt"></i></div>
          <h3>Protección de Derechos</h3>
          <p>Te ayudamos a ejercer tus derechos como consumidor y a reclamar un servicio de calidad.</p>
        </div>
        <div class="benefit-card">
          <div class="benefit-icon"><i class="fas fa-clock"></i></div>
          <h3>Seguimiento Transparente</h3>
          <p>Monitorea el estado de tu reclamo en tiempo real con actualizaciones constantes.</p>
        </div>
        <div class="benefit-card">
          <div class="benefit-icon"><i class="fas fa-users"></i></div>
          <h3>Equipo Especializado</h3>
          <p>Técnicos y abogados listos para resolver tu problema de forma eficiente.</p>
        </div>
        <div class="benefit-card">
          <div class="benefit-icon"><i class="fas fa-map-marker-alt"></i></div>
          <h3>Cobertura Rural</h3>
          <p>Nos enfocamos en zonas rurales donde más se necesita apoyo en conectividad.</p>
        </div>
        <div class="benefit-card">
          <div class="benefit-icon"><i class="fas fa-dollar-sign"></i></div>
          <h3>100% Gratuito</h3>
          <p>Nuestros servicios son completamente gratuitos para todos los usuarios.</p>
        </div>
        <div class="benefit-card">
          <div class="benefit-icon"><i class="fas fa-lock"></i></div>
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
    <div class="cta-content">
      <h2>¿Tu internet no funciona correctamente?</h2>
      <p>No esperes más. Inicia tu reclamo ahora y nosotros nos encargaremos del resto.</p>
      <button class="cta-btn" onclick="showAuthModal('formulario')">
        <i class="fas fa-bolt"></i> Empezar Ahora
      </button>
    </div>
  </section>

  <!-- FAQ SECTION -->
  <section class="faq-section">
    <div class="faq-container">
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
        <div class="footer-section">
          <h3 class="footer-title">Nexora Bolivia</h3>
          <p class="footer-text">
            Plataforma de apoyo para usuarios de internet en zonas rurales de Bolivia. 
            Defendemos tu derecho a una conectividad de calidad y te acompañamos en cada paso.
          </p>
        </div>

        <div class="footer-section">
          <h4 class="footer-subtitle">Enlaces Rápidos</h4>
          <ul class="footer-links">
            <li><a href="#" onclick="showAuthModal('formulario')">Presentar Reclamo</a></li>
            <li><a href="#" onclick="showAuthModal('seguimiento')">Seguimiento</a></li>
            <li><a href="#" onclick="navigateTo('recursos')">Recursos y Guías</a></li>
            <li><a href="{{ route('login') }}">Iniciar Sesión</a></li>
          </ul>
        </div>

        <div class="footer-section">
          <h4 class="footer-subtitle">Legal</h4>
          <ul class="footer-links">
            <li><a href="#">Política de Privacidad</a></li>
            <li><a href="#">Términos de Uso</a></li>
            <li><a href="#">Aviso Legal</a></li>
          </ul>
        </div>

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

      <!-- ✅ BOTONES FLOTANTES PERSONALIZADOS ✅ -->
      <div class="fixed-buttons" style="z-index: 2000;">
        @auth('web')
            <!-- USUARIO LOGUEADO -->
            <div class="floating-user-bar">
                <!-- Nombre -->
                <div class="user-name">
                    {{ auth('web')->user()->primerNombre }}
                </div>
                
                <!-- Botón Editar (Lápiz Azul) -->
                <a href="{{ route('perfil.editar') }}" class="editBtn" title="Editar mi perfil">
                    <svg height="1em" viewBox="0 0 512 512"><path d="M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z"></path></svg>
                </a>

                <!-- Botón Salir (Rojo Deslizante) -->
                <form method="POST" action="{{ route('logout') }}" style="display: inline; margin: 0;">
                    @csrf
                    <button type="submit" class="BtnLogout" title="Cerrar Sesión">
                        <div class="sign"><svg viewBox="0 0 512 512"><path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"></path></svg></div>
                        <div class="text">Salir</div>
                    </button>
                </form>
            </div>
        @endauth

        @auth('empleado')
            <!-- EMPLEADO LOGUEADO -->
            <div class="floating-user-bar">
                <div class="user-name employee">
                    {{ auth('empleado')->user()->primerNombre }}
                </div>
                <form method="POST" action="{{ route('logout') }}" style="display: inline; margin: 0;">
                    @csrf
                    <button type="submit" class="BtnLogout" title="Cerrar Sesión">
                        <div class="sign"><svg viewBox="0 0 512 512"><path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"></path></svg></div>
                        <div class="text">Salir</div>
                    </button>
                </form>
            </div>
        @endauth

        @guest('web')
        @guest('empleado')
            <!-- NO AUTENTICADO (Botones de Estrellas) -->
            <div class="floating-user-bar">
                <!-- LOGIN (Morado) -->
                <a href="{{ route('login') }}" class="star-btn star-btn-purple">
                    Iniciar Sesión
                    <div class="star-1"><svg viewBox="0 0 784.11 815.53"><path class="fil0" d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.06,-407.78z"></path></svg></div>
                    <div class="star-2"><svg viewBox="0 0 784.11 815.53"><path class="fil0" d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.06,-407.78z"></path></svg></div>
                    <div class="star-3"><svg viewBox="0 0 784.11 815.53"><path class="fil0" d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.06,-407.78z"></path></svg></div>
                    <div class="star-4"><svg viewBox="0 0 784.11 815.53"><path class="fil0" d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.06,-407.78z"></path></svg></div>
                    <div class="star-5"><svg viewBox="0 0 784.11 815.53"><path class="fil0" d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.06,-407.78z"></path></svg></div>
                    <div class="star-6"><svg viewBox="0 0 784.11 815.53"><path class="fil0" d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.06,-407.78z"></path></svg></div>
                </a>

                <!-- REGISTRO (Naranja) -->
                <a href="{{ route('register') }}" class="star-btn star-btn-orange">
                    Registrarse
                    <div class="star-1"><svg viewBox="0 0 784.11 815.53"><path class="fil0" d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.06,-407.78z"></path></svg></div>
                    <div class="star-2"><svg viewBox="0 0 784.11 815.53"><path class="fil0" d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.06,-407.78z"></path></svg></div>
                    <div class="star-3"><svg viewBox="0 0 784.11 815.53"><path class="fil0" d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.06,-407.78z"></path></svg></div>
                    <div class="star-4"><svg viewBox="0 0 784.11 815.53"><path class="fil0" d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.06,-407.78z"></path></svg></div>
                    <div class="star-5"><svg viewBox="0 0 784.11 815.53"><path class="fil0" d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.06,-407.78z"></path></svg></div>
                    <div class="star-6"><svg viewBox="0 0 784.11 815.53"><path class="fil0" d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.06,-407.78z"></path></svg></div>
                </a>
            </div>
        @endguest
        @endguest
    </div>

  </footer>


  <script>
    // ========== Variables Globales ==========
    let authModal = null;
    const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};

    // ========== Funciones de Navegación ==========
    function navigateTo(page) {
      const routes = { inicio: '/', recursos: '/recursos' };
      if (routes[page]) window.location.href = routes[page];
    }

    function scrollToSection(section) {
      const element = document.getElementById(section);
      if (element) {
        element.scrollIntoView({ behavior: 'smooth' });
      }
    }

    function showAuthModal(target) {
      if (isAuthenticated) {
        // Si está autenticado, navegar directamente
        const routes = { 'formulario': '/formulario', 'seguimiento': '/seguimiento' };
        if (routes[target]) {
          window.location.href = routes[target];
        }
      } else {
        // Si no está autenticado, mostrar modal
        if (authModal) {
          authModal.classList.remove('hidden');
          document.body.style.overflow = 'hidden';
          console.log('✅ Modal mostrado');
        } else {
          console.error('❌ Modal no encontrado');
        }
      }
    }

    function closeAuthModal() {
      if (authModal) {
        authModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        console.log('✅ Modal cerrado');
      }
    }

    // ========== Inicialización al Cargar el DOM ==========
    document.addEventListener('DOMContentLoaded', function() {
      console.log('📄 DOM cargado, inicializando...');

      // Obtener el modal del DOM
      authModal = document.getElementById('authModal');
      console.log('🔍 authModal encontrado:', authModal ? '✅ SÍ' : '❌ NO');

      // Cerrar modal al hacer click fuera (en el overlay)
      if (authModal) {
        authModal.addEventListener('click', function(e) {
          if (e.target === this) {
            closeAuthModal();
          }
        });

        // Botones del modal
        const loginBtn = authModal.querySelector('.modal-btn-login');
        const registerBtn = authModal.querySelector('.modal-btn-register');

        if (loginBtn) {
          loginBtn.addEventListener('click', function(e) {
            window.location.href = this.href;
          });
        }

        if (registerBtn) {
          registerBtn.addEventListener('click', function(e) {
            window.location.href = this.href;
          });
        }
      }

      // Cerrar modal con tecla Escape
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && authModal && !authModal.classList.contains('hidden')) {
          closeAuthModal();
        }
      });

      // ========== Control del Menú Móvil ==========
      const menuToggle = document.getElementById('menuToggle');
      const mobileMenu = document.getElementById('mobileMenu');
      
      if (menuToggle && mobileMenu) {
        menuToggle.addEventListener('click', function() {
          mobileMenu.classList.toggle('show');
        });

        document.querySelectorAll('.mobile-link').forEach(link => {
          link.addEventListener('click', function() {
            mobileMenu.classList.remove('show');
          });
        });
      }

      // ========== FAQ Interactivo ==========
      document.querySelectorAll('.faq-question').forEach(question => {
        question.addEventListener('click', function() {
          const item = this.parentElement;
          const answer = item.querySelector('.faq-answer');
          const icon = this.querySelector('i');

          document.querySelectorAll('.faq-item').forEach(el => {
            if (el !== item && el.classList.contains('active')) {
              el.classList.remove('active');
              el.querySelector('.faq-answer').style.display = 'none';
              el.querySelector('i').style.transform = 'rotate(0deg)';
              el.style.background = '#f9f9f9';
            }
          });

          item.classList.toggle('active');
          const isActive = item.classList.contains('active');
          
          answer.style.display = isActive ? 'block' : 'none';
          icon.style.transform = isActive ? 'rotate(180deg)' : 'rotate(0deg)';
          item.style.background = isActive ? '#f0f4ff' : '#f9f9f9';
        });
      });

      console.log('✅ Inicialización completada - Modal listo - Autenticado:', isAuthenticated);
    });
  </script>

  <!-- MODAL - POSICIONADO AL FINAL COMO OVERLAY GLOBAL FIJO -->
  <div id="authModal" class="modal hidden">
    <div class="modal-content">
      <h3>🔐 Autenticación Requerida</h3>
      <p>Debes iniciar sesión o registrarte para continuar con esta acción.</p>
      <div class="modal-buttons">
        <a href="{{ route('login') }}" class="modal-btn-login">
          <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
        </a>
        <a href="{{ route('register') }}" class="modal-btn-register">
          <i class="fas fa-user-plus"></i> Registrarse
        </a>
      </div>
    </div>
  </div>

</body>
</html>
