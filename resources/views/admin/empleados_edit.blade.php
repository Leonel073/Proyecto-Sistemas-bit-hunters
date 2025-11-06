<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Empleado - Nexora Bolivia</title>

    {{-- Usamos los mismos estilos que el formulario de crear --}}
    @vite(['resources/css/app.css', 'resources/css/empleados-create.css'])
</head>
<body class="bg-gray-100">
    <div class="form-container">
        <h1 class="form-title">Editar Empleado: {{ $empleado->primerNombre }}</h1>

        @if ($errors->any())
            <div class="alert-errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ¡IMPORTANTE! La acción apunta a 'admin.empleados.update' --}}
        <form action="{{ route('admin.empleados.update', $empleado->idEmpleado) }}" method="POST" class="form-card">
            @csrf
            @method('PUT') {{-- <-- Le decimos a Laravel que esto es una ACTUALIZACIÓN (PUT) --}}

            <div class="form-group">
                <label>Primer Nombre</label>
                {{-- Usamos value="" para rellenar los datos del empleado --}}
                <input type="text" name="primerNombre" value="{{ old('primerNombre', $empleado->primerNombre) }}" required>
            </div>

            <div class="form-group">
                <label>Segundo Nombre</label>
                <input type="text" name="segundoNombre" value="{{ old('segundoNombre', $empleado->segundoNombre) }}">
            </div>

            <div class="form-group">
                <label>Apellido Paterno</label>
                <input type="text" name="apellidoPaterno" value="{{ old('apellidoPaterno', $empleado->apellidoPaterno) }}" required>
            </div>

            <div class="form-group">
                <label>Apellido Materno</label>
                <input type="text" name="apellidoMaterno" value="{{ old('apellidoMaterno', $empleado->apellidoMaterno) }}">
            </div>

            <div class="form-group">
                <label>CI</label>
                <input type="text" name="ci" value="{{ old('ci', $empleado->ci) }}" required>
            </div>

            <div class="form-group">
                <label>Celular</label>
                <input type="text" name="numeroCelular" value="{{ old('numeroCelular', $empleado->numeroCelular) }}" required>
            </div>

            <div class="form-group">
                <label>Correo Corporativo</label>
                {{-- 'disabled' para evitar cambiar el email, que se usa para login --}}
                <input type="email" name="emailCorporativo" value="{{ $empleado->emailCorporativo }}" disabled>
                <small style="color:#6b7280; font-size: 0.8rem; margin-top: 4px; display:block;">El correo corporativo no se puede cambiar.</small>
            </div>

            <div class="form-group">
                <label>Nueva Contraseña (Dejar en blanco para no cambiar)</label>
                {{-- El campo de contraseña NUNCA debe rellenarse --}}
                <input type="password" name="password" placeholder="••••••••">
            </div>

            <select name="rol" required>
                {{-- Marcamos como 'selected' el rol que el empleado ya tiene --}}
                <option value="Gerente" {{ $empleado->rol == 'Gerente' ? 'selected' : '' }}>Gerente de Soporte</option>
                <option value="SupervisorOperador" {{ $empleado->rol == 'SupervisorOperador' ? 'selected' : '' }}>Supervisor de Operadores</option>
                <option value="SupervisorTecnico" {{ $empleado->rol == 'SupervisorTecnico' ? 'selected' : '' }}>Supervisor de Técnicos</option>
                <option value="Operador" {{ $empleado->rol == 'Operador' ? 'selected' : '' }}>Operador</option>
                <option value="Tecnico" {{ $empleado->rol == 'Tecnico' ? 'selected' : '' }}>Técnico</option>
            </select>


            <div class="form-group">
                <label>Fecha de Ingreso</label>
                <input type="date" name="fechaIngreso" value="{{ old('fechaIngreso', $empleado->fechaIngreso) }}" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">Actualizar Empleado</button>
                {{-- ¡IMPORTANTE! El botón 'Volver' apunta a 'admin.empleados.index' --}}
                <a href="{{ route('admin.empleados.index') }}" class="btn-link">Volver</a>
            </div>
        </form>
    </div>
</body>
</html>