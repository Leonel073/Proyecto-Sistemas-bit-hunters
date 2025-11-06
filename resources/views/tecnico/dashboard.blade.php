{{-- 
  Este es el archivo: resources/views/tecnico/dashboard.blade.php
  ¡ACTUALIZADO para usar tus estilos personalizados y Vite!
--}}

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Técnico - Nexora</title>

    {{-- 
      ¡IMPORTANTE!
      Cargamos tu 'app.css' (para estilos globales/Tailwind).
      Cargamos 'empleados-create.css' (para reusar estilos como .form-container, .form-card, .btn-primary).
      Cargamos el NUEVO 'tecnico-dashboard.css' que acabamos de crear.
    --}}
    @vite(['resources/css/app.css', 'resources/css/empleados-create.css', 'resources/css/tecnico-dashboard.css'])
</head>
<body class="bg-gray-100">

    {{-- Usamos tu clase .form-container para centrar el contenido --}}
    <div class="form-container">
        <h1 class="form-title">Panel del Técnico</h1>
        <h2 class="form-subtitle">¡Bienvenido, {{ Auth::user()->primerNombre }}!</h2>

        {{-- 1. SALUDO Y ESTADO DE DISPONIBILIDAD --}}
        {{-- Usamos tu clase .form-card para el diseño de tarjeta --}}
        <div class="form-card">
            
            <form action="{{ route('tecnico.estado.update') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="estadoDisponibilidad">Mi estado actual:</label>
                    
                    {{-- Usamos la clase .select-input que imagino debes tener --}}
                    <select name="estadoDisponibilidad" id="estadoDisponibilidad" class="select-input" required>
                        {{-- Usamos la variable $estadoActual que pasamos desde el controlador --}}
                        <option value="Disponible" {{ $estadoActual == 'Disponible' ? 'selected' : '' }}>Disponible</option>
                        <option value="En Ruta" {{ $estadoActual == 'En Ruta' ? 'selected' : '' }}>En Ruta</option>
                        <option value="Ocupado" {{ $estadoActual == 'Ocupado' ? 'selected' : '' }}>Ocupado</option>
                    </select>
                </div>
                
                {{-- Usamos tu clase .btn-primary --}}
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Actualizar estado</button>
                </div>
            </form>

            {{-- Mostrar mensajes de éxito (ej. "Estado actualizado") --}}
            @if (session('success'))
                {{-- Usamos la clase CSS que definimos en el paso 1 --}}
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>


        {{-- 2. LISTA DE RECLAMOS ASIGNADOS --}}
        <h2 class="form-title" style="margin-top: 2rem;">Mis Reclamos Asignados</h2>

        @forelse ($reclamos as $reclamo)
            {{-- Usamos .form-card para cada reclamo --}}
            <div class="form-card reclamo-card">
                <h3 class="form-title" style="font-size: 1.25rem;">Reclamo #R-{{ $reclamo->idReclamo }}: {{ $reclamo->titulo }}</h3>
                
                <div class="form-group">
                    <strong>Estado Actual:</strong> 
                    {{-- Usamos la clase .badge-warning que definimos --}}
                    <span class="badge-warning">{{ $reclamo->estado }}</span>
                </div>
                
                <div class="form-group">
                    <strong>Descripción del Cliente:</strong>
                    <p>{{ $reclamo->descripcionDetallada }}</p>
                </div>

                <div class="form-group">
                    <strong>Cliente:</strong>
                    <p>{{ $reclamo->usuario->primerNombre }} {{ $reclamo->usuario->apellidoPaterno }} (Cel: {{ $reclamo->usuario->numeroCelular }})</p>
                </div>

                <div class="form-group">
                    <strong>Dirección:</strong>
                    <p>{{ $reclamo->usuario->direccionTexto ?? 'No especificada' }}</p>
                </div>

                <hr style="margin: 1.5rem 0;">

                {{-- Formulario para RESOLVER el reclamo --}}
                <form action="{{ route('tecnico.reclamo.resolver', $reclamo->idReclamo) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="solucionTecnica-{{ $reclamo->idReclamo }}"><strong>Registrar Solución Técnica:</strong></label>
                        {{-- Asumo que tienes una clase para textareas --}}
                        <textarea name="solucionTecnica" id="solucionTecnica-{{ $reclamo->idReclamo }}" 
                                  class="textarea-input" rows="3" required>{{ old('solucionTecnica') }}</textarea>
                        
                        {{-- Mostrar error de validación --}}
                        @error('solucionTecnica')
                            <div class="alert-errors" style="margin-top: 1rem;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary" style="background-color: #28a745; border-color: #28a745;">Marcar como Resuelto</button>
                    </div>
                </form>
            </div>
            
            <div class="card-footer">
                Registrado el: {{ $reclamo->fechaCreacion->format('d/m/Y \a \l\a\s H:i') }}
            </div>

        @empty
            {{-- Esto se muestra si $reclamos está vacío --}}
            <div class="form-card">
                <div class="alert-info">
                    No tienes reclamos pendientes asignados en este momento.
                </div>
            </div>
        @endforelse

    </div> {{-- Fin de .form-container --}}

    {{-- Consejo Pro: Mover a un layout --}}
    <p style="text-align: center; color: #6b7280; margin-top: 2rem;">
        Nota: Para un mejor rendimiento, considera mover el &lt;head&gt; y el &lt;body&gt; a un archivo 
        <code>layouts/app.blade.php</code> y usar <code>@extends('layouts.app')</code>.
    </p>
</body>
</html>