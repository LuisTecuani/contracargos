<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class MediakeyTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_non_authenticated_user_cant_browse_mediakey()
    {
        $response = $this->get('/mediakey');

        $response->assertDontSee('Mediakey');

    }

    /** @test */
    public function an_authenticated_user_can_browse_mediakey()
    {
        $this->be($user = factory('App\User')->create());

        $this->get('/mediakey')->assertSee('Mediakey');
    }

    /** @test */
    public function a_non_authenticated_user_cant_see_nav_links_from_home()
    {
        $response = $this->get('/');

        $response->assertDontSee('mediakey');
    }


}
