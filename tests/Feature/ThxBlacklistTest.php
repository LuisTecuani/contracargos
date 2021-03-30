<?php

namespace Tests\Feature;

use App\ThxUser;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThxBlacklistTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admins_can_browse_to_the_index_page()
    {
        $this->signIn();

        $this->get('/thx/blacklist')
            ->assertOk()
            ->assertSessionHasNoErrors()
            ->assertViewIs('thx.blacklist.index');
    }

    /** @test */
    public function store_method_persist_emails_on_thx_blacklist()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $fakeUser = factory(ThxUser::class)->create([
            'email' => 'verapatino@hotmail.com'
        ]);

        $this->post('/thx/blacklist/store', [
            'emails' => "dilaurys95@hotmail.com\r\nverapatino@hotmail.com",
        ]);

        $this->assertDatabaseHas('thx_blacklist', [
            'email' => 'dilaurys95@hotmail.com',
            'user_id' => null,
        ]);
        $this->assertDatabaseHas('thx_blacklist', [
            'email' => 'verapatino@hotmail.com',
            'user_id' => $fakeUser->id,
        ]);
    }
}
