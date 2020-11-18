<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUrbanoAffinitasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('urbano_affinitas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("CARD_NUMBER")->nullable();
            $table->string("EXPIRATION_MONTH")->nullable();
            $table->string("EXPIRATION_YEAR")->nullable();
            $table->string("CARD_HOLDERNAME")->nullable();
            $table->string("FIRST_NAME")->nullable();
            $table->string("LAST_NAME")->nullable();
            $table->string("EMAIL")->nullable();
            $table->string("ADRESS_CITY")->nullable();
            $table->string("COUNTRY")->nullable();
            $table->string("IP")->nullable();
            $table->string("AMOUNT")->nullable();
            $table->string("CURRENCY")->nullable();
            $table->string("CHARGE_DAY")->nullable();
            $table->string("PERIODICITY")->nullable();
            $table->string("CHARGE_NUMBER")->nullable();
            $table->string("START_DATE")->nullable();
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
        Schema::dropIfExists('urbano_affinitas');
    }
}
