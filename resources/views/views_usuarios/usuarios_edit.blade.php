<!DOCTYPE html>

<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Usuario - Nexora Bolivia</title>

@vite([
    'resources/css/app.css',
    'resources/css/hero.css',
    'resources/css/nav.css',
    'resources/css/footer.css',
    'resources/css/users-management.css',
    'resources/css/admin.css', // Aseguramos la importación del CSS de admin
    'resources/js/nav.js'
])


</head>
<body>

<!-- Incluimos la navegación del administrador para mantener el diseño -->
@include('admin._nav')

<section class="users-management">
    <div class="users-container">
        <h1 class="users-title">Editar Usuario (Cliente)</h1>

        {{-- Mensajes de alerta --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Formulario --}}
        <form action="{{ route('admin.usuarios.update', $usuario->idUsuario) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid md:grid-cols-2 gap-4">
                
                {{-- PRIMER NOMBRE --}}
                <div>
                    <label for="primerNombre">Primer Nombre</label>
                    <input id="primerNombre" type="text" name="primerNombre" 
                           value="{{ old('primerNombre', $usuario->primerNombre) }}" required 
                           class="input-control">
                    @error('primerNombre')<p class="error-message">{{ $message }}</p>@enderror
                </div>

                {{-- SEGUNDO NOMBRE --}}
                <div>
                    <label for="segundoNombre">Segundo Nombre</label>
                    <input id="segundoNombre" type="text" name="segundoNombre" 
                           value="{{ old('segundoNombre', $usuario->segundoNombre) }}" 
                           class="input-control">
                    @error('segundoNombre')<p class="error-message">{{ $message }}</p>@enderror
                </div>

                {{-- APELLIDO PATERNO --}}
                <div>
                    <label for="apellidoPaterno">Apellido Paterno</label>
                    <input id="apellidoPaterno" type="text" name="apellidoPaterno" 
                           value="{{ old('apellidoPaterno', $usuario->apellidoPaterno) }}" required 
                           class="input-control">
                    @error('apellidoPaterno')<p class="error-message">{{ $message }}</p>@enderror
                </div>

                {{-- APELLIDO MATERNO --}}
                <div>
                    <label for="apellidoMaterno">Apellido Materno</label>
                    <input id="apellidoMaterno" type="text" name="apellidoMaterno" 
                           value="{{ old('apellidoMaterno', $usuario->apellidoMaterno) }}" 
                           class="input-control">
                    @error('apellidoMaterno')<p class="error-message">{{ $message }}</p>@enderror
                </div>
                
                {{-- EMAIL --}}
                <div>
                    <label for="email">Correo Electrónico</label>
                    <input id="email" type="email" name="email" 
                           value="{{ old('email', $usuario->email) }}" required 
                           class="input-control">
                    @error('email')<p class="error-message">{{ $message }}</p>@enderror
                </div>
                
                {{-- NUMERO CELULAR (Agregado para ser consistente con el CRUD de Empleado) --}}
                <div>
                    <label for="numeroCelular">Número de Celular</label>
                    <input id="numeroCelular" type="text" name="numeroCelular" 
                           value="{{ old('numeroCelular', $usuario->numeroCelular) }}" required 
                           class="input-control">
                    @error('numeroCelular')<p class="error-message">{{ $message }}</p>@enderror
                </div>

                {{-- ESTADO --}}
                <div>
                    <label for="estado">Estado</label>
                    <select id="estado" name="estado" required class="input-control">
                        @foreach (['Activo', 'Bloqueado', 'Eliminado'] as $estado)
                            <option value="{{ $estado }}" {{ $usuario->estado == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                        @endforeach
                    </select>
                    @error('estado')<p class="error-message">{{ $message }}</p>@enderror
                </div>
                
                {{-- CAMPO CI (NO EDITABLE) --}}
                <div>
                    <label>CI (No editable)</label>
                    <input type="text" value="{{ $usuario->ci }}" class="input-control bg-gray-100" disabled>
                </div>
            </div>

            <div class="action-buttons mt-6">
                <button type="submit" class="btn-action">Guardar Cambios</button>
                <!-- ✅ CORREGIDO: Apuntando a la lista de administración -->
                <a href="{{ route('admin.usuarios.index') }}" class="btn-deleted">Cancelar</a>
            </div>
        </form>
    </div>
</section>

{{-- Scripts --}}
<!-- Aquí iría cualquier script JS si fuera necesario -->


</body>
</html>