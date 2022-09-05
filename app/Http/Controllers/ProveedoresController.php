<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Proveedores; 

class ProveedoresController extends Controller
{
    public function NuevoProveedor (Request $request) {
        $request->validate([
            'Nombre' => 'required|string',
            'Telefono' => 'required|integer',
            'Correo' => 'required|string',
            'Dirección' => 'string',
            'Ciudad' => 'string',
            'Pais' => 'string',
        ]);

        try {
            DB::beginTransaction();

            $Proveedor = new Proveedores();
            $Proveedor->PR_NombreProveedor = $request->Nombre;
            $Proveedor->PR_TelefonoProveedor = $request->Telefono;
            $Proveedor->PR_CorreoProveedor = $request->Correo;
            $Proveedor->PR_DireccionProveedor = $request->Direccion;
            $Proveedor->PR_CiudadProveedor = $request->Ciudad;
            $Proveedor->PR_PaisProveedor = $request->Pais;
            $Proveedor->save();

            DB::commit();
            return response()->json([
                'message' => 'Proveedor creado correctamente'
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Error al crear el Proveedor',
                'error' => $e
            ], 500);
        }

    }

    public function ListarProveedores (Request $request) {
    
        $Proveedores = Proveedores::where('PR_NombreProveedor', 'like', "%$request->Nombre%")
                                    ->where('PR_TelefonoProveedor', 'like', "%$request->Telefono%")
                                    ->where('PR_CorreoProveedor', 'like', "%$request->Correo%")
                                    ->get();

        return response()->json([
            'message' => 'Lista traida correctamente',
            'Proveedores' => $Proveedores,
        ],200);
    }

    public function ModificarProveedor (Request $request) {
        try {
            DB::beginTransaction();
            $Modificar = Proveedores::find($request->Id);
            $Modificar->PR_NombreProveedor = $request->Nombre;
            $Modificar->PR_TelefonoProveedor = $request->Telefono;
            $Modificar->PR_CorreoProveedor = $request->Correo; 
            $Modificar->PR_DireccionProveedor = $request->Direccion;
            $Modificar->PR_CiudadProveedor = $request->Ciudad;
            $Modificar->PR_PaisProveedor = $request->Pais;
            $Modificar->save();

            DB::commit();
            return response()->json([
                'message' => 'Proveedor modificado correctamente'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Error al modificar el Proveedor',
                'error' => $e
            ], 500);
        }
    }

    public function EliminarProveedor (Request $request) {
        try {
            DB::beginTransaction();
            $Eliminar = Proveedores::find($request->Id);
            $Eliminar->delete();
            
            DB::commit();
            return response()->json([
                'message' => 'Proveedor eliminado correctamente'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Error al eliminar el Proveedor',
                'error' => $e,
            ], 500);
        }
    }
}
