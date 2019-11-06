<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBonificacionSanbornsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bonificacion_sanborns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('row_in_file');
            $table->unsignedInteger('sanborns_id');
            $table->string('email');
            $table->integer('cantidad_cargos');
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
        Schema::dropIfExists('bonificacion_sanborns');
    }
}
