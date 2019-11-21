<?php

namespace Tests\Unit;

use App\RespuestaBanorteAliado;
use App\UserTdcAliado;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\UploadedFile as Upfile;
use Tests\TestCase;

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

    /** @test */
    public function admins_can_import_rejected_users_from_respuestas_banorte()
    {
        $this->signIn();
        $expired = factory(UserTdcAliado::class)->create([
            'user_id' => '123456',
            'exp_month' => 10,
            'exp_year' => 2018,
        ]);
        $active = factory(UserTdcAliado::class)->create([
            'user_id' => '654321',
            'exp_month' => 11,
            'exp_year' => 2028,
        ]);
        // rejected on date
        factory(RespuestaBanorteAliado::class)->create([
            'user_id' => $expired->user_id,
            'fecha' => '2019-11-19',
            'detalle_mensaje' => 'Fondos insuficientes',
        ]);
        // rejected not on date
        factory(RespuestaBanorteAliado::class)->create([
            'user_id' => $active->user_id,
            'fecha' => '2019-10-19',
            'detalle_mensaje' => 'Supera el monto límite permitido',
        ]);
        // not rejected on date
        factory(RespuestaBanorteAliado::class)->create([
            'user_id' => '111111',
            'fecha' => '2019-11-19',
            'detalle_mensaje' => 'Aprobado',
        ]);


        $this->post('/aliado/banorte/billingRejected', [
            'date' => '2019-11-19',
            'procedence' => 'Rechazados por saldo',
        ]);

        $this->assertDatabaseHas('aliado_billing_users', [
            'user_id' => $expired->user_id,
            'procedence' => 'Rechazados por saldo',
            'exp_date' => "18-10",
        ]);
        $this->assertDatabaseMissing('aliado_billing_users', [
            'user_id' => $active->user_id,
        ]);
        $this->assertDatabaseMissing('aliado_billing_users', [
            'user_id' => '111111'
        ]);
    }

    /** @test */
    public function admins_can_import_users_writing_in_text_box()
    {
        $this->signIn();
        $this->withoutExceptionHandling();
        $expired = factory(UserTdcAliado::class)->create([
            'user_id' => '123456',
            'exp_month' => 10,
            'exp_year' => 2018,
        ]);
        $active = factory(UserTdcAliado::class)->create([
            'user_id' => '654321',
            'exp_month' => 11,
            'exp_year' => 2028,
        ]);


        $this->post('/aliado/banorte/usersTextbox', [
            'ids' => "123456\r\n654321",
            'procedence' => 'Rechazos historicos',
        ]);

        $this->assertDatabaseHas('aliado_billing_users', [
            'user_id' => $expired->user_id,
            'procedence' => 'Rechazos historicos',
            'exp_date' => "18-10",
        ]);
        $this->assertDatabaseHas('aliado_billing_users', [
            'user_id' => $active->user_id,
        ]);
    }
}
