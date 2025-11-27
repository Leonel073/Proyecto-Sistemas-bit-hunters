<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Mapa de Reclamos - Nexora Bolivia</title>
    @vite(['resources/css/app.css','resources/css/operador.css'])
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <style>
        #map {
            height: calc(100vh - 200px);
            width: 100%;
            border-radius: 8px;
            border: 2px solid #dee2e6;
            margin-top: 20px;
        }
        .map-legend {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin-right: 10px;
            border: 2px solid #333;
        }
        .legend-resuelto {
            background-color: #28a745;
        }
        .legend-pendiente {
            background-color: #dc3545;
        }
        .map-stats {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        .stat-box {
            background: white;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            flex: 1;
        }
        .stat-box h3 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        .stat-box p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
<!-- NAVBAR -->
<header class="navbar">
    <div class="container">
        <div class="nav-left">
            <div class="logo-container">
                <div class="logo-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
                <div class="logo-text">
                    <div class="site-name">Nexora Bolivia</div>
                    <div class="site-role">Mapa de Reclamos</div>
                </div>
            </div>
        </div>

        <div class="nav-right">
            <a href="{{ route('supervisor.tecnicos.index') }}" class="btn-nav" style="background-color: #f7941d;">
                Gestión de Técnicos
            </a>
            <a href="{{ route('supervisor.tecnicos.dashboard') }}" class="btn-nav" style="background-color: #007bff;">
                Reasignar Reclamos
            </a>
            <a href="{{ route('supervisor.tecnicos.mapa') }}" class="btn-nav" style="background-color: #28a745;">
                Mapa de Reclamos
            </a>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button class="btn-nav">Cerrar Sesión</button>
            </form>
        </div>
    </div>
</header>

<div class="container">
    <header class="page-header">
        <h1>Mapa de Reclamos</h1>
        <p>Visualización geográfica de todos los reclamos registrados</p>
    </header>

    <!-- Estadísticas -->
    <div class="map-stats">
        <div class="stat-box">
            <h3>{{ $reclamosResueltos->count() }}</h3>
            <p>Reclamos Resueltos</p>
        </div>
        <div class="stat-box">
            <h3>{{ $reclamosPendientes->count() }}</h3>
            <p>Reclamos Pendientes</p>
        </div>
        <div class="stat-box">
            <h3>{{ $reclamosResueltos->count() + $reclamosPendientes->count() }}</h3>
            <p>Total de Reclamos</p>
        </div>
    </div>

    <!-- Leyenda -->
    <div class="map-legend">
        <h3 style="margin-top: 0;">Leyenda</h3>
        <div class="legend-item">
            <div class="legend-color legend-resuelto"></div>
            <span><strong>Verde:</strong> Reclamos Resueltos/Cerrados</span>
        </div>
        <div class="legend-item">
            <div class="legend-color legend-pendiente"></div>
            <span><strong>Rojo:</strong> Reclamos Pendientes (Requieren Atención)</span>
        </div>
    </div>

    <!-- Mapa -->
    <div id="map"></div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Datos de reclamos desde PHP
    const reclamosResueltos = @json($reclamosResueltos);
    const reclamosPendientes = @json($reclamosPendientes);

    // Inicializar mapa centrado en La Paz, Bolivia
    const map = L.map('map').setView([-16.5000, -68.1500], 12);

    // Añadir capa de OpenStreetMap
    L.tileLayer('//{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Función para crear icono personalizado
    function createCustomIcon(color) {
        return L.divIcon({
            className: 'custom-marker',
            html: `<div style="background-color: ${color}; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>`,
            iconSize: [20, 20],
            iconAnchor: [10, 10]
        });
    }

    // Agregar marcadores para reclamos resueltos (verde)
    reclamosResueltos.forEach(function(reclamo) {
        if (reclamo.latitudIncidente && reclamo.longitudIncidente) {
            const clienteNombre = reclamo.usuario 
                ? `${reclamo.usuario.primerNombre || ''} ${reclamo.usuario.apellidoPaterno || ''}`.trim() || 'N/A'
                : 'N/A';
            
            const popupContent = `
                <div style="min-width: 200px;">
                    <h4 style="margin: 0 0 10px 0; color: #28a745;">R-${reclamo.idReclamo} - ${reclamo.titulo}</h4>
                    <p style="margin: 5px 0;"><strong>Cliente:</strong> ${clienteNombre}</p>
                    <p style="margin: 5px 0;"><strong>Estado:</strong> ${reclamo.estado}</p>
                    <p style="margin: 5px 0;"><strong>Prioridad:</strong> ${reclamo.prioridad}</p>
                    <p style="margin: 5px 0;"><strong>Fecha:</strong> ${new Date(reclamo.fechaCreacion).toLocaleDateString('es-BO')}</p>
                    ${reclamo.fechaResolucion ? `<p style="margin: 5px 0;"><strong>Resuelto:</strong> ${new Date(reclamo.fechaResolucion).toLocaleDateString('es-BO')}</p>` : ''}
                </div>
            `;

            L.marker([parseFloat(reclamo.latitudIncidente), parseFloat(reclamo.longitudIncidente)], {
                icon: createCustomIcon('#28a745')
            })
            .addTo(map)
            .bindPopup(popupContent);
        }
    });

    // Agregar marcadores para reclamos pendientes (rojo)
    reclamosPendientes.forEach(function(reclamo) {
        if (reclamo.latitudIncidente && reclamo.longitudIncidente) {
            const clienteNombre = reclamo.usuario 
                ? `${reclamo.usuario.primerNombre || ''} ${reclamo.usuario.apellidoPaterno || ''}`.trim() || 'N/A'
                : 'N/A';
            
            const tecnicoNombre = reclamo.tecnico 
                ? `${reclamo.tecnico.primerNombre || ''} ${reclamo.tecnico.apellidoPaterno || ''}`.trim() || 'Sin asignar'
                : 'Sin asignar';
            
            const operadorNombre = reclamo.operador 
                ? `${reclamo.operador.primerNombre || ''} ${reclamo.operador.apellidoPaterno || ''}`.trim() || 'N/A'
                : 'N/A';

            const popupContent = `
                <div style="min-width: 200px;">
                    <h4 style="margin: 0 0 10px 0; color: #dc3545;">R-${reclamo.idReclamo} - ${reclamo.titulo}</h4>
                    <p style="margin: 5px 0;"><strong>Cliente:</strong> ${clienteNombre}</p>
                    <p style="margin: 5px 0;"><strong>Estado:</strong> ${reclamo.estado}</p>
                    <p style="margin: 5px 0;"><strong>Prioridad:</strong> ${reclamo.prioridad}</p>
                    <p style="margin: 5px 0;"><strong>Técnico:</strong> ${tecnicoNombre}</p>
                    <p style="margin: 5px 0;"><strong>Operador:</strong> ${operadorNombre}</p>
                    <p style="margin: 5px 0;"><strong>Fecha:</strong> ${new Date(reclamo.fechaCreacion).toLocaleDateString('es-BO')}</p>
                </div>
            `;

            L.marker([parseFloat(reclamo.latitudIncidente), parseFloat(reclamo.longitudIncidente)], {
                icon: createCustomIcon('#dc3545')
            })
            .addTo(map)
            .bindPopup(popupContent);
        }
    });

    // Asegurar que el mapa se redibuje correctamente
    setTimeout(function() {
        map.invalidateSize();
    }, 300);
</script>
</body>
</html>


