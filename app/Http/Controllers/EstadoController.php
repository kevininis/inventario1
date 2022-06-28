<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Estado;

class EstadoController extends Controller
{
    public function NuevoEstado (Request $request) {
        try {
            DB::beginTransaction();

            $Estado = new Estado();
            $Estado->EST_NombreEstado = $request->Nombre;
            $Estado->EST_DescripcionEstado = $request->Descripcion;
            $Estado->save();

            DB::commit();
            return response()->json([
                'message' => 'Tipo de Pago creado correctamente'
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Error al crear el Tipo de Pago',
                'error'   => $e
            ],500);
        }
    }

    public function ListarEstados (Request $request) {
        $Estados = Estado::get();

        return response()->json([
            'Estados' => $Estados
        ], 200);
    }
}
