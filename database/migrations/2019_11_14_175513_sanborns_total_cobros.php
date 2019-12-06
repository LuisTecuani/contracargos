<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SanbornsTotalCobros extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sanborns_total_cobros', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('cuenta');
            $table->bigInteger('veces_cobrado');
            $table->bigInteger('total_cobros');
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
