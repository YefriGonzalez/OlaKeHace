<?php

namespace App\Http\Controllers;

use App\Models\Publicacion;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Obtener publicaciones ordenadas por fecha, y aplicar el filtro de búsqueda si existe
        $posts = Publicacion::when($search, function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%');
        })->orderBy('created_at', 'desc')->paginate(10); // Paginado de 10 publicaciones

        // Pasar publicaciones y búsqueda actual a la vista
        return view('home', compact('posts', 'search'));    
    }
}
