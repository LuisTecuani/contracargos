<?php

namespace Tests\Unit\Http\Controllers;

use App\MediakeyUser;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MediakeyBlacklistControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admins_can_browse_to_the_index_page()
    {
        $this->signIn();

        $this->get('/mediakey/blacklist')
            ->assertOk()
            ->assertSessionHasNoErrors()
            ->assertViewIs('mediakey.blacklist.index');
    }

    /** @test */
    public function admin_can_persist_emails_on_mediakey_blacklist()
    {
        $this->signIn();
        $fakeUser = factory(MediakeyUser::class)->create([
            'email' => 'verapatino@hotmail.com'
        ]);

        $this->post('/mediakey/blacklist/store', [
            'emails' => "dilaurys95@hotmail.com\r\nverapatino@hotmail.com",
        ]);

        $this->assertDatabaseHas('mediakey_blacklist', [
            'email' => 'dilaurys95@hotmail.com',
            'user_id' => null,
        ]);
        $this->assertDatabaseHas('mediakey_blacklist', [
            'email' => 'verapatino@hotmail.com',
            'user_id' => $fakeUser->id,
        ]);
    }
}
