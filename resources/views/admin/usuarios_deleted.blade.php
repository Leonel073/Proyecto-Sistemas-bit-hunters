<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Usuarios Eliminados - Nexora Bolivia</title>

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
<body class="bg-gray-100">
  

  @include('admin._nav')

  <section class="users-management">
    <div class="users-container">
      <h1 class="users-title">Usuarios y Empleados Eliminados</h1>

      <div class="action-buttons" style="display:flex; justify-content:space-between; align-items:center; gap:12px;">
        <div>
          <!-- ✅ CORREGIDO: admin.empleados.index -->
          <a href="{{ route('admin.empleados.index') }}" class="btn-action">← Volver a gestión</a>
        </div>
        <div class="search-filters">
          <div class="search-input">
            <input type="text" id="searchInput" placeholder="Buscar por nombre o correo..." aria-label="Buscar usuarios eliminados">
            <i class="fas fa-search" aria-hidden="true"></i>
          </div>
        </div>
      </div>

      <!-- Barra de búsqueda -->
      <table class="users-table" id="deletedTable">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Tipo</th>
            <th>Fecha Eliminación</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($empleados as $empleado)
          <tr>
            <td>{{ $empleado->primerNombre }} {{ $empleado->apellidoPaterno }}</td>
            <td>{{ $empleado->emailCorporativo }}</td>
            <td>{{ ucfirst($empleado->rol) }}</td>
            <td>Empleado</td>
            <td>{{ $empleado->fechaEliminacion ?? 'Desconocida' }}</td>
            <td class="actions">
              <!-- ✅ CORREGIDO: admin.empleados.restore -->
              <form action="{{ route('admin.empleados.restore', $empleado->idEmpleado) }}" method="POST">
                @csrf
                @method('PUT')
                <button type="submit" class="btn-edit">Activar</button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="6" class="text-center p-4">No hay empleados eliminados</td></tr>
          @endforelse

          @forelse ($usuarios as $usuario)
          <tr>
            <td>{{ $usuario->primerNombre }}</td>
            <td>{{ $usuario->email }}</td>
            <td>Usuario</td>
            <td>Usuario</td>
            <td>{{ $usuario->fechaEliminacion ?? 'Desconocida' }}</td>
            <td class="actions">
              <!-- ✅ CORREGIDO: admin.usuarios.restore -->
              <form action="{{ route('admin.usuarios.restore', ['id' => $usuario->idUsuario]) }}" method="POST">
                @csrf
                @method('PUT')
                <button type="submit" class="btn-edit">Activar</button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="6" class="text-center p-4">No hay usuarios eliminados</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </section>

  <script>
    // 🔍 Filtro de búsqueda en tiempo real (safe)
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
      searchInput.addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        document.querySelectorAll('#deletedTable tbody tr').forEach(row => {
          const text = row.textContent.toLowerCase();
          row.classList.toggle('hidden', !text.includes(filter));
        });
      });
    }
  </script>
</body>
</html>
