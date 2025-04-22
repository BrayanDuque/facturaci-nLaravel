<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Factura</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center mb-4">Detalle de Factura</h1>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Factura #{{ $factura->numero }}</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Fecha:</strong> {{ $factura->fecha->format('d/m/Y') }}</p>
                        <p><strong>Cliente:</strong> {{ $factura->cliente_nombre }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Vendedor:</strong> {{ $factura->vendedor }}</p>
                        <p><strong>Estado:</strong> 
                            <span class="badge {{ $factura->estado ? 'bg-success' : 'bg-danger' }}">
                                {{ $factura->estado ? 'Activa' : 'Inactiva' }}
                            </span>
                        </p>
                    </div>
                </div>

                <h2 class="h4 mb-3">Detalles de la Factura</h2>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Artículo</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($factura->detalles as $detalle)
                                <tr>
                                    <td>{{ $detalle->articulo }}</td>
                                    <td>{{ $detalle->cantidad }}</td>
                                    <td>${{ number_format($detalle->valor_unitario, 2) }}</td>
                                    <td>${{ number_format($detalle->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                            <tr class="table-secondary">
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td><strong>${{ number_format($factura->valor_total, 2) }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('facturas.index') }}" class="btn btn-secondary btn-lg">
                        <i class="bi bi-arrow-left"></i> Volver al Listado
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>