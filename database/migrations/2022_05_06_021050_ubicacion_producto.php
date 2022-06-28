<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('UbicacionProducto', function (Blueprint $table) {
            $table->increments('UP_IdUbicacionProducto');
            $table->string('UP_NombreUbicacionProducto', 45);    
            $table->string('UP_DescripcionUbicacionProducto', 200)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('UbicacionProducto');
    }
};
