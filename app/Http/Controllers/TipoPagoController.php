<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TipoPago;

class TipoPagoController extends Controller
{
    public function NuevoTipoPago (Request $request) {
        try {
            DB::beginTransaction();

            $TipoPago = new TipoPago();
            $TipoPago->TP_NombreTipoPago = $request->Nombre;
            $TipoPago->save();

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

    public function ListarTipoPago (Request $request) {
        $TipoPago = TipoPago::get();

        return response()->json([
            'TipoPago' => $TipoPago
        ], 200);
    }
}
