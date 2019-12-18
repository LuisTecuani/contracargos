<?php

namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AliadoChargebackControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admins_can_browse_to_the_index_page()
    {
        $this->signIn();
        $this->withoutExceptionHandling();

        $this->get('/aliado/chargeback')
            ->assertOk()
            ->assertSessionHasNoErrors()
            ->assertViewIs('aliado.chargeback.index');
    }

}
