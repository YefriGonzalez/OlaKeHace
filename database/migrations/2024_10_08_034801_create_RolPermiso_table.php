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
        Schema::create('RolPermiso', function (Blueprint $table) {
            $table->integer('Rol_id', true)->index('fk_rolpermiso_rol_idx');
            $table->integer('Permiso_id')->index('fk_rolpermiso_permiso1_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('RolPermiso');
    }
};
