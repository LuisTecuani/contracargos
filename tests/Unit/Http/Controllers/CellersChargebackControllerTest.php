<?php

namespace Tests\Unit\Http\Controllers;

use App\CellersUser;
use App\Http\Controllers\CellersChargebackController;
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

    /** test */
    public function method_show_displays_emails_from_last_entry()
    {
        $this->signIn();
        $user = factory(CellersUser::class)->create();
        $charge = factory(Repscellers::class)->create([
            'user_id' => $user->id,
        ]);
        $chargeback = new CellersChargebackController();

    }

}
