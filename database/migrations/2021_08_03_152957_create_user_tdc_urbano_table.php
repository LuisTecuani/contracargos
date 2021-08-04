<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTdcUrbanoTable extends Migration
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
        $this->schema = Schema::connection(config('database.urbano_connection'));
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
                $table->string('name');
                $table->integer('user_id')->unsigned();
                $table->string('number');
                $table->tinyInteger('exp_month')->nullable();
                $table->smallInteger('exp_year')->nullable();
                $table->string('csv')->nullable();
                $table->string('provider_id')->nullable();
                $table->timestamps();
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
