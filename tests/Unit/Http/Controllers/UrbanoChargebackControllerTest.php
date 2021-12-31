<?php

namespace Tests\Unit\Http\Controllers;

use App\ContracargosUrbano;
use App\Repsurbano;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UrbanoChargebackControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admins_can_browse_to_the_index_page()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        $this->get('/urbano/chargeback')
            ->assertOk()
            ->assertSessionHasNoErrors()
            ->assertViewIs('urbano.chargeback.index');
    }

    /** @test */
    public function method_store_persist_data_on_contracargos_table()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $charge1 = factory(Repsurbano::class)->create();
        $charge2 = factory(Repsurbano::class)->create();

        $response = $this->post('/urbano/chargeback/store', [
            'autorizaciones' => "$charge1->autorizacion,$charge1->terminacion\r\n$charge2->autorizacion,$charge2->terminacion",
        ]);

        $this->assertDatabaseHas('contracargos_urbano', [
            'tarjeta' => $charge1->terminacion,
            'autorizacion' => $charge1->autorizacion,
        ]);
        $this->assertDatabaseHas('contracargos_urbano', [
            'tarjeta' => $charge2->terminacion,
            'autorizacion' => $charge2->autorizacion,
        ]);
    }

    /** @test */
    public function show_method_displays_ids_from_users_searched_today()
    {
        $this->signIn();
        $chargebackProsaToday = factory(ContracargosUrbano::class)->create();
        $chargebackProsaPast = factory(ContracargosUrbano::class)->create([
            'created_at' => '2020-01-15 11:57:34'
        ]);


        $this->get('/urbano/chargeback/show')
            ->assertViewIs('urbano.chargeback.last')
            ->assertSee($chargebackProsaToday->user_id)
            ->assertDontSee($chargebackProsaPast->user_id);
    }
}
