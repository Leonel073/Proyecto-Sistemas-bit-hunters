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
<nav class="bg-gradient-to-r from-indigo-600 to-purple-600 shadow-lg mb-8 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            {{-- Logo o Nombre de la App --}}
            <div class="flex-shrink-0">
                <a href="{{ route('home') }}" class="text-3xl font-black text-white hover:text-gray-100 transition duration-200 flex items-center gap-2">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Nexora
                </a>
            </div>

            {{-- Botones de Navegación --}}
            <div class="flex items-center space-x-6">
                @auth('empleado')
                    {{-- Botón de Logout Mejorado --}}
                    <div class="flex items-center space-x-3">
                        <div class="hidden sm:flex flex-col items-end">
                            <p class="text-sm font-semibold text-white">{{ Auth::user()->primerNombre }} {{ Auth::user()->apellidoPaterno }}</p>
                            <p class="text-xs text-indigo-100">{{ Auth::user()->tipo ?? 'Usuario' }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="group relative px-4 py-2 rounded-lg font-bold text-white bg-white bg-opacity-20 hover:bg-opacity-30 border-2 border-white border-opacity-30 hover:border-opacity-50 transition duration-300 flex items-center gap-2 shadow-lg hover:shadow-xl transform hover:scale-105">
                                <svg class="w-5 h-5 transition-transform group-hover:rotate-180 duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                <span class="hidden sm:inline">Salir</span>
                            </button>
                        </form>
                    </div>
                @else
                    {{-- Botón de Login si no hay sesión --}}
                    <a href="{{ route('login') }}" class="px-6 py-2 text-sm font-bold rounded-lg text-indigo-600 bg-white hover:bg-gray-50 transition duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
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
