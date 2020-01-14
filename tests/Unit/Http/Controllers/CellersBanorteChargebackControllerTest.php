<?php

namespace Tests\Unit\Http\Controllers;

use App\RespuestasBanorteCellers;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CellersBanorteChargebackControllerTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    public function method_store_persist_data_on_contracargos_table()
    {
        $this->signIn();
        $charge1 = factory(RespuestasBanorteCellers::class)->create([
            'terminacion' => '0552',
            'autorizacion' => '671712',
        ]);
        $charge2 = factory(RespuestasBanorteCellers::class)->create([
            'terminacion' => '4395',
            'autorizacion' => '460903',
        ]);

        $this->post('/cellers/banorte/chargeback/store', [
            'text' => "  FECHA                  CUENTA											\r\n
•   340       •09/12/2019 • 4037xxxxxxxx0552											\r\n
, IM PORTE              AUTORIZACION											\r\n
, $79.00                    ,671712										\r\n
$79.00			•460903								\r\n
•   350       •04/12/2019 • 5200xxxxxxxx4395	\r\n
•    351        • 11/11/2019  • 5204xxxxxxxx5290               $79.00                      '027136	",
            'chargeback_date' => '2020-01-08',
        ]);

        $this->assertDatabaseHas('contracargos_cellers_banorte', [
            'tarjeta' => $charge1->terminacion,
            'autorizacion' => $charge1->autorizacion,
        ]);
        $this->assertDatabaseHas('contracargos_cellers_banorte', [
            'tarjeta' => $charge2->terminacion,
            'autorizacion' => $charge2->autorizacion,
        ]);
    }
}
