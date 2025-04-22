<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Facturas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Listado de Facturas</h1>
        <a href="{{ route('facturas.create') }}" class="btn btn-primary mb-3">Crear Nueva Factura</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Número</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Vendedor</th>
                        <th>Estado</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($facturas as $factura)
                        <tr>
                            <td>{{ $factura->numero }}</td>
                            <td>{{ $factura->fecha->format('d/m/Y') }}</td>
                            <td>{{ $factura->cliente_nombre }}</td>
                            <td>{{ $factura->vendedor }}</td>
                            <td>{{ $factura->estado ? 'Activa' : 'Inactiva' }}</td>
                            <td>${{ number_format($factura->valor_total, 2) }}</td>
                            <td>
                                <a href="{{ route('facturas.show', $factura) }}" class="btn btn-sm btn-info">Ver Detalles</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>