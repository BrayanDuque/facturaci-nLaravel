<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('facturas_detalle', function (Blueprint $table) {
            // creacion de la tabla facturas_detalle con el campo, el tipo de dato y su valor
            // $table->string('descripcion');
            $table->id();
            $table->string('descripcion');
            $table->foreignId('factura_id')->constrained()->onDelete('cascade');
            $table->string('articulo');
            $table->decimal('cantidad', 8, 2);
            $table->float('valor_unitario')->default(0);
            $table->decimal('valor_total',10,2)->comment('cantidad * valor unitario')->default(0);
            $table->timestamps();

           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas_detalle');
    }
};
