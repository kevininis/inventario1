<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productos extends Model
{
    use HasFactory;

    protected $table = "Producto"; // ESPECIFICAR LA TABLA
    protected $primaryKey = "PRO_IdProducto"; // ESPECIFICAR LA LLAVE PRIMARIA

    public $timestamps = false; // AL NO USAR CREATED_AT Y UPDATED_AT EN BD

    public function Categoria (){
        return $this->belongsTo(Cateogrias::class, 'PRO_IdCategoriaProducto', 'CP_IdCategoriaProducto');
    }
}
