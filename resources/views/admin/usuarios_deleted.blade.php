<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Usuarios Eliminados - Nexora Bolivia</title>
  @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-100">

  <div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Usuarios Eliminados</h1>

    <a href="{{ route('admin.empleados.index') }}" class="text-blue-600 underline mb-4 inline-block">← Volver a la gestión</a>

    <table class="min-w-full bg-white border rounded shadow">
      <thead>
        <tr>
          <th class="p-3 text-left">Nombre</th>
          <th class="p-3 text-left">Correo</th>
          <th class="p-3 text-left">Rol</th>
          <th class="p-3 text-left">Fecha Eliminación</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($empleados as $empleado)
        <tr class="border-t">
          <td class="p-3">{{ $empleado->primerNombre }} {{ $empleado->apellidoPaterno }}</td>
          <td class="p-3">{{ $empleado->emailCorporativo }}</td>
          <td class="p-3">{{ ucfirst(str_replace('_', ' ', $empleado->rol)) }}</td>
          <td class="p-3">{{ $empleado->fechaEliminacion ?? 'Desconocida' }}</td>
        </tr>
        @empty
        <tr><td colspan="4" class="text-center p-4">No hay usuarios eliminados</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</body>
</html>
