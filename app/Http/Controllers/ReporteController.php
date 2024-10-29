<?php

namespace App\Http\Controllers;

use App\Events\RealTimeMessag;
use App\Models\Publicacion;
use App\Models\Reporte;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ReporteController extends Controller
{
    public function createReport(Request $request)
    {
        $validated = $request->validate([
            'post_id' => 'required|int|exists:Publicacion,id',
            'reason' => 'required|string|max:255',
        ]);

        Reporte::create([
            'motivo' => $validated['reason'],
            'idUsuario' => $request->user()->id,
            "idPublicacion" => $validated['post_id'],
        ]);

        $reports=Reporte::where(["idPublicacion"=>$validated['post_id']]);
        if($reports->count()>=3){
            $userAdmin=User::find(1);
            $userAdmin->notify(new RealTimeMessag("Una publicacion ha sido reportada mas de tres veces",1));
        }

        return response()->json([
            'success' => true,
            'message' => 'La publicacion ha sido reportada, gracias por su colaboración.'
        ]);
    }

    public function omitBan(int $id)
    {
        $post = Publicacion::find($id);
        if (isset($post)) {
            Reporte::where('idPublicacion', "=", $id)->delete();
            $userCreator = User::find($post->idUsuario);
            $userCreator->notify(new RealTimeMessag("Se han omitido los reportes de su publicacion", $post->idUsuario));
            return response()->json([
                'success' => true,
                'message' => 'Se ha omitido el ban a la publicacione.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Publicacion no encontrada'
            ]);
        }
    }

    public function ban(int $id)
    {
        $post = Publicacion::find($id);
        $user = User::find($post->idUsuario);
        if (isset($post)) {
            $post->idEstado = 4;
            $post->save();
            if ($user->nivel == 2) {
                $user->nivel = 1;
                $user->save();
            } else if ($user->nivel == 1) {
                $user->activo = false;
                $user->save();
            }
            $userCreator = User::find($post->idUsuario);
            $userCreator->notify(new RealTimeMessag("La publicacion ha sido baneada", $post->idUsuario));
            return response()->json([
                'success' => true,
                'message' => 'Publicacion baneada'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Publicacion no encontrada'
            ]);
        }
    }
}
