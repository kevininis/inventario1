<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleCompra extends Model
{
    use HasFactory;

    protected $table = "detallecompra"; // ESPECIFICAR LA TABLA
    protected $primaryKey = "DC_IdDetalleCompra"; // ESPECIFICAR LA LLAVE PRIMARIA

    public $timestamps = false; // AL NO USAR CREATED_AT Y UPDATED_AT EN BD

    public function Producto () {
        return $this->belongsTo(Productos::class, 'DC_IdProducto', 'PRO_IdProducto');
    }git r

}
