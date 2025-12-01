<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nexora Bolivia - Apoyo para Reclamos de Internet</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-800">

  <!-- NAV -->
  <nav class="bg-slate-900/95 backdrop-blur-md fixed w-full z-50 border-b border-slate-800 transition-all duration-300" id="navbar">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-20 items-center">
        <div class="flex-shrink-0 flex items-center cursor-pointer group gap-3" onclick="navigateTo('inicio')">
          <img src="{{ asset('images/LogoClaro.png') }}" alt="Nexora Bolivia" class="h-12 w-12 object-cover rounded-full group-hover:scale-105 transition-transform duration-300">
          <span class="text-2xl font-bold text-white tracking-tight group-hover:text-indigo-400 transition-colors">Nexora</span>
        </div>

        <!-- Desktop Menu -->
        <div class="hidden md:flex space-x-8 items-center">
          <button onclick="navigateTo('inicio')" class="text-slate-300 hover:text-white font-medium transition-colors text-sm uppercase tracking-wider">Inicio</button>
          <button onclick="scrollToSection('beneficios')" class="text-slate-300 hover:text-white font-medium transition-colors text-sm uppercase tracking-wider">Beneficios</button>
          <button onclick="navigateTo('seguimiento')" class="text-slate-300 hover:text-white font-medium transition-colors text-sm uppercase tracking-wider">Seguimiento</button>
          <button onclick="navigateTo('recursos')" class="text-slate-300 hover:text-white font-medium transition-colors text-sm uppercase tracking-wider">Recursos</button>
          
          @auth
            <a href="{{ route('formulario') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-full font-bold shadow-lg shadow-indigo-500/30 transition-all transform hover:-translate-y-0.5 text-sm">
              Mi Panel
            </a>
          @else
            <a href="{{ route('login') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-full font-bold shadow-lg shadow-indigo-500/30 transition-all transform hover:-translate-y-0.5 text-sm">
              Acceder
            </a>
          @endauth
        </div>

        <!-- Mobile Menu Button -->
        <div class="md:hidden flex items-center">
          <button id="menuToggle" class="text-slate-300 hover:text-white focus:outline-none">
            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="hidden md:hidden bg-slate-800 border-t border-slate-700">
      <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
        <button onclick="navigateTo('inicio')" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-white hover:bg-slate-700">Inicio</button>
        <button onclick="scrollToSection('beneficios')" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-slate-300 hover:text-white hover:bg-slate-700">Beneficios</button>
        <button onclick="navigateTo('seguimiento')" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-slate-300 hover:text-white hover:bg-slate-700">Seguimiento</button>
        <button onclick="navigateTo('recursos')" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-slate-300 hover:text-white hover:bg-slate-700">Recursos</button>
      </div>
    </div>
  </nav>

  <!-- HERO SECTION -->
  <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden bg-slate-900">
    <!-- Background Effects -->
    <div class="absolute inset-0 z-0">
      <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-800 via-slate-900 to-black opacity-80"></div>
      <div class="absolute -top-24 -right-24 w-96 h-96 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
      <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <div class="inline-flex items-center px-4 py-2 rounded-full bg-indigo-900/50 border border-indigo-500/30 text-indigo-300 text-sm font-medium mb-8 backdrop-blur-sm">
        <span class="flex h-2 w-2 rounded-full bg-indigo-400 mr-2 animate-pulse"></span>
        Nexora Bolivia - Tu Aliado Digital
      </div>
      
      <h1 class="text-4xl md:text-6xl font-extrabold text-white tracking-tight mb-6 leading-tight">
        Apoyo para Reclamos de Internet en <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400">Zonas Rurales</span>
      </h1>
      
      <p class="mt-4 max-w-2xl mx-auto text-xl text-slate-300 mb-10">
        En Nexora Bolivia te ayudamos a defender tu derecho a un servicio de internet de calidad. 
        Registra tu reclamo, conoce tus derechos y obtén el apoyo profesional que necesitas.
      </p>
      
      <div class="flex flex-col sm:flex-row justify-center gap-4 mb-16">
        <button onclick="showAuthModal('formulario')" class="px-8 py-4 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold text-lg shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center">
          <i class="fas fa-pencil-alt mr-2"></i> Presentar Reclamo
        </button>
        <button onclick="showAuthModal('seguimiento')" class="px-8 py-4 rounded-xl bg-slate-800 border border-slate-700 text-white font-bold text-lg hover:bg-slate-700 transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center">
          <i class="fas fa-search mr-2"></i> Seguimiento
        </button>
      </div>

      <!-- Hero Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto">
        <!-- Card 1 -->
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 p-6 rounded-2xl hover:bg-slate-800 transition-colors duration-300 text-left group">
          <div class="w-12 h-12 bg-indigo-500/20 rounded-lg flex items-center justify-center text-indigo-400 mb-4 group-hover:scale-110 transition-transform">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
          </div>
          <h3 class="text-xl font-bold text-white mb-2">Velocidad Lenta</h3>
          <p class="text-slate-400 text-sm">Reporta problemas de velocidad inferior a la contratada y exige cumplimiento.</p>
        </div>

        <!-- Card 2 -->
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 p-6 rounded-2xl hover:bg-slate-800 transition-colors duration-300 text-left group">
          <div class="w-12 h-12 bg-purple-500/20 rounded-lg flex items-center justify-center text-purple-400 mb-4 group-hover:scale-110 transition-transform">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
          </div>
          <h3 class="text-xl font-bold text-white mb-2">Asesoría Gratuita</h3>
          <p class="text-slate-400 text-sm">Orientación experta sobre cómo proceder con tu reclamo de manera efectiva.</p>
        </div>

        <!-- Card 3 -->
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 p-6 rounded-2xl hover:bg-slate-800 transition-colors duration-300 text-left group">
          <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center text-green-400 mb-4 group-hover:scale-110 transition-transform">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
          </div>
          <h3 class="text-xl font-bold text-white mb-2">Modelos de Carta</h3>
          <p class="text-slate-400 text-sm">Descarga formatos profesionales para presentar tu reclamo formalmente.</p>
        </div>
      </div>
    </div>
  </section>


  <!-- BENEFITS SECTION -->
  <section id="beneficios" class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-16">
        <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">¿Por qué elegir Nexora Bolivia?</h2>
        <p class="text-xl text-slate-600">Somos el aliado confiable en tu defensa de derechos digitales</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Benefit 1 -->
        <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 border border-slate-100">
          <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 mb-6">
            <i class="fas fa-shield-alt text-2xl"></i>
          </div>
          <h3 class="text-xl font-bold text-slate-900 mb-3">Protección de Derechos</h3>
          <p class="text-slate-600 leading-relaxed">Te ayudamos a ejercer tus derechos como consumidor y a reclamar un servicio de calidad garantizado.</p>
        </div>

        <!-- Benefit 2 -->
        <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 border border-slate-100">
          <div class="w-14 h-14 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600 mb-6">
            <i class="fas fa-chart-line text-2xl"></i>
          </div>
          <h3 class="text-xl font-bold text-slate-900 mb-3">Seguimiento Transparente</h3>
          <p class="text-slate-600 leading-relaxed">Monitorea el estado de tu reclamo en tiempo real con actualizaciones constantes y detalladas.</p>
        </div>

        <!-- Benefit 3 -->
        <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 border border-slate-100">
          <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center text-purple-600 mb-6">
            <i class="fas fa-users text-2xl"></i>
          </div>
          <h3 class="text-xl font-bold text-slate-900 mb-3">Equipo Especializado</h3>
          <p class="text-slate-600 leading-relaxed">Técnicos y abogados listos para resolver tu problema de forma eficiente y profesional.</p>
        </div>

        <!-- Benefit 4 -->
        <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 border border-slate-100">
          <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center text-green-600 mb-6">
            <i class="fas fa-map-marked-alt text-2xl"></i>
          </div>
          <h3 class="text-xl font-bold text-slate-900 mb-3">Cobertura Rural</h3>
          <p class="text-slate-600 leading-relaxed">Nos enfocamos específicamente en zonas rurales donde más se necesita apoyo en conectividad.</p>
        </div>

        <!-- Benefit 5 -->
        <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 border border-slate-100">
          <div class="w-14 h-14 bg-yellow-100 rounded-xl flex items-center justify-center text-yellow-600 mb-6">
            <i class="fas fa-hand-holding-usd text-2xl"></i>
          </div>
          <h3 class="text-xl font-bold text-slate-900 mb-3">100% Gratuito</h3>
          <p class="text-slate-600 leading-relaxed">Nuestros servicios son completamente gratuitos para todos los usuarios, sin costos ocultos.</p>
        </div>

        <!-- Benefit 6 -->
        <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 border border-slate-100">
          <div class="w-14 h-14 bg-red-100 rounded-xl flex items-center justify-center text-red-600 mb-6">
            <i class="fas fa-lock text-2xl"></i>
          </div>
          <h3 class="text-xl font-bold text-slate-900 mb-3">Datos Seguros</h3>
          <p class="text-slate-600 leading-relaxed">Protegemos tu información personal y de reclamos con los más altos estándares de seguridad.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- PROCESS SECTION -->
  <section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-16">
        <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Cómo Funciona el Proceso</h2>
        <p class="text-xl text-slate-600">Simple, rápido y efectivo</p>
      </div>

      <div class="relative">
        <!-- Connecting Line (Desktop) -->
        <div class="hidden md:block absolute top-1/2 left-0 w-full h-1 bg-slate-100 -translate-y-1/2 z-0"></div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 relative z-10">
          <!-- Step 1 -->
          <div class="bg-white p-6 rounded-xl text-center group">
            <div class="w-16 h-16 mx-auto bg-indigo-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mb-4 shadow-lg shadow-indigo-200 group-hover:scale-110 transition-transform">1</div>
            <h3 class="text-lg font-bold text-slate-900 mb-2">Registra tu Reclamo</h3>
            <p class="text-sm text-slate-500">Completa el formulario con los detalles. Menos de 5 minutos.</p>
          </div>

          <!-- Step 2 -->
          <div class="bg-white p-6 rounded-xl text-center group">
            <div class="w-16 h-16 mx-auto bg-white border-4 border-indigo-600 text-indigo-600 rounded-full flex items-center justify-center text-2xl font-bold mb-4 shadow-lg group-hover:scale-110 transition-transform">2</div>
            <h3 class="text-lg font-bold text-slate-900 mb-2">Asignación</h3>
            <p class="text-sm text-slate-500">Un operador revisa tu caso y lo asigna al equipo técnico.</p>
          </div>

          <!-- Step 3 -->
          <div class="bg-white p-6 rounded-xl text-center group">
            <div class="w-16 h-16 mx-auto bg-white border-4 border-indigo-600 text-indigo-600 rounded-full flex items-center justify-center text-2xl font-bold mb-4 shadow-lg group-hover:scale-110 transition-transform">3</div>
            <h3 class="text-lg font-bold text-slate-900 mb-2">Investigación</h3>
            <p class="text-sm text-slate-500">Nuestros técnicos analizan la raíz del problema.</p>
          </div>

          <!-- Step 4 -->
          <div class="bg-white p-6 rounded-xl text-center group">
            <div class="w-16 h-16 mx-auto bg-green-500 text-white rounded-full flex items-center justify-center text-2xl font-bold mb-4 shadow-lg shadow-green-200 group-hover:scale-110 transition-transform">4</div>
            <h3 class="text-lg font-bold text-slate-900 mb-2">Resolución</h3>
            <p class="text-sm text-slate-500">Coordinamos la solución y te comunicamos los resultados.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA SECTION -->
  <section class="py-20 bg-gradient-to-r from-indigo-900 to-slate-900 text-white text-center">
    <div class="max-w-4xl mx-auto px-4">
      <h2 class="text-3xl md:text-4xl font-bold mb-6">¿Tu internet no funciona correctamente?</h2>
      <p class="text-xl text-indigo-200 mb-10">No esperes más. Inicia tu reclamo ahora y nosotros nos encargaremos del resto.</p>
      <button onclick="showAuthModal('formulario')" class="px-10 py-4 bg-white text-indigo-900 font-bold rounded-full text-lg shadow-xl hover:bg-indigo-50 transform hover:-translate-y-1 transition-all duration-300">
        <i class="fas fa-bolt mr-2"></i> Empezar Ahora
      </button>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="bg-slate-900 text-slate-300 py-12 border-t border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
        <div class="col-span-1 md:col-span-1">
          <div class="flex items-center gap-3 mb-4">
            <img src="{{ asset('images/LogoClaro.png') }}" alt="Nexora" class="h-10 w-10 object-cover rounded-full">
            <span class="text-xl font-bold text-white tracking-tight">Nexora</span>
          </div>
          <p class="text-sm text-slate-400 leading-relaxed">
            Plataforma de apoyo para usuarios de internet en zonas rurales de Bolivia. 
            Defendemos tu derecho a una conectividad de calidad.
          </p>
        </div>
        
        <div>
          <h4 class="text-white font-bold mb-4 uppercase text-sm tracking-wider">Enlaces Rápidos</h4>
          <ul class="space-y-2 text-sm">
            <li><a href="#" onclick="showAuthModal('formulario')" class="hover:text-white transition-colors">Presentar Reclamo</a></li>
            <li><a href="#" onclick="showAuthModal('seguimiento')" class="hover:text-white transition-colors">Seguimiento</a></li>
            <li><a href="#" onclick="navigateTo('recursos')" class="hover:text-white transition-colors">Recursos y Guías</a></li>
            <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Iniciar Sesión</a></li>
          </ul>
        </div>

        <div>
          <h4 class="text-white font-bold mb-4 uppercase text-sm tracking-wider">Legal</h4>
          <ul class="space-y-2 text-sm">
            <li><a href="#" class="hover:text-white transition-colors">Política de Privacidad</a></li>
            <li><a href="#" class="hover:text-white transition-colors">Términos de Uso</a></li>
            <li><a href="#" class="hover:text-white transition-colors">Aviso Legal</a></li>
          </ul>
        </div>

        <div>
          <h4 class="text-white font-bold mb-4 uppercase text-sm tracking-wider">Contacto</h4>
          <ul class="space-y-2 text-sm">
            <li class="flex items-center"><i class="fas fa-envelope w-5 text-indigo-500"></i> soporte@nexorabolivia.com</li>
            <li class="flex items-center"><i class="fas fa-phone w-5 text-indigo-500"></i> 800-10-6394</li>
            <li class="flex items-center"><i class="fas fa-map-marker-alt w-5 text-indigo-500"></i> Cobertura Nacional</li>
          </ul>
          <div class="flex space-x-4 mt-6">
            <a href="#" class="text-slate-400 hover:text-white transition-colors"><i class="fab fa-facebook-f text-lg"></i></a>
            <a href="#" class="text-slate-400 hover:text-white transition-colors"><i class="fab fa-twitter text-lg"></i></a>
            <a href="#" class="text-slate-400 hover:text-white transition-colors"><i class="fab fa-instagram text-lg"></i></a>
          </div>
        </div>
      </div>
      
      <div class="border-t border-slate-800 pt-8 text-center text-sm text-slate-500">
        &copy; {{ date('Y') }} Nexora Bolivia. Todos los derechos reservados.
      </div>
    </div>
  </footer>

  <!-- MODAL (Tailwind Styled) -->
  <div id="authModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Overlay -->
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeAuthModal()"></div>

      <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

      <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
          <div class="sm:flex sm:items-start">
            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
              <i class="fas fa-lock text-indigo-600"></i>
            </div>
            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
              <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                Autenticación Requerida
              </h3>
              <div class="mt-2">
                <p class="text-sm text-gray-500">
                  Para continuar con esta acción, necesitas tener una cuenta en Nexora Bolivia. Es rápido y gratuito.
                </p>
              </div>
            </div>
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
          <a href="{{ route('register') }}" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
            Registrarse
          </a>
          <a href="{{ route('login') }}" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
            Iniciar Sesión
          </a>
          <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-transparent text-base font-medium text-gray-500 hover:text-gray-700 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="closeAuthModal()">
            Cancelar
          </button>
        </div>
      </div>
    </div>
  </div>

  <script>
    // ========== Variables Globales ==========
    let authModal = null;
    const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};

    // ========== Funciones de Navegación ==========
    function navigateTo(page) {
      const routes = { inicio: '/', recursos: '/recursos', seguimiento: '/seguimiento' };
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
        const routes = { 'formulario': '/formulario', 'seguimiento': '/seguimiento' };
        if (routes[target]) {
          window.location.href = routes[target];
        }
      } else {
        if (authModal) {
          authModal.classList.remove('hidden');
          document.body.style.overflow = 'hidden';
        }
      }
    }

    function closeAuthModal() {
      if (authModal) {
        authModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
      }
    }

    // ========== Inicialización al Cargar el DOM ==========
    document.addEventListener('DOMContentLoaded', function() {
      authModal = document.getElementById('authModal');

      // Cerrar modal con tecla Escape
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && authModal && !authModal.classList.contains('hidden')) {
          closeAuthModal();
        }
      });

      // Control del Menú Móvil
      const menuToggle = document.getElementById('menuToggle');
      const mobileMenu = document.getElementById('mobileMenu');
      
      if (menuToggle && mobileMenu) {
        menuToggle.addEventListener('click', function() {
          mobileMenu.classList.toggle('hidden');
        });
      }
    });
  </script>
</body>
</html>
