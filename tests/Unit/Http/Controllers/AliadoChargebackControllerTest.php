<?php

namespace Tests\Unit\Http\Controllers;

use App\AliadoUser;
use App\Http\Controllers\AliadoChargebackController;
use App\Repsaliado;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AliadoChargebackControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admins_can_browse_to_the_index_page()
    {
        $this->signIn();

        $this->get('/aliado/chargeback')
            ->assertOk()
            ->assertSessionHasNoErrors()
            ->assertViewIs('aliado.chargeback.index');
    }

    /** @test */
    public function method_store_persist_data_on_contracargos_table()
    {
        $this->signIn();
        $charge1 = factory(Repsaliado::class)->create();
        $charge2 = factory(Repsaliado::class)->create();

        $this->post('/aliado/chargeback/store', [
            'autorizaciones' => "$charge1->autorizacion,$charge1->terminacion\r\n$charge2->autorizacion,$charge2->terminacion",
        ]);

        $this->assertDatabaseHas('contracargos_aliado', [
            'tarjeta' => $charge1->terminacion,
            'autorizacion' => $charge1->autorizacion,
        ]);
        $this->assertDatabaseHas('contracargos_aliado', [
            'tarjeta' => $charge2->terminacion,
            'autorizacion' => $charge2->autorizacion,
        ]);
    }

    /** test */
    public function method_show_displays_emails_from_last_entry()
    {
        $this->signIn();
        $this->withoutExceptionHandling();
        $user = factory(AliadoUser::class)->create();
        $charge = factory(Repsaliado::class)->create([
            'user_id' => $user->id,
        ]);
        $chargeback = new AliadoChargebackController();

    }

}
