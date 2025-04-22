<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FacturaDetalle extends Model
{
     use HasFactory;

    protected $fillable = [
        'factura_id',
        'articulo',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];

    public function factura(): BelongsTo
    {
        return $this->belongsTo(Factura::class); // Un detalle pertenece a una factura 
    }
}
