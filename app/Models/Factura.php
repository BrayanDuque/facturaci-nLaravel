<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Factura extends Model
{
    use HasFactory;

    protected $fillable = [
        'descripcion',
        'numero',
        'fecha',
        'cliente_nombre',
        'vendedor',
        'estado',
        'valor_total',
    ];
    public function detalles(): HasMany
    {
        return $this->hasMany(FacturaDetalle::class); // Una factura tiene muchos detalles [cite: 6]
    }
}
