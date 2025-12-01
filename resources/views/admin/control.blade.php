@extends('layouts.dashboard')

@section('title', 'Control Técnico')
@section('header', 'Panel de Control Super Admin')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    
    <!-- Card: Estado del Sistema -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center">
            <i class="fas fa-server text-indigo-500 mr-2"></i> Estado del Sistema
        </h3>
        <div class="space-y-3">
            @foreach($systemInfo as $key => $value)
                <div class="flex justify-between items-center">
                    <span class="text-gray-500 text-sm">{{ $key }}</span>
                    <span class="font-semibold text-gray-900">{{ $value }}</span>
                </div>
            @endforeach
            <div class="mt-4 pt-3 border-t border-dashed border-gray-200">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-500 text-sm">PHP Version</span>
                    <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded text-gray-700">{{ phpversion() }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500 text-sm">Laravel</span>
                    <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded text-gray-700">{{ app()->version() }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Card: Métricas Clave -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center">
            <i class="fas fa-database text-emerald-500 mr-2"></i> Registros en BD
        </h3>
        <div class="grid grid-cols-2 gap-4">
            @foreach($tableCounts as $table => $count)
                <div class="bg-gray-50 p-4 rounded-lg text-center border border-gray-100">
                    <div class="text-2xl font-bold text-gray-800">{{ number_format($count) }}</div>
                    <div class="text-xs text-gray-500 uppercase tracking-wider mt-1">{{ $table }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Card: Accesos Rápidos -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow md:col-span-2 lg:col-span-3">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center">
            <i class="fas fa-rocket text-amber-500 mr-2"></i> Accesos Directos a Módulos
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('gerente.dashboard') }}" class="group block">
                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 text-white p-6 rounded-xl shadow-lg shadow-indigo-200 transform transition-transform group-hover:-translate-y-1">
                    <i class="fas fa-chart-line text-3xl mb-3 block opacity-80"></i>
                    <span class="font-semibold text-lg">Panel Gerencial</span>
                </div>
            </a>
            <a href="{{ route('supervisor.operadores.index') }}" class="group block">
                <div class="bg-gradient-to-br from-pink-500 to-rose-500 text-white p-6 rounded-xl shadow-lg shadow-pink-200 transform transition-transform group-hover:-translate-y-1">
                    <i class="fas fa-headset text-3xl mb-3 block opacity-80"></i>
                    <span class="font-semibold text-lg">Sup. Operadores</span>
                </div>
            </a>
            <a href="{{ route('supervisor.tecnicos.index') }}" class="group block">
                <div class="bg-gradient-to-br from-cyan-500 to-blue-500 text-white p-6 rounded-xl shadow-lg shadow-cyan-200 transform transition-transform group-hover:-translate-y-1">
                    <i class="fas fa-tools text-3xl mb-3 block opacity-80"></i>
                    <span class="font-semibold text-lg">Sup. Técnicos</span>
                </div>
            </a>
            <a href="{{ route('operador.panel') }}" class="group block">
                <div class="bg-gradient-to-br from-orange-400 to-yellow-500 text-white p-6 rounded-xl shadow-lg shadow-orange-200 transform transition-transform group-hover:-translate-y-1">
                    <i class="fas fa-desktop text-3xl mb-3 block opacity-80"></i>
                    <span class="font-semibold text-lg">Panel Operador</span>
                </div>
            </a>
        </div>
    </div>

    <!-- Tabla: Auditoría Reciente -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow md:col-span-2 lg:col-span-3">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center">
            <i class="fas fa-shield-alt text-red-500 mr-2"></i> Auditoría Reciente
        </h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                        <th class="p-3 font-semibold border-b border-gray-200">Fecha</th>
                        <th class="p-3 font-semibold border-b border-gray-200">Usuario</th>
                        <th class="p-3 font-semibold border-b border-gray-200">Acción</th>
                        <th class="p-3 font-semibold border-b border-gray-200">Detalle</th>
                        <th class="p-3 font-semibold border-b border-gray-200">IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($latestLogs as $log)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-3 text-gray-600 whitespace-nowrap">{{ $log->fechaHora }}</td>
                            <td class="p-3 font-medium text-gray-900">
                                {{ $log->empleado->primerNombre ?? 'Sistema' }} {{ $log->empleado->apellidoPaterno ?? '' }}
                            </td>
                            <td class="p-3">
                                <span class="px-2 py-1 rounded text-xs font-bold bg-sky-100 text-sky-700">
                                    {{ $log->accion }}
                                </span>
                            </td>
                            <td class="p-3 text-gray-500 max-w-xs truncate" title="{{ $log->detalleAccion }}">
                                {{ Str::limit($log->detalleAccion, 50) }}
                            </td>
                            <td class="p-3 font-mono text-xs text-gray-400">{{ $log->ipOrigen }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-gray-400 italic">No hay registros de auditoría recientes.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection