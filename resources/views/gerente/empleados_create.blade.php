@extends('layouts.dashboard')

@section('title', 'Registrar Empleado - Admin')

@section('header')
    Gestión de Usuarios <span class="text-indigo-600 text-lg font-medium ml-2">| Registrar Nuevo Empleado</span>
@endsection

@push('styles')
    @vite([
        'resources/css/register.css',
        'resources/css/btns.css',
        'resources/css/gerente.css',
    ])
@endpush

@push('scripts')
    @vite(['resources/js/empleado-create.js'])
@endpush

@section('content')
  <a href="{{ route('gerente.empleados.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 mb-6 transition-colors">
    <i class="fas fa-arrow-left mr-2"></i> Volver a Gestión
  </a>

  <div class="flex justify-center">
    <div class="card max-w-3xl w-full bg-white rounded-xl shadow-lg p-6 md:p-8 border border-gray-100">

      <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Registrar Nuevo Empleado</h2>
        <p class="text-gray-500 mt-1">Complete todos los campos y asigne un rol al nuevo usuario.</p>
      </div>

      @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg shadow-sm">
          <ul class="list-disc pl-5 text-sm">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form id="createForm" action="{{ route('gerente.empleados.store') }}" method="POST" class="space-y-6" novalidate>
        @csrf

        <!-- Nombres y Apellidos -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="input-group">
            <label for="primerNombre" class="block text-sm font-medium text-gray-700 mb-1">Primer Nombre *</label>
            <input type="text" id="primerNombre" name="primerNombre" value="{{ old('primerNombre') }}" required pattern="^[\pL\s\-]+$" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all" aria-required="true">
            @error('primerNombre')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
          </div>
          <div class="input-group">
            <label for="apellidoPaterno" class="block text-sm font-medium text-gray-700 mb-1">Apellido Paterno *</label>
            <input type="text" id="apellidoPaterno" name="apellidoPaterno" value="{{ old('apellidoPaterno') }}" required pattern="^[\pL\s\-]+$" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all" aria-required="true">
            @error('apellidoPaterno')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
          </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="input-group">
            <label for="segundoNombre" class="block text-sm font-medium text-gray-700 mb-1">Segundo Nombre</label>
            <input type="text" id="segundoNombre" name="segundoNombre" value="{{ old('segundoNombre') }}" pattern="^[\pL\s\-]+$" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
            @error('segundoNombre')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
          </div>
          <div class="input-group">
            <label for="apellidoMaterno" class="block text-sm font-medium text-gray-700 mb-1">Apellido Materno</label>
            <input type="text" id="apellidoMaterno" name="apellidoMaterno" value="{{ old('apellidoMaterno') }}" pattern="^[\pL\s\-]+$" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
            @error('apellidoMaterno')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
          </div>
        </div>

        <!-- CI y Celular -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="input-group">
            <label for="ci" class="block text-sm font-medium text-gray-700 mb-1">Cédula (CI) *</label>
            <input type="tel" id="ci" name="ci" value="{{ old('ci') }}" required pattern="\d{7,10}" maxlength="10" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
            @error('ci')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
          </div>
          <div class="input-group">
            <label for="numeroCelular" class="block text-sm font-medium text-gray-700 mb-1">Celular *</label>
            <input type="tel" id="numeroCelular" name="numeroCelular" value="{{ old('numeroCelular') }}" required pattern="\d{8,}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
            @error('numeroCelular')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
          </div>
        </div>

        <!-- Email -->
        <div class="input-group">
          <label for="emailCorporativo" class="block text-sm font-medium text-gray-700 mb-1">Correo Corporativo *</label>
          <input type="email" id="emailCorporativo" name="emailCorporativo" value="{{ old('emailCorporativo') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all" aria-describedby="emailHelp">
          @error('emailCorporativo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <!-- Rol y Fecha Ingreso -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="input-group">
            <label for="rol" class="block text-sm font-medium text-gray-700 mb-1">Rol del Empleado *</label>
            <select name="rol" id="rol" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all bg-white">
                @if(!$gerenteExiste)
                    <option value="Gerente" {{ old('rol') == 'Gerente' ? 'selected' : '' }}>Gerente de Soporte</option>
                @endif
                <option value="SupervisorOperador" {{ old('rol') == 'SupervisorOperador' ? 'selected' : '' }}>Supervisor de Operadores</option>
                <option value="SupervisorTecnico" {{ old('rol') == 'SupervisorTecnico' ? 'selected' : '' }}>Supervisor de Técnicos</option>
                <option value="Operador" {{ old('rol') == 'Operador' ? 'selected' : '' }}>Operador</option>
                <option value="Tecnico" {{ old('rol') == 'Tecnico' ? 'selected' : '' }}>Técnico</option>
            </select>
            <span id="rol-error" class="text-red-500 text-xs mt-1 hidden"></span>
          </div>
          <div class="input-group">
            <label for="fechaIngreso" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Ingreso *</label>
            <input type="date" id="fechaIngreso" name="fechaIngreso" value="{{ old('fechaIngreso', now()->toDateString()) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
            @error('fechaIngreso')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
          </div>
        </div>

        <!-- Contraseñas -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="input-group">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña *</label>
            <input type="password" id="password" name="password" required minlength="8" pattern="^(?=.[a-z])(?=.[A-Z])(?=.\d)(?=.[\W_]).{8,}$" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all" aria-describedby="passwordHelp">
            @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
          </div>
          <div class="input-group">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmar Contraseña *</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
            @error('password_confirmation')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
          </div>
        </div>
        
        <div class="flex items-center gap-3">
          <input id="togglePasswordCheckbox" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" />
          <label for="togglePasswordCheckbox" class="ml-2 block text-sm text-gray-900">Mostrar contraseñas</label>
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5" aria-label="Registrar Empleado">
                Registrar Empleado
            </button>
        </div>
      </form>
    </div>
  </div>
@endsection
