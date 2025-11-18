<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Sistema de Gestión de Reclamos')</title>

    <!-- Tailwind CSS CDN para desarrollo rápido -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* Fuente Inter de Google Fonts */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f7f9;
        }
    </style>
</head>
<body>
    <!-- Barra de Navegación Simple (ejemplo) -->
    <header class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900">
                <a href="{{ route('home') }}" class="hover:text-indigo-600">Nexora | Gestión Técnica</a>
            </h1>
            <nav>
                <!-- Formulario de Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800 transition duration-150 ease-in-out">
                        Cerrar Sesión
                    </button>
                </form>
            </nav>
        </div>
    </header>

    <!-- Contenido Principal del Panel -->
    <main class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- El contenido específico de cada vista (ej: el dashboard) se inyectará aquí -->
            @yield('content')
        </div>
    </main>
    
    <!-- Script para mensajes flash -->
    <script>
        // Si tienes mensajes de sesión (ej: with('success', 'Mensaje'))
        const flashMessage = document.getElementById('flash-message');
        if (flashMessage) {
            setTimeout(() => {
                flashMessage.style.display = 'none';
            }, 5000);
        }
    </script>
</body>
</html>