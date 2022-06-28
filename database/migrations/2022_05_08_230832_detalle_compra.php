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
        Schema::create('DetalleCompra', function (Blueprint $table) {
            $table->increments('DC_IdDetalleCompra');
            $table->unsignedInteger('DC_IdOrdenCompra');
            $table->foreign('DC_IdOrdenCompra')->references('OC_IdOrdenCompra')->on('OrdenCompra');
            $table->unsignedInteger('DC_IdProducto');
            $table->foreign('DC_IdProducto')->references('PRO_IdProducto')->on('Producto');
            $table->integer('DC_Cantidad');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('DetalleCompra');
    }
};
