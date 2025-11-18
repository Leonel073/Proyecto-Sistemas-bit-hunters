document.addEventListener('DOMContentLoaded', () => {

  // --- 1. LÓGICA PARA VER/OCULTAR CONTRASEÑA (CON CHECKBOX) ---
  const toggleCheckbox = document.getElementById('togglePasswordCheckbox');
  const passwordInput = document.getElementById('password');
  const passwordConfirmInput = document.getElementById('password_confirmation');

  if (toggleCheckbox && passwordInput && passwordConfirmInput) {
    toggleCheckbox.addEventListener('change', () => {
      if (toggleCheckbox.checked) {
        passwordInput.type = 'text';
        passwordConfirmInput.type = 'text';
      } else {
        passwordInput.type = 'password';
        passwordConfirmInput.type = 'password';
      }
    });
  }

  // --- 2. LÓGICA DE VALIDACIÓN EN TIEMPO REAL ---
  const fieldsToValidate = {
    /* 'primerNombre': {
      validate: (value) => /^[\pL\s\-]*$/.test(value),
      message: 'Solo debe contener letras y espacios.',
    },
    'apellidoPaterno': {
      validate: (value) => /^[\pL\s\-]*$/.test(value),
      message: 'Solo debe contener letras y espacios.',
    },
    'segundoNombre': {
      validate: (value) => /^[\pL\s\-]*$/.test(value),
      message: 'Solo debe contener letras y espacios.',
    },
    'apellidoMaterno': {
      validate: (value) => /^[\pL\s\-]*$/.test(value),
      message: 'Solo debe contener letras y espacios.',
    }, */
    'ci': {
      validate: (value) => value === '' || /^\d{7,10}$/.test(value),
      message: 'Debe tener entre 7 y 10 números.',
    },
    'numeroCelular': {
      validate: (value) => value === '' || /^\d{8,}$/.test(value),
      message: 'Debe ser un número de celular válido.',
    },
    'emailCorporativo': { // CAMBIO
      validate: (value) => value === '' || /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value),
      message: 'Debe ser un correo electrónico válido.',
    },
    'fechaIngreso': { // NUEVO
        validate: (value) => value !== '',
        message: 'La fecha es obligatoria.'
    },
    'password': {
      validate: (value) => value === '' || /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/.test(value),
      message: 'Debe cumplir los requisitos (mayúscula, minúscula, número, símbolo).',
    },
    'password_confirmation': {
      validate: (value) => {
        const password = document.getElementById('password').value;
        return value === '' || value === password;
      },
      message: 'Las contraseñas no coinciden.',
    }
  };

  // El resto del script es idéntico al de register.js
  Object.keys(fieldsToValidate).forEach(id => {
    const input = document.getElementById(id);
    const errorSpan = document.getElementById(`${id}-error`);
    const config = fieldsToValidate[id];

    if (input && errorSpan) {
      input.addEventListener('input', () => {
        const value = input.value;
        
        if (config.validate(value)) {
          errorSpan.textContent = '';
        } else {
          // Solo muestra el error si el campo no está vacío (excepto fecha)
          if (value !== '' || id === 'fechaIngreso') {
            errorSpan.textContent = config.message;
          } else {
            errorSpan.textContent = '';
          }
        }

        if (id === 'password') {
          const confirmInput = document.getElementById('password_confirmation');
          const confirmError = document.getElementById('password_confirmation-error');
          if (confirmInput.value && confirmInput.value !== value) {
            confirmError.textContent = fieldsToValidate['password_confirmation'].message;
          } else {
            confirmError.textContent = '';
          }
        }
      });
    }
  });

});