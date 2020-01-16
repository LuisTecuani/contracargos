<?php

namespace Tests\Unit\Http\Controllers;

use App\AliadoBillingUsers;
use App\AliadoUser;
use App\Http\Controllers\AliadoBillingUsersController;
use App\Repsaliado;
use App\RespuestasBanorteAliado;
use App\UserTdcAliado;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\UploadedFile as Upfile;
use Tests\TestCase;


class AliadoBillingUsersControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admins_can_browse_to_the_index_page()
    {
        $this->signIn();
        factory(AliadoBillingUsers::class)->create();

        $this->get('/aliado/billing_users')
            ->assertOk()
            ->assertSessionHasNoErrors()
            ->assertViewIs('aliado.billing_users.index');
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

        $this->post('/aliado/billing_users/storeFtp', [
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
        factory(RespuestasBanorteAliado::class)->create([
            'user_id' => $expired->user_id,
            'fecha' => '2019-11-19',
            'detalle_mensaje' => 'Fondos insuficientes',
        ]);
        // rejected not on date
        factory(RespuestasBanorteAliado::class)->create([
            'user_id' => $active->user_id,
            'fecha' => '2019-10-19',
            'detalle_mensaje' => 'Supera el monto límite permitido',
        ]);
        // not rejected on date
        factory(RespuestasBanorteAliado::class)->create([
            'user_id' => '111111',
            'fecha' => '2019-11-19',
            'detalle_mensaje' => 'Aprobado',
        ]);

        $this->post('/aliado/billing_users/storeRejectedBanorte', [
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
    public function admins_can_import_rejected_users_from_repsaliado()
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
        factory(Repsaliado::class)->create([
            'user_id' => $expired->user_id,
            'fecha' => '2019-11-19',
            'motivo_rechazo' => 'Fondos insuficientes',
        ]);
        // rejected not on date
        factory(Repsaliado::class)->create([
            'user_id' => $active->user_id,
            'fecha' => '2019-10-19',
            'motivo_rechazo' => 'Supera el monto límite permitido',
        ]);
        // not rejected on date
        factory(Repsaliado::class)->create([
            'user_id' => '111111',
            'fecha' => '2019-11-19',
            'motivo_rechazo' => 'Aprobado',
        ]);

        $this->post('/aliado/billing_users/storeRejectedProsa', [
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


        $this->post('/aliado/billing_users/storeTextbox', [
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

    /** @test */
    public function expDates_method_divide_users_by_expired_and_vigent()
    {
        $this->signIn();
        $expired1 = factory(AliadoBillingUsers::class)->create([
            'exp_date' => '18-02'
        ]);
        $expired2 = factory(AliadoBillingUsers::class)->create([
            'exp_date' => '17-12'
        ]);
        $vigent1 = factory(AliadoBillingUsers::class)->create([
            'exp_date' => '27-01'
        ]);
        $vigent2 = factory(AliadoBillingUsers::class)->create([
            'exp_date' => '29-1'
        ]);
        $vigent3 = factory(AliadoBillingUsers::class)->create([
            'exp_date' => '26-12'
        ]);

        $expUsers = (new AliadoBillingUsersController())->expDates();
        $vigUsers = (new AliadoBillingUsersController())->vigDates();

        $this->assertCount(2, $expUsers);
        $this->assertCount(3, $vigUsers);
    }

    /** incomplete test test  */
    public function it_can_build_a_valid_ftp_file()
    {
        $this->signIn();
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
