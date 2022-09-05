<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Productos;

class ProductosController extends Controller
{
    public function NuevoPoducto (Request $request) {
        $request->validate([
            'Nombre' => 'required|string',
            'Categoria' => 'required|integer',
            'PrecioNormal' => 'required',
            'Ubicación' => 'integer',
            'Ciudad' => 'string',
            'Pais' => 'string',
        ]);

        try {
            DB::beginTransaction();

            $Producto = new Productos();
            $Producto->PRO_NombreProducto = $request->Nombre;
            $Producto->PRO_IdCategoriaProducto = $request->Categoria;
            $Producto->PRO_CodigoBarrasProducto = $request->CodigoBarras;
            $Producto->PRO_DescripcionProducto = $request->Descripcion;
            $Producto->PRO_PrecioNormalProducto = $request->PrecioNormal;
            $Producto->PRO_PrecioLiquidacionProducto = $request->PrecioLiquidacion;
            $Producto->PRO_PrecioMayoristaProducto = $request->PrecioMayorista;
            $Producto->PRO_PrecioOfertaProducto = $request->PrecioOferta;
            $Producto->PRO_IdUbicacionProducto = $request->Ubicacion;
            $Producto->PRO_CantidadProducto = $request->Cantidad;
            $Producto->save();

            DB::commit();
            return response()->json([
                'message' => 'Producto creado correctamente'
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Error al crear el producto',
                'error' => $e
            ], 500);
        }

    }

    public function ListarProductos (Request $request) {
    
        $Productos = Productos::where('PRO_NombreProducto', 'like', "%$request->Nombre%")
                                    ->where('PRO_IdCategoriaProducto', 'like', "%$request->Categoria%")
                                    ->where('PRO_IdUbicacionProducto', 'like', "%$request->Ubicacion%")
                                    ->get();

        return response()->json([
            'message' => 'Lista traida correctamente',
            'Productos' => $Productos,
        ],200);
    }

    public function ModificarProducto (Request $request) {
        try {
            DB::beginTransaction();
            $Modificar = Productos::find($request->Id);
            $Modificar->PRO_NombreProducto = $request->Nombre;
            $Modificar->PRO_IdCategoriaProducto = $request->Categoria;
            $Modificar->PRO_CodigoBarrasProducto = $request->CodigoBarras;
            $Modificar->PRO_DescripcionProducto = $request->Descripcion;
            $Modificar->PRO_PrecioNormalProducto = $request->PrecioNormal;
            $Modificar->PRO_PrecioLiquidacionProducto = $request->PrecioLiquidacion;
            $Modificar->PRO_PrecioMayoristaProducto = $request->PrecioMayorista;
            $Modificar->PRO_PrecioOfertaProducto = $request->PrecioOferta;
            $Modificar->PRO_IdUbicacionProducto = $request->Ubicacion;
            $Modificar->PRO_CantidadProducto = $request->Cantidad;
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

    public function EliminarProducto (Request $request) {
        try {
            DB::beginTransaction();
            $Eliminar = Productos::find($request->Id);
            $Eliminar->delete();
            
            DB::commit();
            return response()->json([
                'message' => 'Producto eliminado correctamente'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback(); 
            return response()->json([
                'message' => 'Error al eliminar el Producto',
                'error' => $e,
            ], 500);
        }
    }
}
