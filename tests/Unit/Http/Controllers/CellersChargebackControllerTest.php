<?php

namespace Tests\Unit\Http\Controllers;

use App\ContracargosCellers;
use App\ContracargosCellersBanorte;
use App\Repscellers;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CellersChargebackControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admins_can_browse_to_the_index_page()
    {
        $this->signIn();

        $this->get('/cellers/chargeback')
            ->assertOk()
            ->assertSessionHasNoErrors()
            ->assertViewIs('cellers.chargeback.index');
    }

    /** @test */
    public function method_store_persist_data_on_contracargos_table()
    {
        $this->signIn();
        $charge1 = factory(Repscellers::class)->create();
        $charge2 = factory(Repscellers::class)->create();

        $this->post('/cellers/chargeback/store', [
            'autorizaciones' => "$charge1->autorizacion,$charge1->terminacion\r\n$charge2->autorizacion,$charge2->terminacion",
        ]);

        $this->assertDatabaseHas('contracargos_cellers', [
            'tarjeta' => $charge1->terminacion,
            'autorizacion' => $charge1->autorizacion,
        ]);
        $this->assertDatabaseHas('contracargos_cellers', [
            'tarjeta' => $charge2->terminacion,
            'autorizacion' => $charge2->autorizacion,
        ]);
    }

    /** @test */
    public function show_method_displays_ids_from_users_searched_today()
    {
        $this->signIn();
        $this->withoutExceptionHandling();
        $chargebackProsaToday = factory(ContracargosCellers::class)->create();
        $chargebackBanorteToday = factory(ContracargosCellersBanorte::class)->create();
        $chargebackProsaPast = factory(ContracargosCellers::class)->create([
            'created_at' => '2020-01-15 11:57:34'
        ]);
        $chargebackBanortePast = factory(ContracargosCellersBanorte::class)->create([
            'created_at' => '2020-01-15 11:57:34'
        ]);

        $this->get('/cellers/chargeback/show')
            ->assertViewIs('cellers.chargeback.last')
            ->assertSee($chargebackProsaToday->user_id)
            ->assertSee($chargebackBanorteToday->user_id)
            ->assertDontSee($chargebackProsaPast->user_id)
            ->assertDontSee($chargebackBanortePast->user_id);
    }
}
