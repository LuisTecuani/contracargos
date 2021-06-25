<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThxPaycypsBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('thx_paycyps_bills', function (Blueprint $table) {
            $table->bigIncrements('id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('tdc')->nullable();
            $table->integer('amount')->nullable();
            $table->integer('bill_day')->nullable();
            $table->string('file_name')->nullable();
            $table->string('paycyps_id')->nullable();
            $table->date('deleted_at')->nullable();
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
        Schema::dropIfExists('thx_paycyps_bills');
    }
}
