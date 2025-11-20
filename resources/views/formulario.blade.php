<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Formulario de Reclamo - Nexora Bolivia</title>

  <!-- VITE: Carga CSS y JS -->
  @vite([
      'resources/css/app.css',
      'resources/css/nav.css',
      'resources/css/formulario.css',
      'resources/css/btns.css',
      'resources/js/nav.js'
  ])

  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>

  <!-- NAV -->
  <nav>
    <div class="nav-container">
      <!-- LOGO -->
      <div class="logo" onclick="navigateTo('inicio')">
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
        <button onclick="navigateTo('inicio')">Inicio</button>
        <button class="active" onclick="navigateTo('formulario')">Presentar Reclamo</button>
        <button onclick="navigateTo('seguimiento')">Seguimiento</button>
        <button onclick="navigateTo('recursos')">Recursos</button>
      </div>

      <!-- MENÚ MÓVIL -->
      <button class="menu-button" id="menuToggle">
        <svg id="menuIcon" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2"
          viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
    </div>

    <div class="mobile-menu" id="mobileMenu">
      <button onclick="navigateTo('inicio')">Inicio</button>
      <button class="active" onclick="navigateTo('reclamo')">Presentar Reclamo</button>
      <button onclick="navigateTo('seguimiento')">Seguimiento</button>
      <button onclick="navigateTo('recursos')">Recursos</button>
    </div>
  </nav>
  <!-- NAV END -->

  <div class="main-container" id="app">
    <!-- Formulario de reclamo -->
    <div class="card" id="reclamoFormContainer">
      <div class="card-header">
        <h2 class="card-title">Formulario de Reclamo</h2>
        <p class="card-description">
          Completa todos los campos para registrar tu reclamo sobre el servicio de internet
        </p>
      </div>
      <div class="card-content">
        @auth
          @php $user = auth('web')->user(); @endphp
          <form id="reclamoForm" action="{{ route('reclamo.store') }}" method="POST">
            @csrf

            <!-- Información Personal -->
            <div class="form-section">
              <h3 class="section-title">Información Personal</h3>

              <div class="form-group">
                <label class="form-label" for="nombre">Nombre Completo *</label>
                <input class="form-input" id="nombre" name="nombre" required placeholder="Juan Pérez"
                  value="{{ old('nombre', $user ? ($user->primerNombre . ' ' . ($user->apellidoPaterno ?? '')) : '') }}" />
                @error('nombre')<div class="error">{{ $message }}</div>@enderror
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label class="form-label" for="email">Correo Electrónico *</label>
                  <input type="email" class="form-input" id="email" name="email" required placeholder="correo@ejemplo.com"
                    value="{{ old('email', $user ? $user->email : '') }}" />
                  @error('email')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                  <label class="form-label" for="telefono">Teléfono *</label>
                  <input type="tel" class="form-input" id="telefono" name="telefono" required placeholder="+591 2 1234567"
                    value="{{ old('telefono', $user ? $user->numeroCelular : '') }}" />
                  @error('telefono')<div class="error">{{ $message }}</div>@enderror
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label class="form-label" for="departamento">Departamento *</label>
                  <input class="form-input" id="departamento" name="departamento" value="La Paz" disabled />
                  <p class="small-text">Sistema de reclamos para el departamento de La Paz</p>
                </div>
                <div class="form-group">
                  <label class="form-label" for="localidad">Zona Rural *</label>
                  <select class="form-select" id="localidad" name="localidad" required>
                    <option value="">Selecciona tu zona rural</option>
                    <option value="Pacajes" {{ old('localidad')=='Pacajes' ? 'selected' : '' }}>Pacajes</option>
                    <option value="Ingavi" {{ old('localidad')=='Ingavi' ? 'selected' : '' }}>Ingavi</option>
                    <option value="Los Andes" {{ old('localidad')=='Los Andes' ? 'selected' : '' }}>Los Andes</option>
                  </select>
                  @error('localidad')<div class="error">{{ $message }}</div>@enderror
                  <p class="small-text">Selecciona la provincia rural donde se encuentra el problema</p>
                </div>
              </div>
            </div>

            <!-- Información del Reclamo -->
            <div class="form-section">
              <h3 class="section-title">Información del Reclamo</h3>

              <div class="form-group">
                <label class="form-label" for="titulo">Título del Reclamo *</label>
                <input class="form-input" id="titulo" name="titulo" required placeholder="Resumen breve del problema"
                  value="{{ old('titulo') }}" />
                @error('titulo')<div class="error">{{ $message }}</div>@enderror
              </div>

              <div class="form-group">
                <label class="form-label" for="proveedor">Proveedor de Internet *</label>
                <input class="form-input" id="proveedor" name="proveedor" required placeholder="Nombre del proveedor"
                  value="{{ old('proveedor') }}" />
                @error('proveedor')<div class="error">{{ $message }}</div>@enderror
              </div>

              <div class="form-group">
                <label class="form-label" for="tipoIncidente">Tipo de Incidente *</label>
                <select class="form-select" id="tipoIncidente" name="tipoIncidente" required>
                  <option value="">Selecciona el tipo de incidente</option>
                  @php $tipos = [
                    'Velocidad inferior a la contratada', 'Cortes frecuentes del servicio', 'Sin servicio - Caída total',
                    'Problemas de facturación','Problemas de instalación','Mala atención al cliente','Servicio intermitente','Otro'
                  ]; @endphp
                  @foreach($tipos as $t)
                    <option value="{{ $t }}" {{ old('tipoIncidente')== $t ? 'selected' : '' }}>{{ $t }}</option>
                  @endforeach
                </select>
                @error('tipoIncidente')<div class="error">{{ $message }}</div>@enderror
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label class="form-label" for="velocidadContratada">Velocidad Contratada (Mbps)</label>
                  <input type="number" class="form-input" id="velocidadContratada" name="velocidadContratada" placeholder="10"
                    value="{{ old('velocidadContratada') }}" />
                </div>
                <div class="form-group">
                  <label class="form-label" for="velocidadReal">Velocidad Real (Mbps)</label>
                  <input type="number" class="form-input" id="velocidadReal" name="velocidadReal" placeholder="3"
                    value="{{ old('velocidadReal') }}" />
                </div>
              </div>

              <div class="form-group">
                <label class="form-label" for="descripcionDetallada">Descripción Detallada del Problema *</label>
                <textarea class="form-textarea" id="descripcionDetallada" name="descripcionDetallada" required placeholder="Describe detalladamente el problema...">{{ old('descripcionDetallada') }}</textarea>
                @error('descripcionDetallada')<div class="error">{{ $message }}</div>@enderror
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label class="form-label" for="latitud">Latitud de Ubicación</label>
                  <input type="number" step="0.000001" class="form-input" id="latitud" name="latitud" placeholder="-16.5" value="{{ old('latitud', '-16.5') }}" />
                  <p class="small-text">Opcional: Ayuda a localizar el problema geográficamente</p>
                </div>
                <div class="form-group">
                  <label class="form-label" for="longitud">Longitud de Ubicación</label>
                  <input type="number" step="0.000001" class="form-input" id="longitud" name="longitud" placeholder="-68.15" value="{{ old('longitud', '-68.15') }}" />
                  <p class="small-text">Opcional: Puede obtener las coordenadas desde Google Maps</p>
                </div>
              </div>
            </div>

            <div class="alert">
              <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
              </svg>
              <span>Nexora Bolivia garantiza que toda la información proporcionada será tratada de manera confidencial y utilizada únicamente para dar seguimiento a tu reclamo conforme a las leyes bolivianas de protección de datos.</span>
            </div>

            <button type="submit" class="btn-submit">Enviar Reclamo</button>
          </form>
        @else
          <div class="not-authenticated">
            <h3>Debes iniciar sesión para presentar un reclamo</h3>
            <p>Por favor inicia sesión o regístrate para acceder al formulario de reclamos.</p>
            <div class="actions">
              <a href="{{ route('login') }}" class="btn">Iniciar Sesión</a>
              <a href="{{ route('register') }}" class="btn btn-outline">Registrarse</a>
            </div>
          </div>
        @endauth
      </div>
    </div>
  <!-- Mensaje de Éxito -->
  @if(session('success'))
    <div class="success-message">
      <h2>¡Reclamo registrado exitosamente!</h2>
      <p>{{ session('success') }}</p>
    </div>
  @endif

 
  

  <!-- (El formulario ahora se envía al servidor; las validaciones y mensajes se manejan desde el backend) -->
</body>
</html>