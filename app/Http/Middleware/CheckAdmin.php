<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = request()->user();
        if (!$user || $user->idRol !== 1) {
            error_log($user . " -> No es admin");
            return redirect()->route('home')->with('error', 'No tienes acceso a esta sección.');
        }

        return $next($request);
    }
}
