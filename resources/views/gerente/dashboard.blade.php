@extends('layouts.dashboard')

@section('title', 'Dashboard Gerencial')

@section('header')
    Dashboard Gerencial <span class="text-indigo-600 text-lg font-medium ml-2">| Resumen y Analítica</span>
@endsection

@section('content')

    <!-- GRUPO 1: KPIs PRINCIPALES -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- KPI 1: Carga Pendiente -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
            <div class="w-12 h-12 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center text-xl mr-4">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium">Carga Pendiente</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ number_format($reclamosPendientes) }}</h3>
                <p class="text-xs text-gray-400 mt-1">Reclamos activos</p>
            </div>
        </div>

        <!-- KPI 2: Resueltos Hoy -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
            <div class="w-12 h-12 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-xl mr-4">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium">Resueltos Hoy</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ number_format($reclamosResueltosHoy) }}</h3>
                <p class="text-xs text-gray-400 mt-1">{{ \Carbon\Carbon::today()->format('d/m/Y') }}</p>
            </div>
        </div>

        <!-- KPI 3: Tiempo Promedio -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
            <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xl mr-4">
                <i class="fas fa-hourglass-half"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium">T.P. Resolución</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ number_format($tiempoPromedio, 1) }} hrs</h3>
                <p class="text-xs text-gray-400 mt-1">Últimos 30 días</p>
            </div>
        </div>

        <!-- KPI 4: Total Personal -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
            <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center text-xl mr-4">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Empleados</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ number_format($totalEmpleados) }}</h3>
                <p class="text-xs text-gray-400 mt-1">Activos en sistema</p>
            </div>
        </div>
    </div>

    <!-- GRUPO 2: DETALLES Y GRÁFICOS -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        
        <!-- Distribución de Reclamos -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 lg:col-span-1">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Distribución de Reclamos</h3>
            
            <div class="space-y-6">
                <div>
                    <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Por Estado</h4>
                    <div class="space-y-2">
                        @foreach ($porEstado as $estado => $total)
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">{{ $estado }}</span>
                                <span class="font-bold text-gray-800 bg-gray-100 px-2 py-0.5 rounded-full">{{ $total }}</span>
                            </div>
                            <!-- Barra de progreso simple -->
                            <div class="w-full bg-gray-100 rounded-full h-1.5">
                                <div class="bg-indigo-500 h-1.5 rounded-full" style="width: {{ ($total / max($totalReclamos, 1)) * 100 }}%"></div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100">
                    <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Por Prioridad</h4>
                    <div class="space-y-2">
                        @foreach ($porPrioridad as $prioridad => $total)
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600 flex items-center">
                                    <span class="w-2 h-2 rounded-full mr-2 
                                        @if($prioridad == 'Urgente') bg-red-500
                                        @elseif($prioridad == 'Alta') bg-orange-500
                                        @elseif($prioridad == 'Media') bg-yellow-500
                                        @else bg-green-500 @endif">
                                    </span>
                                    {{ $prioridad }}
                                </span>
                                <span class="font-bold text-gray-800">{{ $total }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Reclamos Recientes -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 lg:col-span-2 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-800">Reclamos Recientes</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                            <th class="p-4 font-semibold">ID</th>
                            <th class="p-4 font-semibold">Cliente</th>
                            <th class="p-4 font-semibold">Estado</th>
                            <th class="p-4 font-semibold">Fecha</th>
                            <th class="p-4 font-semibold">Asignado A</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse ($reclamosRecientes as $reclamo)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="p-4 font-medium text-indigo-600">#{{ $reclamo->idReclamo }}</td>
                                <td class="p-4">
                                    <div class="font-medium text-gray-900">{{ $reclamo->usuario->primerNombre ?? 'N/A' }}</div>
                                    <div class="text-gray-500 text-xs">{{ $reclamo->usuario->numeroCelular ?? '' }}</div>
                                </td>
                                <td class="p-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-bold 
                                        @if($reclamo->estado == 'Nuevo') bg-blue-100 text-blue-700
                                        @elseif($reclamo->estado == 'Resuelto') bg-green-100 text-green-700
                                        @elseif($reclamo->estado == 'Cerrado') bg-gray-100 text-gray-700
                                        @elseif($reclamo->estado == 'Urgente') bg-red-100 text-red-700
                                        @else bg-yellow-100 text-yellow-700 @endif">
                                        {{ $reclamo->estado }}
                                    </span>
                                </td>
                                <td class="p-4 text-gray-600">
                                    {{ \Carbon\Carbon::parse($reclamo->fechaCreacion)->format('d/m H:i') }}
                                </td>
                                <td class="p-4 text-gray-600 text-xs">
                                    @if($reclamo->tecnico)
                                        <span class="flex items-center"><i class="fas fa-tools mr-1 text-gray-400"></i> {{ $reclamo->tecnico->primerNombre }}</span>
                                    @elseif($reclamo->operador)
                                        <span class="flex items-center"><i class="fas fa-headset mr-1 text-gray-400"></i> {{ $reclamo->operador->primerNombre }}</span>
                                    @else
                                        <span class="text-gray-400 italic">Sin asignar</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-gray-500">
                                    No hay actividad reciente registrada.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection