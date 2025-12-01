@extends('layouts.dashboard')

@section('title', 'Panel de Operador - Nexora')

@section('header')
    Panel de Operador <span class="text-slate-500 text-lg font-medium ml-2">| {{ auth('empleado')->user()->primerNombre }} {{ auth('empleado')->user()->apellidoPaterno }}</span>
@endsection

@section('content')

<div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
    
    {{-- COLUMNA 1: CASOS NUEVOS --}}
    <div class="space-y-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center mr-3 text-slate-600 border border-slate-200">
                    <i class="fas fa-inbox"></i>
                </div>
                Casos Nuevos
                <span class="ml-3 bg-cyan-100 text-cyan-700 text-xs font-bold px-2 py-1 rounded-full border border-cyan-200">{{ $nuevos->count() }}</span>
            </h2>
        </div>

        @if($nuevos->isEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 text-slate-300 mb-4">
                    <i class="fas fa-check text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-slate-900">No hay casos nuevos</h3>
                <p class="text-slate-500 mt-1">Todo está bajo control.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($nuevos as $reclamo)
                    <div class="bg-white rounded-xl shadow-md border border-slate-200 p-6 hover:shadow-lg transition-shadow duration-300 relative overflow-hidden group">
                        <div class="absolute top-0 left-0 w-1 h-full bg-cyan-500"></div>
                        
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <span class="text-xs font-bold text-cyan-600 uppercase tracking-wider bg-cyan-50 px-2 py-0.5 rounded border border-cyan-100">Nuevo #{{ $reclamo->idReclamo }}</span>
                                <h3 class="text-lg font-bold text-slate-900 mt-2">{{ $reclamo->titulo }}</h3>
                            </div>
                            <span class="text-xs text-slate-400 flex items-center bg-slate-50 px-2 py-1 rounded border border-slate-100">
                                <i class="far fa-clock mr-1"></i> {{ $reclamo->created_at ? $reclamo->created_at->diffForHumans() : 'Reciente' }}
                            </span>
                        </div>

                        <p class="text-slate-600 text-sm mb-4 line-clamp-2 leading-relaxed">{{ $reclamo->descripcionDetallada }}</p>

                        <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                            <div class="flex items-center text-sm text-slate-500">
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center mr-2 text-slate-600 font-bold text-xs border border-slate-200">
                                    {{ substr($reclamo->usuario->primerNombre ?? 'C', 0, 1) }}
                                </div>
                                <span class="font-medium">{{ $reclamo->usuario->primerNombre ?? 'Cliente' }} {{ $reclamo->usuario->apellidoPaterno ?? '' }} {{ $reclamo->usuario->apellidoMaterno ?? '' }}</span>
                            </div>
                            
                            <form action="{{ route('operador.reclamo.tomar', $reclamo->idReclamo) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-cyan-600 text-white text-sm font-bold rounded-lg hover:bg-cyan-700 transition-colors shadow-sm flex items-center">
                                    <i class="fas fa-hand-paper mr-2"></i> Tomar Caso
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- COLUMNA 2: MIS CASOS --}}
    <div class="space-y-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center mr-3 text-slate-600 border border-slate-200">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                Mis Casos Activos
                <span class="ml-3 bg-cyan-100 text-cyan-700 text-xs font-bold px-2 py-1 rounded-full border border-cyan-200">{{ $misCasos->count() }}</span>
            </h2>
        </div>

        @if($misCasos->isEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 text-slate-300 mb-4">
                    <i class="fas fa-folder-open text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-slate-900">Sin casos activos</h3>
                <p class="text-slate-500 mt-1">Tome un caso nuevo para comenzar a trabajar.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($misCasos as $reclamo)
                    <div class="bg-white rounded-xl shadow-md border border-slate-200 p-6 hover:shadow-lg transition-shadow duration-300 relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-1 h-full bg-cyan-500"></div>
                        
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <span class="text-xs font-bold text-cyan-600 uppercase tracking-wider bg-cyan-50 px-2 py-0.5 rounded border border-cyan-100">En Gestión #{{ $reclamo->idReclamo }}</span>
                                <h3 class="text-lg font-bold text-slate-900 mt-2">{{ $reclamo->titulo }}</h3>
                            </div>
                            <div class="flex flex-col items-end">
                                <span class="px-2 py-1 text-xs font-bold rounded bg-slate-100 text-slate-600 mb-1 border border-slate-200">
                                    {{ $reclamo->estado }}
                                </span>
                            </div>
                        </div>

                        <p class="text-slate-600 text-sm mb-4 line-clamp-2 leading-relaxed">{{ $reclamo->descripcionDetallada }}</p>

                        <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                            <div class="bg-slate-50 p-3 rounded border border-slate-100">
                                <span class="block text-xs text-slate-500 uppercase font-bold mb-1">Cliente</span>
                                <span class="font-medium text-slate-800">{{ $reclamo->usuario->primerNombre ?? 'N/A' }} {{ $reclamo->usuario->apellidoPaterno ?? '' }} {{ $reclamo->usuario->apellidoMaterno ?? '' }}</span>
                            </div>
                            <div class="bg-slate-50 p-3 rounded border border-slate-100">
                                <span class="block text-xs text-slate-500 uppercase font-bold mb-1">Prioridad</span>
                                <span class="font-medium {{ $reclamo->prioridad == 'Alta' || $reclamo->prioridad == 'Urgente' ? 'text-rose-600' : 'text-slate-800' }}">
                                    {{ $reclamo->prioridad ?? 'Normal' }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                            @if($reclamo->idTecnicoAsignado)
                                <div class="flex items-center text-sm text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-full border border-emerald-100 font-medium">
                                    <i class="fas fa-user-cog mr-2"></i>
                                    {{ $reclamo->tecnico->primerNombre ?? 'Técnico' }} {{ $reclamo->tecnico->apellidoPaterno ?? '' }}
                                </div>
                            @else
                                <button onclick="openAssignModal({{ $reclamo->idReclamo }}, {{ $reclamo->latitudIncidente }}, {{ $reclamo->longitudIncidente }})" class="w-full sm:w-auto px-4 py-2 bg-white border border-slate-300 text-slate-700 text-sm font-bold rounded-lg hover:bg-slate-50 transition-colors shadow-sm flex items-center justify-center">
                                    <i class="fas fa-user-plus mr-2 text-cyan-600"></i> Asignar Técnico
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>

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
                    <div class="bg-white px-4 pt-6 pb-4 sm:p-6">
                        <div class="space-y-5">
                            
                            <div>
                                <label for="idTecnico" class="block text-sm font-bold text-slate-700 mb-1.5">Seleccionar Técnico</label>
                                <div class="relative">
                                    <select id="idTecnico" name="idTecnico" required class="block w-full pl-3 pr-10 py-2.5 text-base border-slate-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm rounded-lg transition-all shadow-sm @error('idTecnico') border-rose-500 ring-rose-500 bg-rose-50 @enderror">
                                        <option value="">-- Seleccione un técnico disponible --</option>
                                        @foreach($tecnicos as $tecnico)
                                            <option value="{{ $tecnico->idEmpleado }}">
                                                {{ $tecnico->primerNombre }} {{ $tecnico->apellidoPaterno }} 
                                                ({{ $tecnico->especialidad }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-500">
                                        <i class="fas fa-chevron-down text-xs"></i>
                                    </div>
                                </div>
                                @error('idTecnico')
                                    <p class="mt-1.5 text-xs text-rose-600 font-bold flex items-center"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p>
                                @enderror
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
                                @error('prioridad')
                                    <p class="mt-1.5 text-xs text-rose-600 font-bold flex items-center"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p>
                                @enderror
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
                                <textarea id="comentario" name="comentario" rows="3" class="block w-full border-slate-200 bg-slate-50 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm transition-all @error('comentario') border-rose-500 ring-rose-500 bg-rose-50 @enderror" placeholder="Describa el problema y las herramientas necesarias..."></textarea>
                                @error('comentario')
                                    <p class="mt-1.5 text-xs text-rose-600 font-bold flex items-center"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p>
                                @enderror
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

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    let map = null;
    let marker = null;

    function openAssignModal(reclamoId, lat, lng) {
        const modal = document.getElementById('assignModal');
        const form = document.getElementById('assignForm');
        
        // Configurar la acción del formulario dinámicamente
        form.action = `/operador/reclamo/asignar-tecnico/${reclamoId}`;
        
        modal.classList.remove('hidden');

        // Inicializar mapa después de que el modal sea visible
        setTimeout(() => {
            if (!map) {
                map = L.map('mapa-asignacion').setView([lat, lng], 15);
                
                // Usar OpenStreetMap estándar (Claro)
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);
            } else {
                map.invalidateSize();
                map.setView([lat, lng], 15);
            }

            if (marker) {
                map.removeLayer(marker);
            }
            // Marcador personalizado con color Cyan para resaltar sobre el oscuro
            var customIcon = L.divIcon({
                className: 'custom-div-icon',
                html: "<div style='background-color: #06b6d4; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 10px #06b6d4;'></div>",
                iconSize: [12, 12],
                iconAnchor: [6, 6]
            });

            marker = L.marker([lat, lng], {icon: customIcon}).addTo(map)
                .bindPopup('<div class="text-slate-800 font-bold">Ubicación del Incidente</div>').openPopup();
        }, 300); // Pequeño retraso para asegurar que el modal ya ocupó su espacio
    }

    function closeAssignModal() {
        const modal = document.getElementById('assignModal');
        modal.classList.add('hidden');
    }
</script>
@endpush