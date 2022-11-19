<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Register
    public function register(Request $request)
    // validation
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        // create new user
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        // save new user
        $user->save();

        // return answer
        return response()->json([
            'status' => 1,
            'msg' => 'Registro de datos exitoso'
        ], 200);
    }

    // Login
    public function login(Request $request)
    {
        // validation
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // validation if exists email joined
        $user = User::where('email', '=', $request->email)->first();

        // check if exists email
        if (isset($user->id)) {
            // check if password is correct
            if (Hash::check($request->password, $user->password)) {
                // create token
                $token = $user->createToken("auth_token")->plainTextToken;
                // return answer
                return response()->json([
                    'status' => 1,
                    'msg' => 'Usuario logueado con exito',
                    'access_token' => $token
                ]);
                // no is correct
            } else {
                // return answer
                return response()->json([
                    'status' => 0,
                    'msg' => 'La password es incorrecta'
                ]);
            }
            // email no exists
        } else {
            // return answer
            return response()->json([
                'status' => 0,
                'msg' => 'Su email no esta registrado'
            ], 404);
        }
    }

    public function userProfile()
    {
        // return answer
        return response()->json([
            'status' => 1,
            'msg' => 'Acerca del perfil de Usuario',
            'data' => auth()->user()
        ]);
    }

    public function logout()
    {
        // delete token
        auth()->user()->tokens()->delete();
        // return answer
        return response()->json([
            'status' => 1,
            'msg' => 'Cierre de sesion',
        ]);
    }
}
