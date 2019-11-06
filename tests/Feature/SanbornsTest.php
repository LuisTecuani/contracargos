<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class SanbornsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_browse_sanborns()
    {
        $this->withoutExceptionHandling();

        $response = $this->get('/sanborns');

        $response->assertSee('Sanborns');
    }

    /** @test */
    public function a_user_can_store_data_on_bonificacion_sanborns_table()
    {

        $this->get('/mediakey')->assertSee('Mediakey');
    }

    /** @test */
    public function a_non_()
    {
        $response = $this->get('/');

        $response->assertDontSee('mediakey');
    }


}
