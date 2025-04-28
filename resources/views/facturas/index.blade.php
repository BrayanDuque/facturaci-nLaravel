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
        <!-- Encabezado -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <h1 class="fw-bold mb-3 mb-md-0">Listado de Facturas</h1>
            <a href="{{ route('facturas.created') }}" class="btn btn-primary fw-semibold">
                <i class="bi bi-plus-circle"></i> Crear Nueva Factura
            </a>
        </div>

        <!-- Mensajes de éxito o error -->
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

        <!-- Filtros y búsqueda -->
        <form  id="filter-form" action="{{ route('facturas.index') }}" method="GET" class="mb-3">
            <div class="row g-2">
                <div class="col-12 col-md-4">
                    <label for="filter_field" class="form-label">Filtrar por:</label>
                    <select class="form-select" name="filter_field" id="filter_field">
                        <option value="">Todos los campos</option>
                        <option value="numero" {{ $filterField == 'numero' ? 'selected' : '' }}>Número</option>
                        <option value="fecha" {{ $filterField == 'fecha' ? 'selected' : '' }}>Fecha</option>
                        <option value="cliente_nombre" {{ $filterField == 'cliente_nombre' ? 'selected' : '' }}>Cliente</option>
                        <option value="vendedor" {{ $filterField == 'vendedor' ? 'selected' : '' }}>Vendedor</option>
                        <option value="estado" {{ $filterField == 'estado' ? 'selected' : '' }}>Estado</option>
                    </select>
                </div>
                <div class="col-12 col-md-4">
                    <label for="search" class="form-label">Buscar:</label>
                    <input type="text" class="form-control" name="search" id="search" value="{{ $searchTerm }}" placeholder="Ingrese término de búsqueda">
                </div>
                <div class="col-12 col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Buscar</button>
                    <a href="{{ route('facturas.index') }}" class="btn btn-secondary">Limpiar</a>
                </div>
            </div>
        </form>

        <!-- Tabla de facturas -->
        <div class="table-responsive" id="facturas-container">
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
                    @if($facturas->count() > 0)
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
                                     <form action="{{ route('facturas.destroy', $factura) }}" method="POST"
                                          style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger ml-2"
                                                onclick="return confirm('¿Estás seguro de que deseas eliminar esta factura?')">
                                            Eliminar
                                        </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center">No se encontraron facturas.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4">
            <p class="text-muted mb-3 mb-md-0">Mostrando {{ $facturas->firstItem() }} a {{ $facturas->lastItem() }} de {{ $facturas->total() }} facturas</p>
            <nav aria-label="Paginación">
                <ul class="pagination pagination-sm mb-0">
                    {{ $facturas->links('pagination::bootstrap-5') }}
                </ul>
            </nav>
        </div>
    </div>
    <div id="pagination-container" class="pagination-container">
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
       const obtenerFacturas = async () => {
        const url = '/api/facturas'; 
        try {
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error(`Error HTTP! Estado: ${response.status}`);
            }
            const data = await response.json();
            console.log('Petición GET exitosa:', data);
            return data;
        } catch (error) {
            console.error('Hubo un error al realizar la petición GET:', error);
            
        }
    };
    const obtenerDetalleFactura = async (facturaId) => {
    const url = `/api/facturas/${facturaId}`;
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Error HTTP! Estado: ${response.status}`);
        }
        const data = await response.json();
        console.log('Petición GET exitosa:', data);
        return data;
    } catch (error) {
        console.error('Hubo un error al realizar la petición GET:', error);
    }
};

    const eliminarFactura = async (facturaId) => {
        const url = `/api/facturas/${facturaId}`;
        try {
            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                },
            });
            if (!response.ok) {
                throw new Error(`Error HTTP! Estado: ${response.status}`);
            }
            const data = await response.json();
            console.log('Petición DELETE exitosa:', data);
            return data;
        } catch (error) {
            console.error('Hubo un error al realizar la petición DELETE:', error);
        }
    };
    const crearFactura = async (facturaData) => {
        const url = '/api/facturas';
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(facturaData),
            });
            if (!response.ok) {
                throw new Error(`Error HTTP! Estado: ${response.status}`);
            }
            const data = await response.json();
            console.log('Petición POST exitosa:', data);
            return data;
        } catch (error) {
            console.error('Hubo un error al realizar la petición POST:', error);
        }
    };


    </script>
</body>
</html>