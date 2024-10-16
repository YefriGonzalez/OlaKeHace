<?php

namespace App\Http\Controllers;

use App\Models\Publicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Obtener publicaciones ordenadas por fecha, y aplicar el filtro de búsqueda si existe
        $posts = Publicacion::join('Usuario', 'Publicacion.idUsuario', '=', 'Usuario.id') // INNER JOIN
            ->leftJoin('Evento', 'Publicacion.id', '=', 'Evento.idPublicacion') // LEFT JOIN
            ->select('Publicacion.*', 'Usuario.username as username', DB::raw('COUNT(Evento.id) as cantidadEvento'))
            ->when($search, function ($query, $search) {
                return $query->where('Publicacion.nombre', 'like', '%' . $search . '%');
            })
            ->where(["idEstado" => 2])
            ->groupBy('Publicacion.id', 'Usuario.username', 'Publicacion.nombre', 'Publicacion.descripcion', 'Publicacion.fecha', 'Publicacion.hora', 'Publicacion.cupo', 'Publicacion.url', 'Publicacion.tipoPublico', 'Publicacion.created_at', 'Publicacion.updated_at') // Agregar todas las columnas no agregadas
            ->orderBy('Publicacion.created_at', 'desc')
            ->paginate(10);

        return view('home', compact('posts', 'search'));
    }
}
