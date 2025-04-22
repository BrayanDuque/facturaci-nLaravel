<?php

namespace App\Http\Controllers;
use App\Models\Factura;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\FacturaDetalle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class FacturaController extends Controller
{
    public function index(): View
{
    $facturas = Factura::all();
    return view('facturas.index', compact('facturas'));
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
        'detalles.*.precio_unitario' => 'required|numeric|min:0', // Precio obligatorio, numérico y no negativo
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
        'detalles.*.precio_unitario.required' => 'El precio unitario es obligatorio.',
        'detalles.*.precio_unitario.numeric' => 'El precio unitario debe ser un número.',
        'detalles.*.precio_unitario.min' => 'El precio unitario no puede ser negativo.',
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
            $subtotal = $detalle['cantidad'] * $detalle['precio_unitario']; // Calcular subtotal 
            $valorTotal += $subtotal;
            // Se utiliza el método create para insertar cada detalle en la base de datos
            FacturaDetalle::create([
                'factura_id' => $factura->id,
                'articulo' => $detalle['articulo'],
                'cantidad' => $detalle['cantidad'],
                'precio_unitario' => $detalle['precio_unitario'],
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
