@extends('layouts.dashboard')

@section('title', 'Gestión de Personal')

@section('header')
    Administración <span class="text-indigo-600 text-lg font-medium ml-2">| Gestión de Personal</span>
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

    <!-- ACCIONES Y FILTROS -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div class="flex gap-3">
            <a href="{{ route('gerente.empleados.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow-sm transition-colors flex items-center gap-2">
                <i class="fas fa-user-plus"></i>
                <span>Nuevo Empleado</span>
            </a>
            <a href="{{ route('gerente.empleados.deleted') }}" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg shadow-sm transition-colors flex items-center gap-2">
                <i class="fas fa-trash-alt"></i>
                <span>Papelera</span>
            </a>
        </div>

        <div class="relative w-full md:w-64">
            <input type="text" id="searchInput" placeholder="Buscar empleado..." class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none shadow-sm">
            <i class="fas fa-search absolute left-3 top-3 text-gray-400 text-xs"></i>
        </div>
    </div>

    <!-- TABLA DE EMPLEADOS -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="employeesTable">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                        <th class="p-4 font-semibold">Nombre Completo</th>
                        <th class="p-4 font-semibold">Contacto</th>
                        <th class="p-4 font-semibold">Rol</th>
                        <th class="p-4 font-semibold">Estado</th>
                        <th class="p-4 font-semibold text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse ($empleados as $empleado)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-4">
                                <div class="font-medium text-gray-900">{{ $empleado->primerNombre }} {{ $empleado->apellidoPaterno }}</div>
                                <div class="text-xs text-gray-500">CI: {{ $empleado->ci ?? 'N/A' }}</div>
                            </td>
                            <td class="p-4">
                                <div class="text-gray-700">{{ $empleado->emailCorporativo }}</div>
                                <div class="text-xs text-gray-500">{{ $empleado->numeroCelular ?? 'S/N' }}</div>
                            </td>
                            <td class="p-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($empleado->rol == 'Gerente') bg-purple-100 text-purple-800
                                    @elseif($empleado->rol == 'SuperAdmin') bg-red-100 text-red-800
                                    @elseif(str_contains($empleado->rol, 'Supervisor')) bg-orange-100 text-orange-800
                                    @else bg-blue-100 text-blue-800 @endif">
                                    {{ $empleado->rol }}
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-1 rounded-full text-xs font-bold 
                                    @if($empleado->estado == 'Activo') bg-green-100 text-green-700
                                    @else bg-red-100 text-red-700 @endif">
                                    {{ $empleado->estado }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('gerente.empleados.edit', $empleado->idEmpleado) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-lg transition" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    @if($empleado->rol !== 'SuperAdmin')
                                        <form action="{{ route('gerente.empleados.destroy', $empleado->idEmpleado) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este empleado?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition" title="Eliminar">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-gray-500">
                                No hay empleados registrados en el sistema.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const table = document.getElementById('employeesTable');
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
