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
        Schema::create('Evento', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('idPublicacion')->index('fk_evento_publicacion1_idx');
            $table->integer('idUsuario')->index('fk_evento_usuario1_idx');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Evento');
    }
};
