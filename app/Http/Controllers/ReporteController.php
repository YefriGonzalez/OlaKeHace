<?php

namespace App\Http\Controllers;

use App\Models\Publicacion;
use App\Models\Reporte;
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
        if (isset($post)) {
            $post->idEstado = 4;
            $post->save();
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
