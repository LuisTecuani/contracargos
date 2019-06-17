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
            $table->bigInteger('tarjeta');
            $table->integer('teminacion');
            $table->integer('user_id');
            $table->bigInteger('fecha');
            $table->bigInteger('autorizacion');
            $table->string('monto');
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
