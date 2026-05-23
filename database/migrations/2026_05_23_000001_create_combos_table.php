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
        Schema::create('combos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->double('precio_oferta')->default(0.0);
            $table->double('precio_lista')->default(0.0);
            $table->integer('descuento')->default(0); // Porcentaje de descuento, ej: 28
            $table->string('categoria')->default('baño'); // baño, cocina
            $table->string('imagen')->nullable(); // Imagen representativa
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('combos');
    }
};
