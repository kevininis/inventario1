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
        Schema::create('DetalleVenta', function (Blueprint $table) {
            $table->increments('DV_DetalleVenta');
            $table->unsignedInteger('DV_IdOrdenVenta');
            $table->foreign('DV_IdOrdenVenta')->references('OV_IdOrdenVenta')->on('OrdenVenta');
            $table->unsignedInteger('DV_IdProducto');
            $table->foreign('DV_IdProducto')->references('PRO_IdProducto')->on('Producto');
            $table->integer('DV_Cantidad');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('DetalleVenta');
    }
};
