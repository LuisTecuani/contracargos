<?php

namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AliadoControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admins_can_browse_to_the_index_page()
    {
        $this->signIn();

        $this->get('/aliado')
            ->assertOk()
            ->assertSessionHasNoErrors()
            ->assertViewIs('aliado.index');
    }

    /** @test */
    public function admin_can_persist_emails_on_aliado_blacklist()
    {
        $this->signIn();
//falta terminar test
        $this->post('/aliado/blacklist/insert', [
            'emails' => ['dilaurys95@hotmail.com',
                'verapatino@hotmail.com',
                'davidjungo@gmail.com',
                'dmagana@uas.edu.mx',],
        ]);

        $this->assertDatabaseHas();
    }
}
