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
        Schema::table('Publicacion', function (Blueprint $table) {
            $table->foreign(['idUsuario'], 'fk_Publicacion_Usuario1')->references(['id'])->on('Usuario')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['idEstado'], 'fk_publicacion_estado_idx')->references(['id'])->on('Estado')->onUpdate('no action')->onDelete('no action');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Publicacion', function (Blueprint $table) {
            $table->dropForeign('fk_Publicacion_Usuario1');
            $table->dropForeign('fk_publicacion_estado_idx');
        });
    }
};
