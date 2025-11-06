// resources/js/nav.js

window.navigateTo = function(section, event) {
  // Prevenir comportamiento por defecto
  if (event) {
    event.preventDefault();
    event.stopPropagation();
  }

  // Quitar clase active de todos
  document.querySelectorAll('#navLinks button, #mobileMenu button').forEach(btn => {
    btn.classList.remove('active');
  });

  // Agregar clase active al botón clicado
  if (event && event.target) {
    event.target.classList.add('active');
  }

  // Cerrar menú móvil
  const mobileMenu = document.getElementById('mobileMenu');
  const menuIcon = document.getElementById('menuIcon');
  if (mobileMenu) mobileMenu.classList.remove('show');
  if (menuIcon) {
    menuIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />';
  }

  // RUTAS REALES
  const routes = {
    'inicio': '/',
    'formulario': '/formulario',
    'seguimiento': '/seguimiento',
    'recursos': '/recursos',
    'login': '/login',
    'register': '/sign_up'
  };

  if (routes[section]) {
    window.location.href = routes[section];
  }
};

// Menú móvil
document.addEventListener('DOMContentLoaded', () => {
  const menuToggle = document.getElementById('menuToggle');
  const mobileMenu = document.getElementById('mobileMenu');
  const menuIcon = document.getElementById('menuIcon');

  if (!menuToggle || !mobileMenu || !menuIcon) return;

  menuToggle.addEventListener('click', (e) => {
    e.stopPropagation();
    mobileMenu.classList.toggle('show');
    menuIcon.innerHTML = mobileMenu.classList.contains('show')
      ? '<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />'
      : '<path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />';
  });

  // Cerrar al hacer clic fuera
  document.addEventListener('click', (e) => {
    if (!menuToggle.contains(e.target) && !mobileMenu.contains(e.target)) {
      mobileMenu.classList.remove('show');
      menuIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />';
    }
  });

  // Soporte para clics en botones del menú móvil
  document.querySelectorAll('#mobileMenu button').forEach(btn => {
    btn.addEventListener('click', (e) => {
      const section = e.target.getAttribute('onclick')?.match(/'(.+?)'/)?.[1];
      if (section) {
        navigateTo(section, e);
      }
    });
  });
});