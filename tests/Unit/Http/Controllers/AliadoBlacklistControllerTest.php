<?php

namespace Tests\Unit\Http\Controllers;

use App\AliadoUser;
use App\ContracargosAliado;
use App\ContracargosAliadoBanorte;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AliadoBlacklistControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admins_can_browse_to_the_index_page()
    {
        $this->signIn();

        $this->get('/aliado/blacklist')
            ->assertOk()
            ->assertSessionHasNoErrors()
            ->assertViewIs('aliado.blacklist.index');
    }

    /** @test */
    public function store_method_persist_emails_on_aliado_blacklist()
    {
        $this->signIn();
        $fakeUser = factory(AliadoUser::class)->create([
            'email' => 'verapatino@hotmail.com'
        ]);

        $this->post('/aliado/blacklist/store', [
            'emails' => "dilaurys95@hotmail.com\r\nverapatino@hotmail.com",
        ]);

        $this->assertDatabaseHas('aliado_blacklist', [
            'email' => 'dilaurys95@hotmail.com',
            'user_id' => null,
        ]);
        $this->assertDatabaseHas('aliado_blacklist', [
            'email' => 'verapatino@hotmail.com',
            'user_id' => $fakeUser->id,
        ]);
    }

    /** @test */
    public function storeChargedback_method_persist_users_added_today_to_aliado_chargebacks_tables()
    {
        $this->signIn();
        $this->withoutExceptionHandling();
        $noBanorte = factory(ContracargosAliado::class)->create();
        $noBanortePrevoiuslyCreated = factory(ContracargosAliado::class)->create([
            'created_at' => '2020-05-11 06:09:27'
        ]);
        $banorte = factory(ContracargosAliadoBanorte::class)->create();
        $banortePrevoiuslyCreated = factory(ContracargosAliadoBanorte::class)->create([
            'created_at' => '2020-05-11 06:09:27'
        ]);

        $this->post('/aliado/blacklist/storeChargedback');

        $this->assertDatabaseHas('aliado_blacklist', [
            'email' => $noBanorte->email,
            'user_id' => $noBanorte->user_id,
        ]);
        $this->assertDatabaseHas('aliado_blacklist', [
            'email' => $banorte->email,
            'user_id' => $banorte->user_id,
        ]);
        $this->assertDatabaseMissing('aliado_blacklist', [
            'user_id' => $banortePrevoiuslyCreated->user_id,
        ]);
        $this->assertDatabaseMissing('aliado_blacklist', [
            'user_id' => $noBanortePrevoiuslyCreated->user_id,
        ]);
    }
}
