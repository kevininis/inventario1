<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    use HasFactory;

    protected $table = "detalleventa"; // ESPECIFICAR LA TABLA
    protected $primaryKey = "DV_IdDetalleVenta"; // ESPECIFICAR LA LLAVE PRIMARIA

    public $timestamps = false; // AL NO USAR CREATED_AT Y UPDATED_AT EN BD

    public function Producto () {
        return $this->belongsTo(Productos::class, 'DV_IdProducto', 'PRO_IdProducto');
    }
}
