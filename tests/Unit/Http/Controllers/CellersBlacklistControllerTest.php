<?php

namespace Tests\Unit\Http\Controllers;

use App\CellersUser;
use App\ContracargosCellers;
use App\ContracargosCellersBanorte;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CellersBlacklistControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admins_can_browse_to_the_index_page()
    {
        $this->signIn();
        $this->withoutExceptionHandling();

        $this->get('/cellers/blacklist')
            ->assertOk()
            ->assertSessionHasNoErrors()
            ->assertViewIs('cellers.blacklist.index');
    }

    /** @test */
    public function admin_can_persist_emails_on_cellers_blacklist()
    {
        $this->signIn();
        $this->withoutExceptionHandling();
        $fakeUser = factory(CellersUser::class)->create([
            'email' => 'verapatino@hotmail.com'
        ]);

        $this->post('/cellers/blacklist/store', [
            'emails' => "dilaurys95@hotmail.com\r\nverapatino@hotmail.com",
        ]);

        $this->assertDatabaseHas('cellers_blacklist', [
            'email' => 'dilaurys95@hotmail.com',
            'user_id' => null,
        ]);
        $this->assertDatabaseHas('cellers_blacklist', [
            'email' => 'verapatino@hotmail.com',
            'user_id' => $fakeUser->id,
        ]);
    }

    /** @test */
    public function storeChargedback_method_persist_users_added_today_to_cellers_chargebacks_tables()
    {
        $this->signIn();
        $this->withoutExceptionHandling();
        $noBanorte = factory(ContracargosCellers::class)->create();
        $noBanortePrevoiuslyCreated = factory(ContracargosCellers::class)->create([
            'created_at' => '2020-05-11 06:09:27'
        ]);
        $banorte = factory(ContracargosCellersBanorte::class)->create();
        $banortePrevoiuslyCreated = factory(ContracargosCellersBanorte::class)->create([
            'created_at' => '2020-05-11 06:09:27'
        ]);

        $this->post('/cellers/blacklist/storeChargedback');

        $this->assertDatabaseHas('cellers_blacklist', [
            'email' => $noBanorte->email,
            'user_id' => $noBanorte->user_id,
        ]);
        $this->assertDatabaseHas('cellers_blacklist', [
            'email' => $banorte->email,
            'user_id' => $banorte->user_id,
        ]);
        $this->assertDatabaseMissing('cellers_blacklist', [
            'user_id' => $banortePrevoiuslyCreated->user_id,
        ]);
        $this->assertDatabaseMissing('cellers_blacklist', [
            'user_id' => $noBanortePrevoiuslyCreated->user_id,
        ]);
    }
}
