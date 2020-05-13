<?php

namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FindUserControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admins_can_browse_to_the_index_page()
    {
        $this->signIn();
        $this->withoutExceptionHandling();

        $this->get('/find_user')
            ->assertOk()
            ->assertSessionHasNoErrors()
            ->assertViewIs('find_user.index');
    }
}
