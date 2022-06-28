<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    use HasFactory;

    protected $table = "estado"; // ESPECIFICAR LA TABLA
    protected $primaryKey = "EST_IdEstado"; // ESPECIFICAR LA LLAVE PRIMARIA

    public $timestamps = false; // AL NO USAR CREATED_AT Y UPDATED_AT EN BD

}
