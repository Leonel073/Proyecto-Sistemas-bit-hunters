// vite.config.js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // GLOBAL
                'resources/css/app.css',
                'resources/js/app.js',

                // ESTILOS POR PÁGINA
                'resources/css/hero.css',
                'resources/css/nav.css',
                'resources/css/footer.css',
                'resources/css/btns.css',
                'resources/css/stats.css',

                'resources/css/formulario.css',
                'resources/css/recursos.css',
                'resources/css/login.css',
                'resources/css/register.css',

                // ESTILOS PARA GESTIÓN DE USUARIOS
                'resources/css/users-management.css',
                
                // ¡¡AQUÍ ESTÁN LOS QUE FALTABAN!!
                'resources/css/empleados-create.css',
                'resources/css/tecnico-dashboard.css',


                // JS POR PÁGINA
                'resources/js/nav.js',
                'resources/js/recursos.js',
                'resources/js/login.js',
                'resources/js/register.js',
            ],
            refresh: true,
        }),
    ],
});