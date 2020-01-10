<?php

namespace Tests\Unit\Http\Controllers;

use App\MediakeyBillingUsers;
use App\MediakeyUser;
use App\Http\Controllers\MediakeyBillingUsersController;
use App\RespuestasBanorteMediakey;
use App\UserTdcMediakey;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\UploadedFile as Upfile;
use Tests\TestCase;


class MediakeyBillingUsersControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admins_can_browse_to_the_index_page()
    {
        $this->signIn();
        $this->withoutExceptionHandling();

        factory(MediakeyBillingUsers::class)->create();

        $this->get('/mediakey/billing_users')
            ->assertOk()
            ->assertSessionHasNoErrors()
            ->assertViewIs('mediakey.billing_users.index');
    }

    /** @test */
    public function admins_can_import_users_from_ftp()
    {
        $this->signIn();
        $expired = factory(UserTdcMediakey::class)->create([
            'user_id' => '125914',
            'month' => 10,
            'year' => 2018,
        ]);
        $active = factory(UserTdcMediakey::class)->create([
            'user_id' => '125942',
            'month' => 11,
            'year' => 2028,
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

        $this->post('/mediakey/billing_users/storeFtp', [
            'file' => $file,
            'procedence' => 'dashboard',
        ]);

        $this->assertDatabaseHas('mediakey_billing_users', [
            'user_id' => $expired->user_id,
            'procedence' => 'dashboard',
            'exp_date' => "18-10",
        ]);
        $this->assertDatabaseHas('mediakey_billing_users', [
            'user_id' => $active->user_id,
            'procedence' => 'dashboard',
            'exp_date' => '28-11',
        ]);

    }

    /** @test */
    public function admins_can_import_rejected_users_from_respuestas_banorte()
    {
        $this->signIn();
        $this->withoutExceptionHandling();
        $expired = factory(UserTdcMediakey::class)->create([
            'user_id' => '123456',
            'month' => 10,
            'year' => 2018,
        ]);
        $active = factory(UserTdcMediakey::class)->create([
            'user_id' => '654321',
            'month' => 11,
            'year' => 2028,
        ]);
        // rejected on date
        factory(RespuestasBanorteMediakey::class)->create([
            'user_id' => $expired->user_id,
            'fecha' => '2019-11-19',
            'detalle_mensaje' => 'Fondos insuficientes',
        ]);
        // rejected not on date
        factory(RespuestasBanorteMediakey::class)->create([
            'user_id' => $active->user_id,
            'fecha' => '2019-10-19',
            'detalle_mensaje' => 'Supera el monto límite permitido',
        ]);
        // not rejected on date
        factory(RespuestasBanorteMediakey::class)->create([
            'user_id' => '111111',
            'fecha' => '2019-11-19',
            'detalle_mensaje' => 'Aprobado',
        ]);


        $this->post('/mediakey/billing_users/storeRejected', [
            'date' => '2019-11-19',
            'procedence' => 'Rechazados por saldo',
        ]);

        $this->assertDatabaseHas('mediakey_billing_users', [
            'user_id' => $expired->user_id,
            'procedence' => 'Rechazados por saldo',
            'exp_date' => "18-10",
        ]);
        $this->assertDatabaseMissing('mediakey_billing_users', [
            'user_id' => $active->user_id,
        ]);
        $this->assertDatabaseMissing('mediakey_billing_users', [
            'user_id' => '111111'
        ]);
    }

    /** @test */
    public function admins_can_import_users_writing_in_text_box()
    {
        $this->signIn();

        $this->withoutExceptionHandling();
        $expired = factory(UserTdcMediakey::class)->create([
            'user_id' => '123456',
            'month' => 10,
            'year' => 2018,
        ]);
        $active = factory(UserTdcMediakey::class)->create([
            'user_id' => '654321',
            'month' => 11,
            'year' => 2028,
        ]);


        $this->post('/mediakey/billing_users/storeTextbox', [
            'ids' => "123456\r\n654321",
            'procedence' => 'Rechazos historicos',
        ]);

        $this->assertDatabaseHas('mediakey_billing_users', [
            'user_id' => $expired->user_id,
            'procedence' => 'Rechazos historicos',
            'exp_date' => "18-10",
        ]);
        $this->assertDatabaseHas('mediakey_billing_users', [
            'user_id' => $active->user_id,
        ]);
    }

    /** @test */
    public function expDates_method_divide_users_by_expired_and_vigent()
    {
        $this->signIn();
        $expired1 = factory(MediakeyBillingUsers::class)->create([
            'exp_date' => '18-02'
        ]);
        $expired2 = factory(MediakeyBillingUsers::class)->create([
            'exp_date' => '17-12'
        ]);
        $vigent1 = factory(MediakeyBillingUsers::class)->create([
            'exp_date' => '27-01'
        ]);
        $vigent2 = factory(MediakeyBillingUsers::class)->create([
            'exp_date' => '29-1'
        ]);
        $vigent3 = factory(MediakeyBillingUsers::class)->create([
            'exp_date' => '26-12'
        ]);

        $expUsers = (new MediakeyBillingUsersController())->expDates();

        $vigUsers = (new MediakeyBillingUsersController())->vigDates();

        $this->assertCount(2, $expUsers);
        $this->assertCount(3, $vigUsers);
    }

    /** incomplete test test  */
    public function it_can_build_a_valid_ftp_file()
    {
        $this->signIn();
        $this->withoutExceptionHandling();
        $user1 = factory(MediakeyBillingUsers::class)->create([
            'exp_date' => '18-10',
        ]);
        $user2 = factory(MediakeyBillingUsers::class)->create([
            'exp_date' => '17-01',
        ]);
        $user3 = factory(MediakeyBillingUsers::class)->create([
            'exp_date' => '27-01',
        ]);
        factory(MediakeyUser::class)->create([
            'id' => $user1->user_id,
        ]);
        factory(MediakeyUser::class)->create([
            'id' => $user2->user_id,
        ]);
        factory(MediakeyUser::class)->create([
            'id' => $user3->user_id,
        ]);

        $this->get('/mediakey/banorte/ftpProsa');

        $this->assertFileExists("SCAENT0897D" . now()->format('ymd') . "ER01.ftp",);

    }
}
