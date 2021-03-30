<?php

namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\UploadedFile as Upfile;

class CellersResponsesControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admins_can_browse_to_the_index_page()
    {
        $this->signIn();

        $this->get('/cellers/responses')
            ->assertOk()
            ->assertSessionHasNoErrors()
            ->assertViewIs('cellers.responses.index');
    }

    /** @test */
    public function storeReps_method_persist_billing_info_on_repscellers_model()
    {
        $this->signIn();
        $file = UploadedFile::createFromBase(
            (new UpFile(
                __DIR__ . '/Files/CE201912021631068092950.rep',
                'CE201912021631068092950.rep',
                'text/plain',
                20416,
                0,
                true
            ))
        );

        $this->post('/cellers/responses/storeReps', [
            'files' => [$file],
        ]);

        $this->assertDatabaseHas('repscellers', [
            'user_id' => '237',
            'autorizacion' => '319870'
        ]);
        $this->assertDatabaseHas('repscellers', [
            'user_id' => '184',
            'detalle_mensaje' => 'Rechazada'
        ]);
    }

    /** @test */
    public function storePdf_method_persist_billing_info_on_RespuestasBanorteCellers_model()
    {
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

        $this->post('/cellers/responses/storePdf', [
            'files' => [$file],
        ]);

        $this->assertDatabaseHas('respuestas_banorte_cellers', [
            'user_id' => '301919',
            'autorizacion' => '075319'
        ]);
        $this->assertDatabaseHas('respuestas_banorte_cellers', [
            'user_id' => '301922',
            'estatus' => 'Declinada'
        ]);
    }

}
