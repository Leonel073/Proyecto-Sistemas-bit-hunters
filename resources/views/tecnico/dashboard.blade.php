@extends('layouts.app')

@section('title', 'Panel de Técnico - Reclamos')

@section('content')
{{--
Recuperación de datos del técnico (asumiendo que $tecnico es el modelo de Empleado autenticado)
y aseguramos que $estadoActual esté definido.
--}}
@php
$estadoActual = $estadoActual ?? 'No Disponible';

// Colores base para cada estado (usando clases de Tailwind)
$color = [
    'Disponible' => 'bg-green-600 text-white',
    'En Ruta' => 'bg-yellow-400 text-gray-800',
    'Ocupado' => 'bg-red-600 text-white',
    'No Disponible' => 'bg-gray-500 text-white',
];

// Definimos los posibles estados que el técnico puede elegir
$opcionesEstado = ['Disponible', 'En Ruta', 'Ocupado'];

// Función de ayuda para la prioridad (Asegúrate de que esta esté definida)
$prioridadColor = function($prioridad) {
    return match ($prioridad) {
        'Alta' => 'bg-red-500 text-white',
        'Media' => 'bg-yellow-500 text-gray-900',
        'Baja' => 'bg-blue-500 text-white',
        default => 'bg-gray-400 text-white',
    };
};

@endphp

<!-- Incluir Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<div class="container mx-auto px-4 py-4">

{{-- Mensajes de Sesión (Éxito/Error) --}}
@if (session('success'))
    <div class="alert-success mb-6 shadow-md bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg" role="alert">
        <p class="font-bold">¡Éxito!</p>
        <p class="text-sm">{{ session('success') }}</p>
    </div>
@endif
@if (session('error'))
    <div class="alert-error mb-6 shadow-md bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg" role="alert">
        <p class="font-bold">Error</p>
        <p class="text-sm">{{ session('error') }}</p>
    </div>
@endif
@if ($errors->any())
    {{-- Mensaje genérico de error de validación --}}
    <div class="alert-error mb-6 shadow-md bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg" role="alert">
        <p class="font-bold">Error de Validación</p>
        <p class="text-sm">Por favor, revisa el formulario en la sección de Reclamos.</p>
    </div>
@endif

{{-- TÍTULO PRINCIPAL DEL PANEL --}}
<h2 class="text-3xl font-extrabold text-gray-900 mb-8 pb-3 border-b-4 border-indigo-200">
    Panel del Técnico: <span class="text-indigo-600">{{ $tecnico->primerNombre }} {{ $tecnico->apellidoPaterno }}</span>
</h2>

{{-- CONTENIDO PRINCIPAL DIVIDIDO EN 2 COLUMNAS (Estatus a la izquierda, Reclamos a la derecha) --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    {{-- COLUMNA LATERAL (Control de Estado - Col 1) --}}
    <div class="lg:col-span-1 order-last lg:order-first">
        
        {{-- 1. CONTROL DE ESTATUS DE DISPONIBILIDAD --}}
        <div class="bg-white p-6 rounded-xl shadow-xl sticky top-4">
            <h3 class="text-xl font-bold text-gray-700 mb-4 border-b pb-2 flex items-center">
                <svg class="w-6 h-6 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 100 4m-4-2a2 2 0 100 4m12 0a2 2 0 100 4m-4-2a2 2 0 100 4M8 10a2 2 0 11-4 0 2 2 0 014 0zM12 18h.01M16 4h.01M16 16h.01"></path></svg>
                Mi Estatus
            </h3>
            
            <div class="mb-4 text-center">
                <p class="text-sm font-medium text-gray-500">Estado Actual:</p>
                <span class="inline-block text-lg font-bold px-4 py-2 rounded-xl shadow-lg mt-2 {{ $color[$estadoActual] ?? 'bg-gray-500 text-white' }}">
                    {{ $estadoActual }}
                </span>
            </div>
            
            {{-- CORREGIDO: Ruta actualizada --}}
            <form action="{{ route('tecnico.actualizar.estado') }}" method="POST" class="flex flex-col gap-4 border-t pt-4">
                @csrf
                @method('PUT')
                
                {{-- Dropdown para seleccionar el estado --}}
                <div>
                    <label for="estadoDisponibilidad" class="block text-sm font-medium text-gray-700 mb-1">Cambiar a:</label>
                    <select name="estadoDisponibilidad" id="estadoDisponibilidad" required class="w-full p-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition">
                        <option value="" disabled>Selecciona tu nuevo estado</option>
                        @foreach ($opcionesEstado as $opcion)
                            <option value="{{ $opcion }}" @if($opcion === $estadoActual) selected @endif>
                                {{ $opcion }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="w-full px-6 py-2 rounded-lg font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition duration-200 shadow-md">
                    Actualizar Estado
                </button>
            </form>
        </div>

    </div>
    
    {{-- COLUMNA PRINCIPAL DE RECLAMOS (Cols 2 y 3) --}}
    <div class="lg:col-span-2 order-first lg:order-last">
        
        {{-- 2. RECLAMOS ASIGNADOS --}}
        <div class="bg-white p-6 rounded-xl shadow-lg">
            <h3 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-3 flex items-center">
                <svg class="w-6 h-6 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                Mis Reclamos Pendientes ({{ count($reclamos) }})
            </h3>
            
            @if ($reclamos->isEmpty())
                <div class="text-center py-10 text-gray-500 border-2 border-dashed border-gray-300 rounded-xl bg-gray-50">
                    <p class="text-xl font-semibold">¡Todo Despejado!</p>
                    <p class="text-sm mt-2">No tienes reclamos activos asignados en este momento.</p>
                </div>
            @else
                <div class="space-y-6">
                    @foreach ($reclamos as $reclamo)
                        {{-- Tarjeta de Reclamo Mejorada --}}
                        <div class="reclamo-card border-l-8 rounded-xl p-5 shadow-md hover:shadow-lg transition duration-300 bg-gray-50" style="border-color: 
                            @if($reclamo->prioridad == 'Alta') #ef4444 /* red-500 */
                            @elseif($reclamo->prioridad == 'Media') #f59e0b /* yellow-500 */
                            @else #3b82f6 /* blue-500 */ @endif;">
                            
                            {{-- Encabezado y Prioridad --}}
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <p class="text-sm font-medium text-indigo-600">Reclamo #{{ $reclamo->idReclamo }}</p>
                                    <h4 class="text-xl font-bold text-gray-900">{{ $reclamo->titulo }}</h4>
                                </div>
                                <span class="text-xs font-bold px-3 py-1 rounded-full uppercase shadow-sm {{ $prioridadColor($reclamo->prioridad) }}">
                                    {{ $reclamo->prioridad }}
                                </span>
                            </div>

                            {{-- Descripción --}}
                            <p class="text-gray-600 mb-4 text-sm border-b pb-4">{{ $reclamo->descripcionDetallada }}</p>
                            
                            {{-- Información del Cliente --}}
                            <div class="mb-4 p-3 bg-white rounded-lg border border-gray-200">
                                <p class="text-md font-semibold text-gray-800 mb-2">Detalles del Cliente</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm text-gray-700">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        <span class="font-medium">Cliente:</span> <span>{{ $reclamo->usuario->primerNombre ?? 'N/A' }} {{ $reclamo->usuario->apellidoPaterno ?? '' }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                        <span class="font-medium">Teléfono:</span> <span>{{ $reclamo->usuario->numeroCelular ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2 col-span-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        <span class="font-medium">Dirección:</span> <span class="truncate">{{ $reclamo->usuario->direccionTexto ?? 'No especificada' }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- MAPA DE UBICACIÓN --}}
                            <div class="mb-4">
                                <p class="text-md font-semibold text-gray-800 mb-2">Ubicación del Incidente</p>
                                <div class="bg-white p-3 rounded-lg border border-gray-200">
                                    <div class="grid grid-cols-2 gap-3 mb-3 text-sm">
                                        <div>
                                            <span class="font-medium">Latitud:</span> 
                                            <span class="text-gray-600">{{ $reclamo->latitudIncidente }}</span>
                                        </div>
                                        <div>
                                            <span class="font-medium">Longitud:</span> 
                                            <span class="text-gray-600">{{ $reclamo->longitudIncidente }}</span>
                                        </div>
                                    </div>
                                    <div id="map-{{ $reclamo->idReclamo }}" class="reclamo-map" 
                                         data-lat="{{ $reclamo->latitudIncidente }}" 
                                         data-lng="{{ $reclamo->longitudIncidente }}"
                                         style="height: 250px; width: 100%; border-radius: 0.375rem; border: 1px solid #e5e7eb;">
                                    </div>
                                    <small class="text-muted text-center d-block mt-2">
                                        Ubicación exacta del incidente reportado
                                    </small>
                                </div>
                            </div>

                            {{-- LÓGICA DE ACCIÓN: ACEPTAR O RESOLVER --}}
                            <div class="mt-4 pt-4 border-t border-gray-300">
                                <div class="flex justify-between items-center flex-wrap gap-3">
                                    {{-- Estado actual del reclamo --}}
                                    <span class="text-sm font-semibold px-3 py-1 rounded-full shadow-sm
                                        @if($reclamo->estado == 'Resuelto' || $reclamo->estado == 'Cerrado') bg-green-100 text-green-800 
                                        @elseif($reclamo->estado == 'En Proceso') bg-blue-100 text-blue-800 border border-blue-300
                                        @else bg-yellow-100 text-yellow-800 border border-yellow-300 @endif">
                                        Estado: {{ $reclamo->estado }}
                                    </span>

                                    @if ($reclamo->estado === 'Asignado' || $reclamo->estado === 'Nuevo')
                                        {{-- CORREGIDO: Formulario para aceptar reclamo --}}
                                        <form action="{{ route('tecnico.reclamos.aceptar', $reclamo) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="px-5 py-2 rounded-lg font-semibold text-white bg-blue-600 hover:bg-blue-700 transition duration-200 shadow-lg text-sm">
                                                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                Aceptar y Poner En Proceso
                                            </button>
                                        </form>
                                    @elseif ($reclamo->estado === 'En Proceso')
                                        {{-- Botón para abrir/cerrar el formulario de solución --}}
                                        <button type="button" class="px-5 py-2 rounded-lg font-semibold text-white bg-green-600 hover:bg-green-700 transition duration-200 shadow-lg text-sm"
                                                onclick="document.getElementById('form-resolver-{{ $reclamo->idReclamo }}').classList.toggle('hidden')">
                                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Registrar Solución
                                        </button>
                                    @endif
                                </div>

                                {{-- FORMULARIO PARA REGISTRAR SOLUCIÓN Y MARCAR COMO RESUELTO (Oculto por defecto) --}}
                                @if ($reclamo->estado === 'En Proceso')
                                    {{-- CORREGIDO: Ruta para resolver reclamo --}}
                                    <form id="form-resolver-{{ $reclamo->idReclamo }}" action="{{ route('tecnico.reclamos.resolver', $reclamo) }}" method="POST" class="mt-4 pt-4 border-t border-gray-200 hidden bg-white p-4 rounded-lg shadow-inner">
                                        @csrf
                                        @method('PUT')
                                        
                                        <div class="form-group mb-4">
                                            <label for="solucionTecnica-{{ $reclamo->idReclamo }}" class="block text-md font-medium text-gray-700 mb-2">
                                                <strong>Solución Técnica:</strong>
                                            </label>
                                            <textarea name="solucionTecnica" id="solucionTecnica-{{ $reclamo->idReclamo }}" 
                                                    rows="4" placeholder="Describe detalladamente las acciones tomadas para resolver el problema (Mínimo 10 caracteres)." required
                                                    class="w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 transition">{{ old('solucionTecnica') }}</textarea>
                                            
                                            @error('solucionTecnica')
                                                <div class="text-red-500 text-sm mt-1 p-2 bg-red-100 rounded-md" role="alert">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="flex justify-end">
                                            <button type="submit" class="px-5 py-2 rounded-lg font-semibold text-white bg-green-600 hover:bg-green-700 transition duration-200 shadow-md">
                                                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Marcar como Resuelto
                                            </button>
                                        </div>
                                    </form>
                                @endif
                            </div>
                            
                            {{-- Pie de página de la tarjeta --}}
                            <div class="text-xs text-gray-500 border-t pt-3 mt-4 flex justify-between">
                                <p><strong>Asignado por:</strong> {{ $reclamo->operador->primerNombre ?? 'N/A' }}</p>
                                <p><strong>Fecha Asignación:</strong> {{ \Carbon\Carbon::parse($reclamo->fechaAsignacion)->format('d/m/Y H:i') ?? 'Pendiente' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

</div>

<!-- Incluir Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Inicializar mapas para cada reclamo
    document.querySelectorAll('.reclamo-map').forEach(function(mapElement) {
        var lat = parseFloat(mapElement.getAttribute('data-lat'));
        var lng = parseFloat(mapElement.getAttribute('data-lng'));
        var reclamoId = mapElement.id.split('-')[1];
        
        // Inicializar mapa
        var map = L.map(mapElement.id).setView([lat, lng], 15);
        
        // TileLayer
        L.tileLayer('//{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        
        // Marcador en la ubicación del incidente
        var marker = L.marker([lat, lng]).addTo(map);
        
        // Popup con información del reclamo
        marker.bindPopup(`
            <div class="p-2">
                <strong>Reclamo #${reclamoId}</strong><br>
                <small>Ubicación del incidente</small><br>
                <small>Lat: ${lat.toFixed(6)}</small><br>
                <small>Lng: ${lng.toFixed(6)}</small>
            </div>
        `).openPopup();
        
        // Asegurar que el mapa se redibuje correctamente
        setTimeout(() => {
            map.invalidateSize();
        }, 100);
    });
});
</script>

@endsection