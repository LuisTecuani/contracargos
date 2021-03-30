<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThxUsersTable extends Migration
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
        if (! $this->schema->hasTable('users')) {
            $this->schema->create('users', function (Blueprint $table) {
                $table->increments('id');
                $table->string('customer_id')->nullable();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('phone')->nullable();
                $table->string('location')->nullable();
                $table->string('birthday')->nullable();
                $table->string('password', 60)->nullable();
                $table->string('provisional_password', 60)->nullable();
                $table->string('activation_token', 100)->nullable();
                $table->string('type')->nullable();
                $table->timestamp('first_login')->nullable();
                $table->timestamp('last_login')->nullable();
                $table->integer('deleted_by')->unsigned()->nullable();
                $table->string('auto_login_token')->nullable();
                $table->rememberToken();
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
