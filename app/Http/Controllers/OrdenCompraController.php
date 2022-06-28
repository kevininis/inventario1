<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\OrdenCompra;
use App\Models\DetalleCompra;

class OrdenCompraController extends Controller
{
    public function NuevaCompra(Request $request) {
        $OrdenCompra = $request->OrdenCompra;
        $DetalleCompra = $request->Detalle;

        try {
            DB::beginTransaction();

            $Compra = New OrdenCompra();
            $Compra->OC_IdProveedor = $OrdenCompra->Proveedor;
            $Compra->OC_Fecha = NOW('America/Guatemala');
            $Compra->OC_Hora = NOW('America/Guatemala');
            $Compra->OC_IdTipoPago = $OrdenCompra->TipoPago;
            $Compra->OC_IdEstado = 1;
            $Compra->OC_IdTipoPago = $request->user()->USER_IdUser; 
            $Compra->OC_Comentario = $OrdenCompra->Comentario;
            $Compra->save();
            
            if ($Compra->save()) {
                $IdCompra = $Compra->IdCompra;
                foreach($DetalleCompra as $Detalle) {
                    
                }
            }



            DB::commit();
            return response()->json([
                'message' => 'Compra creada correctamente',
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Error al crear la compra'
            ], 500);
        }
    }
}
