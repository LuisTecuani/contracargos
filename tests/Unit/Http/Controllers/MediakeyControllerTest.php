<?php

namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MediakeyControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admins_can_browse_to_the_index_page()
    {
        $this->signIn();

        $this->get('/mediakey')
            ->assertOk()
            ->assertSessionHasNoErrors()
            ->assertViewIs('mediakey.index');
    }

}
