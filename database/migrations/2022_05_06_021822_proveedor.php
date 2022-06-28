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
        Schema::create('Proveedor', function (Blueprint $table) {
            $table->increments('PR_IdProveedor');
            $table->string('PR_NombreProveedor', 45);    
            $table->string('PR_DireccionProveedor', 250);
            $table->integer('PR_TelefonoProveedor');
            $table->string('PR_CorreoProveedor', 100);
            $table->string('PR_CiudadProveedor', 45)->nullable();
            $table->string('PR_PaisProveedor', 45)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Proveedor');
    }
};
