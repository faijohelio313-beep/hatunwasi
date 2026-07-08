<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Método de pago elegido por el cliente en el checkout:
     * yape | plin | tarjeta | efectivo
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('metodo_pago')->default('efectivo')->after('total');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('metodo_pago');
        });
    }
};
