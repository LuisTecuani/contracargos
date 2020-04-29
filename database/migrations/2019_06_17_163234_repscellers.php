<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Repscellers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repscellers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('estatus');
            $table->bigInteger('tarjeta');
            $table->integer('terminacion');
            $table->integer('user_id');
            $table->date('fecha');
            $table->string('autorizacion')->nullable();
            $table->string('detalle_mensaje')->nullable();
            $table->string('monto')->nullable();
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
        //
    }
}
