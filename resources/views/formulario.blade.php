<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registrar Reclamo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Nuevo Reclamo</h3>
                </div>
                <div class="card-body">
                    
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('reclamo.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Título del Problema *</label>
                            <input type="text" name="titulo" class="form-control" required placeholder="Ej: Corte de Internet">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tipo de Incidente *</label>
                            <select name="idTipoIncidente" class="form-select" required>
                                <option value="">Seleccione...</option>
                                <option value="1">Falla de Conexión</option>
                                <option value="2">Lentitud</option>
                                <option value="3">Facturación</option>
                                <option value="4">Soporte Técnico</option>
                            </select>
                            <small class="text-muted">Asegúrese de que estos IDs (1,2,3...) existan en su base de datos.</small>
                        </div>

            <div class="form-group">
              <label class="form-label" for="tipoIncidente">Tipo de Incidente *</label>
              <select class="form-select" id="tipoIncidente" name="tipoIncidente" required>
                <option value="">Selecciona el tipo de incidente</option>
                <option value="Velocidad inferior a la contratada">Velocidad inferior a la contratada</option>
                <option value="Cortes frecuentes del servicio">Cortes frecuentes del servicio</option>
                <option value="Sin servicio - Caída total">Sin servicio - Caída total</option>
                <option value="Problemas de facturación">Problemas de facturación</option>
                <option value="Problemas de instalación">Problemas de instalación</option>
                <option value="Mala atención al cliente">Mala atención al cliente</option>
                <option value="Servicio intermitente">Servicio intermitente</option>
                <option value="Otro">Otro</option>
              </select>
            </div>

                        <div class="mb-3">
                            <label class="form-label">Descripción Detallada *</label>
                            <textarea name="descripcionDetallada" class="form-control" rows="4" required></textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Latitud</label>
                                <input type="text" name="latitudIncidente" class="form-control" value="-16.5000" required>
                            </div>
                            <div class="col">
                                <label class="form-label">Longitud</label>
                                <input type="text" name="longitudIncidente" class="form-control" value="-68.1500" required>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg">Enviar Reclamo</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="text-center mt-3">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-link text-danger">Cerrar Sesión</button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>