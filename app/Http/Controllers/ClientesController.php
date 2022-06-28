<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Clientes;

class ClientesController extends Controller
{
    public function NuevoCliente (Request $request) {
        $request->validate([
            'Nombres' => 'required|string',
            'Apellidos' => 'required|string',
            'NIT' => 'required|integer',
            'Telefono' => 'required|integer',
            'Correo' => 'required|string',
            'Dirección' => 'string',
            'Ciudad' => 'string',
            'Pais' => 'string',
        ]);

        try {
            DB::beginTransaction();

            $Cliente = new Clientes();
            $Cliente->CL_NombreCliente = $request->Nombres;
            $Cliente->CL_ApellidosCliente = $request->Apellidos;
            $Cliente->CL_NITCliente = $request->NIT;
            $Cliente->CL_TelefonoCliente = $request->Telefono;
            $Cliente->CL_CorreoCliente = $request->Correo;
            $Cliente->CL_DireccionCliente = $request->Direccion;
            $Cliente->CL_CiudadCliente = $request->Ciudad;
            $Cliente->CL_PaisCliente = $request->Pais;
            $Cliente->save();

            DB::commit();
            return response()->json([
                'message' => 'Cliente creado correctamente'
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Error al crear el Cliente',
                'error' => $e
            ], 500);
        }
    }

    public function ListarClientes (Request $request) {
    
        $Clientes = Clientes::where('CL_NombreCliente', 'like', "%$request->Nombre%")
                                    ->where('CL_NITCliente', 'like', "%$request->NIT%")
                                    ->where('CL_TelefonoCliente', 'like', "%$request->Telefono%")
                                    ->where('CL_CorreoCliente', 'like', "%$request->Correo%")
                                    ->get();

        return response()->json([
            'message' => 'Lista traida correctamente',
            'Clientes' => $Clientes,
        ],200);
    }

    public function ModificarCliente (Request $request) {
        try {
            DB::beginTransaction();
            $Modificar = Clientes::find($request->Id);
            $Modificar->CL_NombreCliente = $request->Nombres;
            $Modificar->CL_ApellidosCliente = $request->Apellidos;
            $Modificar->CL_NITCliente = $request->NIT;
            $Modificar->CL_TelefonoCliente = $request->Telefono;
            $Modificar->CL_CorreoCliente = $request->Correo;
            $Modificar->CL_DireccionCliente = $request->Direccion;
            $Modificar->CL_CiudadCliente = $request->Ciudad;
            $Modificar->CL_PaisCliente = $request->Pais;
            $Modificar->save();

            DB::commit();
            return response()->json([
                'message' => 'Cliente modificado correctamente'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Error al modificar el Cliente',
                'error' => $e
            ], 500);
        }
    }

    public function EliminarCliente (Request $request) {
        try {
            DB::beginTransaction();
            $Eliminar = Clientes::find($request->Id);
            $Eliminar->delete();
            
            DB::commit();
            return response()->json([
                'message' => 'Cliente eliminado correctamente'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Error al eliminar el Cliente',
                'error' => $e,
            ], 500);
        }
    }
}
