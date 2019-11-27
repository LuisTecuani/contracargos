<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AliadoControllerTest extends TestCase
{
    /** @test */
    public function admin_can_persist_emails_on_aliado_blacklist()
    {
        $this->signIn();
        $this->withoutExceptionHandling();
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
