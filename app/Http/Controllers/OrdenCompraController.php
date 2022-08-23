<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\OrdenCompra;
use App\Models\DetalleCompra;
use App\Models\Productos;

class OrdenCompraController extends Controller
{
    public function NuevaCompra(Request $request) {
        $OrdenCompra = $request->OrdenCompra;
        $DetalleCompra = $request->Detalle;

        try {
            DB::beginTransaction();

            $Compra = New OrdenCompra();
            $Compra->OC_IdProveedor = $OrdenCompra['Proveedor'];
            $Compra->OC_Fecha = NOW('America/Guatemala');
            $Compra->OC_Hora = NOW('America/Guatemala');
            $Compra->OC_IdTipoPago = $OrdenCompra['TipoPago'];
            $Compra->OC_IdEstado = 1;
            $Compra->OC_IdUser = $request->user()->USER_IdUser; 
            $Compra->OC_Comentario = $OrdenCompra['Comentario'];

            if ($Compra->save()) {
                $IdCompra = $Compra['OC_IdOrdenCompra'];
                foreach($DetalleCompra as $Detalle) {
                    $IdProducto = $Detalle['PRO_IdProducto'];
                    $Producto = Productos::where('PRO_IdProducto', $IdProducto)->get();
                    $RestarCantidad = Productos::find($IdProducto);
                    
                    $DetalleOrdenCompra = New DetalleCompra();
                    $DetalleOrdenCompra->DC_IdOrdenCompra = $IdCompra;
                    $DetalleOrdenCompra->DC_IdProducto = $IdProducto;
                    if ($Detalle['Cantidad'] <= $Producto[0]->PRO_CantidadProducto) {
                        $DetalleOrdenCompra->DC_Cantidad = $Detalle['Cantidad'];
                        $DetalleOrdenCompra->save();
                        
                        // RESTAMOS DEL STOCK DE PRODUCTOS
                        $RestarCantidad->PRO_CantidadProducto -= $Detalle['Cantidad'];
                        $RestarCantidad->save();
                    } else {
                        DB::rollback();
                        return response()->json([
                            'message' => 'Estás queriendo restar más de lo que tienes',
                            'error' => $e,
                        ], 500);
                    }
                }
            }

            DB::commit();
            return response()->json([
                'message' => 'Compra creada correctamente',
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Error 3 al crear la compra',
                'error' => $e
            ], 500);
        }
    }
}
