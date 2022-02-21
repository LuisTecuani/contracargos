<?php

namespace Tests\Unit\Http\Controllers;

use App\RespuestasBanorteUrbano;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UrbanoBanorteChargebackControllerTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    public function method_store_persist_data_on_contracargos_table()
    {
       // $this->withExceptionHandling();
        $this->signIn();
        $charge1 = factory(RespuestasBanorteUrbano::class)->create([
            'terminacion' => '3552',
            'autorizacion' => '671712',
        ]);
        $charge2 = factory(RespuestasBanorteUrbano::class)->create([
            'terminacion' => '4395',
            'autorizacion' => '460903',
        ]);

        $this->post('/urbano/banorte/chargeback/store', [
            'text' => "  FECHA                  CUENTA											\r\n
•   340       •09/12/2019 • 4037xxxxxxxx3552											\r\n
, IM PORTE              AUTORIZACION											\r\n
, $79.00                    ,671712										\r\n
$79.00			•460903								\r\n
•   350       •04/12/2019 • 5200xxxxxxxx4395	\r\n
•    351        • 11/11/2019  • 5204xxxxxxxx5290               $79.00                      '027136	",
            'chargeback_date' => '2020-01-08',
        ]);

        $this->assertDatabaseHas('contracargos_urbano', [
            'tarjeta' => $charge1->terminacion,
            'autorizacion' => $charge1->autorizacion,
            'chargeback_date' => '2020-01-08',
        ]);
        $this->assertDatabaseHas('contracargos_urbano', [
            'tarjeta' => $charge2->terminacion,
            'autorizacion' => $charge2->autorizacion,
            'chargeback_date' => '2020-01-08',
        ]);
    }
}
