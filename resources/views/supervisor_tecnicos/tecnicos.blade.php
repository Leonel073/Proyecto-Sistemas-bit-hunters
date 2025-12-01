@extends('layouts.dashboard')

@section('title', 'Gestión de Técnicos')

@section('header')
    Gestión de Técnicos <span class="text-cyan-600 text-lg font-medium ml-2">| Supervisor</span>
@endsection

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div class="flex gap-2">
        <a href="{{ route('supervisor.tecnicos.create') }}" class="inline-flex items-center px-4 py-2 bg-cyan-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-cyan-700 focus:bg-cyan-700 active:bg-cyan-900 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md shadow-cyan-500/30">
            <i class="fas fa-user-plus mr-2"></i> Nuevo Técnico
        </a>
        <a href="{{ route('supervisor.tecnicos.deleted') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-lg font-bold text-xs text-slate-700 uppercase tracking-widest shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
            <i class="fas fa-trash-alt mr-2 text-rose-500"></i> Eliminados
        </a>
    </div>
    
    <div class="relative w-full sm:w-72">
        <input type="text" id="searchInput" placeholder="Buscar técnico..." class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 outline-none transition-all shadow-sm">
        <i class="fas fa-search absolute left-3 top-3 text-slate-400 text-xs"></i>
    </div>
</div>

@if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg relative shadow-sm" role="alert">
        <strong class="font-bold"><i class="fas fa-check-circle mr-1"></i> ¡Éxito!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse" id="usersTable">
            <thead>
                <tr class="bg-slate-50 text-slate-600 text-xs uppercase tracking-wider">
                    <th class="p-4 font-semibold border-b border-slate-100">Nombre</th>
                    <th class="p-4 font-semibold border-b border-slate-100">Correo</th>
                    <th class="p-4 font-semibold border-b border-slate-100">Especialidad</th>
                    <th class="p-4 font-semibold border-b border-slate-100">Disponibilidad</th>
                    <th class="p-4 font-semibold border-b border-slate-100">Estado</th>
                    <th class="p-4 font-semibold border-b border-slate-100 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @foreach ($tecnicos as $empleado)
                <tr class="hover:bg-slate-50 transition-colors group">
                    <td class="p-4 font-bold text-slate-700">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-cyan-100 text-cyan-600 flex items-center justify-center mr-3 font-bold text-xs border border-cyan-200">
                                {{ substr($empleado->primerNombre, 0, 1) }}{{ substr($empleado->apellidoPaterno, 0, 1) }}
                            </div>
                            {{ $empleado->primerNombre }} {{ $empleado->apellidoPaterno }}
                        </div>
                    </td>
                    <td class="p-4 text-slate-600">{{ $empleado->emailCorporativo }}</td>
                    <td class="p-4">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                            {{ $empleado->tecnico->especialidad ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="p-4">
                        @php
                            $disp = $empleado->tecnico->estadoDisponibilidad ?? 'N/A';
                            $colorDisp = match($disp) {
                                'Disponible' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                'Ocupado' => 'bg-rose-100 text-rose-700 border-rose-200',
                                'En Ruta' => 'bg-cyan-100 text-cyan-700 border-cyan-200',
                                default => 'bg-slate-100 text-slate-600 border-slate-200'
                            };
                        @endphp
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $colorDisp }}">
                            {{ $disp }}
                        </span>
                    </td>
                    <td class="p-4">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $empleado->estado === 'Activo' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-rose-50 text-rose-700 border-rose-100' }}">
                            {{ $empleado->estado }}
                        </span>
                    </td>
                    <td class="p-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('supervisor.tecnicos.edit', $empleado->idEmpleado) }}" class="text-slate-400 hover:text-cyan-600 transition-colors" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('supervisor.tecnicos.destroy', $empleado->idEmpleado) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-slate-400 hover:text-rose-500 transition-colors" onclick="return confirm('¿Eliminar a {{ $empleado->primerNombre }}?')" title="Eliminar">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Mensaje sin resultados -->
        <div id="noResults" class="hidden p-8 text-center text-slate-500 italic">
            <i class="fas fa-search text-slate-300 text-2xl mb-2"></i>
            <p>No se encontraron técnicos.</p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const table = document.getElementById('usersTable');
        const rows = table.getElementsByTagName('tr');
        const noResults = document.getElementById('noResults');

        searchInput.addEventListener('keyup', function() {
            const filter = searchInput.value.toLowerCase();
            let visibleCount = 0;

            // Start from 1 to skip header
            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                if (row.cells.length === 1) continue; // Skip empty message rows if any

                const text = row.textContent.toLowerCase();
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
</script>

@endsection