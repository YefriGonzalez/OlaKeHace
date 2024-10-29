<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index()
    {
        return view("login");
    }
    //
    public function login(Request $request)
    {
        try {
            $validateData = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string'
            ]);
            $user = User::where("email", "=", $validateData['email'])->first();

            if (isset($user)) {
                if ($user->activo == true) {
                    if (Hash::check($validateData['password'], $user->password)) {
                        Auth::login($user);
                        return redirect()->intended('/home');
                    } else {
                        error_log("La contraseña no coincide");
                        return back()->withErrors([
                            'password' => 'Contraseña incorrecta.',
                        ])->onlyInput('email');
                    }
                } else {
                    error_log("Usuario baneado");
                    return back()->withErrors([
                        'email' => 'El usuario ha sido bloqueado.',
                    ]);
                }
            } else {
                error_log("No se encontro al usuario");
                return back()->withErrors([
                    'email' => 'Las credenciales no coinciden con nuestros registros.',
                ])->onlyInput('email');
            }
        } catch (\Throwable $th) {
            error_log($th->getMessage());
            return redirect()->route('error.view')->with('error', 'Ocurrió un error inesperado.');
        }
    }
}
