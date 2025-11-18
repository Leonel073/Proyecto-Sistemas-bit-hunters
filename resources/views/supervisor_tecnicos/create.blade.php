<!DOCTYPE html> 
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrar Técnico - Nexora Bolivia</title>

  @vite([
      'resources/css/app.css',
      'resources/css/register.css', // Reusamos el CSS de registro
      'resources/css/btns.css',
      'resources/js/empleado-create.js' // Reusamos el mismo JS
  ])
  
  <style>
    .error-message { color: #DC2626; font-size: 0.75rem; margin-top: 0.25rem; font-weight: 500; }
  </style>
</head>
<body class="bg-gray-50">

  <a href="{{ route('supervisor.tecnicos.index') }}" class="btn-top-left">
    ← Volver al Panel
  </a>

  <div class="register-container min-h-screen flex items-center justify-center px-4 py-8">
    <div class="card max-w-2xl w-full bg-white rounded-xl shadow-lg p-6 md:p-8">

      <div class="card-header text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mt-4">Registrar Nuevo Técnico</h2>
        <p class="text-gray-600">El rol se asignará automáticamente como "Técnico".</p>
      </div>

      @if ($errors->any())
        <div class="alert alert-error">
          <ul class="list-disc pl-5 text-sm">
            @foreach ($errors->all() as $error)
              <li class="error-message">{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form id="createForm" action="{{ route('supervisor.tecnicos.store') }}" method="POST" class="space-y-5" novalidate>
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="input-group">
            <label for="primerNombre" class="block text-sm font-medium text-gray-700">Primer Nombre *</label>
            <input type="text" id="primerNombre" name="primerNombre" value="{{ old('primerNombre') }}" required pattern="^[\pL\s\-]+$">
            <span id="primerNombre-error" class="error-message empty:hidden"></span>
          </div>
          <div class="input-group">
            <label for="apellidoPaterno" class="block text-sm font-medium text-gray-700">Apellido Paterno *</label>
            <input type="text" id="apellidoPaterno" name="apellidoPaterno" value="{{ old('apellidoPaterno') }}" required pattern="^[\pL\s\-]+$">
            <span id="apellidoPaterno-error" class="error-message empty:hidden"></span>
          </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="input-group">
            <label for="segundoNombre" class="block text-sm font-medium text-gray-700">Segundo Nombre *</label>
            <input type="text" id="segundoNombre" name="segundoNombre" value="{{ old('segundoNombre') }}" required pattern="^[\pL\s\-]+$">
            <span id="segundoNombre-error" class="error-message empty:hidden"></span>
          </div>
          <div class="input-group">
            <label for="apellidoMaterno" class="block text-sm font-medium text-gray-700">Apellido Materno *</label>
            <input type="text" id="apellidoMaterno" name="apellidoMaterno" value="{{ old('apellidoMaterno') }}" required pattern="^[\pL\s\-]+$">
            <span id="apellidoMaterno-error" class="error-message empty:hidden"></span>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="input-group">
            <label for="ci" class="block text-sm font-medium text-gray-700">Cédula (CI) *</label>
            <input type="tel" id="ci" name="ci" value="{{ old('ci') }}" required pattern="\d{7,10}" maxlength="10">
            <span id="ci-error" class="error-message empty:hidden"></span>
          </div>
          <div class="input-group">
            <label for="numeroCelular" class="block text-sm font-medium text-gray-700">Celular *</label>
            <input type="tel" id="numeroCelular" name="numeroCelular" value="{{ old('numeroCelular') }}" required pattern="\d{8,}">
            <span id="numeroCelular-error" class="error-message empty:hidden"></span>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="input-group">
            <label for="emailCorporativo" class="block text-sm font-medium text-gray-700">Correo Corporativo *</label>
            <input type="email" id="emailCorporativo" name="emailCorporativo" value="{{ old('emailCorporativo') }}" required>
            <span id="emailCorporativo-error" class="error-message empty:hidden"></span>
          </div>
          <div class="input-group">
            <label for="fechaIngreso" class="block text-sm font-medium text-gray-700">Fecha de Ingreso *</label>
            <input type="date" id="fechaIngreso" name="fechaIngreso" value="{{ old('fechaIngreso', now()->toDateString()) }}" required>
            <span id="fechaIngreso-error" class="error-message empty:hidden"></span>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="input-group">
            <label for="password" class="block text-sm font-medium text-gray-700">Contraseña *</label>
            <input type="password" id="password" name="password" required minlength="8" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$">
            <span id="password-error" class="error-message empty:hidden"></span>
          </div>
          <div class="input-group">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Contraseña *</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8">
            <span id="password_confirmation-error" class="error-message empty:hidden"></span>
          </div>
        </div>
        
        <div class="flex items-center">
            <input id="togglePasswordCheckbox" type="checkbox" class="h-4 w-4">
            <label for="togglePasswordCheckbox" class="ml-2 block text-sm text-gray-900">Mostrar contraseñas</label>
        </div>

        <button type="submit" class="submit-btn w-full">Registrar Técnico</button>
      </form>
    </div>
  </div>
</body>
</html>