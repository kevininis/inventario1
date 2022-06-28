<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    use HasFactory;

    protected $table = "Roles"; // ESPECIFICAR LA TABLA
    protected $primaryKey = "ROL_IdRoles"; // ESPECIFICAR LA LLAVE PRIMARIA

    public $timestamps = false; // AL NO USAR CRAETED_AT Y UPDATED_AT EN BD
}
