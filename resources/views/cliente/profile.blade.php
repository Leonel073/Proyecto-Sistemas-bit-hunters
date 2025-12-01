@extends('layouts.client')

@section('title', 'Mi Perfil - Nexora Bolivia')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">Mi Perfil</h1>
        <p class="mt-2 text-slate-600">Actualice su información personal y de seguridad.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
        <div class="p-8">
            
            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center shadow-sm text-sm">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg shadow-sm text-sm">
                    <div class="font-bold mb-1">Por favor corrija los siguientes errores:</div>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('perfil.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Información Personal -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-100 pb-2 mb-4">Información Personal</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="primerNombre" class="block text-sm font-medium text-slate-700 mb-1">Primer Nombre <span class="text-red-500">*</span></label>
                            <input type="text" id="primerNombre" name="primerNombre" value="{{ old('primerNombre', $usuario->primerNombre) }}" 
                                   class="block w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </div>
                        <div>
                            <label for="segundoNombre" class="block text-sm font-medium text-slate-700 mb-1">Segundo Nombre</label>
                            <input type="text" id="segundoNombre" name="segundoNombre" value="{{ old('segundoNombre', $usuario->segundoNombre) }}" 
                                   class="block w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="apellidoPaterno" class="block text-sm font-medium text-slate-700 mb-1">Apellido Paterno <span class="text-red-500">*</span></label>
                            <input type="text" id="apellidoPaterno" name="apellidoPaterno" value="{{ old('apellidoPaterno', $usuario->apellidoPaterno) }}" 
                                   class="block w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </div>
                        <div>
                            <label for="apellidoMaterno" class="block text-sm font-medium text-slate-700 mb-1">Apellido Materno</label>
                            <input type="text" id="apellidoMaterno" name="apellidoMaterno" value="{{ old('apellidoMaterno', $usuario->apellidoMaterno) }}" 
                                   class="block w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="ci" class="block text-sm font-medium text-slate-700 mb-1">Cédula de Identidad (CI)</label>
                            <input type="text" id="ci" value="{{ $usuario->ci }}" disabled
                                   class="block w-full px-3 py-2.5 border border-slate-200 bg-slate-50 text-slate-500 rounded-lg sm:text-sm cursor-not-allowed">
                            <p class="mt-1 text-xs text-slate-400">El CI no se puede modificar.</p>
                        </div>
                        <div>
                            <label for="numeroCelular" class="block text-sm font-medium text-slate-700 mb-1">Celular <span class="text-red-500">*</span></label>
                            <input type="tel" id="numeroCelular" name="numeroCelular" value="{{ old('numeroCelular', $usuario->numeroCelular) }}" 
                                   class="block w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </div>
                    </div>
                </div>

                <!-- Información de Contacto -->
                <div class="pt-4">
                    <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-100 pb-2 mb-4">Información de Contacto</h3>
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Correo Electrónico <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-slate-400"></i>
                            </div>
                            <input type="email" id="email" name="email" value="{{ old('email', $usuario->email) }}" 
                                   class="block w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </div>
                    </div>
                </div>

                <!-- Seguridad -->
                <div class="pt-4">
                    <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-100 pb-2 mb-4">Seguridad</h3>
                    <div class="bg-indigo-50 rounded-lg p-4 mb-4 border border-indigo-100">
                        <p class="text-sm text-indigo-700 flex items-start">
                            <i class="fas fa-info-circle mt-0.5 mr-2"></i>
                            Deje los campos de contraseña en blanco si no desea cambiarla.
                        </p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Nueva Contraseña</label>
                            <input type="password" id="password" name="password" placeholder="••••••••" 
                                   class="block w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Confirmar Nueva Contraseña</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" 
                                   class="block w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="pt-6 flex items-center justify-end space-x-4 border-t border-slate-100 mt-6">
                    <a href="{{ route('seguimiento') }}" class="px-4 py-2 border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50 font-medium text-sm transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium text-sm shadow-sm transition-colors flex items-center">
                        <i class="fas fa-save mr-2"></i> Guardar Cambios
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
