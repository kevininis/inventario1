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
        Schema::create('OrdenVenta', function (Blueprint $table) {
            $table->increments('OV_IdOrdenVenta');
            $table->unsignedInteger('OV_IdCliente');
            $table->foreign('OV_IdCliente')->references('CL_IdCLiente')->on('Cliente')->constrained('Cliente')->onDelete('cascade');
            $table->date('OC_Fecha');    
            $table->time('OC_Hora');
            $table->unsignedInteger('OV_IdTipoPago');
            $table->foreign('OV_IdTipoPago')->references('TP_IdTipoPago')->on('TipoPago');
            $table->unsignedInteger('OV_IdEstado');
            $table->foreign('OV_IdEstado')->references('EST_IdEstado')->on('Estado');
            $table->unsignedInteger('OV_IdUser');
            $table->foreign('OV_IdUser')->references('USER_IdUser')->on('users');
            $table->string('OC_Comentario', 600)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('OrdenVenta');
    }
};
