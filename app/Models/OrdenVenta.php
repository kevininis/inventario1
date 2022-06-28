<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenVenta extends Model
{
    use HasFactory;

    protected $table = "ordenventa"; // ESPECIFICAR LA TABLA
    protected $primaryKey = "OV_IdOrdenVenta"; // ESPECIFICAR LA LLAVE PRIMARIA

    public $timestamps = false; // AL NO USAR CREATED_AT Y UPDATED_AT EN BD

}
