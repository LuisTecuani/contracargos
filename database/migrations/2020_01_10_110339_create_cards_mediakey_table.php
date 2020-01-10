<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardsMediakeyTable extends Migration
{
    /**
     * The database schema.
     *
     * @var Schema
     */
    protected $schema;

    /**
     * Create a new migration instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->schema = Schema::connection(config('database.mediakey_connection'));
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! $this->schema->hasTable('cards')) {
            $this->schema->create('cards', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->unsigned();
                $table->string('name');
                $table->string('number');
                $table->string('cvc')->nullable();
                $table->string('month')->nullable();
                $table->string('year')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
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
