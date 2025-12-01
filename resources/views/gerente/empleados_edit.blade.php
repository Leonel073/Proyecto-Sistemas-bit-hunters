@extends('layouts.dashboard')

@section('title', 'Editar Empleado - Nexora Bolivia')

@section('header')
    Gestión de Usuarios <span class="text-indigo-600 text-lg font-medium ml-2">| Editar Empleado</span>
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
  <a href="{{ route('gerente.empleados.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 mb-6 transition-colors">
    <i class="fas fa-arrow-left mr-2"></i> Volver a Gestión
  </a>

  <div class="flex justify-center">
    <div class="card max-w-3xl w-full bg-white rounded-xl shadow-lg p-6 md:p-8 border border-gray-100">

      <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Editar Empleado</h2>
        <p class="text-gray-500 mt-1">Modifique los datos del empleado seleccionado.</p>
      </div>

      <form action="{{ route('gerente.empleados.update', $empleado->idEmpleado) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid md:grid-cols-2 gap-6">
          <div class="input-group">
            <label for="primerNombre" class="block text-sm font-medium text-gray-700 mb-1">Primer Nombre</label>
            <input id="primerNombre" type="text" name="primerNombre" value="{{ old('primerNombre', $empleado->primerNombre) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
            @error('primerNombre')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
          </div>

          <div class="input-group">
            <label for="segundoNombre" class="block text-sm font-medium text-gray-700 mb-1">Segundo Nombre</label>
            <input id="segundoNombre" type="text" name="segundoNombre" value="{{ old('segundoNombre', $empleado->segundoNombre) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
            @error('segundoNombre')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
          </div>

          <div class="input-group">
            <label for="apellidoPaterno" class="block text-sm font-medium text-gray-700 mb-1">Apellido Paterno</label>
            <input id="apellidoPaterno" type="text" name="apellidoPaterno" value="{{ old('apellidoPaterno', $empleado->apellidoPaterno) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
            @error('apellidoPaterno')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
          </div>

          <div class="input-group">
            <label for="apellidoMaterno" class="block text-sm font-medium text-gray-700 mb-1">Apellido Materno</label>
            <input id="apellidoMaterno" type="text" name="apellidoMaterno" value="{{ old('apellidoMaterno', $empleado->apellidoMaterno) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
            @error('apellidoMaterno')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
          </div>

          <div class="input-group md:col-span-2">
            <label for="emailCorporativo" class="block text-sm font-medium text-gray-700 mb-1">Email Corporativo</label>
            <input id="emailCorporativo" type="email" name="emailCorporativo" value="{{ old('emailCorporativo', $empleado->emailCorporativo) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
            @error('emailCorporativo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
          </div>

          <div class="input-group">
            <label for="rol" class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
            <select id="rol" name="rol" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all bg-white">
              @foreach (['Gerente','SupervisorOperador','SupervisorTecnico','Operador','Tecnico'] as $rol)
                <option value="{{ $rol }}" {{ $empleado->rol == $rol ? 'selected' : '' }}>{{ $rol }}</option>
              @endforeach
            </select>
            @error('rol')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
          </div>

          <div class="input-group">
            <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
            <select id="estado" name="estado" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all bg-white">
              @foreach (['Activo','Bloqueado','Eliminado'] as $estado)
                <option value="{{ $estado }}" {{ $empleado->estado == $estado ? 'selected' : '' }}>{{ $estado }}</option>
              @endforeach
            </select>
            @error('estado')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
          </div>
        </div>

        <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-gray-100">
          <a href="{{ route('gerente.empleados.index') }}" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancelar</a>
          <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">Guardar Cambios</button>
        </div>
      </form>
    </div>
  </div>
@endsection
