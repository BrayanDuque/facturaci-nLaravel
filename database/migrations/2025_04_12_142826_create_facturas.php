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
        Schema::create('facturas', function (Blueprint $table) {
            // creacion de la tabla facturas con el campo, el tipo de dato y su valor
            $table->id();
            $table->string('descripcion');
            $table->string('numero')->unique();
            $table->string('cliente_nombre');
            $table->string('vendedor');
            $table->boolean('estado');
            $table->float('valor_total')->default(0);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
