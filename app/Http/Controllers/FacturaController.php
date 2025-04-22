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
    return view('facturas.create');
}
public function store(Request $request): RedirectResponse
{
    $request->validate([
        'numero' => 'required|unique:facturas', // Número único y no vacío [cite: 7]
        'fecha' => 'required|date', // Fecha obligatoria [cite: 7]
        'cliente_nombre' => 'required', // Cliente obligatorio [cite: 7]
        'vendedor' => 'required', // Vendedor obligatorio [cite: 7]
        'estado' => 'required|boolean', // Estado obligatorio [cite: 7]
        'detalles.*.articulo' => 'required', // Artículo obligatorio
        'detalles.*.cantidad' => 'required|integer|min:1', // Cantidad obligatoria, entera y positiva
        'detalles.*.precio_unitario' => 'required|numeric|min:0', // Precio obligatorio, numérico y no negativo
    ], [
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

    try {
        DB::beginTransaction();

        $factura = Factura::create($request->except('detalles', '_token'));
        $valorTotal = 0;

        foreach ($request->input('detalles') as $detalle) {
            $subtotal = $detalle['cantidad'] * $detalle['precio_unitario']; // Calcular subtotal [cite: 7]
            $valorTotal += $subtotal;

            FacturaDetalle::create([
                'factura_id' => $factura->id,
                'articulo' => $detalle['articulo'],
                'cantidad' => $detalle['cantidad'],
                'precio_unitario' => $detalle['precio_unitario'],
                'subtotal' => $subtotal,
            ]);
        }

        $factura->update(['valor_total' => $valorTotal]); // Actualizar valor total [cite: 7]

        DB::commit();

        return redirect()->route('facturas.index')->with('success', 'Factura creada exitosamente.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withInput()->with('error', 'Error al crear la factura: ' . $e->getMessage());
    }
}
public function show(Factura $factura): View
{
    return view('facturas.show', compact('factura'));
}
}
