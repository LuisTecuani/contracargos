<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCellersCancellations extends Migration
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
        if (! $this->schema->hasTable('cancellations')) {
            $this->schema->create('cancellations', function (Blueprint $table) {
                $table->increments('folios');
                $table->unsignedInteger('user_id');
                $table->unsignedInteger('reason_id')->nullable();
                $table->text('notes')->nullable();
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
        Schema::dropIfExists('cellers_cancellations');
    }
}
