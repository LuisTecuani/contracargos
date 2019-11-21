<?php

namespace Tests\Unit;

use App\AliadoBillingUsers;
use App\RespuestaBanorteAliado;
use App\User;
use App\UserTdcAliado;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\File\UploadedFile as Upfile;

class AliadoBanorteControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admins_can_browse_to_the_index_page()
    {
        $this->signIn();

        $this->get('/aliado/banorte')
            ->assertOk()
            ->assertSessionHasNoErrors()
            ->assertViewIs('aliado.banorte.index');
    }

    /** @test */
    public function admins_can_import_users_from_ftp()
    {
        $this->signIn();
        $expired = factory(UserTdcAliado::class)->create([
            'user_id' => '125914',
            'exp_month' => 10,
            'exp_year' => 2018,
        ]);
        $active = factory(UserTdcAliado::class)->create([
            'user_id' => '125942',
            'exp_month' => 11,
            'exp_year' => 2028,
        ]);
        $file = UploadedFile::createFromBase(
            (new UpFile(
                __DIR__ . '/files/SCAENT0897D191113ER01.ftp',
                'SCAENT0897D191113ER01.ftp',
                'text/plain',
                20416,
                null,
                true
            ))
        );

        $this->post('/aliado/banorte/ftp', [
            'file' => $file,
            'procedence' => 'dashboard',
        ]);

        $this->assertDatabaseHas('aliado_billing_users', [
            'user_id' => $expired->user_id,
            'procedence' => 'dashboard',
            'exp_date' => "18-10",
        ]);
        $this->assertDatabaseHas('aliado_billing_users', [
            'user_id' => $active->user_id,
            'procedence' => 'dashboard',
            'exp_date' => '28-11',
        ]);
    }

}
