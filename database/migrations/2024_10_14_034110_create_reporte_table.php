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
        Schema::create('Reporte', function (Blueprint $table) {
            $table->integer('id',autoIncrement:true);
            $table->string("motivo",100);
            $table->integer("idUsuario")->index("fk_reporte_usuario_idx");
            $table->integer("idPublicacion")->index("fk_reporte_publicacion_idx");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Reporte');
    }
};
