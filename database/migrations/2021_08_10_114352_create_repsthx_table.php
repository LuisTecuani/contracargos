<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepsthxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repsthx', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('estatus');
            $table->bigInteger('tarjeta');
            $table->integer('terminacion');
            $table->integer('user_id');
            $table->date('fecha');
            $table->string('autorizacion')->nullable();
            $table->string('detalle_mensaje');
            $table->string('monto');
            $table->string('source_file');
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
        Schema::dropIfExists('repsthx');
    }
}
