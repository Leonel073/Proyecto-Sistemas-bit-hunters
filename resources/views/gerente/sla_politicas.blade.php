@extends('layouts.dashboard')

@section('title', 'Políticas SLA')

@section('header')
    Administración <span class="text-indigo-600 text-lg font-medium ml-2">| Políticas SLA</span>
@endsection

@section('content')

    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- ACCIONES -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Reglas de Tiempo Límite</h2>
            <p class="text-gray-500 text-sm">Gestión de tiempos máximos de solución por prioridad.</p>
        </div>
        <button onclick="openModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow-sm transition-colors flex items-center gap-2">
            <i class="fas fa-plus"></i>
            <span>Nueva Política</span>
        </button>
    </div>

    <!-- GRID DE POLÍTICAS -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($politicas as $politica)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="font-bold text-gray-900 text-lg">{{ $politica->nombrePolitica }}</h3>
                        <div class="flex gap-2 mt-2">
                            <span class="px-2 py-1 rounded-full text-xs font-bold 
                                @if($politica->prioridad == 'Urgente') bg-red-100 text-red-800
                                @elseif($politica->prioridad == 'Alta') bg-orange-100 text-orange-800
                                @elseif($politica->prioridad == 'Media') bg-yellow-100 text-yellow-800
                                @else bg-green-100 text-green-800 @endif">
                                {{ $politica->prioridad }}
                            </span>
                            <span class="px-2 py-1 rounded-full text-xs font-bold 
                                {{ $politica->estaActiva ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $politica->estaActiva ? 'Activa' : 'Inactiva' }}
                            </span>
                        </div>
                    </div>
                    <div class="flex gap-1">
                        <button onclick="editModal({{ $politica->idPoliticaSLA }}, '{{ $politica->nombrePolitica }}', '{{ $politica->prioridad }}', {{ $politica->tiempoMaxSolucionHoras }}, {{ $politica->estaActiva ? 'true' : 'false' }})" class="text-indigo-600 hover:bg-indigo-50 p-2 rounded-lg transition" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form action="{{ route('gerente.sla-politicas.destroy', $politica->idPoliticaSLA) }}" method="POST" onsubmit="return confirm('¿Está seguro de eliminar esta política?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:bg-red-50 p-2 rounded-lg transition" title="Eliminar">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="border-t border-gray-100 pt-4">
                    <div class="flex items-center text-gray-700 mb-2">
                        <i class="fas fa-clock text-indigo-500 mr-2"></i>
                        <span class="font-medium">Tiempo Máximo:</span>
                        <span class="ml-auto font-bold">{{ $politica->tiempoMaxSolucionHoras }} horas</span>
                    </div>
                    <p class="text-xs text-gray-500 text-right">
                        ({{ $politica->tiempoMaxSolucionHoras >= 24 ? number_format($politica->tiempoMaxSolucionHoras / 24, 1) . ' días' : $politica->tiempoMaxSolucionHoras . ' horas' }})
                    </p>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12 bg-white rounded-xl border border-gray-100 border-dashed">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-50 mb-4">
                    <i class="fas fa-clipboard-list text-indigo-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900">No hay políticas definidas</h3>
                <p class="text-gray-500 mt-1">Comienza creando una nueva regla de SLA.</p>
            </div>
        @endforelse
    </div>

    <!-- SLIDE-OVER MODAL -->
    <div id="slaModal" class="fixed inset-0 overflow-hidden z-50 hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeModal()"></div>
            <div class="fixed inset-y-0 right-0 pl-10 max-w-full flex">
                <div class="w-screen max-w-md transform transition ease-in-out duration-500 sm:duration-700 translate-x-full" id="modalPanel">
                    <div class="h-full flex flex-col bg-white shadow-xl overflow-y-scroll">
                        <div class="py-6 px-4 sm:px-6 bg-indigo-600">
                            <div class="flex items-center justify-between">
                                <h2 class="text-lg font-medium text-white" id="modalTitle">Nueva Política SLA</h2>
                                <div class="ml-3 h-7 flex items-center">
                                    <button type="button" class="bg-indigo-600 rounded-md text-indigo-200 hover:text-white focus:outline-none" onclick="closeModal()">
                                        <span class="sr-only">Cerrar panel</span>
                                        <i class="fas fa-times text-xl"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="mt-1">
                                <p class="text-sm text-indigo-300">Configura los parámetros de tiempo para esta prioridad.</p>
                            </div>
                        </div>
                        <div class="relative flex-1 py-6 px-4 sm:px-6">
                            <form id="slaForm" method="POST" class="space-y-6">
                                @csrf
                                <input type="hidden" name="_method" id="formMethod" value="POST">
                                <input type="hidden" name="id" id="politicaId">
                                
                                <div>
                                    <label for="nombrePolitica" class="block text-sm font-medium text-gray-700">Nombre de la Política</label>
                                    <div class="mt-1">
                                        <input type="text" name="nombrePolitica" id="nombrePolitica" required class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md p-2 border" placeholder="Ej: Atención Inmediata">
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="prioridad" class="block text-sm font-medium text-gray-700">Prioridad</label>
                                    <div class="mt-1">
                                        <select name="prioridad" id="prioridad" required class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md p-2 border">
                                            <option value="Urgente">Urgente</option>
                                            <option value="Alta">Alta</option>
                                            <option value="Media">Media</option>
                                            <option value="Baja">Baja</option>
                                        </select>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Solo puede haber una política activa por prioridad.</p>
                                </div>
                                
                                <div>
                                    <label for="tiempoMaxSolucionHoras" class="block text-sm font-medium text-gray-700">Tiempo Máximo (horas)</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <input type="number" name="tiempoMaxSolucionHoras" id="tiempoMaxSolucionHoras" min="1" max="720" required class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pr-12 sm:text-sm border-gray-300 rounded-md p-2 border" placeholder="24">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">hrs</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="estaActiva" name="estaActiva" type="checkbox" value="1" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="estaActiva" class="font-medium text-gray-700">Política Activa</label>
                                        <p class="text-gray-500">Si se desactiva, no se aplicará a nuevos reclamos.</p>
                                    </div>
                                </div>

                                <div class="pt-5 border-t border-gray-200">
                                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Guardar Política
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
        function openModal() {
            document.getElementById('slaModal').classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('modalPanel').classList.remove('translate-x-full');
            }, 10);
            
            document.getElementById('modalTitle').textContent = 'Nueva Política SLA';
            document.getElementById('slaForm').action = '{{ route("gerente.sla-politicas.store") }}';
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('slaForm').reset();
            document.getElementById('politicaId').value = '';
            // Default active
            document.getElementById('estaActiva').checked = true;
        }

        function editModal(id, nombre, prioridad, tiempo, activa) {
            document.getElementById('slaModal').classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('modalPanel').classList.remove('translate-x-full');
            }, 10);

            document.getElementById('modalTitle').textContent = 'Editar Política SLA';
            // Fix route generation for JS
            let url = '{{ route("gerente.sla-politicas.update", ":id") }}';
            url = url.replace(':id', id);
            
            document.getElementById('slaForm').action = url;
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('politicaId').value = id;
            document.getElementById('nombrePolitica').value = nombre;
            document.getElementById('prioridad').value = prioridad;
            document.getElementById('tiempoMaxSolucionHoras').value = tiempo;
            document.getElementById('estaActiva').checked = activa;
        }

        function closeModal() {
            document.getElementById('modalPanel').classList.add('translate-x-full');
            setTimeout(() => {
                document.getElementById('slaModal').classList.add('hidden');
            }, 500);
        }
    </script>

@endsection
