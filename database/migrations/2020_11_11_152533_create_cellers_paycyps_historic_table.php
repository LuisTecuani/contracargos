<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCellersPaycypsHistoricTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cellers_paycyps_historics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Folio')->nullable();
            $table->string('Fecha_Operacion')->nullable();
            $table->string('Fecha_Liq')->nullable();
            $table->string('Tarjeta')->nullable();
            $table->string('Banco')->nullable();
            $table->string('Producto')->nullable();
            $table->string('Importe_Venta')->nullable();
            $table->string('Importe_Original')->nullable();
            $table->string('Divisa')->nullable();
            $table->string('Comision_Cobrada')->nullable();
            $table->string('Costo')->nullable();
            $table->string('Autorizacion')->nullable();
            $table->string('Tipo_Operacion')->nullable();
            $table->string('Tipo_Bin')->nullable();
            $table->string('Terminal')->nullable();
            $table->string('Comercio')->nullable();
            $table->string('Ref2')->nullable();
            $table->string('Ref3')->nullable();
            $table->string('Ref4')->nullable();
            $table->string('Ticket')->nullable();
            $table->string('Codigo_Respuesta')->nullable();
            $table->string('Descripcion')->nullable();
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
        Schema::dropIfExists('cellers_paycyps_historics');
    }
}
