<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nexora Bolivia - Gestión de Operadores</title>
  @vite([
      'resources/css/app.css', 'resources/css/hero.css', 'resources/css/nav.css',
      'resources/css/footer.css', 'resources/css/users-management.css', 'resources/js/nav.js'
  ])
</head>
<body>

  <nav>
    <div class="container">
      <div class="logo" onclick="window.location.href='{{ route('home') }}'">
        </div>
      <div class="nav-links" id="navLinks">
        <button onclick="window.location.href='{{ route('home') }}'">Inicio</button>
        <button class="active">Gestión de Operadores</button>
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit">Cerrar Sesión</button>
        </form>
      </div>
    </div>
  </nav>

  <section class="users-management">
    <div class="users-container">
      <h1 class="users-title">Gestión de Operadores</h1>

      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <div class="action-buttons">
        <a href="{{ route('supervisor.operadores.create') }}" class="btn-action">Registrar Nuevo Operador</a>
        <a href="{{ route('supervisor.operadores.deleted') }}" class="btn-action btn-deleted">Operadores Eliminados</a>
      </div>

      <div class="search-filters">
        <div class="search-input">
          <input type="text" id="searchInput" placeholder="Buscar por nombre o correo...">
        </div>
      </div>

      <table class="users-table" id="usersTable">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Turno</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($operadores as $empleado)
          <tr>
            <td>{{ $empleado->primerNombre }} {{ $empleado->apellidoPaterno }}</td>
            <td>{{ $empleado->emailCorporativo }}</td>
            <td>{{ $empleado->operador->turno ?? 'N/A' }}</td>
            <td>{{ $empleado->estado }}</td>
            <td class="actions">
              <a href="{{ route('supervisor.operadores.edit', $empleado->idEmpleado) }}" class="btn-edit">Editar</a>
              <form action="{{ route('supervisor.operadores.destroy', $empleado->idEmpleado) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-delete" onclick="return confirm('¿Eliminar a {{ $empleado->primerNombre }}?')">Eliminar</button>
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