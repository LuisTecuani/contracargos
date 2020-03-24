<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ContracargosAliado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracargos_aliado', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('autorizacion');
            $table->integer('tarjeta');
            $table->string('fecha_consumo')->nullable();
            $table->string('fecha_contracargo')->nullable();
            $table->string('user_id')->nullable();
            $table->string('email')->nullable();
            $table->string('fecha_rep')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
