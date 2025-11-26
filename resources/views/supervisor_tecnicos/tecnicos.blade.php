<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nexora Bolivia - Gestión de Técnicos</title>
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
        <button class="active">Gestión de Técnicos</button>
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" style="background: linear-gradient(to right, #ef4444, #dc2626); color: white; font-weight: bold; border: 2px solid rgba(255,255,255,0.3); padding: 8px 16px; border-radius: 8px; cursor: pointer; transition: all 0.3s; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);" onmouseover="this.style.boxShadow='0 6px 12px rgba(0,0,0,0.2)'; this.style.transform='scale(1.05)';" onmouseout="this.style.boxShadow='0 4px 6px rgba(0,0,0,0.1)'; this.style.transform='scale(1);'">
                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                <span>Salir</span>
            </button>
        </form>
      </div>
    </div>
  </nav>

  <section class="users-management">
    <div class="users-container">
      <h1 class="users-title">Gestión de Técnicos</h1>

      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <div class="action-buttons">
        <a href="{{ route('supervisor.tecnicos.create') }}" class="btn-action">Registrar Nuevo Técnico</a>
        <a href="{{ route('supervisor.tecnicos.deleted') }}" class="btn-action btn-deleted">Técnicos Eliminados</a>
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
            <th>Especialidad</th>
            <th>Disponibilidad</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($tecnicos as $empleado)
          <tr>
            <td>{{ $empleado->primerNombre }} {{ $empleado->apellidoPaterno }}</td>
            <td>{{ $empleado->emailCorporativo }}</td>
            <td>{{ $empleado->tecnico->especialidad ?? 'N/A' }}</td>
            <td>{{ $empleado->tecnico->estadoDisponibilidad ?? 'N/A' }}</td>
            <td>{{ $empleado->estado }}</td>
            <td class="actions">
              <a href="{{ route('supervisor.tecnicos.edit', $empleado->idEmpleado) }}" class="btn-edit">Editar</a>
              <form action="{{ route('supervisor.tecnicos.destroy', $empleado->idEmpleado) }}" method="POST" style="display:inline;">
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