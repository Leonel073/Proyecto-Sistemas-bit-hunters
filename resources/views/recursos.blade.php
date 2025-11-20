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
      'resources/css/recursos.css',
      'resources/css/btns.css',
      'resources/js/nav.js',
      'resources/js/recursos.js'
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
  <div class="main-container max-w-6xl mx-auto px-4 py-8 space-y-12">

    <!-- Sección: Derechos -->
    <section class="derechos">
      <h2 class="text-3xl font-bold text-gray-900 mb-4">Conoce Tus Derechos</h2>
      <p class="descripcion text-gray-600 mb-8">
        Como usuario de servicios de internet en zonas rurales, cuentas con derechos específicos que te protegen.
      </p>
      <div class="cards grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="card p-6 bg-white rounded-lg shadow-sm border">
          <h3 class="text-xl font-semibold text-indigo-600 mb-2">Derecho a un Servicio de Calidad</h3>
          <p class="text-gray-600">Los proveedores deben garantizar la velocidad contratada y estabilidad del servicio.</p>
        </div>
        <div class="card p-6 bg-white rounded-lg shadow-sm border">
          <h3 class="text-xl font-semibold text-indigo-600 mb-2">Derecho a Información Clara</h3>
          <p class="text-gray-600">Debes recibir información transparente sobre las condiciones del servicio y facturación.</p>
        </div>
        <div class="card p-6 bg-white rounded-lg shadow-sm border">
          <h3 class="text-xl font-semibold text-indigo-600 mb-2">Derecho a Presentar Reclamos</h3>
          <p class="text-gray-600">Puedes reclamar cuando el servicio no cumpla con lo contratado, sin represalias.</p>
        </div>
        <div class="card p-6 bg-white rounded-lg shadow-sm border">
          <h3 class="text-xl font-semibold text-indigo-600 mb-2">Derecho a Compensación</h3>
          <p class="text-gray-600">En caso de incumplimiento prolongado, tienes derecho a compensaciones o devoluciones.</p>
        </div>
      </div>
    </section>

    <!-- Sección: Recursos -->
    <section class="recursos">
      <h2 class="text-3xl font-bold text-gray-900 mb-4">Recursos y Guías</h2>
      <p class="descripcion text-gray-600 mb-8">
        Descarga materiales útiles para fortalecer tu reclamo y conocer tus opciones.
      </p>
      <div class="cards grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="card recurso p-6 bg-white rounded-lg shadow-sm border text-center">
          <div class="icon text-4xl mb-3">Document</div>
          <h3 class="text-lg font-semibold text-gray-900 mb-2">Modelo de Carta de Reclamo</h3>
          <p class="text-gray-600 mb-4">Formato oficial para presentar reclamos ante tu proveedor.</p>
          <button class="btn w-full">Descargar PDF</button>
        </div>
        <div class="card recurso p-6 bg-white rounded-lg shadow-sm border text-center">
          <div class="icon text-4xl mb-3">Guide</div>
          <h3 class="text-lg font-semibold text-gray-900 mb-2">Guía de Medición de Velocidad</h3>
          <p class="text-gray-600 mb-4">Aprende a medir correctamente la velocidad de tu conexión.</p>
          <button class="btn w-full">Descargar PDF</button>
        </div>
        <div class="card recurso p-6 bg-white rounded-lg shadow-sm border text-center">
          <div class="icon text-4xl mb-3">Scale</div>
          <h3 class="text-lg font-semibold text-gray-900 mb-2">Marco Legal en Bolivia</h3>
          <p class="text-gray-600 mb-4">Información sobre leyes bolivianas y regulaciones que te protegen.</p>
          <button class="btn w-full">Descargar PDF</button>
        </div>
        <div class="card recurso p-6 bg-white rounded-lg shadow-sm border text-center">
          <div class="icon text-4xl mb-3">Phone</div>
          <h3 class="text-lg font-semibold text-gray-900 mb-2">Directorio de Organismos</h3>
          <p class="text-gray-600 mb-4">Contactos de ATT y defensa del consumidor.</p>
          <button class="btn w-full">Descargar PDF</button>
        </div>
      </div>
    </section>

    <!-- Sección: Preguntas Frecuentes -->
    <section class="faq">
      <h2 class="text-3xl font-bold text-gray-900 mb-4">Preguntas Frecuentes</h2>
      <p class="descripcion text-gray-600 mb-8">
        Respuestas a las dudas más comunes sobre reclamos de internet.
      </p>

      <div class="accordion space-y-4">
        <div class="accordion-item border rounded-lg overflow-hidden">
          <button class="accordion-header w-full text-left p-4 bg-gray-50 hover:bg-gray-100 flex justify-between items-center font-medium">
            ¿Cuánto tiempo tiene el proveedor para responder mi reclamo?
            <span class="ml-4 transition-transform">Down Arrow</span>
          </button>
          <div class="accordion-content p-4 bg-white" style="display: none;">
            El proveedor tiene un plazo máximo de 10 días hábiles para responder tu reclamo. Si no lo hace, puedes escalarlo ante la ATT o Nexora Bolivia.
          </div>
        </div>

        <div class="accordion-item border rounded-lg overflow-hidden">
          <button class="accordion-header w-full text-left p-4 bg-gray-50 hover:bg-gray-100 flex justify-between items-center font-medium">
            ¿Qué hago si mi velocidad de internet es muy inferior a la contratada?
            <span class="ml-4 transition-transform">Down Arrow</span>
          </button>
          <div class="accordion-content p-4 bg-white" style="display: none;">
            Realiza mediciones de velocidad certificadas, guarda la evidencia y presenta un reclamo formal. Si persiste, puedes pedir compensación o cancelar sin penalidad.
          </div>
        </div>

        <div class="accordion-item border rounded-lg overflow-hidden">
          <button class="accordion-header w-full text-left p-4 bg-gray-50 hover:bg-gray-100 flex justify-between items-center font-medium">
            ¿Puedo cancelar mi contrato por mal servicio?
            <span class="ml-4 transition-transform">Down Arrow</

</span>
          </button>
          <div class="accordion-content p-4 bg-white" style="display: none;">
            Sí, si el proveedor incumple reiteradamente y tienes pruebas, puedes rescindir el contrato sin penalidades.
          </div>
        </div>

        <div class="accordion-item border rounded-lg overflow-hidden">
          <button class="accordion-header w-full text-left p-4 bg-gray-50 hover:bg-gray-100 flex justify-between items-center font-medium">
            ¿Dónde puedo escalar mi reclamo si el proveedor no responde?
            <span class="ml-4 transition-transform">Down Arrow</span>
          </button>
          <div class="accordion-content p-4 bg-white" style="display: none;">
            Puedes acudir a la ATT, Defensa del Consumidor o Nexora Bolivia para recibir asesoría personalizada.
          </div>
        </div>
      </div>
    </section>

    <!-- Banner de Apoyo -->
    <section class="banner bg-linear-to-r from-indigo-600 to-purple-700 text-white rounded-2xl p-8 md:p-12 flex flex-col md:flex-row items-center justify-between gap-8">
      <div class="banner-text text-center md:text-left">
        <h2 class="text-2xl md:text-3xl font-bold mb-4">¿Necesitas Ayuda Personalizada?</h2>
        <p class="mb-6">
          El equipo de Nexora Bolivia está disponible para ayudarte con tu caso. Ofrecemos orientación profesional sobre cómo proceder con tu reclamo.
        </p>
        <div class="banner-buttons flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
          <button class="btn btn-secundario bg-white text-indigo-600 hover:bg-gray-100">
            Contactar Asesor
          </button>
          <button class="btn btn-transparente border border-white text-white hover:bg-white hover:text-indigo-600">
            Chat en Vivo
          </button>
        </div>
      </div>
      <div class="banner-img w-32 h-32 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-16 h-16">
          <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
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
    document.querySelectorAll('.accordion-header').forEach(header => {
      header.addEventListener('click', function() {
        const item = this.parentElement;
        const content = item.querySelector('.accordion-content');
        const isOpen = content.style.display === 'block';
        
        // Cerrar todos los demás
        document.querySelectorAll('.accordion-content').forEach(c => {
          c.style.display = 'none';
        });
        
        // Abrir o cerrar el actual
        content.style.display = isOpen ? 'none' : 'block';
      });
    });
  </script>
</body>
</html>