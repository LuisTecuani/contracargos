<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ContracargosCellers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracargos_cellers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('autorizacion');
            $table->integer('tarjeta');
            $table->string('user_id')->nullable();
            $table->string('email')->nullable();
            $table->date('fecha_rep')->nullable();
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
