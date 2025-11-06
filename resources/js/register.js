// resources/js/register.js

function togglePassword(id) {
  const input = document.getElementById(id);
  if (!input) return;

  const icon = document.getElementById(`eye-${id}`);
  const type = input.type === 'password' ? 'text' : 'password';
  input.type = type;

  if (icon) {
    icon.innerHTML = type === 'text'
      ? `<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />`
      : `<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
  }
}

document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('registerForm');
  const alertBox = document.getElementById('alert');

  if (!form || !alertBox) return;

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    alertBox.classList.add('hidden');

    // Usar querySelector con name (más confiable)
    const getValue = (name) => {
      const el = form.querySelector(`[name="${name}"]`);
      return el ? el.value.trim() : '';
    };

    const data = {
      primerNombre: getValue('primerNombre'),
      segundoNombre: getValue('segundoNombre'),
      apellidoPaterno: getValue('apellidoPaterno'),
      apellidoMaterno: getValue('apellidoMaterno'),
      ci: getValue('ci'),
      numeroCelular: getValue('numeroCelular'),
      email: getValue('email'),
      direccionTexto: getValue('direccionTexto'),
      password: getValue('password'),
      password_confirmation: getValue('password_confirmation'),
    };

    // Validaciones rápidas
    if (!data.primerNombre || !data.apellidoPaterno || !data.ci || !data.numeroCelular || !data.password) {
      showError('Por favor completa todos los campos obligatorios');
      return;
    }

    if (data.password.length < 8) {
      showError('La contraseña debe tener al menos 8 caracteres');
      return;
    }

    if (data.password !== data.password_confirmation) {
      showError('Las contraseñas no coinciden');
      return;
    }

    try {
      const response = await fetch(form.action, {
        method: 'POST',
        body: new FormData(form),
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
          'X-Requested-With': 'XMLHttpRequest',
        },
      });

      if (response.ok) {
        showSuccess('¡Registro exitoso! Redirigiendo...');
        form.reset();
        setTimeout(() => {
          window.location.href = '/';
        }, 1500);
      } else {
        const text = await response.text();
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = text;
        const errorList = tempDiv.querySelector('.alert-error ul');
        if (errorList) {
          const errors = Array.from(errorList.children).map(li => li.textContent).join('<br>');
          showError(errors);
        } else {
          showError('Error al registrar. Intenta nuevamente.');
        }
      }
    } catch (error) {
      showError('Error de conexión. Revisa tu internet.');
    }
  });

  function showError(msg) {
    alertBox.innerHTML = msg;
    alertBox.className = 'alert alert-error';
    alertBox.classList.remove('hidden');
  }

  function showSuccess(msg) {
    alertBox.textContent = msg;
    alertBox.className = 'alert alert-success';
    alertBox.classList.remove('hidden');
    setTimeout(() => alertBox.classList.add('hidden'), 4000);
  }
});