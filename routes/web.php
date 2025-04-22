<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FacturaController;

Route::get('/facturas', [FacturaController::class, 'index'])->name('facturas.index'); // Listado de facturas 
Route::get('/facturas/crear', [FacturaController::class, 'create'])->name('facturas.created'); // Formulario de creación 
Route::post('/facturas', [FacturaController::class, 'store'])->name('facturas.store'); // Guardar la factura
Route::get('/facturas/{factura}', [FacturaController::class, 'show'])->name('facturas.show'); // Vista individual 