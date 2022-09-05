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
                    
                    $DetalleOrdenCompra = New DetalleCompra();
                    $DetalleOrdenCompra->DC_IdOrdenCompra = $IdCompra;
                    $DetalleOrdenCompra->DC_IdProducto = $IdProducto;
                    
                    $DetalleOrdenCompra->DC_Cantidad = $Detalle['Cantidad'];
                    $DetalleOrdenCompra->save();
                    
                    // Sumamos DEL STOCK DE PRODUCTOS
                    $SumarCantidad = Productos::find($Detalle['PRO_IdProducto']);
                    $SumarCantidad->PRO_CantidadProducto += $Detalle['Cantidad'];
                    $SumarCantidad->save();
                } 
            }

            DB::commit();
            return response()->json([
                'message' => 'Compra creada correctamente',
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Error al crear la compra',
                'error' => $e
            ], 500);
        }
    }

    public function ListarCompras (Request $request) {
        $Compras = OrdenCompra::where('OC_IdOrdenCompra', 'like', "%$request->NumeroOrden%")
                                ->where('OC_Fecha', 'like', "%$request->FechaOrden%")                        
                                ->where('OC_IdProveedor', 'like', "%$request->ProveedorOrden%")                        
                                ->where('OC_IdEstado', 'like', "%$request->EstadoOrden%")                        
                                ->get();

        return response()->json([
            'Compras' => $Compras
        ], 200);
    }

    public function DetalleCompra (Request $request) {
        $id = $request->id;
        $Detalle = DetalleCompra::where('DC_IdOrdenCompra', $id)->with('Producto')->get();

        return response()->json([
            'Detalle' => $Detalle
        ], 200);
    }

    public function ModificarCompra (Request $request) {
        $OrdenCompra = $request->OrdenCompra;
        $DetalleCompra = $request->Detalle;
        $Borrados = $request->Borrados;
        $Nuevos = $request->Nuevos;


        try {
            DB::beginTransaction();

            $Compra = OrdenCompra::find($OrdenCompra['Id']);
            $Compra->OC_IdProveedor = $OrdenCompra['Proveedor'];
            $Compra->OC_IdUser = $request->user()->USER_IdUser;
            $Compra->OC_Comentario = $OrdenCompra['Comentario'];
            
            if ($Compra->save()) {
                if(count($Nuevos) != 0) {
                    foreach($Nuevos as $Nuevo) {   
                        $ParaAgregar = New DetalleCompra();
                        $ParaAgregar->DC_IdOrdenCompra = $OrdenCompra['Id'];
                        $ParaAgregar->DC_IdProducto = $Nuevo['PRO_IdProducto'];
                        $ParaAgregar->DC_Cantidad = $Nuevo['Cantidad'];
                        $ParaAgregar->save();

                        $RestarCantidad = Productos::find($Nuevo['PRO_IdProducto']);
                        $RestarCantidad->PRO_CantidadProducto -= $Nuevo['Cantidad'];
                        $RestarCantidad->save();
                    }
                }
                foreach($DetalleCompra as $Detalle) {
                    if ($Detalle['Id'] != null) {
                        $IdProducto = $Detalle['PRO_IdProducto'];
                        $IdDetalleCompra = $Detalle['Id'];
                        
                        $Producto = Productos::find($IdProducto);

                        $DetalleOrdenCompra = DetalleCompra::find($IdDetalleCompra);
                        $DetalleOrdenCompra->DC_IdProducto = $IdProducto;
                        $DetalleOrdenCompra->DC_Cantidad = $Detalle['Cantidad'];
                        $DetalleOrdenCompra->save();
                        
                        if ($Detalle['Cantidad'] < $DetalleOrdenCompra->DC_Cantidad) {
                            //RESTAMOS DEL STOK DE PRODUCTOS
                            $RestarCantidad = Productos::find($Detalle['PRO_IdProducto']);
                            $RestarCantidad->PRO_CantidadProducto += $Detalle['Cantidad'];
                            $RestarCantidad->save();
                        }

                        if ($Detalle['Cantidad'] > $DetalleOrdenCompra->DC_Cantidad) {
                            // SUMAMOS DEL STOK DE PRODUCTOS
                            $SumarCantidad = Productos::find($Detalle['PRO_IdProducto']);
                            $SumarCantidad->PRO_CantidadProducto += $Detalle['Cantidad'];
                            $SumarCantidad->save();  
                        }
                    }
                }
                if(count($Borrados) != 0) {
                    foreach($Borrados as $Borrar) {               
                        $ParaBorrar = DetalleCompra::find($Borrar['Id']);
                        $ParaBorrar->delete();

                        $SumarCantidad = Productos::find($Borrar['PRO_IdProducto']);
                        $SumarCantidad->PRO_CantidadProducto += $Borrar['Cantidad'];
                        $SumarCantidad->save();
                    }
                }
            }

            DB::commit();
            return response()->json([
                'message' => 'Venta modificada correctamente',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Error al modificar la venta',
                'error' => $e,

            ], 500);
        }

    }

    public function EliminarCompra (Request $request) {
        $Detalles = $request->DetalleCompra;

        try {
            DB::beginTransaction();

            foreach ($Detalles as $Detalle) {
                $EliminarDetalle = DetalleCompra::find($Detalle['Id']);
                $EliminarDetalle->delete();
            }

            $Eliminar = OrdenCompra::find($request->IdVenta);
            $Eliminar->delete();
            
            DB::commit();
            return response()->json([
                'message' => 'Compra eliminada correctamente'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();     
            return response()->json([
                'message' => 'Error al eliminar la Compra',
                'error' => $e,
            ], 500);
        }
    }
}
