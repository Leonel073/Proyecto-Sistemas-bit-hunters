<nav class="admin-nav">
  <div class="admin-nav-container">
    <!-- Logo -->
    <div class="admin-logo" onclick="window.location.href='{{ route('home') }}'" role="button" tabindex="0">
      <svg class="admin-logo-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M2 17L12 22L22 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M2 12L12 17L22 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
      <div class="admin-logo-text">
        <div class="admin-logo-name">Nexora Bolivia</div>
        <div class="admin-logo-sub">Panel Admin</div>
      </div>
    </div>

    <!-- Toggle Button -->
    <button class="admin-nav-toggle" id="adminNavToggle" aria-label="Abrir menú" aria-expanded="false">
      <span class="hamburger-line"></span>
      <span class="hamburger-line"></span>
      <span class="hamburger-line"></span>
    </button>

    <!-- Navigation Links -->
    <div class="admin-nav-links" id="adminNavLinks">
      <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M3 9L12 2L21 9V20C21 20.5304 20.7893 21.0391 20.4142 21.4142C20.0391 21.7893 19.5304 22 19 22H5C4.46957 22 3.96086 21.7893 3.58579 21.4142C3.21071 21.0391 3 20.5304 3 20V9Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M9 22V12H15V22" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <span>Inicio</span>
      </a>
      
      <a href="{{ route('admin.empleados.index') }}" class="nav-link {{ request()->routeIs('admin.empleados.*') ? 'active' : '' }}">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M17 21V19C17 17.9391 16.5786 16.9217 15.8284 16.1716C15.0783 15.4214 14.0609 15 13 15H5C3.93913 15 2.92172 15.4214 2.17157 16.1716C1.42143 16.9217 1 17.9391 1 19V21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M9 11C11.2091 11 13 9.20914 13 7C13 4.79086 11.2091 3 9 3C6.79086 3 5 4.79086 5 7C5 9.20914 6.79086 11 9 11Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M23 21V19C22.9993 18.1137 22.7044 17.2528 22.1614 16.5523C21.6184 15.8519 20.8581 15.3516 20 15.13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M16 3.13C16.8604 3.35031 17.623 3.85071 18.1676 4.55232C18.7122 5.25392 19.0078 6.11683 19.0078 7.005C19.0078 7.89318 18.7122 8.75608 18.1676 9.45769C17.623 10.1593 16.8604 10.6597 16 10.88" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <span>Gestión de Usuarios</span>
      </a>
      
      <a href="{{ route('recursos') }}" class="nav-link {{ request()->routeIs('recursos') ? 'active' : '' }}">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M2 3H8C9.06087 3 10.0783 3.42143 10.8284 4.17157C11.5786 4.92172 12 5.93913 12 7V21C12 20.2044 11.6839 19.4413 11.1213 18.8787C10.5587 18.3161 9.79565 18 9 18H2V3Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M22 3H16C14.9391 3 13.9217 3.42143 13.1716 4.17157C12.4214 4.92172 12 5.93913 12 7V21C12 20.2044 12.3161 19.4413 12.8787 18.8787C13.4413 18.3161 14.2044 18 15 18H22V3Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <span>Recursos</span>
      </a>
    </div>

    <!-- User Actions -->
    <div class="admin-nav-actions">
      @auth('empleado')
        <div class="admin-user">
          <button id="adminUserToggle" class="admin-user-btn" aria-haspopup="true" aria-expanded="false">
            <div class="admin-user-avatar">
              {{ strtoupper(substr(Auth::guard('empleado')->user()->primerNombre ?? 'A', 0, 1)) }}{{ strtoupper(substr(Auth::guard('empleado')->user()->apellidoPaterno ?? 'D', 0, 1)) }}
            </div>
            <div class="admin-user-info">
              <span class="admin-user-name">{{ Auth::guard('empleado')->user()->primerNombre ?? 'Admin' }} {{ Auth::guard('empleado')->user()->apellidoPaterno ?? '' }}</span>
              <span class="admin-user-role">Administrador</span>
            </div>
            <svg class="admin-user-chevron" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M6 9L12 15L18 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>
          
          <div class="admin-user-menu hidden" id="adminUserMenu">
            <div class="admin-user-menu-header">
              <div class="admin-user-menu-avatar">
                {{ strtoupper(substr(Auth::guard('empleado')->user()->primerNombre ?? 'A', 0, 1)) }}{{ strtoupper(substr(Auth::guard('empleado')->user()->apellidoPaterno ?? 'D', 0, 1)) }}
              </div>
              <div class="admin-user-menu-info">
                <div class="admin-user-menu-name">{{ Auth::guard('empleado')->user()->primerNombre ?? 'Admin' }} {{ Auth::guard('empleado')->user()->apellidoPaterno ?? '' }}</div>
                <div class="admin-user-menu-email">{{ Auth::guard('empleado')->user()->emailCorporativo ?? 'admin@nexora.bo' }}</div>
              </div>
            </div>
            
            <div class="admin-user-menu-divider"></div>
            
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="admin-user-menu-item admin-logout">
                <svg class="menu-item-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M9 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M16 17L21 12L16 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M21 12H9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>Cerrar Sesión</span>
              </button>
            </form>
          </div>
        </div>
      @else
        <a href="{{ route('login') }}" class="nav-link login-btn">
          <svg class="nav-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M15 3H19C19.5304 3 20.0391 3.21071 20.4142 3.58579C20.7893 3.96086 21 4.46957 21 5V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M10 17L15 12L10 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M15 12H3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          <span>Iniciar sesión</span>
        </a>
      @endauth
    </div>
  </div>

  <script>
    (function(){
      const toggle = document.getElementById('adminNavToggle');
      const links = document.getElementById('adminNavLinks');
      const userBtn = document.getElementById('adminUserToggle');
      const userMenu = document.getElementById('adminUserMenu');
      
      // Toggle mobile menu
      if(toggle && links) {
        toggle.addEventListener('click', () => {
          const isOpen = links.classList.toggle('open');
          toggle.classList.toggle('active');
          toggle.setAttribute('aria-expanded', isOpen);
        });
      }
      
      // Toggle user menu
      if(userBtn && userMenu) {
        userBtn.addEventListener('click', (e) => {
          e.stopPropagation();
          const isOpen = userMenu.classList.toggle('hidden');
          userBtn.setAttribute('aria-expanded', !isOpen);
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
          if (!userMenu.contains(e.target) && !userBtn.contains(e.target)) {
            userMenu.classList.add('hidden');
            userBtn.setAttribute('aria-expanded', 'false');
          }
        });
      }
      
      // Close mobile menu when clicking a link
      if(links) {
        const navLinks = links.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
          link.addEventListener('click', () => {
            links.classList.remove('open');
            if(toggle) toggle.classList.remove('active');
          });
        });
      }
    })();
  </script>
</nav>
