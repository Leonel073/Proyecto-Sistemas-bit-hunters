<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registrar Reclamo</title>

    @vite([
        'resources/css/formulario.css',
        'resources/css/nav.css',
        'resources/js/nav.js'
    ])
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <style>
        /* Define la altura CRÍTICA para que el mapa se muestre */
        #map {
            height: 350px;
            width: 100%;
            border-radius: 6px;
            margin-top: 1rem;
            margin-bottom: 1rem;
            border: 1px solid #d1d5db;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        }

        /* Asegurar que el contenedor fluya correctamente con el mapa */
        .map-section {
            margin-bottom: 1.25rem;
        }

        .map-helper-text {
            font-size: 0.75rem;
            color: #6b7280;
            text-align: center;
            display: block;
            margin-top: -0.75rem;
            margin-bottom: 1rem;
        }
    </style>
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
            <button class="nav-link" onclick="navigateTo('inicio')">Inicio</button>
            <button class="nav-link" onclick="scrollToSection('beneficios')">Beneficios</button>
            <button class="nav-link" onclick="navigateTo('seguimiento')">Seguimiento</button>
            <button class="nav-link active" onclick="navigateTo('reclamo')">Reclamo</button>
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
        <button class="mobile-link active" onclick="navigateTo('reclamo')">Reclamo</button>
    </div>
</nav>

<div class="main-container">
    <div class="card">
        <div class="card-header">
            <h1 class="card-title">Nuevo Reclamo</h1>
            <p class="card-description">Complete el formulario y seleccione su ubicación en el mapa</p>
        </div>
        <div class="card-content">

            <form action="{{ route('reclamo.store') }}" method="POST">
                @csrf

                <div class="form-section">
                    <h3 class="section-title">Información General</h3>

                    <div class="form-group">
                        <label class="form-label">Título del Reclamo *</label>
                        <input type="text" name="titulo" class="form-input" placeholder="Describe brevemente el problema" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Tipo de Incidente *</label>
                            <select name="idTipoIncidente" class="form-select" required>
                                <option value="">Seleccione una opción...</option>
                                <option value="1">Falla de Conexión</option>
                                <option value="2">Lentitud</option>
                                <option value="3">Facturación</option>
                                <option value="4">Soporte Técnico</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Prioridad *</label>
                            <select name="prioridad" class="form-select" required>
                                <option value="Baja">Baja</option>
                                <option value="Media" selected>Media</option>
                                <option value="Alta">Alta</option>
                                <option value="Urgente">Urgente</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Descripción Detallada *</label>
                        <textarea name="descripcionDetallada" class="form-textarea" placeholder="Proporcione los detalles del problema..." required></textarea>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="section-title">Ubicación del Incidente</h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Latitud</label>
                            <input type="text" id="latitudIncidente" name="latitudIncidente" class="form-input" readonly required value="-16.5000">
                            <span class="small-text">Se actualiza automáticamente</span>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Longitud</label>
                            <input type="text" id="longitudIncidente" name="longitudIncidente" class="form-input" readonly required value="-68.1500">
                            <span class="small-text">Se actualiza automáticamente</span>
                        </div>
                    </div>

                    <button type="button" id="btnActualizar" class="btn-submit" style="margin-bottom: 1rem; background: linear-gradient(to right, #f59e0b, #ec4899);">
                        📍 Actualizar a mi Ubicación Actual
                    </button>

                    <div class="map-section">
                        <div id="map"></div>
                        <small class="map-helper-text">
                            Haga clic en el mapa o mueva el marcador para seleccionar la ubicación del incidente
                        </small>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    ✓ Enviar Reclamo
                </button>
            </form>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    var latInput = document.getElementById('latitudIncidente');
    var lngInput = document.getElementById('longitudIncidente');
    var btnActualizar = document.getElementById('btnActualizar');

    var initialLat = parseFloat(latInput.value);
    var initialLng = parseFloat(lngInput.value);

    // Inicializar mapa
    var map = L.map('map').setView([initialLat, initialLng], 14);

    // ⭐ CORRECCIÓN 1: TileLayer con protocolo independiente (compatible con http://127.0.0.1)
    L.tileLayer('//{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Marcador arrastrable
    var marker = L.marker([initialLat, initialLng], {draggable:true}).addTo(map);

    // 1. Escuchar el arrastre del marcador
    marker.on('dragend', function(e){
        var pos = e.target.getLatLng();
        latInput.value = pos.lat.toFixed(6);
        lngInput.value = pos.lng.toFixed(6);
    });

    // 2. Escuchar el click en el mapa
    map.on('click', function(e){
        marker.setLatLng(e.latlng);
        latInput.value = e.latlng.lat.toFixed(6);
        lngInput.value = e.latlng.lng.toFixed(6);
    });

    // Función para actualizar ubicación por geolocalización
    function actualizarUbicacion(){
        if(navigator.geolocation){
            navigator.geolocation.getCurrentPosition(function(position){
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;

                // Mover mapa y marcador
                map.setView([lat,lng],16);
                marker.setLatLng([lat,lng]);
                
                // Actualizar inputs
                latInput.value = lat.toFixed(6);
                lngInput.value = lng.toFixed(6);

            }, function(error){
                // Manejo de errores mejorado
                var errorMsg = "No se pudo obtener la ubicación. ";
                if (error.code === error.TIMEOUT) {
                    // ⭐ CORRECCIÓN 2: Mensaje de timeout. Ocurre si la señal es débil.
                    errorMsg += "La búsqueda de ubicación tardó demasiado (señal débil). Intente de nuevo.";
                } else if (error.code === error.PERMISSION_DENIED) {
                    errorMsg += "Acceso denegado. Debe permitir la ubicación en la configuración del navegador.";
                } else {
                    errorMsg += "Error desconocido: " + error.message;
                }
                alert(errorMsg);

            // ⭐ CORRECCIÓN 3: Aumentar el timeout a 30 segundos (30000ms) para mejorar la fiabilidad
            }, { enableHighAccuracy:true, timeout:30000, maximumAge:0 });
        } else {
            alert("Geolocalización no soportada en este navegador.");
        }
    }

    // Botón fuerza actualización
    btnActualizar.addEventListener('click', actualizarUbicacion);

    // Evitar mapa en blanco (se llama después de un breve retraso para asegurar que el DOM cargó)
    setTimeout(()=>{ map.invalidateSize(); },300);
});
</script>

</body>
</html>