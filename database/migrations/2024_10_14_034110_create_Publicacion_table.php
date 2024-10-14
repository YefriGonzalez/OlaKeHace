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
        Schema::create('Publicacion', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('nombre', 100);
            $table->string('descripcion');
            $table->integer('idUsuario')->index('fk_publicacion_usuario1_idx');
            $table->date('fecha');
            $table->string('hora', 6);
            $table->integer('cupo');
            $table->string('url', 200);
            $table->timestamps();
            $table->enum('tipoPublico', ['NIÑOS', 'ADOLESCENTES', 'ADULTOS', 'TODOS']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Publicacion');
    }
};
