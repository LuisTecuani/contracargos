<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUrbanoAffinitasHistoricTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('urbano_affinitas_historics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Fecha')->nullable();
            $table->string('Hora')->nullable();
            $table->string('Corporativo')->nullable();
            $table->string('Comercio')->nullable();
            $table->string('Sucursal')->nullable();
            $table->string('AfiliaciOn')->nullable();
            $table->string('OperaciOn')->nullable();
            $table->string('Referencia')->nullable();
            $table->string('Id_TPV')->nullable();
            $table->string('num_Serie')->nullable();
            $table->string('TransacciOn')->nullable();
            $table->string('Modo_Entrada')->nullable();
            $table->string('Monto')->nullable();
            $table->string('Monto_adicional')->nullable();
            $table->string('Cash_back')->nullable();
            $table->string('Monto_total')->nullable();
            $table->string('Mesero')->nullable();
            $table->string('Moneda')->nullable();
            $table->string('ISO')->nullable();
            $table->string('ARQC')->nullable();
            $table->string('Trace_Number')->nullable();
            $table->string('Respuesta')->nullable();
            $table->string('AutorizaciOn')->nullable();
            $table->string('Resp')->nullable();
            $table->string('Numero_de_Tarjeta')->nullable();
            $table->string('Banco_Emisor')->nullable();
            $table->string('Marca')->nullable();
            $table->string('Naturaleza')->nullable();
            $table->string('TC')->nullable();
            $table->string('Q6')->nullable();
            $table->string('Meses')->nullable();
            $table->string('Plan')->nullable();
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
        Schema::dropIfExists('urbano_affinitas_historics');
    }
}
