<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ContracargosMediakeyBanorte extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracargos_mediakey_banorte', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('autorizacion');
            $table->integer('tarjeta');
            $table->string('fecha_consumo');
            $table->string('fecha_contracargo');
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
