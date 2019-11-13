<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SanbornsCobros extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sanborns_cobros', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('cuenta');
            $table->date('fecha');
            $table->integer('importe');
            $table->string('respuesta');
            $table->integer('referencia');
            $table->string('source');
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

