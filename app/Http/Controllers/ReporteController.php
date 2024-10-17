<?php

namespace App\Http\Controllers;

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
        error_log("paso aqui");

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
}
