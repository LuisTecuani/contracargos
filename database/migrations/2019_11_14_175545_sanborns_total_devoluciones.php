<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SanbornsTotalDevoluciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sanborns_total_devoluciones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('cuenta');
            $table->bigInteger('veces_devuelto');
            $table->bigInteger('total_devoluciones');
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
