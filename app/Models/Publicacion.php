<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publicacion extends Model
{
    use HasFactory;
    protected $table = "Publicacion";
    public $timestamps = true;
    protected $fillable = [
        'nombre',
        'descripcion',
        "idUsuario",
        "fecha",
        "hora",
        "cupo",
        "url",
        "tipoPublico",
    ];
}
