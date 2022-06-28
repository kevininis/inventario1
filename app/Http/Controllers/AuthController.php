<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function NuevoUsuario(Request $request) {
        $request->validate([
            'Nombres' => 'required|string',
            'Apellidos' => 'required|string',
            'Rol' => 'required',
            'Correo' => 'required|string',
            'Contrasenia' => 'required|string'
        ]);

        $verify = User::where('USER_CorreoUser', $request->Correo)->get();

        if ($verify == "[]") {

            try {
                DB::beginTransaction();

                $user = new User();
                $user->USER_NombresUser = $request->Nombres;
                $user->USER_ApellidosUser = $request->Apellidos;
                $user->USER_IdRolesUser = $request->Rol;
                $user->USER_CorreoUser = $request->Correo;
                $user->email = $request->Correo;
                $user->USER_ContraseniaUser = bcrypt($request->Contrasenia); //$request->Contrasenia;
                $user->password = bcrypt($request->Contrasenia); //$request->Contrasenia;
                $user->save();

                DB::commit();
                return response()->json([
                    'message' => 'Usuario creado correctamente'
                ], 201);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json([   
                    'message' => 'Error al crear el usuario',
                    'error'   => $e
                ], 500);
            }
        }

    return response()->json([
        'message' => 'Este usuario ya está creado',
        'verify' => $verify
    ], 500);

    }

    public function Login (Request $request) {
        $request->validate([
            'Correo' => 'required|string',
            'Contrasenia' => 'required|string'
        ]);

        if(!Auth::attempt(['USER_CorreoUser' => $request->Correo, 'password' => $request->Contrasenia])){
            return response()->json([
                'message' => 'Usuario no Registrado',
            ], 401);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');

        $token = $tokenResult->token;
        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer'
        ], 200);
    }

    public function Logout (Request $request) {
        if (!$request->user()){
            return response()->json([
                'message' => 'no hay usuario',
                'user' => $request->user()
            ],200);
        } else {
            $request->user()->token()->revoke();
        }

        return response()->json([
            'message' => 'Sesion cerrada correctamente'
        ], 200);
    }

    public function user(Request $request) {
        return response()->json($request->user());
    }
}
