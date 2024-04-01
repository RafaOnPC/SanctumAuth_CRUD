<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\LoginRequest;
use Exception;
class AuthController extends Controller
{

    public function create(AuthRequest $request)
    {
        //Validando parametros
        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');
        
        try {
            //Crear un nuevo usuario
            $user = new User();
            $user->name = $name;
            $user->email = $email;
            $user->password = Hash::make($password);
            $user->save();
            return response()->json([
                'status' => true,
                'message' => 'Usuario creado exitosamente',
                'token' => $user->createToken('ApiToken')->plainTextToken
            ],200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Usuario no pudo ser creado'
            ],400);
        }
    }

    public function login(LoginRequest $request)
    {
        //Validando valores
        $email = $request->input('email');
        $password = $request->input('password');

        try {
            if (Auth::attempt(['email' => $email, 'password' => $password])) {
                // Las credenciales son correctas, el usuario está autenticado
                    // El usuario está autenticado correctamente
                    $user = User::where('email',$request->email)->first();
                    $token = $user->createToken('ApiToken')->plainTextToken;
                return response()->json([
                    'status' => true,
                    'message' => 'Usuario logeado exitosamente',
                    'data' => $user,
                    'token' => $token
                ],200);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Usuario no autorizado',
            ],401);
        }
    }


    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Cierre de sesión exitoso'
        ], 200);
    }
}
