<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\UploadedFile as Upfile;

class UrbanoResponsesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admins_can_browse_to_the_index_page()
    {
        $this->signIn();

        $this->get('/urbano/responses')
            ->assertOk()
            ->assertSessionHasNoErrors()
            ->assertViewIs('urbano.responses.index');
    }

    /** @test */
    public function storeReps_method_persist_billing_info_on_repsurbano_model()
    {
        $this->signIn();
        $file = UploadedFile::createFromBase(
            (new UpFile(
                __DIR__ . '/files/CE202106161334088444750.rep',
                'CE202106161334088444750.rep',
                'text/plain',
                20416,
                0,
                true
            ))
        );

        $this->post('/urbano/responses/storeReps', [
            'files' => [$file],
        ]);

        $this->assertDatabaseHas('repsurbano', [
            'user_id' => '14169',
            'autorizacion' => '045042'
        ]);
        $this->assertDatabaseHas('repsurbano', [
            'user_id' => '21738',
            'detalle_mensaje' => 'Rechazada'
        ]);
    }
}
