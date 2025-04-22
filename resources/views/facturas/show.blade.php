<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Factura</title>
    <link href="[https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css](https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css)" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Detalle de Factura</h1>

        <div class="card">
            <div class="card-header">
                Factura #{{ $factura->numero }}
            </div>
            <div class="card-body">
                <p><strong>Fecha:</strong> {{ $factura->fecha->format('d/m/Y') }}</p>
                <p><strong>Cliente:</strong> {{ $factura->cliente_nombre }}</p>
                <p><strong>Vendedor:</strong> {{ $factura->vendedor }}</p>
                <p><strong>Estado:</strong> {{ $factura->estado ? 'Activa' : 'Inactiva' }}</p>

                <h2>Detalles de la Factura</h2>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
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
                                    <td>${{ number_format($detalle->precio_unitario, 2) }}</td>
                                    <td>${{ number_format($detalle->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td>${{ number_format($factura->valor_total, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <a href="{{ route('facturas.index') }}" class="btn btn-secondary">Volver al Listado</a>
            </div>
        </div>
    </div>
</body>
</html>