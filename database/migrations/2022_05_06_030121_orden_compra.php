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
        Schema::create('OrdenCompra', function (Blueprint $table) {
            $table->increments('OC_IdOrdenCompra'); 
            $table->unsignedInteger('OC_IdProveedor');
            $table->foreign('OC_IdProveedor')->references('PR_IdProveedor')->on('Proveedor')->constrained('Proveedor')->onDelete('cascade');
            $table->date('OC_Fecha');    
            $table->time('OC_Hora');
            $table->unsignedInteger('OC_IdTipoPago');
            $table->foreign('OC_IdTipoPago')->references('TP_IdTipoPago')->on('TipoPago');
            $table->unsignedInteger('OC_IdEstado');
            $table->foreign('OC_IdEstado')->references('EST_IdEstado')->on('Estado');
            $table->unsignedInteger('OC_IdUser');
            $table->foreign('OC_IdUser')->references('USER_IdUser')->on('users');
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
        Schema::dropIfExists('OrdenCompra');
    }
};
