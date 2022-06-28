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
        Schema::create('CategoriaProducto', function (Blueprint $table) {
            $table->increments('CP_IdCategoriaProducto');
            $table->string('CP_NombreCategoriaProducto', 45);    
            $table->string('CP_DescripcionCategoriaProducto', 500)->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('CategoriaProducto');
    }
};
