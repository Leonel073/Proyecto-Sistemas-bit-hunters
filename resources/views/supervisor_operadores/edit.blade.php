<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Operador - Nexora Bolivia</title>

  @vite([
      'resources/css/app.css',
      'resources/css/users-management.css',
      'resources/js/nav.js'
  ])
  
  <style>
    /* Estilos básicos para el formulario de edición */
    .form-edit-container { max-width: 800px; margin: 2rem auto; padding: 2rem; background: white; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    .form-edit-container label { display: block; margin-bottom: 0.5rem; font-weight: 500; }
    .form-edit-container input, .form-edit-container select { width: 100%; padding: 0.75rem; border: 1px solid #D1D5DB; border-radius: 6px; }
  </style>
</head>
<body>
  <nav>
    <div class="container">
      <div class="logo" onclick="window.location.href='{{ route('home') }}'">
        </div>
      <div class="nav-links" id="navLinks">
        <button onclick="window.location.href='{{ route('home') }}'">Inicio</button>
        <button onclick="window.location.href='{{ route('supervisor.operadores.index') }}'">Gestión de Operadores</button>
      </div>
    </div>
  </nav>

  <section class="users-management">
    <div class="users-container form-edit-container">
      <h1 class="users-title">Editar Operador</h1>

      <form action="{{ route('supervisor.operadores.update', $empleado->idEmpleado) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div class="grid md:grid-cols-2 gap-4">
          <div>
            <label>Primer Nombre *</label>
            <input type="text" name="primerNombre" value="{{ old('primerNombre', $empleado->primerNombre) }}" required>
          </div>
          <div>
            <label>Segundo Nombre *</label>
            <input type="text" name="segundoNombre" value="{{ old('segundoNombre', $empleado->segundoNombre) }}" required>
          </div>
          <div>
            <label>Apellido Paterno *</label>
            <input type="text" name="apellidoPaterno" value="{{ old('apellidoPaterno', $empleado->apellidoPaterno) }}" required>
          </div>
          <div>
            <label>Apellido Materno *</label>
            <input type="text" name="apellidoMaterno" value="{{ old('apellidoMaterno', $empleado->apellidoMaterno) }}" required>
          </div>
          <div>
            <label>Email Corporativo *</label>
            <input type="email" name="emailCorporativo" value="{{ old('emailCorporativo', $empleado->emailCorporativo) }}" required>
          </div>
          <div>
            <label>Estado *</label>
            <select name="estado" required>
              @foreach (['Activo','Bloqueado','Eliminado'] as $estado)
                <option value="{{ $estado }}" {{ $empleado->estado == $estado ? 'selected' : '' }}>{{ $estado }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="action-buttons mt-6">
          <button type="submit" class="btn-action">Guardar Cambios</button>
          <a href="{{ route('supervisor.operadores.index') }}" class="btn-deleted">Cancelar</a>
        </div>
      </form>
    </div>
  </section>
</body>
</html>