<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Papelera (soft deletes) para combos: al "eliminar" desde el panel,
     * el registro se marca con deleted_at en lugar de borrarse de verdad.
     */
    public function up(): void
    {
        Schema::table('combos', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('combos', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
