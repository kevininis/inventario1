<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenCompra extends Model
{
    use HasFactory;

    protected $table = "ordenCompra"; // ESPECIFICAR LA TABLA
    protected $primaryKey = "OC_IdOrdenCompra"; // ESPECIFICAR LA LLAVE PRIMARIA

    public $timestamps = false; // AL NO USAR CREATED_AT Y UPDATED_AT EN BD


   
}
