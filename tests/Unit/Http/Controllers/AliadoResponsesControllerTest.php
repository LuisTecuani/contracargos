<?php

namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\UploadedFile as Upfile;

class AliadoResponsesControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admins_can_browse_to_the_index_page()
    {
        $this->signIn();

        $this->get('/aliado/responses')
            ->assertOk()
            ->assertSessionHasNoErrors()
            ->assertViewIs('aliado.responses.index');
    }

    /** @test */
    public function storeReps_method_persist_billing_info_on_repsaliado_model()
    {
        $this->signIn();
        $file = UploadedFile::createFromBase(
            (new UpFile(
                __DIR__ . '/Files/CE201912111710048010897.rep',
                'CE201912111710048010897.rep',
                'text/plain',
                20416,
                0,
                true
            ))
        );

        $this->post('/aliado/responses/storeReps', [
            'files' => [$file],
        ]);

        $this->assertDatabaseHas('repsaliado', [
            'user_id' => '288781',
            'autorizacion' => '062932'
        ]);
        $this->assertDatabaseHas('repsaliado', [
            'user_id' => '234993',
            'detalle_mensaje' => 'Rechazada'
        ]);
    }

    /** @test */
    public function storePdf_method_persist_billing_info_on_RespuestasBanorteAliado_model()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $file = UploadedFile::createFromBase(
            (new UpFile(
                __DIR__ . '/Files/aliado-banorte-2019-11-08-1_Respuestas.pdf',
                'aliado-banorte-2019-11-08-1_Respuestas.pdf',
                'application/pdf',
                20416,
                0,
                true
            ))
        );

        $this->post('/aliado/responses/storePdf', [
            'files' => [$file],
        ]);

        $this->assertDatabaseHas('respuestas_banorte_aliado', [
            'user_id' => '301919',
            'autorizacion' => '075319'
        ]);
        $this->assertDatabaseHas('respuestas_banorte_aliado', [
            'user_id' => '301922',
            'estatus' => 'Declinada'
        ]);
    }

}
