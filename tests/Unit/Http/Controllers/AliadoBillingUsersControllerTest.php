<?php

namespace Tests\Unit\Http\Controllers;

use App\AliadoBillingUsers;
use App\AliadoUser;
use App\Http\Controllers\AliadoBillingUsersController;
use App\Repsaliado;
use App\RespuestasBanorteAliado;
use App\UserTdcAliado;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\UploadedFile as Upfile;
use Tests\TestCase;


class AliadoBillingUsersControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->tdc1 = factory(UserTdcAliado::class)->create([
            'user_id' => '125914',
            'exp_month' => 10,
            'exp_year' => 2018,
        ]);

        $this->tdc2 = factory(UserTdcAliado::class)->create([
            'user_id' => '125942',
            'exp_month' => 11,
            'exp_year' => 2028,
        ]);
    }

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
    public function storeFtp_method_import_users_from_ftp()
    {
        $this->signIn();
        $expired = $this->tdc1;
        $active = $this->tdc2;
        $file = UploadedFile::createFromBase(
            (new UpFile(
                __DIR__ . '/files/SCAENT0897D191113ER01.ftp',
                'SCAENT0897D191113ER01.ftp',
                'text/plain',
                20416,
                0,
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
    public function storeRejectedProsa_import_rejected_users_from_maximum_three_previous_files_repsaliado()
    {
        $this->signIn();
        // user1 first charge
        factory(Repsaliado::class)->create([
            'user_id' => $this->tdc1->user_id,
            'fecha' => '2019-11-19',
            'estatus' => 'Rechazada',
            'detalle_mensaje' => 'Fondos insuficientes',
            'source_file' => 'CE201911191745097823918',
        ]);
        // user2 first charge
        factory(Repsaliado::class)->create([
            'user_id' => $this->tdc2->user_id,
            'fecha' => '2019-11-19',
            'estatus' => 'Rechazada',
            'detalle_mensaje' => 'Fondos insuficientes',
            'source_file' => 'CE201911191745097823918',
        ]);
        // user2 second charge
        factory(Repsaliado::class)->create([
            'user_id' => $this->tdc2->user_id,
            'fecha' => '2019-11-10',
            'estatus' => 'Rechazada',
            'detalle_mensaje' => 'Fondos insuficientes',
            'source_file' => 'CE201911191745097823918',
        ]);
        // user2 third charge
        factory(Repsaliado::class)->create([
            'user_id' => $this->tdc2->user_id,
            'fecha' => '2019-10-30',
            'estatus' => 'Rechazada',
            'detalle_mensaje' => 'Fondos insuficientes',
            'source_file' => 'CE201911191745097823918',
        ]);
        // user2 fourth charge
        factory(Repsaliado::class)->create([
            'user_id' => $this->tdc2->user_id,
            'fecha' => '2019-10-15',
            'estatus' => 'Rechazada',
            'detalle_mensaje' => 'Fondos insuficientes',
            'source_file' => 'CE201911191745097823918',
        ]);
        // user3 first charge
        factory(Repsaliado::class)->create([
            'user_id' => '222222',
            'fecha' => '2019-10-15',
            'estatus' => 'Rechazada',
            'detalle_mensaje' => 'Rechazado',
            'source_file' => 'CE201911191745097823918',
        ]);
        // not rejected on date
        factory(Repsaliado::class)->create([
            'user_id' => '111111',
            'fecha' => '2019-11-19',
            'estatus' => 'Aprobada',
            'detalle_mensaje' => 'Aprobado',
            'source_file' => 'CE201911191745097823918',
        ]);

        $this->post('/aliado/billing_users/storeRejectedProsa', [
            'procedence' => 'Rechazos previos',
        ]);

        $this->assertDatabaseHas('aliado_billing_users', [
            'user_id' => $this->tdc1->user_id,
            'procedence' => 'Rechazos previos',
            'exp_date' => "18-10",
        ]);
        $this->assertDatabaseMissing('aliado_billing_users', [
            'user_id' => $this->tdc2->user_id,
        ]);
        $this->assertDatabaseMissing('aliado_billing_users', [
            'user_id' => '111111'
        ]);
        $this->assertDatabaseMissing('aliado_billing_users', [
            'user_id' => '222222'
        ]);
    }

    /** @test */
    public function storeToBanorte_import_rejected_users_to_banorte()
    {
        $this->signIn();
        //5 random reps
        factory(Repsaliado::class, 5)->create([
            'source_file' => 'CE201811191745097820897'
        ]);
        // user1 not from 0897 file
        factory(Repsaliado::class)->create([
            'user_id' => $this->tdc1->user_id,
            'fecha' => date("Y-m-d"),
            'estatus' => 'Rechazada',
            'detalle_mensaje' => 'Fondos insuficientes',
            'source_file' => 'CE201911191745097823918',
        ]);
        // user2 from 0897 file
        factory(Repsaliado::class)->create([
            'user_id' => $this->tdc2->user_id,
            'fecha' => date("Y-m-d"),
            'estatus' => 'Rechazada',
            'detalle_mensaje' => 'Fondos insuficientes',
            'source_file' => 'CE201911191745097820897',
        ]);
        // rejected not due founds from 0897 file
        factory(Repsaliado::class)->create([
            'user_id' => '222222',
            'fecha' => date("Y-m-d"),
            'estatus' => 'Rechazada',
            'detalle_mensaje' => 'Rechazar',
            'source_file' => 'CE201911191745097820897',
        ]);
        // not rejected user
        factory(Repsaliado::class)->create([
            'user_id' => '111111',
            'fecha' => date("Y-m-d"),
            'estatus' => 'Aprobada',
            'detalle_mensaje' => 'Aprobado',
            'source_file' => 'CE201911191745097820897',
        ]);
        $this->post('/aliado/billing_users/storeToBanorte', [
            'procedence' => 'para banorte',
        ]);

        $this->assertDatabaseHas('aliado_billing_users', [
            'user_id' => $this->tdc2->user_id,
            'procedence' => 'para banorte',
            'exp_date' => "28-11",
        ]);
        $this->assertDatabaseMissing('aliado_billing_users', [
            'user_id' => $this->tdc1->user_id,
        ]);
        $this->assertDatabaseMissing('aliado_billing_users', [
            'user_id' => '111111'
        ]);
        $this->assertDatabaseMissing('aliado_billing_users', [
            'user_id' => '222222'
        ]);
    }

    /** @test */
    public function storeTo3918_import_rejected_due_founds_users_to_3918()
    {
        $this->signIn();
        $today = Carbon::now()->format('Y-m-d');
        $user3 = factory(UserTdcAliado::class)->create([
            'user_id' => '333333',
            'exp_month' => 11,
            'exp_year' => 2018,
        ]);
        //5 random reps
        factory(RespuestasBanorteAliado::class, 5)->create();
        // user1 not from banorte file
        factory(Repsaliado::class)->create([
            'user_id' => $this->tdc1->user_id,
            'fecha' => $today,
            'estatus' => 'Rechazada',
            'detalle_mensaje' => 'Fondos insuficientes',
        ]);
        // user2 from banorte file rejected due founds
        factory(RespuestasBanorteAliado::class)->create([
            'user_id' => $this->tdc2->user_id,
            'fecha' => $today,
            'estatus' => 'Rechazada',
            'detalle_mensaje' => 'Fondos insuficientes',
        ]);
        // rejected due founds previous date
        factory(RespuestasBanorteAliado::class)->create([
            'user_id' => '222222',
            'fecha' => Carbon::now()->subDay(2)->format('Y-m-d'),
            'estatus' => 'Rechazada',
            'detalle_mensaje' => 'Fondos insuficientes',
        ]);
        // not rejected user
        factory(RespuestasBanorteAliado::class)->create([
            'user_id' => '111111',
            'fecha' => $today,
            'estatus' => 'Aprobada',
            'detalle_mensaje' => 'Aprobado',
        ]);
        // rejected not due founds banorte file
        factory(RespuestasBanorteAliado::class)->create([
            'user_id' => $user3->user_id,
            'fecha' => $today,
            'estatus' => 'Rechazada',
            'detalle_mensaje' => 'Rechazo',
        ]);

        $this->post('/aliado/billing_users/storeTo3918', [
            'procedence' => 'para 3918',
        ]);

        $this->assertDatabaseHas('aliado_billing_users', [
            'user_id' => $this->tdc2->user_id,
            'procedence' => 'para 3918',
            'exp_date' => "28-11",
        ]);
        $this->assertDatabaseMissing('aliado_billing_users', [
            'user_id' => $this->tdc1->user_id,
        ]);
        $this->assertDatabaseMissing('aliado_billing_users', [
            'user_id' => '111111'
        ]);
        $this->assertDatabaseMissing('aliado_billing_users', [
            'user_id' => '222222'
        ]);
        $this->assertDatabaseMissing('aliado_billing_users', [
            'user_id' => $user3->user_id,
        ]);
    }

    /** @test */
    public function storeTextbox_import_users_writing_in_text_box()
    {
        $this->signIn();
        $expired = $this->tdc1;
        $active = $this->tdc2;

        $this->post('/aliado/billing_users/storeTextbox', [
            'ids' => "125914\r\n125942",
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
}
