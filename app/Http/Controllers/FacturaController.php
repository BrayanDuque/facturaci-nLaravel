<?php

namespace App\Http\Controllers;
use App\Models\Factura;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\FacturaDetalle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator; //  Para validación




class FacturaController extends Controller
{

    public function indexApi(Request $request): JsonResponse
{
    $searchTerm = $request->input('search');
    $filterField = $request->input('filter_field');
    $perPage = 10;

    $facturas = Factura::query();

    if ($searchTerm && $filterField) {
        if ($filterField == 'fecha') {
            $facturas->whereDate($filterField, '=', $searchTerm);
        } else {
            $facturas->where($filterField, 'LIKE', '%' . $searchTerm . '%');
        }
    }

    $facturas = $facturas->paginate($perPage)->withQueryString();

    return response()->json($facturas); // Devuelve JSON
}
public function showApi(Factura $factura): JsonResponse
{
    return response()->json($factura->load('detalles')); // Carga los detalles y devuelve JSON
}
public function storeApi(Request $request): JsonResponse
{
    $validator = Validator::make($request->all(), [
        'numero' => 'required|unique:facturas',
        'fecha' => 'required|date',
        'cliente_nombre' => 'required',
        'vendedor' => 'required',
        'estado' => 'required|boolean',
        'detalles.*.articulo' => 'required',
        'detalles.*.cantidad' => 'required|integer|min:1',
        'detalles.*.precio_unitario' => 'required|numeric|min:0',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422); //  Código de error para validación fallida
    }

    try {
        DB::beginTransaction();

        $facturaData = $request->except('detalles');
        $factura = Factura::create($facturaData);
        $valorTotal = 0;

        foreach ($request->input('detalles') as $detalle) {
            $subtotal = $detalle['cantidad'] * $detalle['precio_unitario'];
            $valorTotal += $subtotal;

            FacturaDetalle::create([
                'factura_id' => $factura->id,
                'articulo' => $detalle['articulo'],
                'cantidad' => $detalle['cantidad'],
                'precio_unitario' => $detalle['precio_unitario'],
                'subtotal' => $subtotal,
            ]);
        }

        $factura->update(['valor_total' => $valorTotal]);

        DB::commit();

        return response()->json(['message' => 'Factura creada exitosamente.', 'factura' => $factura], 201); //  Código 201 para "Creado"
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => 'Error al crear la factura: ' . $e->getMessage()], 500); //  Código 500 para error interno del servidor
    }
}
public function destroyApi(Factura $factura): JsonResponse
{
    $factura->delete();
    return response()->json(['message' => 'Factura eliminada exitosamente.']);
}
 public function index(Request $request): View
    {
        $searchTerm = $request->input('search');
        $filterField = $request->input('filter_field');
        $perPage = 6;

        $facturas = Factura::query();

        if ($searchTerm && $filterField) {
            if ($filterField == 'fecha') {
                $facturas->whereDate($filterField, '=', $searchTerm);
            } else {
                $facturas->where($filterField, 'LIKE', '%' . $searchTerm . '%');
            }
        }

        $facturas = $facturas->paginate($perPage)->withQueryString();

        return view('facturas.index', compact('facturas', 'searchTerm', 'filterField'));
    }
public function create(): View
{
    return view('facturas.created');
}
public function store(Request $request)
{
    // Validar los datos de entrada
    $request->validate([
        'numero' => 'required|unique:facturas', // Número único y no vacío 
        'fecha' => 'required|date', // Fecha obligatoria 
        'cliente_nombre' => 'required', // Cliente obligatorio 
        'vendedor' => 'required', // Vendedor obligatorio 
        'estado' => 'required|boolean', // Estado obligatorio 
        'detalles.*.articulo' => 'required', // Artículo obligatorio
        'detalles.*.cantidad' => 'required|integer|min:1', // Cantidad obligatoria, entera y positiva
        'detalles.*.valor_unitario' => 'required|numeric|min:0', // Precio obligatorio, numérico y no negativo
    ], [
        // Mensajes personalizados de validación
        'numero.required' => 'El número de factura es obligatorio.',
        'numero.unique' => 'El número de factura ya existe.',
        'fecha.required' => 'La fecha es obligatoria.',
        'fecha.date' => 'La fecha debe ser válida.',
        'cliente_nombre.required' => 'El nombre del cliente es obligatorio.',
        'vendedor.required' => 'El nombre del vendedor es obligatorio.',
        'estado.required' => 'El estado es obligatorio.',
        'estado.boolean' => 'El estado debe ser verdadero o falso.',
        'detalles.*.articulo.required' => 'El artículo es obligatorio.',
        'detalles.*.cantidad.required' => 'La cantidad es obligatoria.',
        'detalles.*.cantidad.integer' => 'La cantidad debe ser un número entero.',
        'detalles.*.cantidad.min' => 'La cantidad debe ser al menos 1.',
        'detalles.*.valor_unitario.required' => 'El valor unitario es obligatorio.',
        'detalles.*.valor_unitario.numeric' => 'El valor unitario debe ser un número.',
        'detalles.*.valor_unitario.min' => 'El valor unitario no puede ser negativo.',
    ]);
    // Comenzar la transacción
    try {

        DB::beginTransaction();
        // Crear la factura
        // Se utiliza el método create para insertar la factura en la base de datos
        $factura = Factura::create($request->except('detalles', '_token'));

        $valorTotal = 0;
        // Crear los detalles de la factura
        // Se utiliza el método create para insertar cada detalle en la base de datos
        foreach ($request->input('detalles') as $detalle) {
            $subtotal = $detalle['cantidad'] * $detalle['valor_unitario']; // Calcular subtotal 
            $valorTotal += $subtotal;
            // Se utiliza el método create para insertar cada detalle en la base de datos
            FacturaDetalle::create([
                'factura_id' => $factura->id,
                'articulo' => $detalle['articulo'],
                'cantidad' => $detalle['cantidad'],
                'valor_unitario' => $detalle['valor_unitario'],
                'subtotal' => $subtotal,
            ]);
        }
        // Actualizar el valor total de la factura
        // Se utiliza el método update para modificar el valor total de la factura
        $factura->update(['valor_total' => $valorTotal]); // Actualizar valor total 
        // Confirmar la transacción
        // Se utiliza el método commit
        DB::commit();
        // Redirigir a la vista de listado de facturas con un mensaje de éxito
        // Se utiliza el método redirect para redirigir a la vista de listado de facturas
        // Se utiliza el método route para redirigir a la ruta de listado de facturas
        // Se utiliza el método with para enviar un mensaje de éxito
        return redirect()->route('facturas.index')->with('success', 'Factura creada exitosamente.');
    } catch (\Exception $e) {
        // Deshacer la transacción 
        return $e;
        // Se utiliza el método rollBack para deshacer la transacción
        // Se utiliza el método back para redirigir a la vista anterior
        DB::rollBack();
        return back()->withInput()->with('error', 'Error al crear la factura: ' . $e->getMessage());
    }
}
public function show(Factura $factura): View
{
    return view('facturas.show', compact('factura'));
}
}
