<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Roles;


class RolesController extends Controller
{
    public function NuevoRol (Request $request) {
        // $request->validate([
        //     'NombreRol' => 'required|string'
        // ]);

        try {
            DB::beginTransaction();

            $Roles = new Roles();
            $Roles->ROL_NombreRoles = $request->NombreRol;
            $Roles->ROL_DescripcionRoles = $request->DescripcionRol;
            $Roles->save();

            DB::commit();
            return response()->json([
                'message' => 'Rol creado correctamente'
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Error al crear el Rol',
                'error'   => $e
            ],500);
        }
    }

    public function ListarRoles (Request $request) {
        $Roles = Roles::get();

        return response()->json([
            'Roles' => $Roles
        ], 200);
    }
}
