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
        $this->withoutExceptionHandling();
        $user1 = factory(AliadoUser::class)->create();
        $charge1 = factory(Repsaliado::class)->create([
            'user_id' => $user1->id,
        ]);
        $user2 = factory(AliadoUser::class)->create();
        $charge2 = factory(Repsaliado::class)->create([
            'user_id' => $user2->id,
        ]);

        $this->post('/aliado/chargeback/store', [
            'autorizaciones' => "$charge1->autorizacion,$charge1->tarjeta\r\n$charge2->autorizacion,$charge2->tarjeta",
        ]);

        $this->assertDatabaseHas('contracargos_aliado', [
            'email' => $user1->email,
            'user_id' => $user1->id,
            'autorizacion' => $charge1->autorizacion,
        ]);
        $this->assertDatabaseHas('aliado_blacklist', [
            'email' => $user2->email,
            'user_id' => $user2->id,
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
