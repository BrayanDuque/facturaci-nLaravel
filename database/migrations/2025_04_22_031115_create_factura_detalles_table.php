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
        Schema::create('factura_detalles', function (Blueprint $table) {
             $table->id();
            $table->foreignId('factura_id')->constrained()->onDelete('cascade');
            $table->string('articulo');
            $table->decimal('cantidad', 8, 2);
            $table->float('valor_unitario')->default(0);
            $table->float('subtotal')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factura_detalles');
    }
};
