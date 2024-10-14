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
        Schema::table('RolPermiso', function (Blueprint $table) {
            $table->foreign(['Permiso_id'], 'fk_RolPermiso_Permiso1')->references(['id'])->on('Permiso')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['Rol_id'], 'fk_RolPermiso_Rol')->references(['id'])->on('Rol')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('RolPermiso', function (Blueprint $table) {
            $table->dropForeign('fk_RolPermiso_Permiso1');
            $table->dropForeign('fk_RolPermiso_Rol');
        });
    }
};
