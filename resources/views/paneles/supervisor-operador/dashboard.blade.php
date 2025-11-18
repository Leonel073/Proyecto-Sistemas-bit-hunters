{{-- 
  NOTA: Cambia 'layouts.app' por el nombre de tu plantilla principal 
  (ej: 'layouts.admin', 'layouts.main', etc.) 
--}}
@extends('layouts.app') 

@section('content')
<div class="container">
    
    {{-- 1. Encabezado y Bienvenida --}}
    <h1>Panel del Supervisor de Operadores</h1>
    <p>Bienvenido, <strong>{{ $supervisor->primerNombre }} {{ $supervisor->apellidoPaterno }}</strong>.</p>
    
    {{-- Mensaje de éxito después de reasignar --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- 2. Tabla de Reclamos Pendientes --}}
    <h2>Reclamos Pendientes</h2>
    <p>Aquí están los reclamos nuevos o abiertos que necesitan asignación.</p>

    <table class="table" border="1" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="padding: 8px;">ID Reclamo</th>
                <th style="padding: 8px;">Título</th>
                <th style="padding: 8px;">Cliente</th>
                <th style="padding: 8px;">Estado</th>
                <th style="padding: 8px;">Asignar Operador</th>
            </tr>
        </thead>
        <tbody>
            {{-- Verificamos si hay reclamos --}}
            @forelse ($reclamos as $reclamo)
                <tr>
                    <td style="padding: 8px;">{{ $reclamo->idReclamo }}</td>
                    <td style="padding: 8px;">{{ $reclamo->titulo }}</td>
                    <td style="padding: 8px;">
                        {{-- Usamos la relación 'usuario' que cargamos en el controlador --}}
                        {{ $reclamo->usuario->nombreCompleto ?? 'Sin cliente' }} 
                    </td>
                    <td style="padding: 8px;">{{ $reclamo->estado }}</td>
                    
                    {{-- 3. Formulario de Reasignación --}}
                    <td style="padding: 8px;">
                        
                        {{-- Este formulario llama a la ruta PUT que creamos --}}
                        <form action="{{ route('supervisor.reclamos.reasignar', $reclamo->idReclamo) }}" method="POST">
                            
                            {{-- Token de seguridad OBLIGATORIO --}}
                            @csrf 
                            
                            {{-- Le decimos a Laravel que este formulario es un PUT --}}
                            @method('PUT') 

                            <select name="idOperador" required>
                                <option value="">-- Seleccionar operador --</option>
                                
                                {{-- Llenamos el dropdown con los operadores disponibles --}}
                                @foreach ($operadores as $operador)
                                    <option value="{{ $operador->idEmpleado }}">
                                        {{ $operador->primerNombre }} {{ $operador->apellidoPaterno }}
                                        ({{ $operador->operador->turno }}) {{-- Mostramos el turno --}}
                                    </option>
                                @endforeach
                            </select>

                            <button type="submit">Asignar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 10px;">
                        ¡Excelente! No hay reclamos pendientes por asignar.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>
@endsection