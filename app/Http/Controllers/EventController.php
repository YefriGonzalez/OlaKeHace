<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Publicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{

    public function listMyEvents(Request $request)
    {
        $events = Event::join("Publicacion", "Evento.idPublicacion", "Publicacion.id")
            ->select("Publicacion.id","Publicacion.nombre","Publicacion.fecha")
            ->where("Evento.idUsuario", "=", $request->user()->id)->get();
        return response()->json([
            "success" => true,
            "data" => $events
        ], 200);
    }

    public function registerEvent(Request $request)
    {
        $validated = $request->validate([
            "idPublicacion" => "required|int|exists:Publicacion,id"
        ]);

        $asistentes = Publicacion::join('Evento', 'Publicacion.id', '=', 'Evento.idPublicacion')
        ->where('Publicacion.id', $validated['idPublicacion'])
        ->select(DB::raw('COUNT(Evento.id) as cantidadEvento'))
        ->first();
        $post=Publicacion::find($validated['idPublicacion'])->first();
        if($post->cupo<$asistentes->cantidadEvento){
            return response()->json([
                "success" => false,
                "message" => "No hay cupos disponibles"
            ]);
        }
        // Busca si ya existe un registro
        $row = Event::where([
            "idPublicacion" => $validated["idPublicacion"],
            "idUsuario" => $request->user()->id
        ])->first();

        if ($row) {
            return response()->json([
                "success" => false,
                "message" => "Ya se ha registrado en este evento"
            ]);
        }

        // Crea el registro si no existe uno previo
        Event::create([
            "idPublicacion" => $validated["idPublicacion"],
            "idUsuario" => $request->user()->id
        ]);

        return response()->json([
            "success" => true,
            "message" => "Registro exitoso en el evento"
        ]);
    }
}
