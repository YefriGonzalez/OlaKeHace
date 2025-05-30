<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reporte extends Model
{
    use HasFactory;
    protected $table = "Reporte";
    public $timestamps = true;
    protected $fillable = [
        'motivo',
        'idUsuario',
        "idPublicacion",
    ];
}
