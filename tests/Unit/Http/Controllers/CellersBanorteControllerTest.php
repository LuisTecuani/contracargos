<?php

namespace Tests\Unit\Http\Controllers;

use App\CellersBillingUsers;
use App\CellersUser;
use App\Http\Controllers\CellersBanorteController;
use App\RespuestasBanorteCellers;
use App\CellersTdc;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\UploadedFile as Upfile;
use Tests\TestCase;

class CellersBanorteControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admins_can_browse_to_the_index_page()
    {
        $this->signIn();
        $this->withoutExceptionHandling();
        $this->get('/cellers/banorte')
            ->assertOk()
            ->assertSessionHasNoErrors()
            ->assertViewIs('cellers.banorte.index');
    }

    /** @test */
    public function admins_can_import_users_from_ftp()
    {
        $this->signIn();
        $this->withoutExceptionHandling();
        $expired = factory(CellersTdc::class)->create([
            'user_id' => '77',
            'exp_date' => 1018,
        ]);
        $active = factory(CellersTdc::class)->create([
            'user_id' => '1223',
            'exp_date' => 128,
        ]);
        $file = UploadedFile::createFromBase(
            (new UpFile(
                __DIR__ . '/files/SCAENT2950D191205ER01.ftp',
                'SCAENT2950D191205ER01.ftp',
                'text/plain',
                20416,
                null,
                true
            ))
        );

        $this->post('/cellers/banorte/ftp', [
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
            'exp_date' => '28-01',
        ]);
    }

    /** @test */
    public function admins_can_import_rejected_users_from_respuestas_banorte()
    {
        $this->signIn();
        $this->withoutExceptionHandling();
        $expired = factory(CellersTdc::class)->create([
            'user_id' => '123456',
            'exp_date' => 1018,
        ]);
        $active = factory(CellersTdc::class)->create([
            'user_id' => '654321',
            'exp_date' => 1128,
        ]);
        // rejected on date
        factory(RespuestasBanorteCellers::class)->create([
            'user_id' => $expired->user_id,
            'fecha' => '2019-11-19',
            'detalle_mensaje' => 'Fondos insuficientes',
        ]);
        // rejected not on date
        factory(RespuestasBanorteCellers::class)->create([
            'user_id' => $active->user_id,
            'fecha' => '2019-10-19',
            'detalle_mensaje' => 'Supera el monto límite permitido',
        ]);
        // not rejected on date
        factory(RespuestasBanorteCellers::class)->create([
            'user_id' => '111111',
            'fecha' => '2019-11-19',
            'detalle_mensaje' => 'Aprobado',
        ]);


        $this->post('/cellers/banorte/billingRejected', [
            'date' => '2019-11-19',
            'procedence' => 'Rechazados por saldo',
        ]);

        $this->assertDatabaseHas('cellers_billing_users', [
            'user_id' => $expired->user_id,
            'procedence' => 'Rechazados por saldo',
            'exp_date' => "18-10",
        ]);
        $this->assertDatabaseMissing('cellers_billing_users', [
            'user_id' => $active->user_id,
        ]);
        $this->assertDatabaseMissing('cellers_billing_users', [
            'user_id' => '111111'
        ]);
    }

    /** @test */
    public function admins_can_import_users_writing_in_text_box()
    {
        $this->signIn();

        $this->withoutExceptionHandling();
        $expired = factory(CellersTdc::class)->create([
            'user_id' => '123456',
            'exp_date' => 1018,
        ]);
        $active = factory(CellersTdc::class)->create([
            'user_id' => '654321',
            'exp_date' => 1128,
        ]);


        $this->post('/cellers/banorte/usersTextbox', [
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
        $this->withoutExceptionHandling();
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

        $expUsers = (new CellersBanorteController())->expDates();

        $vigUsers = (new CellersBanorteController())->vigDates();

        $this->assertCount(2, $expUsers);
        $this->assertCount(3, $vigUsers);
    }

    /** @ incompleted test  */
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

        $this->assertFileExists("SCAENT2950D" . now()->format('ymd') . "ER01.ftp",);

    }
}
