<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Profile;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            "name" => 'required',
            "email" => 'required|email|unique:users',
            "password" => 'required|confirmed'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $user->save();

        return response()->json([
            "status" => 1,
            "message" => "Registro exitoso!",
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            "name" => 'required',
            "password" => 'required',
            "id_perfil" => 'required'
        ]);

        $user = User::where("name", $request->name)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            $token = JWTAuth::fromUser($user);
            $roles = $user->getRoleNames();

            return response()->json([
                "status" => true,
                "message" => "Conexión correcta",
                "data" => [
                    "access_token" => $token,
                    "user" => [
                        "id" => $user->id,
                        "name" => $user->name,
                        "email" => $user->email,
                        "roles" => $roles,
                        "perfil" => $request->id_perfil
                    ]
                ]
            ], 200);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Credenciales incorrectas!"
            ], 404);
        }
    }

    public function userProfile(Request $request, $name)
    {
        $user = User::where('name', $name)->first();

        if (!$user) {
            return response()->json([
                "status" => 0,
                "message" => "El usuario no existe."
            ], 404);
        }

        $profiles = $user->profiles()->get();

        $data = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ],
            'profiles' => $profiles
        ];

        return response()->json([
            "status" => 1,
            "message" => "Perfiles del usuario",
            "data" => $data
        ]);
    }

    public function allUser()
    {
        $users = User::all();

        return response()->json([
            "status" => true,
            "message" => "Acerca del perfil de usuario",
            "data" => $users
        ]);
    }

    public function refreshToken()
    {
        $token = JWTAuth::parseToken()->refresh();
        $user = JWTAuth::setToken($token)->toUser();

        return response()->json([
            'email' => $user->email,
            'token' => $token
        ]);
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json([
            "status" => 1,
            "message" => "Sesión cerrada correctamente"
        ]);
    }
}

