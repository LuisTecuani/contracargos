<?php

namespace Tests\Unit\Http\Controllers;

use App\CellersUser;
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
}
