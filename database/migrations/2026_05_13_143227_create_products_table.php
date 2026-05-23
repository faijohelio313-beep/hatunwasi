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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("nombre");
            $table->string("codigo")->nullable();
            $table->string("marca")->nullable();
            $table->string("formato")->nullable();
            $table->string("color")->nullable();
            $table->string("tipo_producto")->nullable(); // ej: Cerámico, Porcelanato, Inodoro, Lápiz
            $table->string("categoria")->nullable(); // ej: baño, cocina
            $table->text("descripcion")->nullable();
            $table->integer("cantidad")->default(100);
            $table->double("precio");
            $table->boolean("disponible")->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
