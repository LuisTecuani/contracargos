<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTdcThxTable extends Migration
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
        $this->schema = Schema::connection(config('database.thx_connection'));
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! $this->schema->hasTable('tdc')) {
            $this->schema->create('tdc', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->unsigned();
                $table->string('number');
                $table->string('exp_date')->nullable();
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
