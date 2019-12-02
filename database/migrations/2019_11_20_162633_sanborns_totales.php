<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SanbornsTotales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sanborns_totales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('cuenta');
            $table->bigInteger('veces_cobrado');
            $table->bigInteger('total_cobros');
            $table->bigInteger('veces_devuelto')->nullable();
            $table->bigInteger('total_devoluciones')->nullable();
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

    }
}
