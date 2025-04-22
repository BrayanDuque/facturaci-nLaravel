<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Factura</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .detalle-item {
            border: 1px solid #ccc;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h1 class="h4 mb-0">Crear Factura</h1>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('facturas.store') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="numero" class="form-label">Número de Factura</label>
                            <input type="text" class="form-control" id="numero" name="numero" value="{{ old('numero') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="fecha" class="form-label">Fecha de Creación</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}" required>
                        </div>  
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="cliente_nombre" class="form-label">Nombre del Cliente</label>
                            <input type="text" class="form-control" id="cliente_nombre" name="cliente_nombre" value="{{ old('cliente_nombre') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <input type="text" class="form-control" id="descripcion" name="descripcion" value="{{ old('descripcion') }}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="vendedor" class="form-label">Nombre del Vendedor</label>
                            <input type="text" class="form-control" id="vendedor" name="vendedor" value="{{ old('vendedor') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="1" {{ old('estado') == 1 ? 'selected' : '' }}>Activa</option>
                                <option value="0" {{ old('estado') == 0 ? 'selected' : '' }}>Inactiva</option>
                            </select>
                        </div>
                    </div>

                    <div id="detalles-container">
                        <div class="detalle-item">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Artículo</label>
                                    <input type="text" class="form-control" name="detalles[0][articulo]" required value="{{ old('detalles.0.articulo') }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Cantidad</label>
                                    <input type="number" class="form-control" name="detalles[0][cantidad]" required value="{{ old('detalles.0.cantidad', 1) }}" min="1">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Valor Unitario</label>
                                    <input type="number" step="0.01" class="form-control" name="detalles[0][valor_unitario]" required value="{{ old('detalles.0.valor_unitario', 0) }}" min="0">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-center align-items-center">
                        <button type="button" id="agregar-detalle" class="btn btn-success">Agregar Otro Detalle</button>
                        <button type="submit" class="btn btn-primary">Guardar Factura</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let contadorDetalles = 1;

        document.getElementById('agregar-detalle').addEventListener('click', function() {
            const detallesContainer = document.getElementById('detalles-container');
            const nuevoDetalle = document.createElement('div');
            nuevoDetalle.classList.add('detalle-item');

            nuevoDetalle.innerHTML = `
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Artículo</label>
                        <input type="text" class="form-control" name="detalles[${contadorDetalles}][articulo]" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Cantidad</label>
                        <input type="number" class="form-control" name="detalles[${contadorDetalles}][cantidad]" required value="1" min="1">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Valor Unitario</label>
                        <input type="number" step="0.01" class="form-control" name="detalles[${contadorDetalles}][valor_unitario]" required value="0" min="0">
                    </div>
                </div>
            `;

            detallesContainer.appendChild(nuevoDetalle);
            contadorDetalles++;
        });
    </script>
</body>
</html>