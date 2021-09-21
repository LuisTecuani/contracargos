<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewHomeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admins_can_see_header_links()
    {
        $this->signIn();

        $this->get('/')
            ->assertOk()
            ->assertSessionHasNoErrors()
            ->assertViewIs('welcome')
            ->assertSee(route('aliado.index'))
            ->assertSee(route('cellers.index'))
            ->assertSee(route('mediakey.index'))
            ->assertSee(route('sanborns.index'))
            ->assertSee(route('thx.index'))
            ->assertSee(route('urbano.index'))
            ->assertSee(route('tools.index'));
    }
}
