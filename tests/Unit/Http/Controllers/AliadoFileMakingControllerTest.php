<?php

namespace Tests\Unit\Http\Controllers;

use App\AliadoBillingUsers;
use App\AliadoUser;
use App\Http\Controllers\AliadoBanorteController;
use App\RespuestasBanorteAliado;
use App\UserTdcAliado;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\UploadedFile as Upfile;
use Tests\TestCase;

class AliadoFileMakingControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admins_can_browse_to_the_index_page()
    {
        $this->signIn();
        $this->withoutExceptionHandling();

        $this->get('/aliado/file_making')
            ->assertOk()
            ->assertSessionHasNoErrors()
            ->assertViewIs('aliado.file_making.index');
    }

    /** incomplete test test  */
    public function it_can_build_a_valid_ftp_file()
    {
        $this->signIn();
        $this->withoutExceptionHandling();
        $user1 = factory(AliadoBillingUsers::class)->create([
            'exp_date' => '18-10',
        ]);
        $user2 = factory(AliadoBillingUsers::class)->create([
            'exp_date' => '17-01',
        ]);
        $user3 = factory(AliadoBillingUsers::class)->create([
            'exp_date' => '27-01',
        ]);
        factory(AliadoUser::class)->create([
            'id' => $user1->user_id,
        ]);
        factory(AliadoUser::class)->create([
            'id' => $user2->user_id,
        ]);
        factory(AliadoUser::class)->create([
            'id' => $user3->user_id,
        ]);

        $this->get('/aliado/banorte/ftpProsa');

        $this->assertFileExists("SCAENT0897D" . now()->format('ymd') . "ER01.ftp",);

    }
}
