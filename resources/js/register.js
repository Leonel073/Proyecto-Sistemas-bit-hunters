/**
 * Se ejecuta cuando el contenido del DOM está completamente cargado.
 * Añade los listeners para el toggle de contraseña y la validación en tiempo real.
 */
document.addEventListener('DOMContentLoaded', () => {

  // --- 1. LÓGICA PARA VER/OCULTAR CONTRASEÑA (CON CHECKBOX) ---
  
  const toggleCheckbox = document.getElementById('togglePasswordCheckbox');
  const passwordInput = document.getElementById('password');
  const passwordConfirmInput = document.getElementById('password_confirmation');

  if (toggleCheckbox && passwordInput && passwordConfirmInput) {
    toggleCheckbox.addEventListener('change', () => {
      // Comprueba si el checkbox está marcado
      if (toggleCheckbox.checked) {
        // Si está marcado, cambia el tipo a 'text'
        passwordInput.type = 'text';
        passwordConfirmInput.type = 'text';
      } else {
        // Si no está marcado, vuelve a 'password'
        passwordInput.type = 'password';
        passwordConfirmInput.type = 'password';
      }
    });
  }

  // --- 2. LÓGICA DE VALIDACIÓN EN TIEMPO REAL ---

  const fieldsToValidate = {
    /* 'primerNombre': {
      // Devuelve true si está vacío (empty string) O si solo tiene letras/espacios.
      // Devuelve false solo si hay caracteres inválidos (ej: números, símbolos).
      validate: (value) => /^[\pL\s\-]*$/.test(value),
      message: 'Solo debe contener letras.',
    },
    'apellidoPaterno': {
      validate: (value) => /^[\pL\s\-]*$/.test(value),
      message: 'Solo debe contener letras.',
    },
    'segundoNombre': {
      validate: (value) => /^[\pL\s\-]*$/.test(value),
      message: 'Solo debe contener letras.',
    },
    'apellidoMaterno': {
      validate: (value) => /^[\pL\s\-]*$/.test(value),
      message: 'Solo debe contener letras.',
    }, */
    'ci': {
      // Valida si está vacío O si cumple el formato.
      validate: (value) => value === '' || /^\d{7,10}$/.test(value),
      message: 'Debe tener entre 7 y 10 números.',
    },
    'numeroCelular': {
      validate: (value) => value === '' || /^\d{8,}$/.test(value),
      message: 'Debe ser un número de celular válido (ej: 70123456).',
    },
    'email': {
      validate: (value) => value === '' || /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value),
      message: 'Debe ser un correo electrónico válido.',
    },
    'password': {
      validate: (value) => value === '' || /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/.test(value),
      message: 'Debe cumplir los requisitos (mayúscula, minúscula, número, símbolo).',
    },
    'password_confirmation': {
      validate: (value) => {
        const password = document.getElementById('password').value;
        // Solo valida si hay algo en el campo de confirmación
        return value === '' || value === password;
      },
      message: 'Las contraseñas no coinciden.',
    }
  };

  // Añade un listener de 'input' a cada campo que necesita validación
  Object.keys(fieldsToValidate).forEach(id => {
    const input = document.getElementById(id);
    const errorSpan = document.getElementById(`${id}-error`);
    const config = fieldsToValidate[id];

    if (input && errorSpan) {
      input.addEventListener('input', () => {
        const value = input.value;
        
        // Valida el campo
        if (config.validate(value)) {
          // Si es válido, borra el mensaje de error
          errorSpan.textContent = '';
          input.classList.remove('border-red-500'); 
        } else {
          // Si es inválido (y no está vacío), muestra el mensaje de error
          errorSpan.textContent = config.message;
          input.classList.add('border-red-500'); 
        }

        // Caso especial: si se está editando 'password', re-validar 'password_confirmation'
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