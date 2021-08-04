<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUrbanoBillingUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('urbano_billing_users', function (Blueprint $table) {
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
        Schema::dropIfExists('urbano_billing_users');
    }
}
