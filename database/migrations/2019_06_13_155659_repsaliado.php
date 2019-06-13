<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Repsaliado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repsaliado', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('tarjeta');
            $table->integer('terminacion');
            $table->integer('user_id');
            $table->date('fecha');
            $table->string('autorizacion');
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
        //
    }
}
