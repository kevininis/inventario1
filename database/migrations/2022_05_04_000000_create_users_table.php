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
        Schema::create('Users', function (Blueprint $table) {
            $table->increments('USER_IdUser');
            $table->string('USER_NombresUser');
            $table->string('USER_ApellidosUser');
            $table->string('USER_CorreoUser')->unique();
            $table->unsignedInteger('USER_IdRolesUser');
            $table->foreign('USER_IdRolesUser')->references('ROL_IdRoles')->on('Roles');
            $table->string('USER_ContraseniaUser');
            $table->string('email');
            $table->string('password');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};