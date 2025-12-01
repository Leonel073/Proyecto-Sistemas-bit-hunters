@extends('layouts.dashboard')

@section('title', 'Usuarios Eliminados - Nexora Bolivia')

@section('header')
    Gestión de Usuarios <span class="text-indigo-600 text-lg font-medium ml-2">| Papelera de Reciclaje</span>
@endsection

@push('styles')
    @vite([
        'resources/css/users-management.css',
        'resources/css/gerente.css',
    ])
@endpush

@push('scripts')
    @vite(['resources/js/nav.js'])
@endpush

@section('content')
  <section class="users-management">
    <div class="users-container">
      
      <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <a href="{{ route('gerente.empleados.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Volver a gestión
        </a>
        
        <div class="relative w-full md:w-64">
            <input type="text" id="searchInput" placeholder="Buscar por nombre o correo..." class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none shadow-sm">
            <i class="fas fa-search absolute left-3 top-3 text-gray-400 text-xs"></i>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse" id="deletedTable">
            <thead>
              <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                <th class="p-4 font-semibold">Nombre</th>
                <th class="p-4 font-semibold">Correo</th>
                <th class="p-4 font-semibold">Rol</th>
                <th class="p-4 font-semibold">Tipo</th>
                <th class="p-4 font-semibold">Fecha Eliminación</th>
                <th class="p-4 font-semibold text-right">Acciones</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
              @forelse ($empleados as $empleado)
              <tr class="hover:bg-gray-50 transition-colors">
                <td class="p-4 font-medium text-gray-900">{{ $empleado->primerNombre }} {{ $empleado->apellidoPaterno }}</td>
                <td class="p-4 text-gray-500">{{ $empleado->emailCorporativo }}</td>
                <td class="p-4"><span class="px-2 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700">{{ ucfirst($empleado->rol) }}</span></td>
                <td class="p-4 text-gray-500">Empleado</td>
                <td class="p-4 text-gray-500">{{ $empleado->fechaEliminacion ?? 'Desconocida' }}</td>
                <td class="p-4 text-right">
                  <form action="{{ route('gerente.empleados.restore', $empleado->idEmpleado) }}" method="POST" class="inline">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="text-green-600 hover:text-green-800 font-medium text-sm bg-green-50 hover:bg-green-100 px-3 py-1.5 rounded-lg transition">
                        <i class="fas fa-trash-restore mr-1"></i> Activar
                    </button>
                  </form>
                </td>
              </tr>
              @empty
              @endforelse

              @forelse ($usuarios as $usuario)
              <tr class="hover:bg-gray-50 transition-colors">
                <td class="p-4 font-medium text-gray-900">{{ $usuario->primerNombre }}</td>
                <td class="p-4 text-gray-500">{{ $usuario->email }}</td>
                <td class="p-4"><span class="px-2 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-700">Usuario</span></td>
                <td class="p-4 text-gray-500">Usuario</td>
                <td class="p-4 text-gray-500">{{ $usuario->fechaEliminacion ?? 'Desconocida' }}</td>
                <td class="p-4 text-right">
                  <form action="{{ route('gerente.usuarios.restore', ['id' => $usuario->idUsuario]) }}" method="POST" class="inline">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="text-green-600 hover:text-green-800 font-medium text-sm bg-green-50 hover:bg-green-100 px-3 py-1.5 rounded-lg transition">
                        <i class="fas fa-trash-restore mr-1"></i> Activar
                    </button>
                  </form>
                </td>
              </tr>
              @empty
              @endforelse
              
              @if($empleados->isEmpty() && $usuarios->isEmpty())
                <tr><td colspan="6" class="p-8 text-center text-gray-500">No hay registros eliminados</td></tr>
              @endif
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>

  <script>
    // 🔍 Filtro de búsqueda en tiempo real (safe)
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
      searchInput.addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        document.querySelectorAll('#deletedTable tbody tr').forEach(row => {
          const text = row.textContent.toLowerCase();
          row.classList.toggle('hidden', !text.includes(filter));
        });
      });
    }
  </script>
@endsection
