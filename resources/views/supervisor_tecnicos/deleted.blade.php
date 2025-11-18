<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Técnicos Eliminados - Nexora Bolivia</title>
  @vite([
      'resources/css/app.css', 'resources/css/hero.css', 'resources/css/nav.css', 
      'resources/css/footer.css', 'resources/css/users-management.css', 'resources/js/nav.js'
  ])
</head>
<body class="bg-gray-100">

  <nav>
    <div class="container">
      <div class="logo" onclick="window.location.href='{{ route('home') }}'">
        </div>
      <div class="nav-links" id="navLinks">
        <button onclick="window.location.href='{{ route('home') }}'">Inicio</button>
        <button onclick="window.location.href='{{ route('supervisor.tecnicos.index') }}'">Gestión de Técnicos</button>
      </div>
    </div>
  </nav>

  <section class="users-management">
    <div class="users-container">
      <h1 class="users-title">Técnicos Eliminados</h1>

      <div class="action-buttons">
        <a href="{{ route('supervisor.tecnicos.index') }}" class="btn-action">← Volver a gestión</a>
      </div>

      <table class="users-table" id="deletedTable">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Fecha Eliminación</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($tecnicos as $empleado)
          <tr>
            <td>{{ $empleado->primerNombre }} {{ $empleado->apellidoPaterno }}</td>
            <td>{{ $empleado->emailCorporativo }}</td>
            <td>{{ ucfirst($empleado->rol) }}</td>
            <td>{{ $empleado->fechaEliminacion ?? 'Desconocida' }}</td>
            <td class="actions">
              <form action="{{ route('supervisor.tecnicos.restore', $empleado->idEmpleado) }}" method="POST">
                @csrf
                @method('PUT')
                <button type="submit" class="btn-edit">Activar</button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="5" class="text-center p-4">No hay técnicos eliminados</td></tr>
          @endforelse
          </tbody>
      </table>
    </div>
  </section>
</body>
</html>