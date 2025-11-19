<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Denegado - Nexora Bolivia</title>
    @vite(['resources/css/app.css'])
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            background-color: #F3F4F6;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: sans-serif;
        }
        .error-card {
            background: white;
            padding: 3rem;
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 500px;
            width: 90%;
        }
        .icon-lock {
            font-size: 4rem;
            color: #DC2626; /* Rojo */
            margin-bottom: 1.5rem;
        }
        h1 {
            font-size: 2rem;
            color: #1F2937;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }
        p {
            color: #6B7280;
            margin-bottom: 2rem;
            line-height: 1.5;
        }
        .btn-back {
            background-color: #4F46E5;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.3s;
        }
        .btn-back:hover {
            background-color: #4338CA;
        }
    </style>
</head>
<body>

    <div class="error-card">
        <div class="icon-lock">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 mx-auto">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
            </svg>
        </div>
        <h1>Acceso Restringido</h1>
        <p>
            Lo sentimos, no tienes los permisos necesarios (Rol Incorrecto) para ver esta página. 
            <br><br>
            {{ $exception->getMessage() }}
        </p>
        
        <a href="{{ route('home') }}" class="btn-back">
            Volver al Inicio
        </a>
    </div>

</body>
</html>