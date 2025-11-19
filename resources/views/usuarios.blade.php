<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nexora Bolivia - Gestión de Usuarios</title>

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
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" style="background:none; border:none; color:white; cursor:pointer;">Salir</button>
        </form>
      </div>
    </div>
  </nav>

  <section class="users-management">
    <div class="users-container">
      <h1 class="users-title">Gestión de Usuarios</h1>

      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <div class="action-buttons">
        <a href="{{ route('admin.empleados.create') }}" class="btn-action">Registrar Nuevo Empleado</a>
        <a href="{{ route('admin.empleados.deleted') }}" class="btn-action btn-deleted">Usuarios Eliminados</a>
      </div>

      <div class="search-filters">
        <div class="search-input">
          <input type="text" id="searchInput" placeholder="Buscar por nombre o correo...">
          <i class="fa-solid fa-search"></i>
        </div>
      </div>

      <table class="users-table" id="usersTable">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($empleados as $empleado)
          <tr>
            <td>{{ $empleado->primerNombre }} {{ $empleado->apellidoPaterno }}</td>
            <td>{{ $empleado->emailCorporativo }}</td>
            <td>{{ ucfirst(str_replace('_', ' ', $empleado->rol)) }}</td>
            <td>{{ $empleado->estado }}</td>
            <td class="actions">
              <a href="{{ route('admin.empleados.edit', $empleado->idEmpleado) }}" class="btn-edit">Editar</a>
              
              <form action="{{ route('admin.empleados.destroy', $empleado->idEmpleado) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-delete" onclick="return confirm('¿Eliminar empleado?')">Eliminar</button>
              </form>
            </td>
          </tr>
        @endforeach

        @foreach ($usuarios as $usuario)
          <tr>
            <td>{{ $usuario->primerNombre }} {{ $usuario->apellidoPaterno }}</td>
            <td>{{ $usuario->email }}</td>
            <td>Usuario</td>
            <td>{{ $usuario->estado }}</td>
            <td class="actions">
              <a href="{{ route('admin.usuarios.edit', $usuario->idUsuario) }}" class="btn-edit">Editar</a>
              
              <form action="{{ route('admin.usuarios.destroy', $usuario->idUsuario) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-delete" onclick="return confirm('¿Eliminar usuario?')">Eliminar</button>
              </form>
            </td>
          </tr>
        @endforeach

        </tbody>
      </table>
    </div>
  </section>

  <script>
    document.getElementById('searchInput').addEventListener('input', function() {
      const filter = this.value.toLowerCase();
      document.querySelectorAll('#usersTable tbody tr').forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
      });
    });
  </script>
</body>
</html>