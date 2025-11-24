<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registrar Reclamo</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

    <style>
        /* Define la altura CRÍTICA para que el mapa se muestre */
        #map {
            height: 350px;
            width: 100%;
            border-radius: 0.25rem;
            margin-top: 15px;
            margin-bottom: 15px;
            border: 1px solid #ced4da;
        }
    </style>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Nuevo Reclamo</h3>
                </div>
                <div class="card-body">

                    <form action="{{ route('reclamo.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Título *</label>
                            <input type="text" name="titulo" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tipo de Incidente *</label>
                            <select name="idTipoIncidente" class="form-select" required>
                                <option value="">Seleccione...</option>
                                <option value="1">Falla de Conexión</option>
                                <option value="2">Lentitud</option>
                                <option value="3">Facturación</option>
                                <option value="4">Soporte Técnico</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Prioridad *</label>
                            <select name="prioridad" class="form-select" required>
                                <option value="Baja">Baja</option>
                                <option value="Media" selected>Media</option>
                                <option value="Alta">Alta</option>
                                <option value="Urgente">Urgente</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Descripción *</label>
                            <textarea name="descripcionDetallada" class="form-control" required></textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label>Latitud</label>
                                <input type="text" id="latitudIncidente" name="latitudIncidente" class="form-control" readonly required value="-16.5000">
                            </div>
                            <div class="col">
                                <label>Longitud</label>
                                <input type="text" id="longitudIncidente" name="longitudIncidente" class="form-control" readonly required value="-68.1500">
                            </div>
                        </div>

                        <button type="button" id="btnActualizar" class="btn btn-warning mb-3 w-100">
                            Actualizar a mi Ubicación Actual
                        </button>

                        <div id="map"></div>
                        <small class="text-muted text-center d-block mb-3">
                            Haga clic en el mapa o mueva el marcador para seleccionar la ubicación.
                        </small>

                        <button type="submit" class="btn btn-success btn-lg w-100">Enviar Reclamo</button>
                    </form>
                </div>
            </div>
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