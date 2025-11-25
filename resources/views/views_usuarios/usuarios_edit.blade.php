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
      'resources/js/nav.js'
  ])

</head>
<body>
  <!-- NAV -->
  <nav>
    <div class="container">
      <div class="logo" onclick="window.location.href='{{ route('home') }}'">
        <div class="logo-icon">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 20h.01M2 8.82a15.91 15.91 0 0 1 20 0M5.17 12.25a10.91 10.91 0 0 1 13.66 0M8.31 15.68a5.91 5.91 0 0 1 7.38 0" />
          </svg>
        </div>
        <div class="logo-text">
          <div class="title">Nexora Bolivia</div>
          <div class="subtitle">Apoyo al Usuario</div>
        </div>
      </div>

      <div class="nav-links" id="navLinks">
        <button onclick="window.location.href='{{ route('home') }}'">Inicio</button>
        <button class="active">Gestión de Usuarios</button>
      </div>
    </div>
  </nav>

  <section class="users-management">
    <div class="users-container">
      <h1 class="users-title">Editar Usuario</h1>

      {{-- Validacion para atrapar los errores --}}
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
          <div>
            <label>Primer Nombre</label>
            <input type="text" name="primerNombre" value="{{ old('primerNombre', $usuario->primerNombre) }}" required>
          </div>

          <div>
            <label>Segundo Nombre</label>
            <input type="text" name="segundoNombre" value="{{ old('segundoNombre', $usuario->segundoNombre) }}">
          </div>

          <div>
            <label>Apellido Paterno</label>
            <input type="text" name="apellidoPaterno" value="{{ old('apellidoPaterno', $usuario->apellidoPaterno) }}" required>
          </div>

          <div>
            <label>Apellido Materno</label>
            <input type="text" name="apellidoMaterno" value="{{ old('apellidoMaterno', $usuario->apellidoMaterno) }}">
          </div>

          <div>
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', $usuario->email) }}" required>
          </div>

          <div>
            <label>Estado</label>
            <select name="estado" required>
              @foreach (['Activo','Bloqueado','Eliminado'] as $estado)
                <option value="{{ $estado }}" {{ $usuario->estado == $estado ? 'selected' : '' }}>{{ $estado }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="action-buttons mt-6">
          <button type="submit" class="btn-action">Guardar Cambios</button>
          <a href="{{ route('usuarios') }}" class="btn-deleted">Cancelar</a>
        </div>
      </form>
    </div>
  </section>
</body>
</html>