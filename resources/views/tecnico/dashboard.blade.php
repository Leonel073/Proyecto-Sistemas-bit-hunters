@extends('layouts.app')

@section('title', 'Panel de Técnico - Reclamos')

@section('content')
@php
$estadoActual = $estadoActual ?? 'No Disponible';

// Colores modernos para cada estado
$color = [
    'Disponible' => 'bg-gradient-to-r from-green-400 to-emerald-500 text-white',
    'En Ruta' => 'bg-gradient-to-r from-blue-400 to-cyan-500 text-white',
    'Ocupado' => 'bg-gradient-to-r from-orange-400 to-red-500 text-white',
    'No Disponible' => 'bg-gradient-to-r from-gray-400 to-slate-500 text-white',
];

$opcionesEstado = ['Disponible', 'En Ruta', 'Ocupado'];

$prioridadColor = function($prioridad) {
    return match ($prioridad) {
        'Alta' => 'bg-red-500 text-white',
        'Media' => 'bg-yellow-500 text-gray-900',
        'Baja' => 'bg-blue-500 text-white',
        default => 'bg-gray-400 text-white',
    };
};

$prioridadBorder = function($prioridad) {
    return match ($prioridad) {
        'Alta' => 'border-l-4 border-red-500',
        'Media' => 'border-l-4 border-yellow-500',
        'Baja' => 'border-l-4 border-blue-500',
        default => 'border-l-4 border-gray-400',
    };
};
@endphp

<!-- Leaflet (estilos y scripts) -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<div class="container mx-auto px-4 py-6 bg-gray-50 min-h-screen">

    {{-- ALERTAS --}}
    @if (session('success'))
        <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg shadow-md" role="alert">
            <p class="font-bold text-lg">✓ ¡Éxito!</p>
            <p class="text-sm">{{ session('success') }}</p>
        </div>
    @endif
    @if (session('error'))
        <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-lg shadow-md" role="alert">
            <p class="font-bold text-lg">✕ Error</p>
            <p class="text-sm">{{ session('error') }}</p>
        </div>
    @endif

    {{-- ENCABEZADO CON SALUDO Y METRICS --}}
    <div class="mb-8">
        @vite('resources/css/tecnico.css')

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="tech-avatar bg-gradient-to-br from-indigo-600 to-purple-600 text-white flex items-center justify-center font-extrabold text-xl shadow-md">
                    {{ strtoupper(substr($tecnico->primerNombre,0,1) . substr($tecnico->apellidoPaterno,0,1)) }}
                </div>
                <div>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 leading-tight">
                        👨 Hola, <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">{{ $tecnico->primerNombre }} {{ $tecnico->apellidoPaterno }}</span>
                    </h1>
                    <p class="text-sm small-muted mt-1">Gestiona tus reclamos asignados • Actualiza tu disponibilidad</p>
                </div>
            </div>

            <div class="flex items-center gap-4 overflow-auto">
                <div class="metric-card bg-white rounded-xl shadow p-3 flex flex-col items-center">
                    <span class="text-xs small-muted">Pendientes</span>
                    <span class="text-xl font-bold text-blue-600">{{ count($reclamos->where('estado', 'Asignado')) }}</span>
                </div>
                <div class="metric-card bg-white rounded-xl shadow p-3 flex flex-col items-center">
                    <span class="text-xs small-muted">En Proceso</span>
                    <span class="text-xl font-bold text-yellow-600">{{ count($reclamos->where('estado', 'En Proceso')) }}</span>
                </div>
                <div class="metric-card bg-white rounded-xl shadow p-3 flex flex-col items-center">
                    <span class="text-xs small-muted">Resueltos</span>
                    <span class="text-xl font-bold text-green-600">{{ count($reclamos->where('estado', 'Resuelto')) }}</span>
                </div>
                <div class="bg-white rounded-xl shadow p-2 flex items-center">
                    @if (Route::has('tecnico.reclamos.nuevos'))
                        <a href="{{ route('tecnico.reclamos.nuevos') }}" class="px-3 py-2 bg-indigo-600 text-white rounded-lg font-semibold hover:opacity-95 transition">Ver Nuevos</a>
                    @else
                        <a href="#" onclick="alert('No hay una ruta configurada para "Ver Nuevos".'); return false;" class="px-3 py-2 bg-indigo-600 text-white rounded-lg font-semibold hover:opacity-95 transition">Ver Nuevos</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- GRID PRINCIPAL: ESTATUS A LA IZQUIERDA, RECLAMOS A LA DERECHA --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        
        {{-- COLUMNA LATERAL: CONTROL DE ESTADO (1 COLUMNA) --}}
        <div class="lg:col-span-1">
            
            {{-- TARJETA DE ESTATUS --}}
            <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-6 hover:shadow-2xl transition-shadow duration-300">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h-2m0 0H8m4 0v2m0-2v-2m0 0h2m0 0l1.414-1.414M12 20a8 8 0 100-16 8 8 0 000 16z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">Mi Estado</h3>
                </div>
                
                {{-- ESTADO ACTUAL CON INDICADOR --}}
                <div class="mb-6 p-4 bg-gray-50 rounded-xl text-center">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Estado Actual:</p>
                    <div class="flex items-center justify-center gap-2 mb-3">
                        <span class="w-3 h-3 rounded-full animate-pulse {{ 
                            $estadoActual === 'Disponible' ? 'bg-green-500' : 
                            ($estadoActual === 'En Ruta' ? 'bg-blue-500' : 
                            ($estadoActual === 'Ocupado' ? 'bg-orange-500' : 'bg-gray-500')) 
                        }}"></span>
                        <span class="inline-block text-2xl font-bold px-4 py-2 rounded-xl {{ $color[$estadoActual] ?? 'bg-gray-500 text-white' }}">
                            {{ $estadoActual }}
                        </span>
                    </div>
                </div>

                {{-- FORMULARIO DE CAMBIO DE ESTADO --}}
                <form action="{{ route('tecnico.estado.update') }}" method="POST" class="space-y-4 border-t pt-4">
                    @csrf
                    
                    <div>
                        <label for="estadoDisponibilidad" class="block text-sm font-semibold text-gray-700 mb-2">Cambiar a:</label>
                        <select name="estadoDisponibilidad" id="estadoDisponibilidad" required 
                                class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition font-medium">
                            <option value="" disabled selected>Selecciona tu nuevo estado</option>
                            @foreach ($opcionesEstado as $opcion)
                                <option value="{{ $opcion }}" @if($opcion === $estadoActual) selected @endif>
                                    {{ $opcion }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="w-full px-4 py-3 rounded-lg font-bold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 transition duration-300 shadow-md hover:shadow-lg">
                        ↻ Actualizar Estado
                    </button>
                </form>

                {{-- ESTADÍSTICAS RÁPIDAS --}}
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Resumen</p>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between items-center p-2 bg-blue-50 rounded-lg">
                            <span class="font-medium text-gray-700">Pendientes:</span>
                            <span class="font-bold text-blue-600">{{ count($reclamos->where('estado', 'Asignado')) }}</span>
                        </div>
                        <div class="flex justify-between items-center p-2 bg-yellow-50 rounded-lg">
                            <span class="font-medium text-gray-700">En Proceso:</span>
                            <span class="font-bold text-yellow-600">{{ count($reclamos->where('estado', 'En Proceso')) }}</span>
                        </div>
                        <div class="flex justify-between items-center p-2 bg-green-50 rounded-lg">
                            <span class="font-medium text-gray-700">Resueltos:</span>
                            <span class="font-bold text-green-600">{{ count($reclamos->where('estado', 'Resuelto')) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- COLUMNA PRINCIPAL: RECLAMOS (3 COLUMNAS) --}}
        <div class="lg:col-span-3">
            
            {{-- ENCABEZADO DE RECLAMOS --}}
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Mis Reclamos</h2>
                            <p class="text-sm text-gray-600">{{ count($reclamos) }} reclamo(s) asignado(s)</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MENSAJE SI NO HAY RECLAMOS --}}
            @if ($reclamos->isEmpty())
                <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                    <div class="inline-block w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">¡Todo Despejado!</h3>
                    <p class="text-gray-600 mb-6">No tienes reclamos activos asignados en este momento.</p>
                    <p class="text-sm text-gray-500">Cuando se asigne un nuevo reclamo, aparecerá aquí.</p>
                </div>
            @else
                {{-- LISTA DE RECLAMOS --}}
                <div class="space-y-6">
                    @foreach ($reclamos as $reclamo)
                        <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden {{ $prioridadBorder($reclamo->prioridad) }}">
                            
                            {{-- ENCABEZADO DE LA TARJETA --}}
                            <div class="p-6 pb-4">
                                <div class="flex justify-between items-start mb-3 gap-4">
                                    <div class="flex-1">
                                        <p class="text-xs font-semibold text-indigo-600 uppercase tracking-wider">Reclamo #{{ $reclamo->idReclamo }}</p>
                                        <h3 class="text-xl font-bold text-gray-900 mt-1">{{ $reclamo->titulo }}</h3>
                                    </div>
                                    <div class="flex gap-2">
                                        <span class="inline-block text-xs font-bold px-3 py-1 rounded-full shadow-sm {{ $prioridadColor($reclamo->prioridad) }}">
                                            🔴 {{ $reclamo->prioridad }}
                                        </span>
                                        <span class="inline-block text-xs font-bold px-3 py-1 rounded-full shadow-sm
                                            @if($reclamo->estado == 'Resuelto' || $reclamo->estado == 'Cerrado') bg-green-100 text-green-800 
                                            @elseif($reclamo->estado == 'En Proceso') bg-blue-100 text-blue-800
                                            @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ $reclamo->estado }}
                                        </span>
                                    </div>
                                </div>

                                {{-- DESCRIPCIÓN --}}
                                <p class="text-gray-700 text-sm leading-relaxed">{{ $reclamo->descripcionDetallada }}</p>
                            </div>

                            {{-- INFORMACIÓN DEL CLIENTE --}}
                            <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-t border-gray-200">
                                <p class="text-sm font-semibold text-gray-900 mb-3">👤 Detalles del Cliente</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div class="flex items-center gap-2 text-sm">
                                        <span class="text-gray-600">Nombre:</span>
                                        <span class="font-medium text-gray-900">{{ $reclamo->usuario->primerNombre ?? 'N/A' }} {{ $reclamo->usuario->apellidoPaterno ?? '' }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-sm">
                                        <span class="text-gray-600">Teléfono:</span>
                                        <span class="font-medium text-gray-900">{{ $reclamo->usuario->numeroCelular ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-sm col-span-2">
                                        <span class="text-gray-600">Dirección:</span>
                                        <span class="font-medium text-gray-900 truncate">{{ $reclamo->usuario->direccionTexto ?? 'No especificada' }}</span>
                                    </div>

                                    {{-- Pequeño mapa con la ubicación del reclamo (si hay coordenadas) --}}
                                    <div class="col-span-2 mt-3">
                                        @if(!empty($reclamo->latitudIncidente) && !empty($reclamo->longitudIncidente))
                                            <div id="map-{{ $reclamo->idReclamo }}" class="small-map reclamo-map" data-lat="{{ $reclamo->latitudIncidente }}" data-lng="{{ $reclamo->longitudIncidente }}" style="height:180px; border-radius:8px; border:1px solid #e5e7eb; overflow:hidden;"></div>
                                        @else
                                            <p class="text-xs text-gray-500">Ubicación no disponible</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- ACCIONES --}}
                            <div class="px-6 py-4 border-t border-gray-200">
                                @if ($reclamo->estado === 'Asignado' || $reclamo->estado === 'Pendiente')
                                    <form action="{{ route('tecnico.reclamo.aceptar', $reclamo->idReclamo) }}" method="POST" class="flex gap-3">
                                        @csrf
                                        <button type="submit" class="flex-1 px-4 py-3 rounded-lg font-bold text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 transition duration-300 shadow-md hover:shadow-lg">
                                            <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Aceptar y Poner En Proceso
                                        </button>
                                    </form>
                                @elseif ($reclamo->estado === 'En Proceso')
                                    <button type="button" 
                                            class="w-full px-4 py-3 rounded-lg font-bold text-white bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 transition duration-300 shadow-md hover:shadow-lg"
                                            onclick="document.getElementById('form-resolver-{{ $reclamo->idReclamo }}').classList.toggle('hidden')">
                                        <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Registrar Solución
                                    </button>
                                @else
                                    <div class="px-4 py-3 bg-green-50 rounded-lg text-center">
                                        <p class="text-sm font-semibold text-green-700">✓ Reclamo Resuelto</p>
                                    </div>
                                @endif
                            </div>

                            {{-- FORMULARIO DE SOLUCIÓN (OCULTO POR DEFECTO) --}}
                            @if ($reclamo->estado === 'En Proceso')
                                <div id="form-resolver-{{ $reclamo->idReclamo }}" class="hidden px-6 py-4 bg-blue-50 border-t border-blue-200">
                                    <form action="{{ route('tecnico.reclamo.resolver', $reclamo->idReclamo) }}" method="POST" class="space-y-4">
                                        @csrf
                                        
                                        <div>
                                            <label for="solucionTecnica-{{ $reclamo->idReclamo }}" class="block text-sm font-bold text-gray-800 mb-2">
                                                Descripción de la Solución *
                                            </label>
                                            <textarea name="solucionTecnica" 
                                                    id="solucionTecnica-{{ $reclamo->idReclamo }}" 
                                                    rows="5" 
                                                    placeholder="Describe detalladamente las acciones tomadas para resolver el problema..." 
                                                    required
                                                    class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-green-500 focus:ring-2 focus:ring-green-200 transition font-medium">{{ old('solucionTecnica') }}</textarea>
                                            
                                            @error('solucionTecnica')
                                                <p class="text-red-500 text-sm mt-2 font-medium">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="flex gap-3">
                                            <button type="submit" class="flex-1 px-4 py-3 rounded-lg font-bold text-white bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 transition duration-300 shadow-md hover:shadow-lg">
                                                ✓ Marcar como Resuelto
                                            </button>
                                            <button type="button" 
                                                    class="flex-1 px-4 py-3 rounded-lg font-bold text-gray-700 bg-white border-2 border-gray-300 hover:bg-gray-50 transition duration-300"
                                                    onclick="document.getElementById('form-resolver-{{ $reclamo->idReclamo }}').classList.toggle('hidden')">
                                                Cancelar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @endif

                            {{-- PIE DE LA TARJETA --}}
                            <div class="px-6 py-3 bg-gray-50 border-t border-gray-200 flex justify-between items-center text-xs text-gray-600">
                                <p><strong>Asignado por:</strong> {{ $reclamo->operador->primerNombre ?? 'N/A' }}</p>
                                <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($reclamo->fechaAsignacion)->format('d/m/Y H:i') ?? 'Pendiente' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const maps = document.querySelectorAll('.reclamo-map');
            if (!maps || maps.length === 0) return;

            maps.forEach(function(el) {
                const lat = parseFloat(el.dataset.lat);
                const lng = parseFloat(el.dataset.lng);
                if (Number.isFinite(lat) && Number.isFinite(lng)) {
                    try {
                        const map = L.map(el.id, { attributionControl:false, zoomControl:true }).setView([lat, lng], 14);
                        L.tileLayer('//{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                            attribution: '© OpenStreetMap contributors'
                        }).addTo(map);

                        const marker = L.marker([lat, lng]).addTo(map);
                        // evitar problemas de renderizado en contenedores dinámicos
                        setTimeout(function(){ map.invalidateSize(); }, 250);
                    } catch (e) {
                        console.error('Leaflet init error:', e);
                    }
                }
            });
        });
    </script>