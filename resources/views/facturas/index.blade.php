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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fw-bold">Listado de Facturas</h1>
            <a href="{{ route('facturas.created') }}" class="btn btn-primary fw-semibold">
                <i class="bi bi-plus-circle"></i> Crear Nueva Factura
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered align-middle">
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
                            <td>
                                <span class="badge {{ $factura->estado ? 'bg-success' : 'bg-danger' }}">
                                    {{ $factura->estado ? 'Activa' : 'Inactiva' }}
                                </span>
                            </td>
                            <td>${{ number_format($factura->valor_total, 2) }}</td>
                            <td>
                                <a href="{{ route('facturas.show', $factura) }}" class="btn btn-sm btn-info fw-semibold">
                                    <i class="bi bi-eye"></i> Ver Detalles
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>