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
        Schema::create('Cliente', function (Blueprint $table) {
            $table->increments('CL_IdCliente');
            $table->string('CL_NombreCliente', 45);    
            $table->string('CL_ApellidosCliente', 45);
            $table->integer('CL_NITCliente')->nullable();
            $table->integer('CL_TelefonoCliente');
            $table->string('CL_CorreoCliente', 100);
            $table->string('CL_DireccionCliente', 100)->nullable();
            $table->string('CL_CiudadCliente', 45)->nullable();
            $table->string('CL_PaisCliente', 45)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Cliente');
    }
};
