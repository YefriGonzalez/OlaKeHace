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
        Schema::table('Reporte', function (Blueprint $table) {
            $table->foreign(['idPublicacion'], 'fk_reporte_publicacion_idx')->references(['id'])->on('Publicacion')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Reporte', function (Blueprint $table) {
            $table->dropForeign('fk_reporte_publicacion_idx');
        });
    }
};