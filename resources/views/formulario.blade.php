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
    <div class="container">
      <!-- LOGO -->
      <div class="logo" onclick="navigateTo('inicio')">
        <div class="logo-icon">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-joined="round"
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
          <path stroke-linecap="round" stroke-joined="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
    </div>

    <div class="mobile-menu" id="mobileMenu">
      <button onclick="navigateTo('inicio')">Inicio</button>
      <button class="active" onclick="navigateTo('formulario')">Presentar Reclamo</button>
      <button onclick="navigateTo('seguimiento')">Seguimiento</button>
      <button onclick="navigateTo('recursos')">Recursos</button>
    </div>
  </nav>
  <!-- NAV END -->

  <main id="formulario">
    <div class="card">
      <div class="card-header">
        <div class="card-title">Formulario de Reclamo</div>
        <div class="card-description">Completa todos los campos para registrar tu reclamo sobre el servicio de internet.</div>
      </div>

      <div class="card-content">
        <form id="reclamoForm">
          <!-- Información Personal -->
          <div class="section">
            <h3>Información Personal</h3>
            <div>
              <label for="nombre">Nombre Completo *</label>
              <input id="nombre" type="text" required placeholder="Juan Pérez" />
            </div>

            <div class="grid grid-2">
              <div>
                <label for="email">Correo Electrónico *</label>
                <input id="email" type="email" required placeholder="correo@ejemplo.com" />
              </div>
              <div>
                <label for="telefono">Teléfono *</label>
                <input id="telefono" type="tel" required placeholder="+591 2 1234567" />
              </div>
            </div>

            <div class="grid grid-2">
              <div>
                <label for="departamento">Zona Rural *</label>
                <select id="departamento" required>
                  <option value="">Selecciona tu zona</option>
                  <option>Los Andes</option>
                  <option>Pacajes</option>
                  <option>Ingavi</option>
                </select>
              </div>

              <div>
                <label for="localidad">Localidad *</label>
                <input id="localidad" required placeholder="Nombre de tu localidad" />
              </div>
            </div>
          </div>

          <!-- Información del Servicio -->
          <div class="section">
            <h3>Información del Servicio</h3>
            <div>
              <label for="proveedor">Proveedor de Internet *</label>
              <input id="proveedor" required placeholder="Nombre del proveedor" />
            </div>

            <div>
              <label for="tipoProblema">Tipo de Problema *</label>
              <select id="tipoProblema" required>
                <option value="">Selecciona el tipo de problema</option>
                <option>Velocidad inferior a la contratada</option>
                <option>Cortes frecuentes</option>
                <option>Sin servicio</option>
                <option>Problemas de facturación</option>
                <option>Instalación deficiente</option>
                <option>Mala atención al cliente</option>
                <option>Otro</option>
              </select>
            </div>

            <div class="grid grid-2">
              <div>
                <label for="velocidadContratada">Velocidad Contratada (Mbps)</label>
                <input id="velocidadContratada" type="number" placeholder="10" />
              </div>
              <div>
                <label for="velocidadReal">Velocidad Real (Mbps)</label>
                <input id="velocidadReal" type="number" placeholder="3" />
              </div>
            </div>

            <div>
              <label for="descripcion">Descripción del Problema *</label>
              <textarea id="descripcion" rows="5" required
                placeholder="Describe detalladamente el problema..."></textarea>
            </div>
          </div>

          <div class="alert">
            Nexora Bolivia garantiza que toda la información será tratada de forma confidencial y usada solo para el seguimiento del reclamo.
          </div>

          <button class="button" type="submit">Enviar Reclamo</button>
        </form>
      </div>
    </div>
  </main>

  <!-- Mensaje de Éxito -->
  <div id="successMessage" class="success-message" style="display: none;">
    <h2>¡Reclamo registrado exitosamente!</h2>
    <p>Tu reclamo ha sido enviado correctamente a <b>Nexora Bolivia</b>.</p>
    <p>Número de seguimiento: <code id="reclamoId"></code></p>
    <p>Guarda este número para consultar el estado de tu reclamo.</p>
  </div>

  <!-- Botones flotantes -->
  <div class="fixed-buttons">
    <a href="{{ route('login') }}" class="btn-floating">Iniciar Sesión</a>
    <a href="{{ route('register') }}" class="btn-floating btn-register">Registrarse</a>
  </div>

  <!-- JS del formulario -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const form = document.getElementById('reclamoForm');
      const successBox = document.getElementById('successMessage');
      const formBox = document.getElementById('formulario');
      const reclamoId = document.getElementById('reclamoId');

      form.addEventListener('submit', e => {
        e.preventDefault();

        const numero = 'RX-' + Math.floor(100000 + Math.random() * 900000);

        const reclamo = {
          nombre: form.nombre.value,
          email: form.email.value,
          telefono: form.telefono.value,
          departamento: form.departamento.value,
          localidad: form.localidad.value,
          proveedor: form.proveedor.value,
          tipoProblema: form.tipoProblema.value,
          velocidadContratada: form.velocidadContratada.value,
          velocidadReal: form.velocidadReal.value,
          descripcion: form.descripcion.value,
          numero,
          fecha: new Date().toLocaleString()
        };

        const reclamos = JSON.parse(localStorage.getItem('reclamos') || '[]');
        reclamos.push(reclamo);
        localStorage.setItem('reclamos', JSON.stringify(reclamos));

        formBox.style.display = 'none';
        successBox.style.display = 'block';
        reclamoId.textContent = numero;

        setTimeout(() => {
          successBox.style.display = 'none';
          formBox.style.display = 'block';
          form.reset();
        }, 5000);
      });
    });
  </script>
</body>
</html>