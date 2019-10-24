<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRespuestasBanorteMediakeyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('respuestas_banorte_mediakey', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('comentarios');
            $table->string('detalle_mensaje');
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
        Schema::dropIfExists('respuestas_banorte_mediakey');
    }
}
