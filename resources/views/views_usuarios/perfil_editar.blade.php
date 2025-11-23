<!DOCTYPE html> 
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mi Perfil - Nexora Bolivia</title>

  @vite([
      'resources/css/app.css',
      'resources/css/register.css',
      'resources/css/btns.css',
      'resources/js/register.js'
  ])

  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  
  <style>
    body {
        background-color: #f0f4f8;
        font-family: 'Inter', sans-serif;
    }
    .error-message { color: #DC2626; font-size: 0.75rem; margin-top: 0.25rem; font-weight: 500; }
    
    /* Estilos para la tarjeta limpia */
    .profile-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        padding: 2.5rem;
        max-width: 700px;
        width: 100%;
        margin: 2rem auto;
    }
    
    /* Avatar */
    .avatar-container {
        text-align: center;
        margin-bottom: 1.5rem;
    }
    .avatar-circle {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4f46e5 0%, #8b5cf6 100%);
        margin: 0 auto 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        box-shadow: 0 4px 10px rgba(99, 102, 241, 0.3);
    }
    
    .page-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }
    .page-subtitle {
        color: #6b7280;
        font-size: 0.95rem;
        margin-bottom: 2rem;
    }

    /* Inputs */
    .form-label {
        display: block;
        font-size: 0.9rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.3rem;
    }
    .form-input {
        width: 100%;
        padding: 0.65rem 0.8rem;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 0.95rem;
        transition: border-color 0.2s;
    }
    .form-input:focus {
        border-color: #6366f1;
        outline: none;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
    .form-input:disabled {
        background-color: #f3f4f6;
        cursor: not-allowed;
    }

    /* Botones */
    .btn-save {
        width: 100%;
        background: linear-gradient(to right, #3b82f6, #8b5cf6);
        color: white;
        font-weight: 600;
        padding: 0.85rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-size: 1rem;
        transition: opacity 0.2s;
        margin-top: 1.5rem;
    }
    .btn-save:hover {
        opacity: 0.9;
    }
    .btn-cancel {
        display: block;
        text-align: center;
        color: #4b5563;
        text-decoration: none;
        margin-top: 1rem;
        font-size: 0.9rem;
    }
    .btn-cancel:hover {
        color: #1f2937;
        text-decoration: underline;
    }
    
    .section-title {
        font-size: 1rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 1rem;
        margin-top: 0;
    }
  </style>
</head>
<body>

  <div class="profile-card">
    <div class="avatar-container">
        <div class="avatar-circle">
            <i class="fas fa-user"></i>
        </div>
        <h1 class="page-title">Editar Mi Perfil</h1>
        <p class="page-subtitle">Actualiza tu información personal o contraseña</p>
    </div>

    @if (session('success'))
      <div class="alert alert-success bg-green-100 text-green-800 p-3 rounded mb-4 border border-green-200 text-center text-sm">
        {{ session('success') }}
      </div>
    @endif

    @if ($errors->any())
      <div class="alert alert-error bg-red-50 text-red-600 p-3 rounded mb-4 border border-red-200 text-sm">
        <ul class="list-disc pl-5">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form id="registerForm" action="{{ route('perfil.update') }}" method="POST">
        @csrf
        @method('PUT')

        <h3 class="section-title">Datos Personales</h3>
        
        <!-- Fila 1 -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="form-label">Primer Nombre *</label>
                <input type="text" name="primerNombre" value="{{ old('primerNombre', $usuario->primerNombre) }}" required pattern="^[\pL\s\-]+$" class="form-input">
                <span id="primerNombre-error" class="error-message empty:hidden"></span>
            </div>
            <div>
                <label class="form-label">Apellido Paterno *</label>
                <input type="text" name="apellidoPaterno" value="{{ old('apellidoPaterno', $usuario->apellidoPaterno) }}" required pattern="^[\pL\s\-]+$" class="form-input">
                <span id="apellidoPaterno-error" class="error-message empty:hidden"></span>
            </div>
        </div>

        <!-- Fila 2 -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="form-label">Segundo Nombre</label>
                <input type="text" name="segundoNombre" value="{{ old('segundoNombre', $usuario->segundoNombre) }}" pattern="^[\pL\s\-]+$" class="form-input">
                <span id="segundoNombre-error" class="error-message empty:hidden"></span>
            </div>
            <div>
                <label class="form-label">Apellido Materno</label>
                <input type="text" name="apellidoMaterno" value="{{ old('apellidoMaterno', $usuario->apellidoMaterno) }}" pattern="^[\pL\s\-]+$" class="form-input">
                <span id="apellidoMaterno-error" class="error-message empty:hidden"></span>
            </div>
        </div>

        <!-- Fila 3 -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="form-label">Cédula (CI)</label>
                <input type="text" value="{{ $usuario->ci }}" class="form-input" disabled title="El CI no se puede cambiar">
                <p class="text-xs text-gray-500 mt-1">El CI no se puede editar.</p>
            </div>
            <div>
                <label class="form-label">Celular *</label>
                <input type="tel" name="numeroCelular" value="{{ old('numeroCelular', $usuario->numeroCelular) }}" required pattern="\d{8,}" class="form-input">
                <span id="numeroCelular-error" class="error-message empty:hidden"></span>
            </div>
        </div>

        <!-- Email -->
        <div class="mb-6">
            <label class="form-label">Correo Electrónico *</label>
            <input type="email" name="email" value="{{ old('email', $usuario->email) }}" required class="form-input">
            <span id="email-error" class="error-message empty:hidden"></span>
        </div>

        <h3 class="section-title">Cambiar Contraseña (Opcional)</h3>
        <p class="text-sm text-gray-500 mb-3">Deja estos campos vacíos si no quieres cambiar tu contraseña.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-2">
            <div>
                <label class="form-label">Nueva Contraseña</label>
                <input type="password" id="password" name="password" placeholder="Nueva contraseña" minlength="8" class="form-input">
                <span id="password-error" class="error-message empty:hidden"></span>
            </div>
            <div>
                <label class="form-label">Confirmar Nueva Contraseña</label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Repetir contraseña" minlength="8" class="form-input">
                <span id="password_confirmation-error" class="error-message empty:hidden"></span>
            </div>
        </div>
        
        <p id="password-hint" class="text-xs text-gray-500 mb-3">
            Mínimo 8 caracteres, con mayúsculas, minúsculas, números y símbolos.
        </p>

        <!-- Checkbox -->
        <div class="flex items-center mb-6">
            <input id="togglePasswordCheckbox" type="checkbox" class="h-4 w-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
            <label for="togglePasswordCheckbox" class="ml-2 block text-sm text-gray-900 cursor-pointer">
              Mostrar contraseñas
            </label>
        </div>

        <!-- Acciones -->
        <a href="{{ route('home') }}" class="btn-cancel">Cancelar</a>
        <button type="submit" class="btn-save">Guardar Cambios</button>

    </form>
  </div>

</body>
</html>