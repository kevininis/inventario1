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
        Schema::create('Producto', function (Blueprint $table) {
            $table->increments('PRO_IdProducto');
            $table->string('PRO_NombreProducto', 45);
            $table->Integer('PRO_CodigoBarrasProducto')->nullable();
            $table->string('PRO_DescripcionProducto', 300)->nullable();
            $table->decimal('PRO_PrecioNormalProducto', 12,2);
            $table->decimal('PRO_PrecioLiquidacionProducto', 12,2)->nullable();
            $table->decimal('PRO_PrecioMayoristaProducto', 12,2)->nullable();
            $table->decimal('PRO_PrecioOfertaProducto', 12,2)->nullable();
            $table->unsignedInteger('PRO_IdCategoriaProducto');
            $table->foreign('PRO_IdCategoriaProducto')->references('CP_IdCategoriaProducto')->on('CategoriaProducto');
            $table->unsignedInteger('PRO_IdUbicacionProducto');
            $table->foreign('PRO_IdUbicacionProducto')->references('UP_IdUbicacionProducto')->on('UbicacionProducto');
            $table->integer('PRO_CantidadProducto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Producto');
    }
};
