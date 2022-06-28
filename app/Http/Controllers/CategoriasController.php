<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Categorias;

class CategoriasController extends Controller
{
    public function NuevaCategoria (Request $request) {
        try {
            DB::beginTransaction();

            $Categoria = new Categorias();
            $Categoria->CP_NombreCategoriaProducto = $request->Nombre;
            $Categoria->CP_DescripcionCategoriaProducto = $request->Descripcion;
            $Categoria->save();

            DB::commit();
            return response()->json([
                'message' => 'Categoria creado correctamente'
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Error al crear el Categoria',
                'error'   => $e
            ],500);
        }
    }

    public function ListarCategorias (Request $request) {
        try {
            $Categorias = Categorias::get();

            return response()->json([
                'Categorias' => $Categorias
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al listar categorias',
                'error' => $e
            ], 500);
        }
    }
}
