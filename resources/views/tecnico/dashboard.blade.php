@extends('layouts.dashboard')

@section('title', 'Panel de Técnico - Nexora')

@section('header')
    Panel Técnico <span class="text-slate-500 text-lg font-medium ml-2">| {{ auth('empleado')->user()->primerNombre }} {{ auth('empleado')->user()->apellidoPaterno }}</span>
@endsection

@section('content')
{{--
Recuperación de datos del técnico y configuración de colores
--}}
@php
$estadoActual = $estadoActual ?? 'No Disponible';

// Colores base para cada estado (usando clases de Tailwind)
$color = [
    'Disponible' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
    'En Ruta' => 'bg-cyan-100 text-cyan-800 border-cyan-200',
    'Ocupado' => 'bg-rose-100 text-rose-800 border-rose-200',
    'No Disponible' => 'bg-slate-100 text-slate-800 border-slate-200',
];

// Definimos los posibles estados que el técnico puede elegir
$opcionesEstado = ['Disponible', 'En Ruta', 'Ocupado'];

// Función de ayuda para la prioridad
$prioridadColor = function($prioridad) {
    return match ($prioridad) {
        'Alta' => 'bg-rose-500 text-white',
        'Media' => 'bg-amber-500 text-white',
        'Baja' => 'bg-cyan-500 text-white',
        'Urgente' => 'bg-rose-700 text-white',
        default => 'bg-slate-400 text-white',
    };
};
@endphp

<!-- Incluir Leaflet CSS -->
@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
@endpush

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    {{-- COLUMNA LATERAL (Control de Estado - Col 1) --}}
    <div class="lg:col-span-1 order-last lg:order-first">
        
        {{-- 1. CONTROL DE ESTATUS DE DISPONIBILIDAD --}}
        @if($tecnico)
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 sticky top-24">
            <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center">
                <div class="w-10 h-10 rounded-full bg-cyan-50 flex items-center justify-center mr-3 text-cyan-600 border border-cyan-100">
                    <i class="fas fa-user-clock"></i>
                </div>
                Mi Estatus
            </h3>
            
            <div class="mb-6 text-center p-5 bg-slate-50 rounded-xl border border-slate-100">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Estado Actual</p>
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold border {{ $color[$estadoActual] ?? 'bg-slate-100 text-slate-800' }} shadow-sm">
                    <i class="fas fa-circle text-[8px] mr-2"></i> {{ $estadoActual }}
                </span>
            </div>
            
            <form action="{{ route('tecnico.actualizar.estado') }}" method="POST" class="flex flex-col gap-4">
                @csrf
                @method('PUT')
                
                <div>
                    <label for="estadoDisponibilidad" class="block text-sm font-bold text-slate-700 mb-2">Cambiar estado a:</label>
                    <div class="relative">
                        <select name="estadoDisponibilidad" id="estadoDisponibilidad" required class="appearance-none w-full p-3 pl-4 pr-10 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition outline-none text-slate-700 font-medium shadow-sm">
                            <option value="" disabled>Seleccionar...</option>
                            @foreach ($opcionesEstado as $opcion)
                                <option value="{{ $opcion }}" @if($opcion === $estadoActual) selected @endif>
                                    {{ $opcion }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full py-3 rounded-lg font-bold text-white bg-gradient-to-r from-cyan-600 to-slate-700 hover:from-cyan-700 hover:to-slate-800 transition shadow-lg shadow-cyan-500/30 transform hover:scale-[1.02]">
                    Actualizar Estado
                </button>
            </form>
        </div>
        @else
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 sticky top-24">
            <div class="text-center">
                <div class="w-12 h-12 rounded-full bg-cyan-50 flex items-center justify-center mx-auto mb-3 text-cyan-600 border border-cyan-100">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Vista de Administrador</h3>
                <p class="text-slate-500 text-sm mt-2 leading-relaxed">Estás viendo este panel con privilegios de SuperAdmin. No tienes un estado de disponibilidad técnico asignado.</p>
            </div>
        </div>
        @endif

    </div>
    
    {{-- COLUMNA PRINCIPAL DE RECLAMOS (Cols 2 y 3) --}}
    <div class="lg:col-span-2 order-first lg:order-last">
        
        {{-- 2. RECLAMOS ASIGNADOS --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-6 border-b border-slate-100 pb-4">
                <h3 class="text-xl font-bold text-slate-800 flex items-center">
                    <div class="w-10 h-10 rounded-full bg-rose-50 flex items-center justify-center mr-3 text-rose-500 border border-rose-100">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    Mis Reclamos Pendientes
                </h3>
                <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-sm font-bold border border-slate-200">{{ count($reclamos) }}</span>
            </div>
            
            @if ($reclamos->isEmpty())
                <div class="text-center py-12">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-50 text-emerald-500 mb-4 border border-emerald-100">
                        <i class="fas fa-check text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-bold text-slate-800">¡Todo al día!</h4>
                    <p class="text-slate-500 mt-1">No tienes reclamos pendientes asignados.</p>
                </div>
            @else
                <div class="space-y-6">
                    @foreach ($reclamos as $reclamo)
                        {{-- Tarjeta de Reclamo --}}
                        <div class="group bg-white border border-slate-200 rounded-xl overflow-hidden hover:shadow-lg transition-all duration-300">
                            <!-- Header Card -->
                            <div class="p-5 border-b border-slate-100 bg-slate-50 flex justify-between items-start">
                                <div>
                                    <span class="text-xs font-bold text-cyan-600 uppercase tracking-wider bg-cyan-50 px-2 py-0.5 rounded border border-cyan-100">Reclamo #{{ $reclamo->idReclamo }}</span>
                                    <h4 class="text-lg font-bold text-slate-900 mt-2 group-hover:text-cyan-700 transition-colors">{{ $reclamo->titulo }}</h4>
                                </div>
                                <span class="text-xs font-bold px-3 py-1 rounded-full uppercase shadow-sm {{ $prioridadColor($reclamo->prioridad) }}">
                                    {{ $reclamo->prioridad }}
                                </span>
                            </div>

                            <div class="p-5">
                                {{-- Descripción --}}
                                <p class="text-slate-600 mb-6 text-sm leading-relaxed">{{ $reclamo->descripcionDetallada }}</p>
                                
                                {{-- Grid Detalles --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <!-- Cliente -->
                                    <div class="bg-slate-50 rounded-lg p-4 border border-slate-100">
                                        <h5 class="text-xs font-bold text-slate-500 uppercase mb-3 flex items-center">
                                            <i class="fas fa-user mr-2"></i> Cliente
                                        </h5>
                                        <div class="space-y-2 text-sm">
                                            <p class="text-slate-700"><span class="font-semibold text-slate-900">Nombre:</span> {{ $reclamo->usuario->primerNombre ?? 'N/A' }} {{ $reclamo->usuario->apellidoPaterno ?? '' }} {{ $reclamo->usuario->apellidoMaterno ?? '' }}</p>
                                            <p class="text-slate-700"><span class="font-semibold text-slate-900">Teléfono:</span> {{ $reclamo->usuario->numeroCelular ?? 'N/A' }}</p>
                                            <p class="text-slate-700 truncate"><span class="font-semibold text-slate-900">Dirección:</span> {{ $reclamo->usuario->direccionTexto ?? 'No especificada' }}</p>
                                        </div>
                                    </div>

                                    <!-- Ubicación Mapa -->
                                    <div class="rounded-lg overflow-hidden border border-slate-200 h-40 relative shadow-inner">
                                        <div id="map-{{ $reclamo->idReclamo }}" class="reclamo-map w-full h-full z-0" 
                                             data-lat="{{ $reclamo->latitudIncidente }}" 
                                             data-lng="{{ $reclamo->longitudIncidente }}">
                                        </div>
                                    </div>
                                </div>

                                {{-- Acciones --}}
                                <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                                    <div class="flex items-center space-x-2">
                                        <span class="w-2.5 h-2.5 rounded-full 
                                            @if($reclamo->estado == 'Resuelto' || $reclamo->estado == 'Cerrado') bg-emerald-500 
                                            @elseif($reclamo->estado == 'En Proceso') bg-cyan-500 
                                            @else bg-amber-500 @endif"></span>
                                        <span class="text-sm font-bold text-slate-600">{{ $reclamo->estado }}</span>
                                    </div>

                                    <div class="flex space-x-3">
                                        @if ($reclamo->estado === 'Asignado' || $reclamo->estado === 'Nuevo')
                                            <form action="{{ route('tecnico.reclamos.aceptar', $reclamo) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="px-4 py-2 rounded-lg font-bold text-white bg-cyan-600 hover:bg-cyan-700 transition text-sm shadow-md hover:shadow-lg flex items-center">
                                                    <i class="fas fa-play mr-2"></i> Iniciar
                                                </button>
                                            </form>
                                        @elseif ($reclamo->estado === 'En Proceso')
                                            <button type="button" class="px-4 py-2 rounded-lg font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition text-sm shadow-md hover:shadow-lg flex items-center"
                                                    onclick="document.getElementById('form-resolver-{{ $reclamo->idReclamo }}').classList.toggle('hidden')">
                                                <i class="fas fa-check mr-2"></i> Resolver
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                {{-- Formulario Resolver (Hidden) --}}
                                @if ($reclamo->estado === 'En Proceso')
                                    <form id="form-resolver-{{ $reclamo->idReclamo }}" action="{{ route('tecnico.reclamos.resolver', $reclamo) }}" method="POST" class="mt-4 pt-4 border-t border-slate-100 hidden bg-emerald-50 -mx-5 -mb-5 p-5">
                                        @csrf
                                        @method('PUT')
                                        
                                        <div class="mb-3">
                                            <label class="block text-sm font-bold text-emerald-800 mb-2">Informe Técnico de Solución</label>
                                            <textarea name="solucionTecnica" rows="3" placeholder="Describe las acciones realizadas..." required
                                                    class="w-full p-3 border border-emerald-200 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none text-sm bg-white text-slate-700"></textarea>
                                        </div>

                                        <div class="flex justify-end">
                                            <button type="submit" class="px-4 py-2 rounded-lg font-bold text-white bg-emerald-700 hover:bg-emerald-800 transition text-sm shadow-md">
                                                Confirmar Solución
                                            </button>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Esperar un poco para asegurar que el DOM y los estilos estén listos
    setTimeout(() => {
        document.querySelectorAll('.reclamo-map').forEach(function(mapElement) {
            var lat = parseFloat(mapElement.getAttribute('data-lat'));
            var lng = parseFloat(mapElement.getAttribute('data-lng'));
            
            if(!isNaN(lat) && !isNaN(lng)) {
                // Verificar si ya existe una instancia de mapa y limpiarla si es necesario
                if (mapElement._leaflet_id) {
                    return; // Ya inicializado
                }

                var map = L.map(mapElement, {
                    zoomControl: false, // Mapa más limpio
                    attributionControl: false
                }).setView([lat, lng], 15);
                
                // Usar OpenStreetMap estándar (Claro)
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);
                
                // Marcador personalizado con color Cyan
                var customIcon = L.divIcon({
                    className: 'custom-div-icon',
                    html: "<div style='background-color: #06b6d4; width: 10px; height: 10px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 8px #06b6d4;'></div>",
                    iconSize: [10, 10],
                    iconAnchor: [5, 5]
                });

                L.marker([lat, lng], {icon: customIcon}).addTo(map);
                
                // Forzar redibujado para evitar problemas de visualización en contenedores ocultos/flex
                map.invalidateSize();
            }
        });
    }, 500); // Pequeño retardo para asegurar renderizado
});
</script>
@endpush

@endsection