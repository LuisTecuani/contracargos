<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MediakeyBillingUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mediakey_billing_users', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->string('procedence');
            $table->string('exp_date')->nullable();
            $table->bigInteger('number');
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
        Schema::drop('mediakey_billing_users');
    }
}
