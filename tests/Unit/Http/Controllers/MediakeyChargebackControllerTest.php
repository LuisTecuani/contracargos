<?php

namespace Tests\Unit\Http\Controllers;

use App\ContracargosMediakey;
use App\ContracargosMediakeyBanorte;
use App\Repsmediakey;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MediakeyChargebackControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admins_can_browse_to_the_index_page()
    {
        $this->signIn();

        $this->get('/mediakey/chargeback')
            ->assertOk()
            ->assertSessionHasNoErrors()
            ->assertViewIs('mediakey.chargeback.index');
    }

    /** @test */
    public function method_store_persist_data_on_contracargos_table()
    {
        $this->signIn();
        $charge1 = factory(Repsmediakey::class)->create();
        $charge2 = factory(Repsmediakey::class)->create();

        $this->post('/mediakey/chargeback/store', [
            'autorizaciones' => "$charge1->autorizacion,$charge1->terminacion\r\n$charge2->autorizacion,$charge2->terminacion",
        ]);

        $this->assertDatabaseHas('contracargos_mediakey', [
            'tarjeta' => $charge1->terminacion,
            'autorizacion' => $charge1->autorizacion,
        ]);
        $this->assertDatabaseHas('contracargos_mediakey', [
            'tarjeta' => $charge2->terminacion,
            'autorizacion' => $charge2->autorizacion,
        ]);
    }

    /** @test */
    public function show_method_displays_emails_from_users_searched_today()
    {
        $this->signIn();
        $chargebackProsaToday = factory(ContracargosMediakey::class)->create();
        $chargebackBanorteToday = factory(ContracargosMediakeyBanorte::class)->create();
        $chargebackProsaPast = factory(ContracargosMediakey::class)->create([
            'created_at' => '2020-01-15 11:57:34'
        ]);
        $chargebackBanortePast = factory(ContracargosMediakeyBanorte::class)->create([
            'created_at' => '2020-01-15 11:57:34'
        ]);

        $this->get('/mediakey/chargeback/show')
            ->assertViewIs('mediakey.chargeback.last')
            ->assertSee($chargebackProsaToday->email)
            ->assertSee($chargebackBanorteToday->email)
            ->assertDontSee($chargebackProsaPast->email)
            ->assertDontSee($chargebackBanortePast->email);
    }
}
