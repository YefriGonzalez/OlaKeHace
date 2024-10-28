<?php

namespace App\Http\Controllers;

use App\Models\Publicacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Obtener publicaciones ordenadas por fecha, y aplicar el filtro de búsqueda si existe
        $posts = Publicacion::join('Usuario', 'Publicacion.idUsuario', '=', 'Usuario.id')
            ->leftJoin('Evento', 'Publicacion.id', '=', 'Evento.idPublicacion')
            ->leftJoin("Reporte", "Publicacion.id", "=", "Reporte.idPublicacion")
            ->select('Publicacion.*', 'Usuario.username as username', DB::raw('COUNT(Evento.id) as cantidadEvento'), DB::raw("COUNT(Reporte.id) as cantidadReporte"))
            ->when($search, function ($query, $search) {
                return $query->where('Publicacion.nombre', 'like', '%' . $search . '%');
            })
            ->where(["idEstado" => 2])
            ->groupBy('Publicacion.id', 'Usuario.username', 'Publicacion.nombre', 'Publicacion.descripcion', 'Publicacion.fecha', 'Publicacion.hora', 'Publicacion.cupo', 'Publicacion.url', 'Publicacion.tipoPublico', 'Publicacion.created_at', 'Publicacion.updated_at') // Agregar todas las columnas no agregadas
            ->havingRaw('COUNT(Reporte.id) < 3')
            ->orderBy('Publicacion.created_at', 'desc')
            ->paginate(10);
        $unreadNotifications = User::find($request->user()->id)->unreadNotifications()->get();
     
        return view('home', compact('posts', 'search', 'unreadNotifications'));
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return redirect()->back();
    }
}
