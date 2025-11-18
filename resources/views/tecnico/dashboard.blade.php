@extends('layouts.app')

@section('title', 'Panel de Técnico - Reclamos')

@section('content')
    {{-- La variable $estadoActual viene directamente del controlador. --}}
    @php
        $estadoActual = $estadoActual ?? 'No Disponible'; // Asegurar que tenga un valor por defecto
        
        // Colores base para cada estado (usando clases de Tailwind, complementadas por CSS)
        $color = [
            'Disponible' => 'bg-green-500 text-white',
            'En Ruta' => 'bg-yellow-500 text-gray-800',
            'Ocupado' => 'bg-red-500 text-white',
            'No Disponible' => 'bg-gray-500 text-white',
        ];

        // Definimos los posibles estados que el técnico puede elegir
        $opcionesEstado = ['Disponible', 'En Ruta', 'Ocupado'];
    @endphp

    <div class="container mx-auto px-4">

        {{-- Mensajes de Sesión (Éxito/Error) --}}
        @if (session('success'))
            <div class="alert-success" role="alert">
                <p class="font-bold">¡Éxito!</p>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        @endif
        @if (session('error'))
            <div class="alert-error" role="alert">
                <p class="font-bold">Error</p>
                <p class="text-sm">{{ session('error') }}</p>
            </div>
        @endif
        @if ($errors->any())
            {{-- Mensaje genérico de error de validación --}}
            <div class="alert-error mb-6 shadow-md" role="alert">
                <p class="font-bold">Error de Validación</p>
                <p class="text-sm">Por favor, revisa el formulario en la sección de Reclamos.</p>
            </div>
        @endif

        <h2 class="text-3xl font-extrabold text-gray-900 mb-6 border-b pb-2">
            Panel del Técnico: {{ $tecnico->primerNombre }} {{ $tecnico->apellidoPaterno }}
        </h2>

        {{-- 1. CONTROL DE ESTATUS DE DISPONIBILIDAD (Usando la clase 'status-control-card' del CSS) --}}
        <div class="status-control-card">
            <h3 class="text-xl font-semibold text-gray-700 mb-4 flex justify-between items-center">
                Mi Estatus Actual
                {{-- Muestra el estado actual y su color correspondiente --}}
                <span class="text-sm font-medium px-3 py-1 rounded-full shadow-md {{ $color[$estadoActual] ?? 'bg-gray-500 text-white' }}">
                    {{ $estadoActual }}
                </span>
            </h3>
            
            <form action="{{ route('tecnico.estado.update') }}" method="POST" class="flex flex-col sm:flex-row gap-4 items-start sm:items-center">
                @csrf
                
                {{-- Dropdown para seleccionar el estado --}}
                <select name="estadoDisponibilidad" id="estadoDisponibilidad" required class="w-full sm:w-auto p-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition">
                    <option value="" disabled selected>Selecciona tu nuevo estado</option>
                    @foreach ($opcionesEstado as $opcion)
                        <option value="{{ $opcion }}" @if($opcion === $estadoActual) selected @endif>
                            {{ $opcion }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="w-full sm:w-auto px-6 py-2 rounded-lg font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition duration-200">
                    Cambiar Estado
                </button>
            </form>
        </div>

        {{-- 2. RECLAMOS ASIGNADOS (Usando la clase 'reclamo-card' del CSS) --}}
        <div class="bg-white p-6 rounded-xl shadow-lg">
            <h3 class="text-2xl font-bold text-gray-800 mb-6">Mis Reclamos Pendientes ({{ count($reclamos) }})</h3>
            
            @if ($reclamos->isEmpty())
                <div class="text-center py-10 text-gray-500 border-2 border-dashed border-gray-300 rounded-lg">
                    <p class="text-lg">¡Excelente! No tienes reclamos activos asignados.</p>
                    <p class="text-sm mt-1">Recuerda poner tu estado en "Disponible" para recibir nuevas asignaciones.</p>
                </div>
            @else
                <div class="space-y-6">
                    @foreach ($reclamos as $reclamo)
                        {{-- Tarjeta de Reclamo --}}
                        <div class="reclamo-card border border-gray-200 bg-white">
                            {{-- Encabezado de la Tarjeta (Usando la clase 'card-header' del CSS) --}}
                            <div class="card-header">
                                <div>
                                    <p class="text-sm font-medium text-indigo-600">Reclamo #{{ $reclamo->idReclamo }}</p>
                                    <h4 class="card-title">{{ $reclamo->titulo }}</h4>
                                </div>
                                <span class="badge-warning">
                                    {{ $reclamo->estado }}
                                </span>
                            </div>

                            {{-- Cuerpo de la Tarjeta (Usando la clase 'card-body' del CSS) --}}
                            <div class="card-body">
                                <p class="text-gray-600 mb-4">{{ $reclamo->descripcionDetallada }}</p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                    <p class="card-text-light">
                                        <strong>Cliente:</strong> {{ $reclamo->usuario->primerNombre }} {{ $reclamo->usuario->apellidoPaterno }}
                                    </p>
                                    <p class="card-text-light">
                                        <strong>Teléfono:</strong> {{ $reclamo->usuario->numeroCelular }}
                                    </p>
                                    <p class="card-text-light">
                                        <strong>Fecha Creación:</strong> {{ $reclamo->fechaCreacion->format('d/m/Y H:i') }}
                                    </p>
                                    <p class="card-text-light">
                                        <strong>Prioridad:</strong> 
                                        <span class="font-semibold @if($reclamo->prioridad == 'Alta') text-red-600 @elseif($reclamo->prioridad == 'Media') text-yellow-600 @else text-blue-600 @endif">
                                            {{ $reclamo->prioridad }}
                                        </span>
                                    </p>
                                    {{-- Nota: El campo de dirección no existe en el modelo Usuario, se quita para evitar error --}}
                                </div>

                                {{-- FORMULARIO DE RESOLUCIÓN (Usando la clase 'resolution-form' del CSS) --}}
                                <form action="{{ route('tecnico.reclamo.resolver', $reclamo->idReclamo) }}" method="POST" class="resolution-form">
                                    @csrf
                                    
                                    <div class="form-group mb-4">
                                        <label for="solucionTecnica-{{ $reclamo->idReclamo }}"><strong>Registrar Solución Técnica:</strong></label>
                                        {{-- El textarea usa el estilo definido en tecnico-dashboard.css --}}
                                        <textarea name="solucionTecnica" id="solucionTecnica-{{ $reclamo->idReclamo }}" 
                                                rows="4" placeholder="Describe detalladamente las acciones tomadas para resolver el problema (Mínimo 10 caracteres)." required
                                                class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('solucionTecnica') }}</textarea>
                                        
                                        {{-- Mostrar error de validación ESPECÍFICO para este campo --}}
                                        @error('solucionTecnica')
                                            <div class="text-red-500 text-sm mt-1 p-2 bg-red-100 rounded-md" role="alert">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="flex justify-end">
                                        <button type="submit">
                                            Marcar como Resuelto
                                        </button>
                                    </div>
                                </form>
                            </div>
                            
                            {{-- Pie de página de la tarjeta (Usando la clase 'card-footer' del CSS) --}}
                            <div class="card-footer">
                                <strong>Asignado por:</strong> {{ $reclamo->operador->primerNombre ?? 'N/A' }} 
                                | <strong>Fecha Asignación:</strong> {{ $reclamo->fechaAsignacion ?? 'Pendiente' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection