<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRespuestasBanorteUrbanoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('respuestas_banorte_urbano', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('comentarios')->nullable();
            $table->string('detalle_mensaje')->nullable();
            $table->string('autorizacion')->nullable();
            $table->string('estatus');
            $table->integer('user_id');
            $table->string('num_control');
            $table->string('tarjeta');
            $table->integer('terminacion');
            $table->string('monto');
            $table->date('fecha');
            $table->string('source_file');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('respuestas_banorte_urbano');
    }
}
