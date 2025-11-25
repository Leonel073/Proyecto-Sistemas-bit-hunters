<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Empleado - Nexora Bolivia</title>

  @vite([
      'resources/css/app.css',
      'resources/css/hero.css',
      'resources/css/nav.css',
      'resources/css/footer.css',
      'resources/css/users-management.css',
      'resources/css/admin.css',
      'resources/js/nav.js'
  ])

</head>
<body>
  @include('admin._nav')

  <section class="users-management">
    <div class="users-container">
      <h1 class="users-title">Editar Empleado</h1>

      <!-- ✅ CORREGIDO: route('admin.empleados.update') -->
      <form action="{{ route('admin.empleados.update', $empleado->idEmpleado) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div class="grid md:grid-cols-2 gap-4">
          <div>
            <label for="primerNombre">Primer Nombre</label>
            <input id="primerNombre" type="text" name="primerNombre" value="{{ old('primerNombre', $empleado->primerNombre) }}" required class="input-control">
            @error('primerNombre')<p class="error-message">{{ $message }}</p>@enderror
          </div>

          <div>
            <label for="segundoNombre">Segundo Nombre</label>
            <input id="segundoNombre" type="text" name="segundoNombre" value="{{ old('segundoNombre', $empleado->segundoNombre) }}" class="input-control">
            @error('segundoNombre')<p class="error-message">{{ $message }}</p>@enderror
          </div>

          <div>
            <label for="apellidoPaterno">Apellido Paterno</label>
            <input id="apellidoPaterno" type="text" name="apellidoPaterno" value="{{ old('apellidoPaterno', $empleado->apellidoPaterno) }}" required class="input-control">
            @error('apellidoPaterno')<p class="error-message">{{ $message }}</p>@enderror
          </div>

          <div>
            <label for="apellidoMaterno">Apellido Materno</label>
            <input id="apellidoMaterno" type="text" name="apellidoMaterno" value="{{ old('apellidoMaterno', $empleado->apellidoMaterno) }}" class="input-control">
            @error('apellidoMaterno')<p class="error-message">{{ $message }}</p>@enderror
          </div>

          <div>
            <label for="emailCorporativo">Email Corporativo</label>
            <input id="emailCorporativo" type="email" name="emailCorporativo" value="{{ old('emailCorporativo', $empleado->emailCorporativo) }}" required class="input-control">
            @error('emailCorporativo')<p class="error-message">{{ $message }}</p>@enderror
          </div>

          <div>
            <label for="rol">Rol</label>
            <select id="rol" name="rol" required class="input-control">
              @foreach (['Gerente','SupervisorOperador','SupervisorTecnico','Operador','Tecnico'] as $rol)
                <option value="{{ $rol }}" {{ $empleado->rol == $rol ? 'selected' : '' }}>{{ $rol }}</option>
              @endforeach
            </select>
            @error('rol')<p class="error-message">{{ $message }}</p>@enderror
          </div>

          <div>
            <label for="estado">Estado</label>
            <select id="estado" name="estado" required class="input-control">
              @foreach (['Activo','Bloqueado','Eliminado'] as $estado)
                <option value="{{ $estado }}" {{ $empleado->estado == $estado ? 'selected' : '' }}>{{ $estado }}</option>
              @endforeach
            </select>
            @error('estado')<p class="error-message">{{ $message }}</p>@enderror
          </div>
        </div>

        <div class="action-buttons mt-6">
          <button type="submit" class="btn-action">Guardar Cambios</button>
          <!-- ✅ CORREGIDO: route('admin.empleados.index') -->
          <a href="{{ route('admin.empleados.index') }}" class="btn-deleted">Cancelar</a>
        </div>
      </form>
    </div>
  </section>
</body>
</html>
