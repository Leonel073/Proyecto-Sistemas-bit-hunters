@extends('layouts.client')

@section('title', 'Registrar Reclamo - Nexora Bolivia')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
@endpush

@section('content')
<div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">Nuevo Reclamo</h1>
        <p class="mt-2 text-slate-600">Complete el siguiente formulario para reportar un incidente técnico. Nuestro equipo procesará su solicitud a la brevedad.</p>
    </div>

    <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-slate-200">
        
        <!-- Form Header -->
        <div class="bg-slate-900 px-8 py-4 border-b border-slate-800">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-white flex items-center gap-2">
                    <i class="fas fa-file-alt text-indigo-400"></i> Formulario de Registro
                </h3>
                <span class="px-3 py-1 rounded-full bg-slate-800 text-xs font-semibold text-slate-300 border border-slate-700">
                    ID: {{ uniqid() }}
                </span>
            </div>
        </div>
        
        <div class="p-8">
            <!-- Alerts -->
            @if(session('success'))
                <div class="mb-8 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg flex items-start shadow-sm">
                    <i class="fas fa-check-circle mt-1 mr-3 text-lg"></i>
                    <div>
                        <span class="font-bold block">Solicitud Enviada</span>
                        {{ session('success') }}
                    </div>
                </div>
            @endif
            
            @if($errors->any())
                <div class="mb-8 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-lg shadow-sm">
                    <div class="flex items-center mb-2 font-bold">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Por favor corrija los siguientes errores:
                    </div>
                    <ul class="list-disc list-inside text-sm ml-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form action="{{ route('reclamo.store') }}" method="POST" class="space-y-8" novalidate>
                @csrf

                <!-- Section 1: General Information -->
                <div>
                    <h4 class="text-slate-900 font-bold text-lg mb-4 pb-2 border-b border-slate-100 flex items-center">
                        <span class="bg-indigo-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs mr-2">1</span>
                        Detalles del Incidente
                    </h4>

                    @if(isset($usuarios) && $usuarios)
                        <div class="mb-6 bg-slate-50 border border-slate-200 rounded-lg p-4">
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                <i class="fas fa-user-shield mr-1 text-slate-500"></i> Modo Administrativo: Cliente Afectado
                            </label>
                            <select name="idUsuario" class="w-full rounded-md border-slate-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 bg-white text-slate-700 py-2 px-3 shadow-sm" required>
                                <option value="">Seleccione un cliente...</option>
                                @foreach($usuarios as $usuario)
                                    <option value="{{ $usuario->idUsuario }}">{{ $usuario->apellidoPaterno }} {{ $usuario->apellidoMaterno }} {{ $usuario->primerNombre }} (CI: {{ $usuario->ci }})</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Asunto del Reclamo <span class="text-rose-500">*</span></label>
                            <input type="text" name="titulo" class="w-full rounded-md border-slate-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 shadow-sm py-2 px-3 placeholder-slate-400" required value="{{ old('titulo') }}" placeholder="Ej: Interrupción del servicio de internet">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Categoría <span class="text-rose-500">*</span></label>
                            <select name="idTipoIncidente" class="w-full rounded-md border-slate-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 shadow-sm py-2 px-3 bg-white" required>
                                <option value="">Seleccionar...</option>
                                <option value="1" {{ old('idTipoIncidente') == 1 ? 'selected' : '' }}>Falla de Conexión</option>
                                <option value="2" {{ old('idTipoIncidente') == 2 ? 'selected' : '' }}>Lentitud de Servicio</option>
                                <option value="3" {{ old('idTipoIncidente') == 3 ? 'selected' : '' }}>Instalación o Cambio de Servicio</option>
                                <option value="4" {{ old('idTipoIncidente') == 4 ? 'selected' : '' }}>Problema con Equipo (Router/Módem)</option>
                            </select>
                        </div>

                        <div class="col-span-3">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Descripción Detallada <span class="text-rose-500">*</span></label>
                            <textarea name="descripcionDetallada" class="w-full rounded-md border-slate-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 shadow-sm py-2 px-3 placeholder-slate-400" required rows="4" placeholder="Por favor describa el problema con el mayor detalle posible, incluyendo cuándo comenzó el incidente...">{{ old('descripcionDetallada') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Location -->
                <div>
                    <h4 class="text-slate-900 font-bold text-lg mb-4 pb-2 border-b border-slate-100 flex items-center">
                        <span class="bg-indigo-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs mr-2">2</span>
                        Ubicación Geográfica
                    </h4>
                    
                    <input type="hidden" id="latitudIncidente" name="latitudIncidente" value="{{ old('latitudIncidente', '-16.5000') }}">
                    <input type="hidden" id="longitudIncidente" name="longitudIncidente" value="{{ old('longitudIncidente', '-68.1500') }}">
                    
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                        <p class="text-sm text-slate-600">
                            Haga clic en el mapa o arrastre el marcador para indicar la ubicación exacta del incidente.
                        </p>
                        <button type="button" id="btn-location" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-md font-semibold text-xs text-slate-700 uppercase tracking-widest shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            <i class="fas fa-location-arrow mr-2 text-indigo-600"></i> Usar mi ubicación
                        </button>
                    </div>
                    
                    <div class="bg-slate-50 border border-slate-200 rounded-md p-3 mb-4 flex items-center gap-3">
                        <i class="fas fa-map-marker-alt text-indigo-500"></i>
                        <span id="location-text" class="text-sm text-slate-700 font-mono">Coordenadas: -16.5000, -68.1500</span>
                    </div>
                    
                    <div class="relative rounded-lg overflow-hidden border border-slate-300 shadow-sm h-[400px]">
                        <div id="map" class="w-full h-full z-10"></div>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        <i class="fas fa-paper-plane mr-2"></i> Registrar Reclamo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    var latInput = document.getElementById('latitudIncidente');
    var lngInput = document.getElementById('longitudIncidente');
    var locationText = document.getElementById('location-text');
    var btnLocation = document.getElementById('btn-location');
    
    var initialLat = parseFloat(latInput.value);
    var initialLng = parseFloat(lngInput.value);

    // Inicializar mapa
    var map = L.map('map').setView([initialLat, initialLng], 14);
    
    L.tileLayer('//{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    var marker = L.marker([initialLat, initialLng], {draggable:true}).addTo(map);

    function updateLocation(lat, lng) {
        latInput.value = lat.toFixed(6);
        lngInput.value = lng.toFixed(6);
        locationText.textContent = "Coordenadas: " + lat.toFixed(6) + ", " + lng.toFixed(6);
    }

    marker.on('dragend', function(e){
        var pos = e.target.getLatLng();
        updateLocation(pos.lat, pos.lng);
    });

    map.on('click', function(e){
        marker.setLatLng(e.latlng);
        updateLocation(e.latlng.lat, e.latlng.lng);
    });
    
    btnLocation.addEventListener('click', function() {
        var originalText = locationText.textContent;
        locationText.innerHTML = "<i class='fas fa-spinner fa-spin'></i> Obteniendo ubicación...";
        
        if (!navigator.geolocation) {
            locationText.textContent = "Geolocalización no soportada.";
            return;
        }
        
        navigator.geolocation.getCurrentPosition(
            function(position) {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;
                
                marker.setLatLng([lat, lng]);
                map.setView([lat, lng], 16);
                updateLocation(lat, lng);
            },
            function(error) {
                locationText.textContent = "Error al obtener ubicación.";
                console.error(error);
            },
            { enableHighAccuracy: true, timeout: 10000, maximumAge: 60000 }
        );
    });
    
    setTimeout(()=>{ map.invalidateSize(); },300);
});
</script>
@endpush
