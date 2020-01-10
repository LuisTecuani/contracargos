<?php

namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\UploadedFile as Upfile;

class MediakeyResponsesControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admins_can_browse_to_the_index_page()
    {
        $this->signIn();

        $this->get('/mediakey/responses')
            ->assertOk()
            ->assertSessionHasNoErrors()
            ->assertViewIs('mediakey.responses.index');
    }

    /** @test */
    public function storeReps_method_persist_billing_info_on_repsmediakey_model()
    {
        $this->signIn();
        $file = UploadedFile::createFromBase(
            (new UpFile(
                __DIR__ . '/files/CE201912111710048010897.rep',
                'CE201912111710048010897.rep',
                'text/plain',
                20416,
                null,
                true
            ))
        );

        $this->post('/mediakey/responses/storeReps', [
            'files' => [$file],
        ]);

        $this->assertDatabaseHas('repsmediakey', [
            'user_id' => '288781',
            'autorizacion' => '062932'
        ]);
        $this->assertDatabaseHas('repsmediakey', [
            'user_id' => '234993',
            'motivo_rechazo' => 'Rechazada'
        ]);
    }

    /** @test */
    public function storePdf_method_persist_billing_info_on_RespuestasBanorteMediakey_model()
    {
        $this->signIn();
        $this->withoutExceptionHandling();
        $file = UploadedFile::createFromBase(
            (new UpFile(
                __DIR__ . '/files/aliado-banorte-2019-11-08-1_Respuestas.pdf',
                'aliado-banorte-2019-11-08-1_Respuestas.pdf',
                'application/pdf',
                20416,
                null,
                true
            ))
        );

        $this->post('/mediakey/responses/storePdf', [
            'files' => [$file],
        ]);

        $this->assertDatabaseHas('respuestas_banorte_mediakey', [
            'user_id' => '301919',
            'autorizacion' => '075319'
        ]);
        $this->assertDatabaseHas('respuestas_banorte_mediakey', [
            'user_id' => '301922',
            'estatus' => 'Declinada'
        ]);
    }

}
