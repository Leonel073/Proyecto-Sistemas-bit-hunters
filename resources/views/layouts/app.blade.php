<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>@yield('title', 'Sistema de Reclamos')</title>

<!-- Tailwind CSS (Asegúrate de que estás compilando tus assets o usando un CDN temporal si no usas Mix/Vite) -->
<!-- En un entorno de producción, deberías usar tu propio archivo CSS compilado. -->
<!-- Si usas Laravel 10+, probablemente uses Vite para compilar Tailwind: -->
{{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

<!-- Para propósitos de este entorno, asumiremos que Tailwind está disponible o se puede usar un CDN para prueba: -->
<script src="https://cdn.tailwindcss.com"></script>
<style>
    /* Estilos generales para la fuente Inter */
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f7f9fc;
    }
    /* Aseguramos que los bordes sean redondeados en general */
    .rounded-xl { border-radius: 0.75rem; }
    .rounded-lg { border-radius: 0.5rem; }
</style>


</head>
<body class="min-h-screen antialiased">

{{-- BARRA DE NAVEGACIÓN --}}
<nav class="bg-white shadow-md mb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            {{-- Logo o Nombre de la App --}}
            <div class="flex-shrink-0">
                <a href="{{ route('home') }}" class="text-2xl font-bold text-indigo-600">Nexora</a>
            </div>

            {{-- Botones de Navegación --}}
            <div class="flex items-center space-x-4">
                @auth('empleado')
                    {{-- Botón de Logout --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-600 hover:text-red-500 font-medium transition duration-150">
                            Salir ({{ Auth::user()->primerNombre }})
                        </button>
                    </form>
                @else
                    {{-- Botón de Login si no hay sesión --}}
                    <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition duration-200">
                        Iniciar Sesión
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

{{-- CONTENIDO PRINCIPAL --}}
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
    @yield('content')
</main>

{{-- Opcional: Footer --}}
<footer class="mt-auto py-4 text-center text-sm text-gray-500 border-t">
    &copy; {{ date('Y') }} Nexora Sistemas. Todos los derechos reservados.
</footer>
