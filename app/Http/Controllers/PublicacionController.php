<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Publicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Number;
use PhpParser\Node\Stmt\TryCatch;

class PublicacionController extends Controller
{
    //

    public function create(Request $request)
    {
        try {
            $validateData = $request->validate([
                'nombre' => 'required|string',
                'descripcion' => 'required|string',
                "fecha" => 'required|date',
                "hora" => 'required|string',
                "cupo" => "required|int",
                "url" => "required|string",
                "tipoPublico" => "required|string"
            ]);

            $postsUser = Publicacion::where('idUsuario', "=", $request->user()->id)
                ->where('idEstado', "=", 2)
                ->count();
            $params = [
                'nombre' => $validateData['nombre'],
                'descripcion' => $validateData['descripcion'],
                'fecha' => $validateData['fecha'],
                'hora' => $validateData['hora'],
                'cupo' => $validateData['cupo'],
                'url' => $validateData['url'],
                'tipoPublico' => $validateData['tipoPublico'],
                'idUsuario' => $request->user()->id,
                'idEstado' => ($postsUser >= 2) ? 2 : 1
            ];

            Publicacion::create($params);
            if ($postsUser < 2) {
                Notification::create([
                    "titulo" => "Nueva publicacion pendiente de aprobación",
                    'descripcion' => 'El usuario ' . $request->user()->username . ' ha creado una publicacion.',
                    'idUsuarioEmisor' => $request->user()->id,
                    'idUsuarioReceptor' => 1, //admin
                    'leido' => false
                ]);
            }
            return redirect()->route('home')->with('success', 'Publicación creada correctamente');
        } catch (ValidationException $e) {
            return redirect()->route('home')->with('error', $e->getMessage());
        } catch (\Throwable $th) {
            error_log("Error: " . $th->getMessage());
            return redirect()->route('home')->with('error', 'Ocurrió un error al crear la publicación.');
        }
    }

    public function show() {}


    public function myPostsView(Request $request)
    {
        $search = $request->input('search');

        // Obtener publicaciones ordenadas por fecha, y aplicar el filtro de búsqueda si existe
        $posts = Publicacion::join('Usuario', 'Publicacion.idUsuario', '=', 'Usuario.id')
            ->leftJoin('Evento', 'Publicacion.id', '=', 'Evento.idPublicacion')
            ->leftJoin("Reporte", "Publicacion.id", "=", "Reporte.idPublicacion")
            ->join("Estado","Publicacion.idEstado","=","Estado.id")
            ->select('Publicacion.*',"Estado.nombre as estado", 'Usuario.username as username', DB::raw('COUNT(Evento.id) as cantidadEvento'), DB::raw("COUNT(Reporte.id) as cantidadReporte"))
            ->when($search, function ($query, $search) {
                return $query->where('Publicacion.nombre', 'like', '%' . $search . '%');
            })
            ->where(["Publicacion.idUsuario" => $request->user()->id])
            ->groupBy('Publicacion.id', 'Usuario.username', 'Publicacion.nombre', 'Publicacion.descripcion', 'Publicacion.fecha', 'Publicacion.hora', 'Publicacion.cupo', 'Publicacion.url', 'Publicacion.tipoPublico', 'Publicacion.created_at', 'Publicacion.updated_at') // Agregar todas las columnas no agregadas
            ->orderBy('Publicacion.created_at', 'desc')
            ->paginate(10);

        return view('myPosts', compact('posts', 'search'));
    }



    public function showListAprove(Request $request)
    {
        $search = $request->input('search');

        // Obtener publicaciones ordenadas por fecha, y aplicar el filtro de búsqueda si existe
        $posts = Publicacion::join('Usuario', 'Publicacion.idUsuario', '=', 'Usuario.id')
            ->leftJoin('Evento', 'Publicacion.id', '=', 'Evento.idPublicacion')
            ->leftJoin("Reporte", "Publicacion.id", "=", "Reporte.idPublicacion")
            ->select('Publicacion.*', 'Usuario.username as username', DB::raw('COUNT(Evento.id) as cantidadEvento'))
            ->when($search, function ($query, $search) {
                return $query->where('Publicacion.nombre', 'like', '%' . $search . '%');
            })
            ->where(["idEstado" => 1])
            ->groupBy('Publicacion.id', 'Usuario.username', 'Publicacion.nombre', 'Publicacion.descripcion', 'Publicacion.fecha', 'Publicacion.hora', 'Publicacion.cupo', 'Publicacion.url', 'Publicacion.tipoPublico', 'Publicacion.created_at', 'Publicacion.updated_at') // Agregar todas las columnas no agregadas
            ->orderBy('Publicacion.created_at', 'desc')
            ->paginate(10);

        return view('postAprove', compact('posts', 'search'));
    }

    public function aprove(int $id)
    {
        $post = Publicacion::findOrFail($id);


        $post->idEstado = 2;
        $post->save();

        return response()->json([
            'success' => true,
            'message' => 'La publicacion ha sido aprobada.'
        ]);
    }

    public function rechaze(int $id)
    {
        $post = Publicacion::findOrFail($id);


        $post->idEstado = 3; //Estado de rechazado
        $post->save();

        return response()->json([
            'success' => true,
            'message' => 'La publicacion ha sido rechazada.'
        ]);
    }
}
