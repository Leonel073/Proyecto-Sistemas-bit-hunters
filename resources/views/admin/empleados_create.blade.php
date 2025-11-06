<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrar Empleado - Nexora Bolivia</title>

  @vite(['resources/css/app.css', 
          'resources/css/empleados-create.css'])
</head>
<body class="bg-gray-100">
  <div class="form-container">
    <h1 class="form-title">Registrar Nuevo Empleado</h1>

    @if ($errors->any())
      <div class="alert-errors">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('empleados.store') }}" method="POST" class="form-card">
      @csrf

      <div class="form-group">
        <label>Primer Nombre</label>
        <input type="text" name="primerNombre" required>
      </div>

      <div class="form-group">
        <label>Segundo Nombre</label>
        <input type="text" name="segundoNombre">
      </div>

      <div class="form-group">
        <label>Apellido Paterno</label>
        <input type="text" name="apellidoPaterno" required>
      </div>

      <div class="form-group">
        <label>Apellido Materno</label>
        <input type="text" name="apellidoMaterno">
      </div>

      <div class="form-group">
        <label>CI</label>
        <input type="text" name="ci" required>
      </div>

      <div class="form-group">
        <label>Celular</label>
        <input type="text" name="numeroCelular" required>
      </div>

      <div class="form-group">
        <label>Correo Corporativo</label>
        <input type="email" name="emailCorporativo" required>
      </div>

      <div class="form-group">
        <label>Contraseña</label>
        <input type="password" name="password" required>
      </div>

      <select name="rol" required>
            @if(!$gerenteExiste)
                <option value="Gerente">Gerente de Soporte</option>
            @endif
            <option value="SupervisorOperador">Supervisor de Operadores</option>
            <option value="SupervisorTecnico">Supervisor de Técnicos</option>
            <option value="Operador">Operador</option>
            <option value="Tecnico">Técnico</option>
        </select>


      <div class="form-group">
        <label>Fecha de Ingreso</label>
        <input type="date" name="fechaIngreso" required>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn-primary">Registrar</button>
        <a href="{{ route('usuarios') }}" class="btn-link">Volver</a>
      </div>
    </form>
  </div>
</body>
</html>