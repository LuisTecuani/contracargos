<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContracargosUrbanoAffinitasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracargos_urbano_affinitas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('email');
            $table->string('tdc');
            $table->string('chargeback_date');
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
        Schema::dropIfExists('contracargos_urbano_affinitas');
    }
}
