<?php

namespace Tests\Unit\Http\Controllers;

use App\AliadoUser;
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
}
