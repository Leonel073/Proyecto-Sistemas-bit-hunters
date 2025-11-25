<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recursos - Nexora Bolivia</title>

  <!-- VITE: Carga CSS y JS -->
  @vite([
      'resources/css/app.css',
      'resources/css/nav.css',
      'resources/css/recursos-new.css',
      'resources/css/btns.css',
      'resources/js/nav.js'
  ])

  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gray-50">

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
        <button class="nav-link" onclick="navigateTo('inicio')">Inicio</button>
        <button class="nav-link" onclick="scrollToSection('beneficios')">Beneficios</button>
        <button class="nav-link" onclick="navigateTo('seguimiento')">Seguimiento</button>
        <button class="nav-link active" onclick="navigateTo('recursos')">Recursos</button>
      </div>

      <!-- MENÚ MÓVIL -->
      <button class="menu-button" id="menuToggle" aria-label="Menú de navegación">
        <svg id="menuIcon" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
    </div>

    <div class="mobile-menu" id="mobileMenu">
      <button class="mobile-link" onclick="navigateTo('inicio')">Inicio</button>
      <button class="mobile-link" onclick="scrollToSection('beneficios')">Beneficios</button>
      <button class="mobile-link" onclick="navigateTo('seguimiento')">Seguimiento</button>
      <button class="mobile-link active" onclick="navigateTo('recursos')">Recursos</button>
    </div>
  </nav>

  <!-- CONTENIDO PRINCIPAL -->
  <div class="recursos-main">

    <!-- HERO SECTION -->
    <section class="recursos-hero">
      <div class="hero-content">
        <h1>Centro de Recursos</h1>
        <p>Todo lo que necesitas para defender tus derechos como usuario de internet</p>
      </div>
    </section>

    <!-- SECCIÓN: DERECHOS -->
    <section class="recursos-section derechos-section">
      <div class="container">
        <h2>Conoce Tus Derechos</h2>
        <p class="section-subtitle">Como usuario de servicios de internet, cuentas con derechos específicos que te protegen</p>
        
        <div class="cards-grid">
          <div class="derecho-card">
            <div class="card-icon"><i class="fas fa-shield-alt"></i></div>
            <h3>Derecho a Servicio de Calidad</h3>
            <p>Los proveedores deben garantizar la velocidad contratada y estabilidad del servicio en todo momento.</p>
          </div>
          <div class="derecho-card">
            <div class="card-icon"><i class="fas fa-file-alt"></i></div>
            <h3>Derecho a Información Clara</h3>
            <p>Debes recibir información transparente sobre condiciones, tarifas y términos de tu contrato.</p>
          </div>
          <div class="derecho-card">
            <div class="card-icon"><i class="fas fa-comments"></i></div>
            <h3>Derecho a Presentar Reclamos</h3>
            <p>Puedes reclamar sin represalias cuando el servicio no cumpla con lo contratado.</p>
          </div>
          <div class="derecho-card">
            <div class="card-icon"><i class="fas fa-hand-holding-usd"></i></div>
            <h3>Derecho a Compensación</h3>
            <p>En caso de incumplimiento prolongado, tienes derecho a compensaciones o devoluciones.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- SECCIÓN: DESCARGAS -->
    <section class="recursos-section descargas-section">
      <div class="container">
        <h2>Recursos para Descargar</h2>
        <p class="section-subtitle">Materiales útiles para fortalecer tu reclamo y proteger tus derechos</p>
        
        <div class="recursos-grid">
          <div class="recurso-card">
            <div class="card-icon-big"><i class="fas fa-file-pdf"></i></div>
            <h3>Modelo de Carta de Reclamo</h3>
            <p>Formato oficial y efectivo para presentar reclamos ante tu proveedor con documentación correcta.</p>
            <button class="btn-download" onclick="alert('Descarga iniciada')" title="Descargar PDF">
              <i class="fas fa-download"></i> Descargar
            </button>
          </div>
          
          <div class="recurso-card">
            <div class="card-icon-big"><i class="fas fa-wifi"></i></div>
            <h3>Guía de Medición de Velocidad</h3>
            <p>Aprende a medir correctamente tu velocidad de conexión con herramientas certificadas.</p>
            <button class="btn-download" onclick="alert('Descarga iniciada')" title="Descargar PDF">
              <i class="fas fa-download"></i> Descargar
            </button>
          </div>
          
          <div class="recurso-card">
            <div class="card-icon-big"><i class="fas fa-gavel"></i></div>
            <h3>Marco Legal en Bolivia</h3>
            <p>Información detallada sobre leyes bolivianas y regulaciones que te protegen como usuario.</p>
            <button class="btn-download" onclick="alert('Descarga iniciada')" title="Descargar PDF">
              <i class="fas fa-download"></i> Descargar
            </button>
          </div>
          
          <div class="recurso-card">
            <div class="card-icon-big"><i class="fas fa-phone-alt"></i></div>
            <h3>Directorio de Organismos</h3>
            <p>Contactos de ATT, Defensa del Consumidor y otras instituciones de apoyo.</p>
            <button class="btn-download" onclick="alert('Descarga iniciada')" title="Descargar PDF">
              <i class="fas fa-download"></i> Descargar
            </button>
          </div>
          
          <div class="recurso-card">
            <div class="card-icon-big"><i class="fas fa-book"></i></div>
            <h3>Guía de Derechos del Consumidor</h3>
            <p>Manual completo sobre tus derechos como consumidor de servicios de telecomunicaciones.</p>
            <button class="btn-download" onclick="alert('Descarga iniciada')" title="Descargar PDF">
              <i class="fas fa-download"></i> Descargar
            </button>
          </div>
          
          <div class="recurso-card">
            <div class="card-icon-big"><i class="fas fa-lightbulb"></i></div>
            <h3>Tips para Resolver Problemas</h3>
            <p>Soluciones prácticas para los problemas más comunes de conexión a internet.</p>
            <button class="btn-download" onclick="alert('Descarga iniciada')" title="Descargar PDF">
              <i class="fas fa-download"></i> Descargar
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- SECCIÓN: FAQ -->
    <section class="recursos-section faq-section">
      <div class="container">
        <h2>Preguntas Frecuentes</h2>
        <p class="section-subtitle">Respuestas a tus dudas más comunes sobre reclamos de internet</p>
        
        <div class="faq-container">
          <div class="faq-accordion">
            <div class="faq-item">
              <button class="faq-header">
                <span>¿Cuánto tiempo tiene el proveedor para responder mi reclamo?</span>
                <i class="fas fa-chevron-down"></i>
              </button>
              <div class="faq-body">
                <p>El proveedor tiene un plazo máximo de 10 días hábiles para responder tu reclamo. Si no lo hace, puedes escalarlo ante la ATT o Nexora Bolivia para recibir apoyo.</p>
              </div>
            </div>

            <div class="faq-item">
              <button class="faq-header">
                <span>¿Qué hago si mi velocidad es muy inferior a la contratada?</span>
                <i class="fas fa-chevron-down"></i>
              </button>
              <div class="faq-body">
                <p>Realiza mediciones de velocidad certificadas, guarda la evidencia y presenta un reclamo formal. Si persiste, puedes pedir compensación o cancelar sin penalidad.</p>
              </div>
            </div>

            <div class="faq-item">
              <button class="faq-header">
                <span>¿Puedo cancelar mi contrato por mal servicio?</span>
                <i class="fas fa-chevron-down"></i>
              </button>
              <div class="faq-body">
                <p>Sí, si el proveedor incumple reiteradamente y tienes pruebas documentadas, puedes rescindir el contrato sin penalidades.</p>
              </div>
            </div>

            <div class="faq-item">
              <button class="faq-header">
                <span>¿Dónde escalo mi reclamo si no responden?</span>
                <i class="fas fa-chevron-down"></i>
              </button>
              <div class="faq-body">
                <p>Puedes acudir a la ATT, Defensa del Consumidor, o Nexora Bolivia para recibir asesoría personalizada y apoyo legal.</p>
              </div>
            </div>

            <div class="faq-item">
              <button class="faq-header">
                <span>¿Es realmente gratuito el servicio de Nexora Bolivia?</span>
                <i class="fas fa-chevron-down"></i>
              </button>
              <div class="faq-body">
                <p>Sí, 100% gratuito. Nexora Bolivia es una iniciativa sin fines de lucro para proteger los derechos de usuarios de internet en zonas rurales.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA SECTION -->
    <section class="recursos-cta">
      <div class="cta-content">
        <h2>¿Necesitas Ayuda Personalizada?</h2>
        <p>El equipo de Nexora Bolivia está disponible para brindarte asesoría profesional sobre tu caso específico</p>
        <div class="cta-buttons">
          <button class="btn-cta btn-primary" onclick="alert('Contactando asesor...')">
            <i class="fas fa-headset"></i> Contactar Asesor
          </button>
          <button class="btn-cta btn-secondary" onclick="alert('Iniciando chat...')">
            <i class="fas fa-comments"></i> Chat en Vivo
          </button>
        </div>
      </div>
    </section>

  </div>

  

  <!-- SCRIPTS -->
  <script>
    function navigateTo(page) {
      const routes = {
        inicio: '/',
        recursos: '/recursos',
        seguimiento: '/seguimiento',
        formulario: '/formulario'
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

    // Menú móvil toggle
    document.getElementById('menuToggle')?.addEventListener('click', () => {
      document.getElementById('mobileMenu').classList.toggle('show');
    });

    // FAQ Accordion
    document.querySelectorAll('.faq-header').forEach(header => {
      header.addEventListener('click', function() {
        const item = this.parentElement;
        const body = item.querySelector('.faq-body');
        const isOpen = body.style.display === 'block';
        
        // Cerrar todos los demás
        document.querySelectorAll('.faq-body').forEach(c => {
          c.style.display = 'none';
        });
        document.querySelectorAll('.faq-header').forEach(h => {
          h.querySelector('i').style.transform = 'rotate(0deg)';
        });
        
        // Abrir o cerrar el actual
        body.style.display = isOpen ? 'none' : 'block';
        this.querySelector('i').style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
      });
    });
  </script>
</body>
</html>