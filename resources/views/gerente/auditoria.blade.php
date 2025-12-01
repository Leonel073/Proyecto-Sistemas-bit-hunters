@extends('layouts.dashboard')

@section('title', 'Auditoría del Sistema')

@section('header')
    Auditoría del Sistema <span class="text-indigo-600 text-lg font-medium ml-2">| Registro de Actividad</span>
@endsection

@section('content')

    <!-- FILTROS Y BÚSQUEDA (Opcional, visual por ahora) -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div class="flex gap-3">
            <!-- Aquí podrían ir filtros por fecha o tipo de acción -->
        </div>

        <div class="relative w-full md:w-64">
            <input type="text" id="searchInput" placeholder="Buscar en registros..." class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none shadow-sm">
            <i class="fas fa-search absolute left-3 top-3 text-gray-400 text-xs"></i>
        </div>
    </div>

    <!-- TABLA DE AUDITORÍA -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="auditTable">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                        <th class="p-4 font-semibold">ID</th>
                        <th class="p-4 font-semibold">Fecha/Hora</th>
                        <th class="p-4 font-semibold">Acción</th>
                        <th class="p-4 font-semibold">Detalle</th>
                        <th class="p-4 font-semibold">Usuario/Empleado</th>
                        <th class="p-4 font-semibold">Contexto</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($registros as $registro)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-4 font-mono text-indigo-600 text-xs">#{{ $registro->idLog }}</td>
                            <td class="p-4">
                                <div class="font-medium text-gray-900">{{ $registro->fechaHora->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $registro->fechaHora->format('H:i:s') }}</div>
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-700">
                                    {{ $registro->accion }}
                                </span>
                            </td>
                            <td class="p-4 max-w-xs truncate" title="{{ $registro->detalleAccion }}">
                                {{ $registro->detalleAccion ?? 'N/A' }}
                            </td>
                            <td class="p-4">
                                @if($registro->empleado)
                                    <div class="flex items-center">
                                        <i class="fas fa-user-tie text-gray-400 mr-2"></i>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $registro->empleado->primerNombre }} {{ $registro->empleado->apellidoPaterno }}</div>
                                            <div class="text-xs text-gray-500">Empleado</div>
                                        </div>
                                    </div>
                                @elseif($registro->usuario)
                                    <div class="flex items-center">
                                        <i class="fas fa-user text-gray-400 mr-2"></i>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $registro->usuario->primerNombre }} {{ $registro->usuario->apellidoPaterno }}</div>
                                            <div class="text-xs text-gray-500">Cliente</div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-gray-400 italic">Sistema</span>
                                @endif
                            </td>
                            <td class="p-4">
                                <div class="text-xs text-gray-500">
                                    @if($registro->reclamo)
                                        <span class="block"><i class="fas fa-file-alt mr-1"></i> Reclamo #{{ $registro->reclamo->idReclamo }}</span>
                                    @endif
                                    <span class="block mt-1"><i class="fas fa-network-wired mr-1"></i> {{ $registro->ipOrigen ?? 'IP Desconocida' }}</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-3 text-gray-400">
                                        <i class="fas fa-clipboard-list text-xl"></i>
                                    </div>
                                    <p>No hay registros de auditoría disponibles.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- PAGINACIÓN -->
        <div class="p-4 border-t border-gray-100">
            {{ $registros->links() }}
        </div>
    </div>

    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const table = document.getElementById('auditTable');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                const cells = row.getElementsByTagName('td');
                let found = false;

                for (let j = 0; j < cells.length; j++) {
                    const cellText = cells[j].textContent || cells[j].innerText;
                    if (cellText.toLowerCase().indexOf(searchValue) > -1) {
                        found = true;
                        break;
                    }
                }

                if (found) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>

@endsection
