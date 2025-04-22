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
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Crear Factura</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('facturas.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="numero" class="form-label">Número de Factura</label>
                <input type="text" class="form-control" id="numero" name="numero" value="{{ old('numero') }}" required>
            </div>
            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha de Emisión</label>
                <input type="date" class="form-control" id="fecha" name="fecha" value="{{ old('fecha') }}" required>
            </div>
            <div class="mb-3">
                <label for="cliente_nombre" class="form-label">Nombre del Cliente</label>
                <input type="text" class="form-control" id="cliente_nombre" name="cliente_nombre" value="{{ old('cliente_nombre') }}" required>
            </div>
            <div class="mb-3">
                <label for="vendedor" class="form-label">Nombre del Vendedor</label>
                <input type="text" class="form-control" id="vendedor" name="vendedor" value="{{ old('vendedor') }}" required>
            </div>
            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-select" id="estado" name="estado" required>
                    <option value="1" {{ old('estado') == 1 ? 'selected' : '' }}>Activa</option>
                    <option value="0" {{ old('estado') == 0 ? 'selected' : '' }}>Inactiva</option>
                </select>
            </div>

            <div id="detalles-container">
                <div class="detalle-item">
                    <div class="mb-3">
                        <label class="form-label">Artículo</label>
                        <input type="text" class="form-control articulo" name="detalles[0][articulo]" required value="{{ old('detalles.0.articulo') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cantidad</label>
                        <input type="number" class="form-control cantidad" name="detalles[0][cantidad]" required value="{{ old('detalles.0.cantidad', 1) }}" min="1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Precio Unitario</label>
                        <input type="number" step="0.01" class="form-control precio_unitario" name="detalles[0][precio_unitario]" required value="{{ old('detalles.0.precio_unitario', 0) }}" min="0">
                    </div>
                </div>
            </div>
                       <button type="button" id="agregar-detalle" class="btn btn-success mb-3">Agregar Otro Detalle</button>

            <button type="submit" class="btn btn-primary">Guardar Factura</button>
        </form>
    </div>

    <script>
        let contadorDetalles = 1;

        document.getElementById('agregar-detalle').addEventListener('click', function() {
            const detallesContainer = document.getElementById('detalles-container');
            const nuevoDetalle = document.createElement('div');
            nuevoDetalle.classList.add('detalle-item');

            nuevoDetalle.innerHTML = `
                <div class="mb-3">
                    <label class="form-label">Artículo</label>
                    <input type="text" class="form-control articulo" name="detalles[${contadorDetalles}][articulo]" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Cantidad</label>
                    <input type="number" class="form-control cantidad" name="detalles[${contadorDetalles}][cantidad]" required value="1" min="1">
                </div>
                <div class="mb-3">
                    <label class="form-label">Precio Unitario</label>
                    <input type="number" step="0.01" class="form-control precio_unitario" name="detalles[${contadorDetalles}][precio_unitario]" required value="0" min="0">
                </div>
            `;

            detallesContainer.appendChild(nuevoDetalle);
            contadorDetalles++;
        });
    </script>
    </body>
</html>