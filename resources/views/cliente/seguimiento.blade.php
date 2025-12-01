@extends('layouts.client')

@section('title', 'Mis Reclamos - Nexora Bolivia')

@section('content')
<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">Mis Reclamos</h1>
        <p class="mt-2 text-slate-600">Historial y seguimiento en tiempo real de sus casos registrados.</p>
    </div>

    @if($reclamos->isEmpty())
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200 p-12 text-center">
            <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-6 text-indigo-500">
                <i class="fas fa-inbox text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">No tiene reclamos registrados</h3>
            <p class="text-slate-500 mb-8 max-w-md mx-auto">Parece que todo está funcionando correctamente. Si tiene algún problema, no dude en crear un nuevo reclamo.</p>
            <a href="{{ route('formulario') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-md hover:shadow-lg">
                <i class="fas fa-plus mr-2"></i> Presentar Reclamo
            </a>
        </div>
    @else
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-3 mb-8">
            <div class="bg-white overflow-hidden shadow-md rounded-xl border border-slate-200 hover:shadow-lg transition-shadow">
                <div class="px-4 py-5 sm:p-6">
                    <dt class="text-sm font-medium text-slate-500 truncate uppercase tracking-wider">Total Reclamos</dt>
                    <dd class="mt-1 text-3xl font-bold text-slate-900">{{ $reclamos->count() }}</dd>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-md rounded-xl border border-slate-200 hover:shadow-lg transition-shadow">
                <div class="px-4 py-5 sm:p-6">
                    <dt class="text-sm font-medium text-slate-500 truncate uppercase tracking-wider">En Proceso</dt>
                    <dd class="mt-1 text-3xl font-bold text-indigo-600">
                        {{ $reclamos->whereIn('estado', ['Nuevo', 'Asignado', 'En Proceso'])->count() }}
                    </dd>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-md rounded-xl border border-slate-200 hover:shadow-lg transition-shadow">
                <div class="px-4 py-5 sm:p-6">
                    <dt class="text-sm font-medium text-slate-500 truncate uppercase tracking-wider">Resueltos</dt>
                    <dd class="mt-1 text-3xl font-bold text-emerald-600">
                        {{ $reclamos->whereIn('estado', ['Resuelto', 'Cerrado'])->count() }}
                    </dd>
                </div>
            </div>
        </div>

        <!-- Lista de Reclamos -->
        <div class="space-y-8">
            @foreach($reclamos as $reclamo)
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <!-- Header del Card -->
                    <div class="p-6 border-b border-slate-100 bg-slate-900">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-slate-800 text-indigo-400 border border-slate-700">
                                        <i class="fas fa-file-alt text-lg"></i>
                                    </span>
                                </div>
                                <div>
                                    <div class="flex items-center gap-3">
                                        <h3 class="text-lg font-bold text-white">R-{{ $reclamo->idReclamo }}</h3>
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wide
                                            @if($reclamo->prioridad == 'Urgente') bg-rose-900 text-rose-200 border border-rose-700
                                            @elseif($reclamo->prioridad == 'Alta') bg-orange-900 text-orange-200 border border-orange-700
                                            @else bg-emerald-900 text-emerald-200 border border-emerald-700 @endif">
                                            {{ $reclamo->prioridad }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-slate-400 mt-1 font-medium">{{ $reclamo->titulo }}</p>
                                </div>
                            </div>
                            <div class="flex flex-col items-end">
                                <span class="px-3 py-1 rounded-full text-sm font-bold border
                                    @if($reclamo->estado == 'Nuevo') bg-blue-100 text-blue-800 border-blue-200
                                    @elseif($reclamo->estado == 'Resuelto') bg-emerald-100 text-emerald-800 border-emerald-200
                                    @elseif($reclamo->estado == 'Cerrado') bg-slate-100 text-slate-800 border-slate-200
                                    @else bg-amber-100 text-amber-800 border-amber-200 @endif">
                                    {{ $reclamo->estado }}
                                </span>
                                <span class="text-xs text-slate-400 mt-2 flex items-center">
                                    <i class="far fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($reclamo->fechaCreacion)->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Body del Card -->
                    <div class="p-8">
                        <!-- Barra de Progreso -->
                        <div class="mb-8">
                            <div class="relative">
                                <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-slate-100">
                                    @php
                                        $estados = ['Nuevo', 'Asignado', 'En Proceso', 'Resuelto', 'Cerrado'];
                                        $estadoActual = $reclamo->estado;
                                        $indiceActual = array_search($estadoActual, $estados);
                                        $indiceActual = $indiceActual !== false ? $indiceActual : 0;
                                        $porcentaje = (($indiceActual + 1) / count($estados)) * 100;
                                    @endphp
                                    <div style="width: {{ $porcentaje }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-indigo-600 transition-all duration-500"></div>
                                </div>
                                <div class="flex justify-between text-xs text-slate-400 font-medium uppercase tracking-wider">
                                    @foreach($estados as $index => $estado)
                                        <span class="{{ $index <= $indiceActual ? 'text-indigo-600 font-bold' : '' }}">{{ $estado }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Detalles del Incidente</h4>
                                <div class="bg-slate-50 p-4 rounded-lg border border-slate-100">
                                    <p class="text-slate-700 text-sm leading-relaxed">
                                        {{ $reclamo->descripcionDetallada }}
                                    </p>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Información Técnica</h4>
                                <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                                    <div class="sm:col-span-1">
                                        <dt class="text-xs font-medium text-slate-500">Tipo de Incidente</dt>
                                        <dd class="mt-1 text-sm text-slate-900 font-bold">{{ $reclamo->tipoIncidente->nombreIncidente ?? 'General' }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-xs font-medium text-slate-500">Técnico Asignado</dt>
                                        <dd class="mt-1 text-sm text-slate-900 font-medium">
                                            @if($reclamo->tecnico)
                                                <div class="flex items-center">
                                                    <div class="h-6 w-6 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs mr-2 font-bold border border-indigo-200">
                                                        {{ substr($reclamo->tecnico->primerNombre, 0, 1) }}
                                                    </div>
                                                    {{ $reclamo->tecnico->primerNombre }}
                                                </div>
                                            @else
                                                <span class="text-slate-400 italic">Pendiente de asignación</span>
                                            @endif
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <!-- Acciones de Usuario (Confirmar / Rechazar) -->
                        @if($reclamo->estado == 'Resuelto')
                            <div class="mt-8 pt-6 border-t border-slate-100 bg-indigo-50/50 -mx-8 px-8 py-6">
                                <h4 class="text-sm font-bold text-slate-900 mb-2">Acción Requerida</h4>
                                <p class="text-sm text-slate-600 mb-4">El técnico ha marcado este reclamo como resuelto. Por favor confirme si el problema se ha solucionado.</p>
                                
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <form action="{{ route('reclamo.cerrar', $reclamo->idReclamo) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors">
                                            <i class="fas fa-check mr-2"></i> Confirmar Solución
                                        </button>
                                    </form>

                                    <button type="button" onclick="openRejectionModal(this)" data-url="{{ route('reclamo.reabrir', $reclamo->idReclamo) }}" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-rose-600 hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 transition-colors">
                                        <i class="fas fa-times mr-2"></i> Rechazar Solución
                                    </button>
                                </div>
                            </div>
                        @endif

                        <!-- Comentarios / Historial -->
                        @if($reclamo->comentarios)
                            <div class="mt-8 pt-6 border-t border-slate-100">
                                <h4 class="text-sm font-bold text-slate-900 mb-6 flex items-center">
                                    <i class="fas fa-history mr-2 text-indigo-500"></i> Historial de Actividad
                                </h4>
                                <div class="flow-root">
                                    <ul role="list" class="-mb-8">
                                        @php
                                            $comentarios = is_string($reclamo->comentarios) ? json_decode($reclamo->comentarios, true) : $reclamo->comentarios;
                                            $comentarios = is_array($comentarios) ? $comentarios : [];
                                        @endphp
                                        
                                        @foreach($comentarios as $comentario)
                                            <li>
                                                <div class="relative pb-8">
                                                    @if(!$loop->last)
                                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-slate-200" aria-hidden="true"></span>
                                                    @endif
                                                    <div class="relative flex space-x-3">
                                                        <div>
                                                            <span class="h-8 w-8 rounded-full bg-indigo-50 flex items-center justify-center ring-8 ring-white border border-indigo-100">
                                                                <i class="fas fa-comment-alt text-indigo-500 text-xs"></i>
                                                            </span>
                                                        </div>
                                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                            <div>
                                                                <p class="text-sm text-slate-600">{{ $comentario['texto'] ?? 'Actualización del sistema' }}</p>
                                                            </div>
                                                            <div class="text-right text-sm whitespace-nowrap text-slate-400">
                                                                <time datetime="{{ $comentario['fecha'] ?? '' }}">{{ isset($comentario['fecha']) ? \Carbon\Carbon::parse($comentario['fecha'])->format('d/m H:i') : '' }}</time>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Modal de Rechazo -->
<!-- Modal de Rechazo -->
<div id="rejectionModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/75 transition-opacity" aria-hidden="true" onclick="closeRejectionModal()"></div>

    <!-- Modal Panel Container -->
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            
            <!-- Modal Panel -->
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <form id="rejectionForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-rose-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-exclamation-triangle text-rose-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Rechazar Solución
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 mb-4">
                                        Por favor indique el motivo por el cual rechaza la solución propuesta. El reclamo será reabierto.
                                    </p>
                                    <textarea name="motivo_rechazo" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Explique por qué persiste el problema..." required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-rose-600 text-base font-medium text-white hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Reabrir Reclamo
                        </button>
                        <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="closeRejectionModal()">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openRejectionModal(button) {
        const modal = document.getElementById('rejectionModal');
        const form = document.getElementById('rejectionForm');
        form.action = button.getAttribute('data-url');
        modal.classList.remove('hidden');
    }

    function closeRejectionModal() {
        const modal = document.getElementById('rejectionModal');
        modal.classList.add('hidden');
    }
</script>
@endsection
