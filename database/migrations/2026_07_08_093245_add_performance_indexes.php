<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Índices de rendimiento para las consultas más frecuentes del sistema.
 * Sin ellos, cada filtro por categoría o estado es un full table scan
 * que degrada linealmente con el volumen de datos y usuarios.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Catálogos públicos y combos filtran SIEMPRE por categoría;
            // el índice compuesto cubre también el filtro de disponibilidad.
            $table->index(['categoria', 'disponible'], 'idx_products_categoria_disponible');
        });

        Schema::table('orders', function (Blueprint $table) {
            // Panel de pedidos y dashboard agrupan/filtran por estado.
            $table->index('status', 'idx_orders_status');
            // "Últimos pedidos" y listados ordenan por fecha.
            $table->index('created_at', 'idx_orders_created_at');
        });

        Schema::table('combos', function (Blueprint $table) {
            // La tienda filtra por categoría; el soft delete participa
            // en cada consulta (WHERE deleted_at IS NULL).
            $table->index(['categoria', 'deleted_at'], 'idx_combos_categoria_deleted');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_products_categoria_disponible');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('idx_orders_status');
            $table->dropIndex('idx_orders_created_at');
        });

        Schema::table('combos', function (Blueprint $table) {
            $table->dropIndex('idx_combos_categoria_deleted');
        });
    }
};
