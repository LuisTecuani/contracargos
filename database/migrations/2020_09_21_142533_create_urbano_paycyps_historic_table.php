<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUrbanoPaycypsHistoricTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('urbano_paycyps_historic', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Folio')->nullable();
            $table->string('Fecha_Operación')->nullable();
            $table->string('Fecha_Liq')->nullable();
            $table->string('Tarjeta')->nullable();
            $table->string('Banco')->nullable();
            $table->string('Producto')->nullable();
            $table->string('Importe_Venta')->nullable();
            $table->string('Importe_Original')->nullable();
            $table->string('Divisa')->nullable();
            $table->string('Comisión_Cobrada')->nullable();
            $table->string('Costo')->nullable();
            $table->string('Autorización')->nullable();
            $table->string('Tipo_Operación')->nullable();
            $table->string('Tipo_Bin')->nullable();
            $table->string('Terminal')->nullable();
            $table->string('Comercio')->nullable();
            $table->string('Ref2')->nullable();
            $table->string('Ref3')->nullable();
            $table->string('Ref4')->nullable();
            $table->string('Ticket')->nullable();
            $table->string('Código_Respuesta')->nullable();
            $table->string('Descripción')->nullable();
            $table->string('file_name');
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
        Schema::dropIfExists('urbano_paycyps_historic');
    }
}
