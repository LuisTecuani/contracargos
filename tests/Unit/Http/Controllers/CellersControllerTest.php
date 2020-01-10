<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CellersControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admins_can_browse_to_the_index_page()
    {
        $this->signIn();

        $this->get('/cellers')
            ->assertOk()
            ->assertSessionHasNoErrors()
            ->assertViewIs('cellers.index');
    }
}
