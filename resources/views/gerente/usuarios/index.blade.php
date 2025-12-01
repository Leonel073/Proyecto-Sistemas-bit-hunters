@extends('layouts.dashboard')

@section('title', 'Gestión de Clientes')

@section('header')
    Gestión de Clientes <span class="text-indigo-600 text-lg font-medium ml-2">| Administración</span>
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
            <a href="{{ route('gerente.usuarios.deleted') }}" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg shadow-sm transition-colors flex items-center gap-2">
                <i class="fas fa-trash-alt"></i>
                <span>Papelera</span>
            </a>
        </div>

        <div class="relative w-full md:w-64">
            <input type="text" id="searchInput" placeholder="Buscar cliente..." class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none shadow-sm">
            <i class="fas fa-search absolute left-3 top-3 text-gray-400 text-xs"></i>
        </div>
    </div>

    <!-- TABLA DE CLIENTES -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="usersTable">
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
                    @forelse ($usuarios as $usuario)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-4">
                                <div class="font-medium text-gray-900">{{ $usuario->primerNombre }} {{ $usuario->apellidoPaterno }}</div>
                                <div class="text-xs text-gray-500">CI: {{ $usuario->ci ?? 'N/A' }}</div>
                            </td>
                            <td class="p-4">
                                <div class="text-gray-700">{{ $usuario->email }}</div>
                                <div class="text-xs text-gray-500">{{ $usuario->numeroCelular ?? 'S/N' }}</div>
                            </td>
                            <td class="p-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Cliente
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-1 rounded-full text-xs font-bold 
                                    @if($usuario->estado == 'Activo') bg-green-100 text-green-700
                                    @elseif($usuario->estado == 'Bloqueado') bg-gray-100 text-gray-700
                                    @else bg-red-100 text-red-700 @endif">
                                    {{ $usuario->estado }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <!-- Bloquear / Desbloquear -->
                                    <form action="{{ route('gerente.usuarios.toggle-block', $usuario->idUsuario) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PUT')
                                        @if($usuario->estado == 'Bloqueado')
                                            <button type="submit" class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 p-2 rounded-lg transition" title="Desbloquear">
                                                <i class="fas fa-unlock"></i>
                                            </button>
                                        @else
                                            <button type="submit" class="text-orange-600 hover:text-orange-900 bg-orange-50 hover:bg-orange-100 p-2 rounded-lg transition" title="Bloquear">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        @endif
                                    </form>
                                    
                                    <form action="{{ route('gerente.usuarios.destroy', $usuario->idUsuario) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition" title="Eliminar">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-gray-500">
                                No hay clientes registrados en el sistema.
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
            const table = document.getElementById('usersTable');
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
