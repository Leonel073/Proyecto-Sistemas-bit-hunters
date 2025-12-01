@extends('layouts.dashboard')

@section('title', 'Gestión de Asignaciones')

@section('header')
    Gestión de Asignaciones <span class="text-cyan-600 text-lg font-medium ml-2">| Supervisor</span>
@endsection

@section('content')



<!-- ESTADÍSTICAS RÁPIDAS -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8" id="statsContainer">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex items-center hover:shadow-md transition-shadow">
        <div class="w-12 h-12 rounded-full bg-cyan-100 text-cyan-600 flex items-center justify-center text-xl mr-4">
            <i class="fas fa-tasks"></i>
        </div>
        <div>
            <p class="text-slate-500 text-sm font-medium">Total Asignados</p>
            <h3 class="text-2xl font-bold text-slate-800">{{ count($reclamos) }}</h3>
        </div>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex items-center hover:shadow-md transition-shadow">
        <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl mr-4">
            <i class="fas fa-user-check"></i>
        </div>
        <div>
            <p class="text-slate-500 text-sm font-medium">Técnicos Activos</p>
            <h3 class="text-2xl font-bold text-slate-800">{{ count($tecnicos) }}</h3>
        </div>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex items-center hover:shadow-md transition-shadow">
        <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center text-xl mr-4">
            <i class="fas fa-exclamation-circle"></i>
        </div>
        <div>
            <p class="text-slate-500 text-sm font-medium">Alta Prioridad</p>
            <h3 class="text-2xl font-bold text-slate-800">{{ $reclamos->whereIn('prioridad', ['Alta', 'Urgente'])->count() }}</h3>
        </div>
    </div>
</div>

<!-- TABLA DE RECLAMOS -->
<div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-800">Reclamos Asignados</h2>
            <p class="text-sm text-slate-500">Gestione y reasigne los reclamos actuales.</p>
        </div>
        
        <!-- Filtro de Búsqueda -->
        <div class="relative w-full sm:w-72">
            <input type="text" id="searchInput" placeholder="Buscar por cliente, técnico o ID..." class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 outline-none transition-all">
            <i class="fas fa-search absolute left-3 top-3 text-slate-400 text-xs"></i>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse" id="reclamosTable">
            <thead>
                <tr class="bg-slate-50 text-slate-600 text-xs uppercase tracking-wider">
                    <th class="p-4 font-semibold border-b border-slate-100">ID</th>
                    <th class="p-4 font-semibold border-b border-slate-100">Cliente</th>
                    <th class="p-4 font-semibold border-b border-slate-100">Técnico Actual</th>
                    <th class="p-4 font-semibold border-b border-slate-100">Estado</th>
                    <th class="p-4 font-semibold border-b border-slate-100">Prioridad</th>
                    <th class="p-4 font-semibold border-b border-slate-100 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($reclamos as $reclamo)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="p-4 font-bold text-cyan-700">#{{ $reclamo->idReclamo }}</td>
                        <td class="p-4">
                            <div class="font-medium text-slate-900">{{ $reclamo->usuario->primerNombre ?? 'N/A' }} {{ $reclamo->usuario->apellidoPaterno ?? '' }}</div>
                            <div class="text-slate-500 text-xs">{{ $reclamo->usuario->numeroCelular ?? '' }}</div>
                        </td>
                        <td class="p-4">
                            @if($reclamo->tecnico)
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600 mr-2 border border-slate-300">
                                        {{ substr($reclamo->tecnico->primerNombre, 0, 1) }}
                                    </div>
                                    <span class="font-medium text-slate-700">{{ $reclamo->tecnico->primerNombre }} {{ $reclamo->tecnico->apellidoPaterno }}</span>
                                </div>
                            @else
                                <span class="text-rose-500 italic flex items-center"><i class="fas fa-exclamation-triangle mr-1"></i> Sin asignar</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold border 
                                @if($reclamo->estado == 'En Proceso') bg-blue-50 text-blue-700 border-blue-100
                                @elseif($reclamo->estado == 'Asignado') bg-amber-50 text-amber-700 border-amber-100
                                @elseif($reclamo->estado == 'Abierto') bg-emerald-50 text-emerald-700 border-emerald-100
                                @else bg-slate-100 text-slate-700 border-slate-200 @endif">
                                {{ $reclamo->estado }}
                            </span>
                        </td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold border
                                @if($reclamo->prioridad == 'Alta' || $reclamo->prioridad == 'Urgente') bg-rose-50 text-rose-700 border-rose-100
                                @elseif($reclamo->prioridad == 'Media') bg-orange-50 text-orange-700 border-orange-100
                                @else bg-emerald-50 text-emerald-700 border-emerald-100 @endif">
                                {{ $reclamo->prioridad }}
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            <button onclick="openReassignModal({{ $reclamo->idReclamo }}, '{{ $reclamo->tecnico->idEmpleado ?? '' }}')" 
                                    class="text-cyan-600 hover:text-white border border-cyan-200 hover:bg-cyan-600 font-medium text-xs px-3 py-1.5 rounded-lg transition-all shadow-sm">
                                <i class="fas fa-exchange-alt mr-1"></i> Reasignar
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-slate-500 italic">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-inbox text-4xl text-slate-300 mb-3"></i>
                                <p>No hay reclamos asignados pendientes de revisión.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <!-- Mensaje de no resultados para búsqueda -->
        <div id="noResults" class="hidden p-8 text-center text-slate-500 italic">
            <i class="fas fa-search text-slate-300 text-2xl mb-2"></i>
            <p>No se encontraron coincidencias.</p>
        </div>
    </div>
</div>

<!-- SLIDE-OVER REASIGNAR -->
<div id="reassignModal" class="relative z-50 hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
    <!-- Background backdrop -->
    <div id="backdrop" class="fixed inset-0 bg-slate-900/75 transition-opacity opacity-0 ease-in-out duration-500"></div>

    <div class="fixed inset-0 overflow-hidden">
        <div class="absolute inset-0 overflow-hidden">
            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                
                <!-- Slide-over panel -->
                <div id="slidePanel" class="pointer-events-auto w-screen max-w-md transform transition ease-in-out duration-500 translate-x-full sm:duration-700">
                    <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-xl">
                        <div class="bg-slate-800 px-4 py-6 sm:px-6">
                            <div class="flex items-center justify-between">
                                <h2 class="text-base font-semibold leading-6 text-white" id="slide-over-title">
                                    <i class="fas fa-user-edit mr-2 text-cyan-400"></i> Reasignar Reclamo
                                </h2>
                                <div class="ml-3 flex h-7 items-center">
                                    <button type="button" onclick="closeReassignModal()" class="relative rounded-md bg-slate-800 text-slate-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-white">
                                        <span class="absolute -inset-2.5"></span>
                                        <span class="sr-only">Cerrar panel</span>
                                        <i class="fas fa-times text-xl"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="mt-1">
                                <p class="text-sm text-slate-400">Complete la información para transferir el reclamo a otro técnico.</p>
                            </div>
                        </div>
                        
                        <form id="reassignForm" method="POST" action="" novalidate class="flex flex-1 flex-col justify-between">
                            @csrf
                            @method('PUT')
                            
                            <div class="divide-y divide-gray-200 px-4 sm:px-6">
                                <div class="space-y-6 pb-5 pt-6">
                                    
                                    <!-- Detalles del Reclamo -->
                                    <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
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

                                    <div>
                                        <label for="idTecnico" class="block text-sm font-bold text-slate-900 mb-2">Seleccionar Nuevo Técnico</label>
                                        <select name="idTecnico" id="idTecnico" class="block w-full rounded-md border-0 py-2.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-cyan-600 sm:text-sm sm:leading-6 bg-slate-50">
                                            <option value="">-- Seleccione un técnico --</option>
                                            @foreach($tecnicos as $tecnico)
                                                <option value="{{ $tecnico->idEmpleado }}">
                                                    {{ $tecnico->primerNombre }} {{ $tecnico->apellidoPaterno }} 
                                                    ({{ $tecnico->tecnico->especialidad ?? 'General' }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-bold text-slate-900 mb-3">Actualizar Prioridad</label>
                                        <div class="grid grid-cols-2 gap-3">
                                            <label class="cursor-pointer">
                                                <input type="radio" name="prioridad" value="Baja" class="peer sr-only">
                                                <div class="text-center py-2 rounded-md border border-slate-200 bg-white text-slate-600 text-sm font-medium hover:bg-slate-50 peer-checked:bg-emerald-50 peer-checked:text-emerald-700 peer-checked:border-emerald-500 peer-checked:ring-1 peer-checked:ring-emerald-500 transition-all">
                                                    <div class="flex items-center justify-center"><div class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></div> Baja</div>
                                                </div>
                                            </label>
                                            <label class="cursor-pointer">
                                                <input type="radio" name="prioridad" value="Media" class="peer sr-only">
                                                <div class="text-center py-2 rounded-md border border-slate-200 bg-white text-slate-600 text-sm font-medium hover:bg-slate-50 peer-checked:bg-blue-50 peer-checked:text-blue-700 peer-checked:border-blue-500 peer-checked:ring-1 peer-checked:ring-blue-500 transition-all">
                                                    <div class="flex items-center justify-center"><div class="w-2 h-2 rounded-full bg-blue-500 mr-2"></div> Media</div>
                                                </div>
                                            </label>
                                            <label class="cursor-pointer">
                                                <input type="radio" name="prioridad" value="Alta" class="peer sr-only">
                                                <div class="text-center py-2 rounded-md border border-slate-200 bg-white text-slate-600 text-sm font-medium hover:bg-slate-50 peer-checked:bg-orange-50 peer-checked:text-orange-700 peer-checked:border-orange-500 peer-checked:ring-1 peer-checked:ring-orange-500 transition-all">
                                                    <div class="flex items-center justify-center"><div class="w-2 h-2 rounded-full bg-orange-500 mr-2"></div> Alta</div>
                                                </div>
                                            </label>
                                            <label class="cursor-pointer">
                                                <input type="radio" name="prioridad" value="Urgente" class="peer sr-only">
                                                <div class="text-center py-2 rounded-md border border-slate-200 bg-white text-slate-600 text-sm font-medium hover:bg-slate-50 peer-checked:bg-rose-50 peer-checked:text-rose-700 peer-checked:border-rose-500 peer-checked:ring-1 peer-checked:ring-rose-500 transition-all">
                                                    <div class="flex items-center justify-center"><div class="w-2 h-2 rounded-full bg-rose-500 mr-2"></div> Urgente</div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    @php
                                        $reclamoActual = $reclamos->first() ?? null;
                                    @endphp
                                    <input type="hidden" name="idPoliticaSLA" id="idPoliticaSLA" value="{{ $reclamoActual->idPoliticaSLA ?? 1 }}">
                                </div>
                            </div>
                            
                            <div class="flex flex-shrink-0 justify-end px-4 py-4 bg-slate-50 border-t border-slate-200 gap-3">
                                <button type="button" onclick="closeReassignModal()" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50">Cancelar</button>
                                <button type="submit" id="btnConfirmar" disabled class="inline-flex justify-center rounded-md bg-cyan-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-cyan-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-cyan-600 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <i class="fas fa-check mr-2"></i> Confirmar Asignación
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // --- BÚSQUEDA EN TIEMPO REAL ---
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const table = document.getElementById('reclamosTable');
        const rows = table.getElementsByTagName('tr');
        const noResults = document.getElementById('noResults');

        searchInput.addEventListener('keyup', function() {
            const filter = searchInput.value.toLowerCase();
            let visibleCount = 0;

            // Empezamos desde 1 para saltar el encabezado
            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                // Si es la fila de "No hay reclamos", la ignoramos
                if (row.cells.length === 1) continue;

                const idCell = row.cells[0]?.textContent || '';
                const clienteCell = row.cells[1]?.textContent || '';
                const tecnicoCell = row.cells[2]?.textContent || '';
                const estadoCell = row.cells[3]?.textContent || '';

                const text = (idCell + clienteCell + tecnicoCell + estadoCell).toLowerCase();

                if (text.indexOf(filter) > -1) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            }

            if (visibleCount === 0 && filter !== '') {
                noResults.classList.remove('hidden');
            } else {
                noResults.classList.add('hidden');
            }
        });
    });

    // --- MODAL LOGIC (SLIDE-OVER) ---
    function openReassignModal(reclamoId, currentTecnicoId) {
        const modal = document.getElementById('reassignModal');
        const backdrop = document.getElementById('backdrop');
        const slidePanel = document.getElementById('slidePanel');
        
        const form = document.getElementById('reassignForm');
        const selectTecnico = document.getElementById('idTecnico');
        
        // Configurar la acción del formulario dinámicamente
        form.action = `/supervisor/tecnicos/reclamo/${reclamoId}/reasignar`;
        
        // Preseleccionar el técnico actual
        if(currentTecnicoId) {
            selectTecnico.value = currentTecnicoId;
        } else {
            selectTecnico.value = ""; // Resetear si no hay
        }
        
        // Preseleccionar prioridad (usando datos inyectados)
        const reclamo = window.reclamosData?.find(r => r.idReclamo == reclamoId);
        if(reclamo) {
            // Poblar detalles del reclamo
            const nombreCliente = reclamo.usuario ? `${reclamo.usuario.primerNombre} ${reclamo.usuario.apellidoPaterno}` : 'N/A';
            const direccion = reclamo.usuario ? reclamo.usuario.direccionTexto : 'Sin dirección';
            const descripcion = reclamo.descripcionDetallada || 'Sin descripción';

            document.getElementById('modal-cliente').textContent = nombreCliente;
            document.getElementById('modal-direccion').textContent = direccion;
            document.getElementById('modal-descripcion').textContent = descripcion;

            // Seleccionar radio button de prioridad
            if(reclamo.prioridad) {
                const radio = document.querySelector(`input[name="prioridad"][value="${reclamo.prioridad}"]`);
                if(radio) radio.checked = true;
            }
            
            const slaInput = document.getElementById('idPoliticaSLA');
            if(slaInput && reclamo.idPoliticaSLA) {
                slaInput.value = reclamo.idPoliticaSLA;
            }
        }
        
        // Mostrar modal y animar
        modal.classList.remove('hidden');
        
        // Pequeño delay para permitir que el navegador renderice antes de la transición
        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            slidePanel.classList.remove('translate-x-full');
            slidePanel.classList.add('translate-x-0');
        }, 10);
        
        // Validar estado inicial del botón
        validateForm();
    }

    function closeReassignModal() {
        const modal = document.getElementById('reassignModal');
        const backdrop = document.getElementById('backdrop');
        const slidePanel = document.getElementById('slidePanel');

        // Animar salida
        backdrop.classList.add('opacity-0');
        slidePanel.classList.remove('translate-x-0');
        slidePanel.classList.add('translate-x-full');

        // Esperar a que termine la transición para ocultar
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 500); // 500ms coincide con duration-500
    }

    // --- FORM VALIDATION ---
    const selectTecnico = document.getElementById('idTecnico');
    const btnConfirmar = document.getElementById('btnConfirmar');

    function validateForm() {
        if (selectTecnico.value) {
            btnConfirmar.disabled = false;
            btnConfirmar.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            btnConfirmar.disabled = true;
            btnConfirmar.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }

    selectTecnico.addEventListener('change', validateForm);
</script>

{{-- Inyectar datos del servidor al JavaScript --}}
<script>
    window.reclamosData = @json($reclamos);
    window.tecnicosData = @json($tecnicos);
</script>

@endsection
