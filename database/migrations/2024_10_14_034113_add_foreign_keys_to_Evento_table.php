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
        Schema::table('Evento', function (Blueprint $table) {
            $table->foreign(['idPublicacion'], 'fk_Evento_Publicacion1')->references(['id'])->on('Publicacion')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['idUsuario'], 'fk_Evento_Usuario1')->references(['id'])->on('Usuario')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Evento', function (Blueprint $table) {
            $table->dropForeign('fk_Evento_Publicacion1');
            $table->dropForeign('fk_Evento_Usuario1');
        });
    }
};
