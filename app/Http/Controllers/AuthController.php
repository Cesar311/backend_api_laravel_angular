<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function funLogin(Request $request) {
        // Validar datos
        $credenciales = $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);
        // Autenticar
        if(!Auth::attempt($credenciales)){
            return response()->json(["mensaje" => "Credenciales incorrectas"], 401);
        }
        // Generar token
        $token = $request->user()->createToken('Token Auth')->plainTextToken;
        // Responder
        return response()->json([
            "access_token" => $token,
            "usuario" => $request->user()
        ], 201);
    }

    public function funRegister(Request $request) {
        // validar
        $request->validate([
            "name" => "required|max:100|min:2|string",
            "email" => "required|email|unique:users",
            "password" => "required|same:c_password"
        ]);
        // Crear usuario
        $usuario = new User();
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->password =Hash::make($request->password);
        $usuario->save();
        // Generar una respuesta
        return response()->json(["mensaje" => "Usuario Registrado"], 201);
    }

    public function funProfile(Request $request) {

        return response()->json($request->user(), 200);
    }

    public function funLogout(Request $request) {

        $request->user()->tokens()->delete();
        return response()->json(["mensaje" => "Logout"]);

    }
}
