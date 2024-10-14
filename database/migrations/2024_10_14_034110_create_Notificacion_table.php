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
        Schema::create('Notificacion', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('titulo', 45);
            $table->string('descripcion', 45);
            $table->tinyInteger('leido');
            $table->integer('idUsuarioEmisor')->index('fk_notificacion_usuario1_idx');
            $table->integer('idUsuarioReceptor')->index('fk_notificacion_usuario2_idx');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Notificacion');
    }
};
