<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\OrdenVenta;
use App\Models\DetalleVenta;
use App\Models\Productos;

class OrdenVentaController extends Controller
{
    public function NuevaVenta (Request $request) {
        $OrdenVenta = $request->OrdenVenta;
        $DetalleVenta = $request->Detalle;

        try {
            DB::beginTransaction();

            $Venta = New OrdenVenta();
            $Venta->OV_IdCliente = $OrdenVenta['Cliente'];
            $Venta->OC_Fecha = NOW('America/Guatemala');
            $Venta->OC_Hora = NOW('America/Guatemala');
            $Venta->OV_IdTipoPago = $OrdenVenta['TipoPago'];
            $Venta->OV_IdEstado = 1;
            $Venta->OV_IdUser = $request->user()->USER_IdUser;
            $Venta->OC_Comentario = $OrdenVenta['Comentario'];
            
            if ($Venta->save()) {
                $IdVenta = $Venta['OV_IdOrdenVenta'];
                foreach($DetalleVenta as $Detalle) {
                    $IdProducto = $Detalle['PRO_IdProducto'];
                    $Producto = Productos::where('PRO_IdProducto', $IdProducto)->get();
                    $RestarCantidad = Productos::find($IdProducto);

                    $DetalleOrdenVenta = New DetalleVenta();
                    $DetalleOrdenVenta->DV_IdOrdenVenta = $IdVenta;
                    $DetalleOrdenVenta->DV_IdProducto = $IdProducto;
                    
                    if ($Detalle['Cantidad'] <= $Producto[0]->PRO_CantidadProducto) {
                        $DetalleOrdenVenta->DV_Cantidad = $Detalle['Cantidad'];
                        $DetalleOrdenVenta->save();
                        
                        //RESTAMOS DEL STOK DE PRODUCTOS
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
                'message' => 'Venta creada correctamente',
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Error al crear la venta',
                'error' => $e
            ], 500);
        }
    }

    public function ListarVentas (Request $request) {
        $Ventas = OrdenVenta::where('OV_IdOrdenVenta', 'like', "%$request->NumeroOrden%")
                                ->where('OV_IdCliente', 'like', "%$request->Cliente%")                        
                                ->where('OC_Fecha', 'like', "%$request->Fecha%")                        
                                ->get();
                                
        return response()->json([
            'Ventas' => $Ventas
        ], 200);
    }

    public function DetalleVenta (Request $request) {
        $id = $request->id;
        $Detalle = DetalleVenta::where('DV_IdOrdenVenta', $id)->with('Producto')->get();

        return response()->json([
            'Detalle' => $Detalle
        ], 200);
    }

    public function ModificarVenta (Request $request) {
        $OrdenVenta = $request->OrdenVenta;
        $DetalleVenta = $request->Detalle;
        $Borrados = $request->Borrados;
        $Nuevos = $request->Nuevos;

        try {
            DB::beginTransaction();

            $Venta = OrdenVenta::find($OrdenVenta['Id']);
            $Venta->OV_IdCliente = $OrdenVenta['Cliente'];
            $Venta->OV_IdUser = $request->user()->USER_IdUser;
            $Venta->OC_Comentario = $OrdenVenta['Comentario'];
            
            if ($Venta->save()) {
                if(count($Nuevos) != 0) {
                    foreach($Nuevos as $Nuevo) {   
                        $ParaAgregar = New DetalleVenta();
                        $ParaAgregar->DV_IdOrdenVenta = $OrdenVenta['Id'];
                        $ParaAgregar->DV_IdProducto = $Nuevo['PRO_IdProducto'];
                        $ParaAgregar->DV_Cantidad = $Nuevo['Cantidad'];
                        $ParaAgregar->save();

                        $RestarCantidad = Productos::find($Nuevo['PRO_IdProducto']);
                        $RestarCantidad->PRO_CantidadProducto -= $Nuevo['Cantidad'];
                        $RestarCantidad->save();
                    }
                }
                foreach($DetalleVenta as $Detalle) {
                    if ($Detalle['IdDetalleVenta'] != null) {
                        $IdProducto = $Detalle['PRO_IdProducto'];
                        $IdDetalleVenta = $Detalle['IdDetalleVenta'];
                        
                        $Producto = Productos::find($IdProducto);

                        $DetalleOrdenVenta = DetalleVenta::find($IdDetalleVenta);
                        $DetalleOrdenVenta->DV_IdProducto = $IdProducto;
                        $DetalleOrdenVenta->DV_Cantidad = $Detalle['Cantidad'];
                        $DetalleOrdenVenta->save();
                        
                        if ($Detalle['Cantidad'] >= $DetalleOrdenVenta->DV_Cantidad) {
                            //RESTAMOS DEL STOK DE PRODUCTOS
                            $Producto->PRO_CantidadProducto -= $Detalle['Cantidad'];
                            $Producto->save();
                        }

                        if ($Detalle['Cantidad'] < $DetalleOrdenVenta->DV_Cantidad) {
                            // SUMAMOS DEL STOK DE PRODUCTOS
                            $Producto->PRO_CantidadProducto += $Detalle['Cantidad'];
                            $Producto->save();      
                        }
                    }
                }
                if(count($Borrados) != 0) {
                    foreach($Borrados as $Borrar) {               
                        $ParaBorrar = DetalleVenta::find($Borrar['IdDetalleVenta']);
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


    public function EliminarVenta (Request $request) {
        $Detalles = $request->DetalleVenta;

        try {
            DB::beginTransaction();

            foreach ($Detalles as $Detalle) {
                $EliminarDetalle = DetalleVenta::find($Detalle['IdDetalleVenta']);
                $EliminarDetalle->delete();
                
                $SumarCantidad = Productos::find($Detalle['PRO_IdProducto']);
                $SumarCantidad->PRO_CantidadProducto += $Detalle['Cantidad'];
                $SumarCantidad->save();
            }

            $Eliminar = OrdenVenta::find($request->IdVenta);
            $Eliminar->delete();

            
            DB::commit();
            return response()->json([
                'message' => 'venta eliminada correctamente'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();     
            return response()->json([
                'message' => 'Error al eliminar la venta',
                'error' => $e,
            ], 500);
        }
    }
}
