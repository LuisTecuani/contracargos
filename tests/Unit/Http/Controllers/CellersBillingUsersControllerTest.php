<?php

namespace Tests\Unit\Http\Controllers;

use App\CellersBillingUsers;
use App\CellersUser;
use App\Http\Controllers\CellersBillingUsersController;
use App\Repscellers;
use App\RespuestasBanorteCellers;
use App\UserTdcCellers;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\UploadedFile as Upfile;
use Tests\TestCase;


class CellersBillingUsersControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admins_can_browse_to_the_index_page()
    {
        $this->signIn();

        factory(CellersBillingUsers::class)->create();

        $this->get('/cellers/billing_users')
            ->assertOk()
            ->assertSessionHasNoErrors()
            ->assertViewIs('cellers.billing_users.index');
    }

    /** @test */
    public function admins_can_import_users_from_ftp()
    {
        $this->signIn();
        $expired = factory(UserTdcCellers::class)->create([
            'user_id' => '77',
            'exp_date' => 1018,
        ]);
        $active = factory(UserTdcCellers::class)->create([
            'user_id' => '1223',
            'exp_date' => 1128,
        ]);
        $file = UploadedFile::createFromBase(
            (new UpFile(
                __DIR__ . '/Files/SCAENT2950D191205ER01.ftp',
                'SCAENT2950D191205ER01.ftp',
                'text/plain',
                20416,
                0,
                true
            ))
        );

        $this->post('/cellers/billing_users/storeFtp', [
            'file' => $file,
            'procedence' => 'dashboard',
        ]);

        $this->assertDatabaseHas('cellers_billing_users', [
            'user_id' => $expired->user_id,
            'procedence' => 'dashboard',
            'exp_date' => "18-10",
        ]);
        $this->assertDatabaseHas('cellers_billing_users', [
            'user_id' => $active->user_id,
            'procedence' => 'dashboard',
            'exp_date' => '28-11',
        ]);

    }

    /** @test */
    public function admins_can_import_rejected_users_from_three_previous_files_respuestas_banorte_cellers()
    {
        $this->signIn();
        $user1 = factory(UserTdcCellers::class)->create([
            'user_id' => '123456',
            'exp_date' => 1018,
        ]);
        $user2 = factory(UserTdcCellers::class)->create([
            'user_id' => '654321',
            'exp_date' => 1128,
        ]);
        // user1 first charge
        factory(RespuestasBanorteCellers::class)->create([
            'user_id' => $user1->user_id,
            'fecha' => '2019-11-19',
            'estatus' => 'Rechazada',
            'detalle_mensaje' => 'Fondos insuficientes',
            'source_file' => 'CE201911191745097823918',
        ]);
        // user2 first charge
        factory(RespuestasBanorteCellers::class)->create([
            'user_id' => $user2->user_id,
            'fecha' => '2019-11-19',
            'estatus' => 'Rechazada',
            'source_file' => 'CE201911191745097823918',
        ]);
        // user2 second charge
        factory(RespuestasBanorteCellers::class)->create([
            'user_id' => $user2->user_id,
            'fecha' => '2019-11-10',
            'estatus' => 'Rechazada',
            'source_file' => 'CE201911191745097823918',
        ]);
        // user2 third charge
        factory(RespuestasBanorteCellers::class)->create([
            'user_id' => $user2->user_id,
            'fecha' => '2019-10-30',
            'estatus' => 'Rechazada',
            'source_file' => 'CE201911191745097823918',
        ]);
        // user2 fourth charge
        factory(RespuestasBanorteCellers::class)->create([
            'user_id' => $user2->user_id,
            'fecha' => '2019-10-15',
            'estatus' => 'Rechazada',
            'source_file' => 'CE201911191745097823918',
        ]);
        // not rejected on date
        factory(RespuestasBanorteCellers::class)->create([
            'user_id' => '111111',
            'fecha' => '2019-11-19',
            'estatus' => 'Aprobada',
            'detalle_mensaje' => 'Aprobado',
            'source_file' => 'CE201911191745097823918',
        ]);

        $this->post('/cellers/billing_users/storeRejectedProsa', [
            'procedence' => 'Rechazos previos',
        ]);

        $this->assertDatabaseHas('cellers_billing_users', [
            'user_id' => $user1->user_id,
            'procedence' => 'Rechazos previos',
            'exp_date' => "18-10",
        ]);
        $this->assertDatabaseMissing('cellers_billing_users', [
            'user_id' => $user2->user_id,
        ]);
        $this->assertDatabaseMissing('cellers_billing_users', [
            'user_id' => '111111'
        ]);
    }

    /** @test */
    public function admins_can_import_users_writing_in_text_box()
    {
        $this->signIn();
        $expired = factory(UserTdcCellers::class)->create([
            'user_id' => '123456',
            'exp_date' => 1018,
        ]);
        $active = factory(UserTdcCellers::class)->create([
            'user_id' => '654321',
            'exp_date' => 1128,
        ]);


        $this->post('/cellers/billing_users/storeTextbox', [
            'ids' => "123456\r\n654321",
            'procedence' => 'Rechazos historicos',
        ]);

        $this->assertDatabaseHas('cellers_billing_users', [
            'user_id' => $expired->user_id,
            'procedence' => 'Rechazos historicos',
            'exp_date' => "18-10",
        ]);
        $this->assertDatabaseHas('cellers_billing_users', [
            'user_id' => $active->user_id,
        ]);
    }

    /** @test */
    public function expDates_method_divide_users_by_expired_and_vigent()
    {
        $this->signIn();
        $expired1 = factory(CellersBillingUsers::class)->create([
            'exp_date' => '18-02'
        ]);
        $expired2 = factory(CellersBillingUsers::class)->create([
            'exp_date' => '17-12'
        ]);
        $vigent1 = factory(CellersBillingUsers::class)->create([
            'exp_date' => '27-01'
        ]);
        $vigent2 = factory(CellersBillingUsers::class)->create([
            'exp_date' => '29-1'
        ]);
        $vigent3 = factory(CellersBillingUsers::class)->create([
            'exp_date' => '26-12'
        ]);

        $expUsers = (new CellersBillingUsersController())->expDates();
        $vigUsers = (new CellersBillingUsersController())->vigDates();

        $this->assertCount(2, $expUsers);
        $this->assertCount(3, $vigUsers);
    }

    /** incomplete test test  */
    public function it_can_build_a_valid_ftp_file()
    {
        $this->signIn();
        $this->withoutExceptionHandling();
        $user1 = factory(CellersBillingUsers::class)->create([
            'exp_date' => '18-10',
        ]);
        $user2 = factory(CellersBillingUsers::class)->create([
            'exp_date' => '17-01',
        ]);
        $user3 = factory(CellersBillingUsers::class)->create([
            'exp_date' => '27-01',
        ]);
        factory(CellersUser::class)->create([
            'id' => $user1->user_id,
        ]);
        factory(CellersUser::class)->create([
            'id' => $user2->user_id,
        ]);
        factory(CellersUser::class)->create([
            'id' => $user3->user_id,
        ]);

        $this->get('/cellers/banorte/ftpProsa');

        $this->assertFileExists("SCAENT0897D" . now()->format('ymd') . "ER01.ftp",);

    }
}
