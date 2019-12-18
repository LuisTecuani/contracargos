<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCellersUsersTable extends Migration
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
        $this->schema = Schema::connection(config('database.cellers_connection'));
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! $this->schema->hasTable('users')) {
            $this->schema->create('users', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('celex_id')->unique()->nullable();
                $table->string('type')->nullable();
                $table->string('customer_id')->nullable();
                $table->string('name')->nullable();
                $table->string('email')->unique();
                $table->string('phone')->unique()->nullable();
                $table->string('location')->nullable();
                $table->string('birthday')->nullable();
                $table->string('password', 60)->nullable();
                $table->string('one_time_access_password', 10)->nullable();
                $table->timestamp('first_login')->nullable();
                $table->timestamp('last_login')->nullable();
                $table->integer('cancelled_by')->unsigned()->nullable();
                $table->rememberToken()->nullable();
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
