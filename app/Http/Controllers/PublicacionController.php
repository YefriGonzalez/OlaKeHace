<?php

namespace App\Http\Controllers;

use App\Models\Publicacion;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
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

            Publicacion::create([
                'nombre' => $validateData['nombre'],
                'descripcion' => $validateData['descripcion'],
                'fecha' => $validateData['fecha'],
                'hora' => $validateData['hora'],
                'cupo' => $validateData['cupo'],
                'url' => $validateData['url'],
                'tipoPublico' => $validateData['tipoPublico'],
                'idUsuario' => $request->user()->id,
            ]);

            return redirect()->route('home')->with('success', 'Publicación creada correctamente');
        } catch (ValidationException $e) {
            return redirect()->route('home')->with('error', $e->getMessage());
        } catch (\Throwable $th) {
            error_log("Error: " . $th->getMessage());
            return redirect()->route('home')->with('error', 'Ocurrió un error al crear la publicación.');
        }
    }

    public function show() {}

    public function report(Request $request){
        $validated = $request->validate([
            'post_id' => 'required|exists:Publicacion,id',
            'reason' => 'required|string|max:255',
        ]);
        error_log("llego aqui ". $validated['post_id']." ".$validated['reason']);
    }
}
