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
        /* Estilo para la notificación de éxito */
        .notification-success {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
            border-radius: 4px;
            color: #155724;
            background-color: #d4edda;
            text-align: center;
            font-weight: bold;
        }
        /* Estilo para errores de validación */
        .notification-error {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            color: #721c24;
            background-color: #f8d7da;
            text-align: left;
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
                
                {{-- Bloque de Notificaciones --}}
                <div class="card-body">

                    @if(session('success'))
                        <div class="notification-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="notification-error">
                            <p><strong>Error de Validación:</strong> Por favor, corrige los siguientes campos:</p>
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form action="{{ route('reclamo.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Título *</label>
                            <input type="text" name="titulo" class="form-control" required value="{{ old('titulo') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tipo de Incidente *</label>
                            <select name="idTipoIncidente" class="form-select" required>
                                <option value="">Seleccione...</option>
                                <option value="1" {{ old('idTipoIncidente') == 1 ? 'selected' : '' }}>Falla de Conexión</option>
                                <option value="2" {{ old('idTipoIncidente') == 2 ? 'selected' : '' }}>Lentitud</option>
                                <option value="3" {{ old('idTipoIncidente') == 3 ? 'selected' : '' }}>Facturación</option>
                                <option value="4" {{ old('idTipoIncidente') == 4 ? 'selected' : '' }}>Soporte Técnico</option>
                            </select>
                        </div>

                        {{-- PRIORIDAD: Se asigna cuando el operador asigna técnico o el supervisor gestiona --}}

                        <div class="mb-3">
                            <label class="form-label">Descripción *</label>
                            <textarea name="descripcionDetallada" class="form-control" required>{{ old('descripcionDetallada') }}</textarea>
                        </div>
                        
                        <!-- ============================================== -->
                        <!-- CAMPOS OCULTOS DE GEOLOCALIZACIÓN -->
                        <!-- ============================================== -->
                        <input type="hidden" id="latitudIncidente" name="latitudIncidente" value="{{ old('latitudIncidente', '-16.5000') }}">
                        <input type="hidden" id="longitudIncidente" name="longitudIncidente" value="{{ old('longitudIncidente', '-68.1500') }}">
                        
                        <!-- MAPA VISIBLE PARA SELECCIÓN MANUAL -->
                        <label class="form-label mt-3">Ubicación del Incidente (Seleccione en el mapa)</label>
                        <div id="map"></div>
                        <small class="text-muted text-center d-block mb-3">
                            Arrastre el marcador o haga clic en el mapa para especificar la ubicación exacta.
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
    
    var initialLat = parseFloat(latInput.value);
    var initialLng = parseFloat(lngInput.value);

    // Inicializar mapa (Vista centrada en La Paz)
    var map = L.map('map').setView([initialLat, initialLng], 14);
    
    // Añadir capa de OpenStreetMap
    L.tileLayer('//{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Marcador arrastrable (draggable: true)
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
    
    // Forzar el redibujado si la ventana cambia
    setTimeout(()=>{ map.invalidateSize(); },300);
});
</script>

</body>
</html>