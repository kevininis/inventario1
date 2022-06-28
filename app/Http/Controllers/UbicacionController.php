<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Ubicacion;

class UbicacionController extends Controller
{
    public function NuevaUbicacion (Request $request) {
        try {
            DB::beginTransaction();

            $Ubicacion = new Ubicacion();
            $Ubicacion->UP_NombreUbicacionProducto = $request->Nombre;
            $Ubicacion->UP_DescripcionUbicacionProducto = $request->Descripcion;
            $Ubicacion->save();

            DB::commit();
            return response()->json([
                'message' => 'Ubicacion creado correctamente'
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Error al crear la Ubicacion',
                'error'   => $e
            ],500);
        }
    }

    public function ListarUbicaciones (Request $request) {
        $Ubicacion = Ubicacion::get();

        return response()->json([
            'Ubicaciones' => $Ubicacion
        ], 200);
    }
}
