<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index()
    {
        // return view('posts.index', [
        //     'posts' => Post::latest()->filter(
        //                 request(['search', 'category', 'author'])
        //             )->paginate(18)->withQueryString()
        // ]);
        return view("login");
    }
    //
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        // if (Auth::attempt($credentials)) {
        //     // Autenticación exitosa
        //     return response()->json(['message' => 'Inicio de sesión exitoso'], 200);
        // }
    
        // Autenticación fallida
        return response()->json(['message' => 'Credenciales incorrectas'], 401);
    }
}
