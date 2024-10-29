<?php

namespace App\Http\Controllers;

use App\Events\RealTimeMessag;
use App\Models\Notification;
use App\Models\Publicacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PublicacionController extends Controller
{

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
            $user=User::find($request->user()->id);    
            $params = [
                'nombre' => $validateData['nombre'],
                'descripcion' => $validateData['descripcion'],
                'fecha' => $validateData['fecha'],
                'hora' => $validateData['hora'],
                'cupo' => $validateData['cupo'],
                'url' => $validateData['url'],
                'tipoPublico' => $validateData['tipoPublico'],
                'idUsuario' => $request->user()->id,
                'idEstado' => ($user->nivel==2) ? 2 : 1,
                "nivel"=>($postsUser >= 2) ? 2 : 1
            ];

            Publicacion::create($params);
            if ($user->nivel != 2) {
                $userAdmin=User::find(1);
                $userAdmin->notify(new RealTimeMessag("El usuario ". $request->user()->username . " ha creado una publicacion, debe ser revisada",1));
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
            ->join("Estado", "Publicacion.idEstado", "=", "Estado.id")
            ->select('Publicacion.*', "Estado.nombre as estado", 'Usuario.username as username', DB::raw('COUNT(Evento.id) as cantidadEvento'), DB::raw("COUNT(Reporte.id) as cantidadReporte"))
            ->when($search, function ($query, $search) {
                return $query->where('Publicacion.nombre', 'like', '%' . $search . '%');
            })
            ->where(["Publicacion.idUsuario" => $request->user()->id])
            ->groupBy('Publicacion.id', 'Usuario.username', 'Publicacion.nombre', 'Publicacion.descripcion', 'Publicacion.fecha', 'Publicacion.hora', 'Publicacion.cupo', 'Publicacion.url', 'Publicacion.tipoPublico', 'Publicacion.created_at', 'Publicacion.updated_at') // Agregar todas las columnas no agregadas
            ->orderBy('Publicacion.created_at', 'desc')
            ->cursorPaginate(5);

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
            ->cursorPaginate(5);

        return view('postAprove', compact('posts', 'search'));
    }

    public function postsReported(Request $request)
    {


        $search = $request->input('search');

        $posts = Publicacion::join('Usuario', 'Publicacion.idUsuario', '=', 'Usuario.id')
            ->leftJoin('Evento', 'Publicacion.id', '=', 'Evento.idPublicacion')
            ->leftJoin("Reporte", "Publicacion.id", "=", "Reporte.idPublicacion")
            ->join("Estado", "Publicacion.idEstado", "=", "Estado.id")
            ->select('Publicacion.*', "Estado.nombre as estado", 'Usuario.username as username', DB::raw('COUNT(Evento.id) as cantidadEvento'), DB::raw("COUNT(Reporte.id) as cantidadReporte"))
            ->when($search, function ($query, $search) {
                return $query->where('Publicacion.nombre', 'like', '%' . $search . '%');
            })
            ->havingRaw('COUNT(Reporte.id) >= 3')
            ->where("idEstado","!=",4)
            ->groupBy('Publicacion.id', 'Usuario.username', 'Publicacion.nombre', 'Publicacion.descripcion', 'Publicacion.fecha', 'Publicacion.hora', 'Publicacion.cupo', 'Publicacion.url', 'Publicacion.tipoPublico', 'Publicacion.created_at', 'Publicacion.updated_at') // Agregar todas las columnas no agregadas
            ->orderBy('Publicacion.created_at', 'desc')
            ->cursorPaginate(5);
        return view('postReported', compact('posts', 'search'));
    }
    public function aprove(int $id)
    {
        $post = Publicacion::findOrFail($id);
        
        $post->idEstado = 2;
        $post->save();
        $userCreator=User::find($post->idUsuario);
        $userCreator->notify(new RealTimeMessag("Publicacion aprobada",$post->idUsuario));

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
