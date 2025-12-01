@extends('layouts.dashboard')

@section('title', 'Mapa de Reclamos - Nexora Bolivia')

@section('header')
    Mapa de Operaciones <span class="text-slate-500 text-lg font-medium ml-2">| Supervisión Técnica</span>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
    #map {
        height: calc(100vh - 280px); /* Ajustado para el layout dashboard */
        width: 100%;
        border-radius: 16px;
        border: 1px solid #e2e8f0; /* slate-200 */
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        z-index: 0;
        background-color: #f8fafc; /* slate-50 */
    }
    .map-legend {
        background: white;
        padding: 20px;
        border-radius: 16px;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        margin-bottom: 24px;
        border: 1px solid #f1f5f9; /* slate-100 */
    }
    .legend-item {
        display: flex;
        align-items: center;
        margin-bottom: 0;
        font-size: 0.875rem;
        color: #475569; /* slate-600 */
    }
    .legend-color {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-right: 8px;
        box-shadow: 0 0 0 2px white, 0 0 0 4px #f1f5f9;
    }
    .legend-resuelto {
        background-color: #10b981; /* emerald-500 */
        box-shadow: 0 0 10px #10b981;
    }
    .legend-pendiente {
        background-color: #f43f5e; /* rose-500 */
        box-shadow: 0 0 10px #f43f5e;
    }
    .map-stats {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 24px;
        margin-bottom: 24px;
    }
    @media (min-width: 768px) {
        .map-stats {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    .stat-box {
        background: white;
        padding: 24px;
        border-radius: 16px;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        border: 1px solid #f1f5f9;
        transition: transform 0.2s;
    }
    .stat-box:hover {
        transform: translateY(-2px);
    }
    .stat-box h3 {
        margin: 0;
        font-size: 32px;
        font-weight: 800;
        color: #1e293b; /* slate-800 */
    }
    .stat-box p {
        margin: 8px 0 0 0;
        color: #64748b; /* slate-500 */
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    /* Highlight Animation */
    @keyframes pulse-ring {
        0% { transform: scale(0.33); opacity: 1; }
        80%, 100% { opacity: 0; }
    }
    .marker-highlight {
        position: relative;
    }
    .marker-highlight::before {
        content: '';
        position: absolute;
        left: -12px; top: -12px;
        width: 44px; height: 44px;
        border-radius: 50%;
        background-color: rgba(239, 68, 68, 0.6); /* red-500 */
        animation: pulse-ring 1.25s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
    }
</style>
@endpush

@section('content')
    <!-- Alertas -->
    @if(session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg relative shadow-sm" role="alert">
            <strong class="font-bold"><i class="fas fa-check-circle mr-1"></i> ¡Éxito!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    @if($errors->any())
        <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-lg relative shadow-sm" role="alert">
            <strong class="font-bold"><i class="fas fa-exclamation-circle mr-1"></i> ¡Atención!</strong>
            <ul class="mt-1 list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- DEBUG INFO (Visible para el usuario) -->
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-bug text-yellow-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-yellow-700">
                    <strong>Debug Info:</strong><br>
                    Reclamos Resueltos cargados: <strong>{{ $reclamosResueltos->count() }}</strong><br>
                    Reclamos Pendientes cargados: <strong>{{ $reclamosPendientes->count() }}</strong><br>
                    Total en BD con coordenadas: <strong>{{ \App\Models\Reclamo::whereNotNull('latitudIncidente')->count() }}</strong>
                </p>
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="map-stats">
        <div class="stat-box border-l-4 border-l-emerald-500">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-emerald-600">{{ $reclamosResueltos->count() }}</h3>
                    <p class="text-emerald-600/80">Resueltos</p>
                </div>
                <div class="p-3 bg-emerald-50 rounded-lg text-emerald-500">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
            </div>
        </div>
        <div class="stat-box border-l-4 border-l-rose-500">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-rose-600">{{ $reclamosPendientes->count() }}</h3>
                    <p class="text-rose-600/80">Pendientes</p>
                </div>
                <div class="p-3 bg-rose-50 rounded-lg text-rose-500">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
            </div>
        </div>
        <div class="stat-box border-l-4 border-l-cyan-500">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-cyan-600">{{ $reclamosResueltos->count() + $reclamosPendientes->count() }}</h3>
                    <p class="text-cyan-600/80">Total Casos</p>
                </div>
                <div class="p-3 bg-cyan-50 rounded-lg text-cyan-500">
                    <i class="fas fa-folder-open text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros y Leyenda -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 mb-6">
        
        <!-- Buscador -->
        <div class="w-full mb-6">
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Buscar por ID (ej: 15), Cliente o Título..." 
                    class="w-full pl-12 pr-4 py-3 rounded-xl border border-slate-200 shadow-sm focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all text-slate-700 font-medium"
                    onkeyup="if(event.key === 'Enter') searchClaim()">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-slate-400 text-lg"></i>
                </div>
                <button onclick="searchClaim()" class="absolute inset-y-1 right-1 bg-cyan-600 hover:bg-cyan-700 text-white px-4 rounded-lg font-bold text-sm transition-colors">
                    Buscar
                </button>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
            
            <!-- Filtros -->
            <div class="flex-1 w-full">
                <h3 class="text-slate-800 font-bold mb-4 flex items-center text-sm uppercase tracking-wider">
                    <i class="fas fa-filter mr-2 text-slate-400"></i> Filtros de Visualización
                </h3>
                <div class="flex flex-wrap gap-4">
                    <label class="flex items-center gap-2 cursor-pointer bg-slate-50 px-4 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-100 transition select-none">
                        <input type="checkbox" id="filter-resueltos" checked onchange="filterMap()" class="rounded text-emerald-500 focus:ring-emerald-500 w-5 h-5 border-slate-300">
                        <span class="text-slate-700 font-bold text-sm">Resueltos</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer bg-slate-50 px-4 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-100 transition select-none">
                        <input type="checkbox" id="filter-pendientes" checked onchange="filterMap()" class="rounded text-rose-500 focus:ring-rose-500 w-5 h-5 border-slate-300">
                        <span class="text-slate-700 font-bold text-sm">Pendientes</span>
                    </label>
                    <div class="relative min-w-[200px]">
                        <select id="filter-prioridad" onchange="filterMap()" class="appearance-none bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 block w-full p-2.5 pr-8 font-bold cursor-pointer hover:bg-slate-100 transition">
                            <option value="all">Todas las Prioridades</option>
                            <option value="Alta">Alta</option>
                            <option value="Media">Media</option>
                            <option value="Baja">Baja</option>
                            <option value="Urgente">Urgente</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Leyenda -->
            <div class="w-full lg:w-auto lg:border-l lg:border-slate-100 lg:pl-8">
                <h3 class="text-slate-800 font-bold mb-4 text-sm uppercase tracking-wider">Leyenda</h3>
                <div class="flex gap-6">
                    <div class="legend-item">
                        <div class="legend-color legend-resuelto"></div>
                        <span class="font-bold text-slate-600">Resueltos</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color legend-pendiente"></div>
                        <span class="font-bold text-slate-600">Pendientes</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mapa -->
    <div id="map"></div>

    {{-- MODAL ASIGNAR TÉCNICO --}}
    <div id="assignModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity backdrop-blur-sm" aria-hidden="true" onclick="closeAssignModal()"></div>

        <!-- Modal Container -->
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                
                <!-- Modal Content -->
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-100">
                    
                    <!-- Header con gradiente fresco -->
                    <div class="bg-gradient-to-r from-slate-800 to-slate-700 px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-bold text-white flex items-center" id="modal-title">
                                <i class="fas fa-user-plus mr-2 text-cyan-400"></i>
                                Asignar Técnico
                            </h3>
                            <button type="button" onclick="closeAssignModal()" class="text-slate-400 hover:text-white transition-colors">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <form id="assignForm" method="POST" action="" novalidate>
                        @csrf
                        @method('PUT')
                        <div class="bg-white px-4 pt-6 pb-4 sm:p-6">
                            
                            <!-- Detalles del Reclamo -->
                            <div class="mb-6 bg-slate-50 p-4 rounded-lg border border-slate-200">
                                <h4 class="font-bold text-slate-800 mb-3 flex items-center border-b border-slate-200 pb-2">
                                    <i class="fas fa-info-circle mr-2 text-cyan-600"></i> Detalles del Reclamo
                                </h4>
                                <div class="grid grid-cols-1 gap-2 text-sm">
                                    <p class="flex justify-between"><span class="font-semibold text-slate-600">Cliente:</span> <span id="modal-cliente" class="text-slate-800 font-medium text-right"></span></p>
                                    <p class="flex justify-between"><span class="font-semibold text-slate-600">Dirección:</span> <span id="modal-direccion" class="text-slate-800 font-medium text-right truncate max-w-[200px]"></span></p>
                                    <div class="mt-2">
                                        <span class="font-semibold text-slate-600 block mb-1">Descripción del Problema:</span>
                                        <p id="modal-descripcion" class="text-slate-700 italic bg-white p-2 rounded border border-slate-100 text-xs"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-5">
                                
                                <div>
                                    <label for="idTecnico" class="block text-sm font-bold text-slate-700 mb-1.5">Seleccionar Técnico</label>
                                    <div class="relative">
                                        <select id="idTecnico" name="idTecnico" required class="block w-full pl-3 pr-10 py-2.5 text-base border-slate-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm rounded-lg transition-all shadow-sm">
                                            <option value="">-- Seleccione un técnico disponible --</option>
                                            @foreach($tecnicos as $tecnico)
                                                <option value="{{ $tecnico->idEmpleado }}">
                                                    {{ $tecnico->primerNombre }} {{ $tecnico->apellidoPaterno }} 
                                                    ({{ $tecnico->tecnico->especialidad ?? 'General' }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-500">
                                            <i class="fas fa-chevron-down text-xs"></i>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label for="prioridad" class="block text-sm font-bold text-slate-700 mb-1.5">Nivel de Prioridad</label>
                                    <div class="grid grid-cols-4 gap-2">
                                        <label class="cursor-pointer">
                                            <input type="radio" name="prioridad" value="Baja" class="peer sr-only">
                                            <div class="text-center py-2 rounded-lg border border-slate-200 bg-slate-50 text-slate-600 text-sm font-medium hover:bg-slate-100 peer-checked:bg-emerald-100 peer-checked:text-emerald-700 peer-checked:border-emerald-300 transition-all">
                                                Baja
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="prioridad" value="Media" checked class="peer sr-only">
                                            <div class="text-center py-2 rounded-lg border border-slate-200 bg-slate-50 text-slate-600 text-sm font-medium hover:bg-slate-100 peer-checked:bg-blue-100 peer-checked:text-blue-700 peer-checked:border-blue-300 transition-all">
                                                Media
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="prioridad" value="Alta" class="peer sr-only">
                                            <div class="text-center py-2 rounded-lg border border-slate-200 bg-slate-50 text-slate-600 text-sm font-medium hover:bg-slate-100 peer-checked:bg-orange-100 peer-checked:text-orange-700 peer-checked:border-orange-300 transition-all">
                                                Alta
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="prioridad" value="Urgente" class="peer sr-only">
                                            <div class="text-center py-2 rounded-lg border border-slate-200 bg-slate-50 text-slate-600 text-sm font-medium hover:bg-slate-100 peer-checked:bg-rose-100 peer-checked:text-rose-700 peer-checked:border-rose-300 transition-all">
                                                Urgente
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Ubicación del Incidente</label>
                                    <div class="relative overflow-hidden rounded-lg border border-slate-200 shadow-sm">
                                        <div id="mapa-asignacion" class="w-full h-48 z-0"></div>
                                        <div class="absolute bottom-0 left-0 right-0 bg-white/90 backdrop-blur-sm py-1 px-3 text-xs text-slate-500 border-t border-slate-100 flex items-center">
                                            <i class="fas fa-map-marker-alt mr-1.5 text-rose-500"></i> Verifique la zona antes de asignar
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label for="comentario" class="block text-sm font-bold text-slate-700 mb-1.5">Instrucciones para el Técnico</label>
                                    <textarea id="comentario" name="comentario" rows="3" class="block w-full border-slate-200 bg-slate-50 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm transition-all" placeholder="Describa el problema y las herramientas necesarias..."></textarea>
                                </div>

                            </div>
                        </div>
                        <div class="bg-slate-50 px-4 py-4 sm:px-6 sm:flex sm:flex-row-reverse border-t border-slate-100 gap-3">
                            <button type="submit" class="w-full inline-flex justify-center items-center rounded-lg border border-transparent shadow-lg shadow-cyan-500/30 px-4 py-2.5 bg-gradient-to-r from-cyan-600 to-blue-600 text-base font-bold text-white hover:from-cyan-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 sm:w-auto sm:text-sm transition-all transform hover:scale-105">
                                <i class="fas fa-check-circle mr-2"></i> Confirmar Asignación
                            </button>
                            <button type="button" onclick="closeAssignModal()" class="mt-3 w-full inline-flex justify-center items-center rounded-lg border border-slate-300 shadow-sm px-4 py-2.5 bg-white text-base font-bold text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 sm:mt-0 sm:w-auto sm:text-sm transition-all">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Datos de reclamos desde PHP
    const reclamosResueltos = @json($reclamosResueltos);
    const reclamosPendientes = @json($reclamosPendientes);

    console.log('Reclamos Resueltos:', reclamosResueltos);
    console.log('Reclamos Pendientes:', reclamosPendientes);

    if (reclamosResueltos.length > 0) {
        console.log('Primer Resuelto:', reclamosResueltos[0]);
        console.log('Lat:', reclamosResueltos[0].latitudIncidente, 'Lng:', reclamosResueltos[0].longitudIncidente);
    }

    // Inicializar mapa centrado en La Paz, Bolivia
    const map = L.map('map', {
        zoomControl: false
    }).setView([-16.5000, -68.1500], 13);

    L.control.zoom({
        position: 'bottomright'
    }).addTo(map);

    // Usar OpenStreetMap estándar (Claro)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Test Marker REMOVED

    // Función para crear icono personalizado con MÁS brillo
    function createCustomIcon(color, glowColor) {
        return L.divIcon({
            className: 'custom-marker',
            html: `<div style="background-color: ${color}; width: 14px; height: 14px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 15px 2px ${glowColor}, 0 0 5px ${color};"></div>`,
            iconSize: [14, 14],
            iconAnchor: [7, 7]
        });
    }

    // Almacenar todos los marcadores
    let markers = [];
    let markersResueltos = [];
    let markersPendientes = [];

    // Función para agregar marcadores
    function addMarkers() {
        // Limpiar marcadores existentes
        markers.forEach(marker => map.removeLayer(marker));
        markers = [];
        markersResueltos = [];
        markersPendientes = [];

        const showResueltos = document.getElementById('filter-resueltos').checked;
        const showPendientes = document.getElementById('filter-pendientes').checked;
        const prioridadFilter = document.getElementById('filter-prioridad').value;

        console.log('Filtrando mapa...', { showResueltos, showPendientes, prioridadFilter });

        // Agregar marcadores para reclamos resueltos (Emerald)
        if (showResueltos) {
            reclamosResueltos.forEach(function(reclamo) {
                if (reclamo.latitudIncidente && reclamo.longitudIncidente) {
                    if (prioridadFilter !== 'all' && reclamo.prioridad !== prioridadFilter) {
                        console.log(`R-${reclamo.idReclamo} oculto por prioridad: ${reclamo.prioridad} != ${prioridadFilter}`);
                        return;
                    }
                    
                    const lat = parseFloat(String(reclamo.latitudIncidente).trim());
                    const lng = parseFloat(String(reclamo.longitudIncidente).trim());

                    if (isNaN(lat) || isNaN(lng)) {
                        console.error(`R-${reclamo.idReclamo} tiene coordenadas inválidas:`, reclamo.latitudIncidente, reclamo.longitudIncidente);
                        return;
                    }

                    const clienteNombre = reclamo.usuario 
                        ? `${reclamo.usuario.primerNombre || ''} ${reclamo.usuario.apellidoPaterno || ''}`.trim() || 'N/A'
                        : 'N/A';
                    
                    const popupContent = `
                        <div class="p-2 min-w-[200px]">
                            <h4 class="font-bold text-emerald-600 mb-2 text-base border-b border-emerald-100 pb-1">R-${reclamo.idReclamo} - Resuelto</h4>
                            <p class="text-slate-600 text-sm mb-1"><strong class="text-slate-800">Cliente:</strong> ${clienteNombre}</p>
                            <p class="text-slate-600 text-sm mb-1"><strong class="text-slate-800">Título:</strong> ${reclamo.titulo}</p>
                            <p class="text-slate-600 text-sm mb-1"><strong class="text-slate-800">Fecha:</strong> ${new Date(reclamo.fechaCreacion).toLocaleDateString('es-BO')}</p>
                            ${reclamo.fechaResolucion ? `<p class="text-emerald-600 text-sm font-bold mt-2 bg-emerald-50 p-1 rounded">Resuelto: ${new Date(reclamo.fechaResolucion).toLocaleDateString('es-BO')}</p>` : ''}
                        </div>
                    `;

                    const marker = L.marker([lat, lng], {
                        icon: createCustomIcon('#10b981', '#34d399') // Emerald-500 con brillo
                    })
                    .addTo(map)
                    .bindPopup(popupContent);
                    
                    markers.push(marker);
                    markersResueltos.push(marker);
                }
            });
        }

        // Agregar marcadores para reclamos pendientes (Rose)
        if (showPendientes) {
            reclamosPendientes.forEach(function(reclamo) {
                if (reclamo.latitudIncidente && reclamo.longitudIncidente) {
                    if (prioridadFilter !== 'all' && reclamo.prioridad !== prioridadFilter) {
                        console.log(`R-${reclamo.idReclamo} oculto por prioridad: ${reclamo.prioridad} != ${prioridadFilter}`);
                        return;
                    }
                    
                    const lat = parseFloat(String(reclamo.latitudIncidente).trim());
                    const lng = parseFloat(String(reclamo.longitudIncidente).trim());

                    if (isNaN(lat) || isNaN(lng)) {
                        console.error(`R-${reclamo.idReclamo} tiene coordenadas inválidas:`, reclamo.latitudIncidente, reclamo.longitudIncidente);
                        return;
                    }

                    const clienteNombre = reclamo.usuario 
                        ? `${reclamo.usuario.primerNombre || ''} ${reclamo.usuario.apellidoPaterno || ''}`.trim() || 'N/A'
                        : 'N/A';
                    
                    const tecnicoNombre = reclamo.tecnico 
                        ? `${reclamo.tecnico.primerNombre || ''} ${reclamo.tecnico.apellidoPaterno || ''}`.trim() || 'Sin asignar'
                        : 'Sin asignar';
                    
                    let actionButton = '';
                    if (!reclamo.idTecnicoAsignado) {
                        actionButton = `
                            <button onclick="openAssignModal(${reclamo.idReclamo}, ${lat}, ${lng})" class="mt-3 w-full px-3 py-1.5 bg-cyan-600 text-white text-xs font-bold rounded hover:bg-cyan-700 transition-colors shadow-sm flex items-center justify-center">
                                <i class="fas fa-user-plus mr-1.5"></i> Asignar Técnico
                            </button>
                        `;
                    }

                    const popupContent = `
                        <div class="p-2 min-w-[200px]">
                            <h4 class="font-bold text-rose-600 mb-2 text-base border-b border-rose-100 pb-1">R-${reclamo.idReclamo} - Pendiente</h4>
                            <p class="text-slate-600 text-sm mb-1"><strong class="text-slate-800">Cliente:</strong> ${clienteNombre}</p>
                            <p class="text-slate-600 text-sm mb-1"><strong class="text-slate-800">Título:</strong> ${reclamo.titulo}</p>
                            <p class="text-slate-600 text-sm mb-1"><strong class="text-slate-800">Prioridad:</strong> <span class="uppercase font-bold text-xs px-2 py-0.5 rounded bg-slate-100">${reclamo.prioridad}</span></p>
                            <p class="text-slate-600 text-sm mb-1"><strong class="text-slate-800">Técnico:</strong> ${tecnicoNombre}</p>
                            ${actionButton}
                        </div>
                    `;

                    const marker = L.marker([lat, lng], {
                        icon: createCustomIcon('#f43f5e', '#fb7185') // Rose-500 con brillo
                    })
                    .addTo(map)
                    .bindPopup(popupContent);
                    
                    markers.push(marker);
                    markersPendientes.push(marker);
                }
            });
        }
    }

    // Función para filtrar el mapa
    function filterMap() {
        addMarkers();
    }

    // --- Lógica de Búsqueda y Resaltado ---
    let highlightLayer = null;

    function searchClaim() {
        const query = document.getElementById('searchInput').value.toLowerCase().trim();
        if (!query) return;

        const allClaims = [...reclamosResueltos, ...reclamosPendientes];
        const found = allClaims.find(r => 
            String(r.idReclamo).includes(query) ||
            (r.usuario && (r.usuario.primerNombre + ' ' + r.usuario.apellidoPaterno).toLowerCase().includes(query)) ||
            r.titulo.toLowerCase().includes(query)
        );

        if (found) {
            if (found.latitudIncidente && found.longitudIncidente) {
                const lat = parseFloat(found.latitudIncidente);
                const lng = parseFloat(found.longitudIncidente);
                
                // Fly to location
                map.flyTo([lat, lng], 16, { duration: 1.5 });

                // Remove previous highlight
                if (highlightLayer) map.removeLayer(highlightLayer);

                // Create highlight marker (Big Red Dot with Pulse)
                const highlightIcon = L.divIcon({
                    className: 'marker-highlight',
                    html: `<div style="background-color: #ef4444; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 20px #ef4444;"></div>`,
                    iconSize: [20, 20],
                    iconAnchor: [10, 10]
                });

                highlightLayer = L.marker([lat, lng], { icon: highlightIcon, zIndexOffset: 1000 }).addTo(map);

                // Open popup of the existing marker at that location
                const targetMarker = markers.find(m => {
                    const mLat = m.getLatLng().lat;
                    const mLng = m.getLatLng().lng;
                    return Math.abs(mLat - lat) < 0.0001 && Math.abs(mLng - lng) < 0.0001;
                });

                if (targetMarker) {
                    setTimeout(() => targetMarker.openPopup(), 1500);
                }
            }
        } else {
            alert('No se encontró ningún reclamo con ese criterio.');
        }
    }

    // Inicializar marcadores
    addMarkers();

    // Asegurar que el mapa se redibuje correctamente
    setTimeout(function() {
        map.invalidateSize();
    }, 300);

    // --- Lógica del Modal ---
    let mapModal = null;
    let markerModal = null;

    function openAssignModal(reclamoId, lat, lng) {
        const modal = document.getElementById('assignModal');
        const form = document.getElementById('assignForm');
        
        // Usamos la ruta de SupervisorTecnicoController para reasignar (PUT)
        form.action = `/supervisor/tecnicos/reclamo/${reclamoId}/reasignar`; 
        
        modal.classList.remove('hidden');

        // Poblar datos del modal
        const reclamo = reclamosPendientes.find(r => r.idReclamo == reclamoId) || reclamosResueltos.find(r => r.idReclamo == reclamoId);
        
        if (reclamo) {
            const nombreCliente = reclamo.usuario ? `${reclamo.usuario.primerNombre} ${reclamo.usuario.apellidoPaterno}` : 'N/A';
            const direccion = reclamo.usuario ? reclamo.usuario.direccionTexto : 'Sin dirección';
            const descripcion = reclamo.descripcionDetallada || 'Sin descripción';

            document.getElementById('modal-cliente').textContent = nombreCliente;
            document.getElementById('modal-direccion').textContent = direccion;
            document.getElementById('modal-descripcion').textContent = descripcion;
        }

        // Inicializar mapa después de que el modal sea visible
        setTimeout(() => {
            if (!mapModal) {
                mapModal = L.map('mapa-asignacion').setView([lat, lng], 15);
                
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(mapModal);
            } else {
                mapModal.invalidateSize();
                mapModal.setView([lat, lng], 15);
            }

            if (markerModal) {
                mapModal.removeLayer(markerModal);
            }
            
            var customIcon = L.divIcon({
                className: 'custom-div-icon',
                html: "<div style='background-color: #06b6d4; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 10px #06b6d4;'></div>",
                iconSize: [12, 12],
                iconAnchor: [6, 6]
            });

            markerModal = L.marker([lat, lng], {icon: customIcon}).addTo(mapModal)
                .bindPopup('<div class="text-slate-800 font-bold">Ubicación del Incidente</div>').openPopup();
        }, 300);
    }

    function closeAssignModal() {
        const modal = document.getElementById('assignModal');
        modal.classList.add('hidden');
    }
</script>
@endpush




