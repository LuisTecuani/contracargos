<?php

namespace Tests\Unit\Http\Controllers;

use App\ContracargosAliadoBanorte;
use App\RespuestasBanorteAliado;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AliadoBanorteChargebackControllerTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    public function method_store_persist_data_on_contracargos_table()
    {
        $this->signIn();
        $charge1 = factory(RespuestasBanorteAliado::class)->create([
            'terminacion' => '0552',
            'autorizacion' => '671712',
        ]);
        $charge2 = factory(RespuestasBanorteAliado::class)->create([
            'terminacion' => '4395',
            'autorizacion' => '460903',
        ]);

        $this->post('/aliado/banorte/chargeback/store', [
            'text' => "  FECHA                  CUENTA											\r\n
•   340       •09/12/2019 • 4037xxxxxxxx0552											\r\n
, IM PORTE              AUTORIZACION											\r\n
, $79.00                    ,671712										\r\n
$79.00			•460903								\r\n
•   350       •04/12/2019 • 5200xxxxxxxx4395	\r\n
•    351        • 11/11/2019  • 5204xxxxxxxx5290               $79.00                      '027136	",
            'chargeback_date' => '2020-01-08',
        ]);

        $this->assertDatabaseHas('contracargos_aliado_banorte', [
            'tarjeta' => $charge1->terminacion,
            'autorizacion' => $charge1->autorizacion,
        ]);
        $this->assertDatabaseHas('contracargos_aliado_banorte', [
            'tarjeta' => $charge2->terminacion,
            'autorizacion' => $charge2->autorizacion,
        ]);
    }

    /** @test */
    public function store_method_dont_persist_record_if_is_recorded_previously()
    {
        $this->signIn();
        $this->withoutExceptionHandling();
        $persistedChargeback = factory(ContracargosAliadoBanorte::class)->create([
            'fecha_contracargo' => '2019-02-10'
        ]);

        $this->post('/aliado/banorte/chargeback/store', [
            'text' => "  $persistedChargeback->fecha_consumo.FE 2345xxxxxxxx$persistedChargeback->tarjeta,
            C $persistedChargeback->autorizacion ",
            'chargeback_date' => '2020-01-08',
        ]);

        $this->assertDatabaseMissing('contracargos_aliado_banorte', ['fecha_contracargo' => '2020-01-08']);
    }
}
