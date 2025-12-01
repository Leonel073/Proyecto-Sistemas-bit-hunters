@extends('layouts.dashboard')

@section('title', 'Supervisor - Operadores')

@section('header')
    Supervisión de Operadores <span class="text-indigo-600 text-lg font-medium ml-2">| Panel de Control</span>
@endsection

@section('content')

<!-- ESTADÍSTICAS RÁPIDAS -->
@php
    $criticalDelays = $reclamos->filter(function($r) {
        return $r->fechaCreacion->diffInHours(now()) >= 4;
    })->count();
@endphp

@if($criticalDelays > 0)
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center shadow-sm animate-pulse" role="alert">
        <i class="fas fa-exclamation-triangle mr-3 text-xl"></i>
        <div>
            <strong class="font-bold">¡Atención!</strong>
            <span class="block sm:inline">Hay {{ $criticalDelays }} reclamos con una demora crítica de más de 4 horas.</span>
        </div>
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
        <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xl mr-4">
            <i class="fas fa-inbox"></i>
        </div>
        <div>
            <p class="text-gray-500 text-sm font-medium">Reclamos Pendientes</p>
            <h3 class="text-2xl font-bold text-gray-800">{{ count($reclamos) }}</h3>
        </div>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
        <div class="w-12 h-12 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-xl mr-4">
            <i class="fas fa-headset"></i>
        </div>
        <div>
            <p class="text-gray-500 text-sm font-medium">Operadores Activos</p>
            <h3 class="text-2xl font-bold text-gray-800">{{ count($operadores) }}</h3>
        </div>
    </div>
</div>

<!-- TABLA DE RECLAMOS -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Reclamos por Asignar</h2>
            <p class="text-sm text-gray-500">Gestione la asignación de reclamos a operadores.</p>
        </div>
        
        <div class="relative">
            <input type="text" placeholder="Buscar..." class="pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
            <i class="fas fa-search absolute left-3 top-3 text-gray-400 text-xs"></i>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                    <th class="p-4 font-semibold">ID</th>
                    <th class="p-4 font-semibold">Cliente</th>
                    <th class="p-4 font-semibold">Operador Actual</th>
                    <th class="p-4 font-semibold">Tiempo de Espera</th>
                    <th class="p-4 font-semibold">Estado</th>
                    <th class="p-4 font-semibold">Prioridad</th>
                    <th class="p-4 font-semibold text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($reclamos as $reclamo)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="p-4 font-medium text-indigo-600">#{{ $reclamo->idReclamo }}</td>
                        <td class="p-4">
                            <div class="font-medium text-gray-900">{{ $reclamo->usuario->primerNombre ?? 'N/A' }} {{ $reclamo->usuario->apellidoPaterno ?? '' }}</div>
                            <div class="text-gray-500 text-xs">{{ $reclamo->usuario->numeroCelular ?? '' }}</div>
                        </td>
                        <td class="p-4">
                            @if($reclamo->operador)
                                <div class="flex items-center">
                                    <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600 mr-2">
                                        {{ substr($reclamo->operador->primerNombre, 0, 1) }}
                                    </div>
                                    <span>{{ $reclamo->operador->primerNombre }} {{ $reclamo->operador->apellidoPaterno }}</span>
                                </div>
                            @else
                                <span class="text-red-500 italic">Sin asignar</span>
                            @endif
                        </td>
                        <td class="p-4">
                            @php
                                $hours = $reclamo->fechaCreacion->diffInHours(now());
                                $minutes = $reclamo->fechaCreacion->diffInMinutes(now()) % 60;
                                
                                $colorClass = 'bg-green-100 text-green-800 border-green-200';
                                $icon = 'fa-clock';
                                
                                if ($hours >= 4) {
                                    $colorClass = 'bg-red-100 text-red-800 border-red-200 font-bold';
                                    $icon = 'fa-exclamation-circle';
                                } elseif ($hours >= 1) {
                                    $colorClass = 'bg-orange-100 text-orange-800 border-orange-200';
                                    $icon = 'fa-hourglass-half';
                                }
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs border flex items-center w-fit {{ $colorClass }}">
                                <i class="fas {{ $icon }} mr-1.5"></i>
                                {{ $hours }}h {{ $minutes }}m
                            </span>
                        </td>
                        <td class="p-4">
                            <span class="px-2 py-1 rounded-full text-xs font-bold 
                                @if($reclamo->estado == 'Nuevo') bg-blue-100 text-blue-700
                                @elseif($reclamo->estado == 'Abierto') bg-yellow-100 text-yellow-700
                                @else bg-gray-100 text-gray-700 @endif">
                                {{ $reclamo->estado }}
                            </span>
                        </td>
                        <td class="p-4">
                            <span class="px-2 py-1 rounded-full text-xs font-bold 
                                @if($reclamo->prioridad == 'Alta') bg-red-100 text-red-700
                                @elseif($reclamo->prioridad == 'Media') bg-orange-100 text-orange-700
                                @else bg-green-100 text-green-700 @endif">
                                {{ $reclamo->prioridad }}
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            <button onclick="openReassignModal({{ $reclamo->idReclamo }}, '{{ $reclamo->operador->idEmpleado ?? '' }}')" 
                                    class="text-indigo-600 hover:text-indigo-800 font-medium text-sm bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition">
                                Reasignar
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-500">
                            No hay reclamos pendientes de asignación.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL REASIGNAR -->
<div id="reassignModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeReassignModal()"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
            <form id="reassignForm" method="POST" action="">
                @csrf
                @method('PUT')
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-user-edit text-indigo-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Asignar Operador
                            </h3>
                            <div class="mt-4">
                                <label for="idOperador" class="block text-sm font-medium text-gray-700 mb-2">Seleccionar Operador</label>
                                <select name="idOperador" id="idOperador" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md border">
                                    @foreach($operadores as $operador)
                                        <option value="{{ $operador->idEmpleado }}">
                                            {{ $operador->primerNombre }} {{ $operador->apellidoPaterno }} 
                                            ({{ $operador->operador->turno ?? 'General' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="mt-4">
                                <label for="prioridad" class="block text-sm font-medium text-gray-700 mb-2">Prioridad</label>
                                <select name="prioridad" id="prioridad" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md border">
                                    <option value="Baja">Baja</option>
                                    <option value="Media">Media</option>
                                    <option value="Alta">Alta</option>
                                    <option value="Urgente">Urgente</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Guardar Cambios
                    </button>
                    <button type="button" onclick="closeReassignModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openReassignModal(reclamoId, currentOperadorId) {
        const modal = document.getElementById('reassignModal');
        const form = document.getElementById('reassignForm');
        const selectOperador = document.getElementById('idOperador');
        
        // Configurar ruta
        form.action = `/supervisor/operadores/reclamo/${reclamoId}/reasignar`;
        
        // Preseleccionar
        if(currentOperadorId) {
            selectOperador.value = currentOperadorId;
        }
        
        modal.classList.remove('hidden');
    }

    function closeReassignModal() {
        document.getElementById('reassignModal').classList.add('hidden');
    }
</script>

@endsection