<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\UploadedFile as Upfile;

trait BillingUsersContractTests
{
    use RefreshDatabase;

    protected $pName;
    protected $card;
    protected $affinitas;
    protected $respuestasBanorte;

    public function setUp(): void
    {
        parent::setUp();

        $this->pName = strtolower($this->getPlatformData()['name']);
        $this->card = $this->getPlatformData()['card_model'];
        $this->affinitas = substr($this->getPlatformData()['affinitas'], -4);
        $this->respuestasBanorte = $this->getPlatformData()['respuestas_banorte_model'];
    }

    abstract protected function getPlatformData();

    /** @test */
    public function admins_can_browse_to_the_index_page()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        $this->get("/$this->pName/billing_users")
            ->assertOk()
            ->assertSessionHasNoErrors()
            ->assertViewIs("$this->pName.billing_users.index");
    }

    /** @test */
    public function admins_can_import_users_from_ftp()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $expired = factory($this->card)->create([
            'user_id' => '77',
        ]);
        $active = factory($this->card)->create([
            'user_id' => '1223',
        ]);
        $file = UploadedFile::createFromBase(
            (new UpFile(
                __DIR__ . '/Files/SCAENT' . $this->affinitas . 'D191205ER01.ftp',
                'SCAENT' . $this->affinitas . 'D191205ER01.ftp',
                'text/plain',
                20416,
                0,
                true
            ))
        );

        $this->post("/$this->pName/billing_users/storeFtp", [
            'file' => $file,
            'procedence' => 'dashboard',
        ]);

        $this->assertDatabaseHas($this->pName . '_billing_users', [
            'user_id' => $expired->user_id,
            'procedence' => 'dashboard',
        ]);
        $this->assertDatabaseHas($this->pName . '_billing_users', [
            'user_id' => $active->user_id,
            'procedence' => 'dashboard',
        ]);
    }

    /** @test */
    public function admins_can_import_rejected_due_funds_users_from_previous_respuestas_banorte_file()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $user1 = factory($this->card)->create([
            'user_id' => '123456',
        ]);
        $user2 = factory($this->card)->create([
            'user_id' => '654321',
        ]);
        // user1 first charge
        factory($this->respuestasBanorte)->create([
            'user_id' => $user1->user_id,
            'fecha' => '2019-11-19',
            'estatus' => 'Rechazada',
            'detalle_mensaje' => 'Fondos insuficientes',
            'source_file' => 'CE201911191745097823918',
        ]);
        // user2 first charge
        factory($this->respuestasBanorte)->create([
            'user_id' => $user2->user_id,
            'fecha' => '2019-11-19',
            'estatus' => 'Rechazada',
            'source_file' => 'CE201911191745097823918',
        ]);
        // user2 second charge
        factory($this->respuestasBanorte)->create([
            'user_id' => $user2->user_id,
            'fecha' => '2019-11-10',
            'estatus' => 'Rechazada',
            'source_file' => 'CE201911191745097823918',
        ]);
        // user2 third charge
        factory($this->respuestasBanorte)->create([
            'user_id' => $user2->user_id,
            'fecha' => '2019-10-30',
            'estatus' => 'Rechazada',
            'source_file' => 'CE201911191745097823918',
        ]);
        // user2 fourth charge
        factory($this->respuestasBanorte)->create([
            'user_id' => $user2->user_id,
            'fecha' => '2019-10-15',
            'estatus' => 'Rechazada',
            'source_file' => 'CE201911191745097823918',
        ]);
        // not rejected on date
        factory($this->respuestasBanorte)->create([
            'user_id' => '111111',
            'fecha' => '2019-11-19',
            'estatus' => 'Aprobada',
            'detalle_mensaje' => 'Aprobado',
            'source_file' => 'CE201911191745097823918',
        ]);

        $this->post("/$this->pName/billing_users/storeRejectedProsa", [
            'procedence' => 'Rechazos previos',
        ]);

        $this->assertDatabaseHas($this->pName . "_billing_users", [
            'user_id' => $user1->user_id,
            'procedence' => 'Rechazos previos',
        ]);
        $this->assertDatabaseMissing($this->pName . "_billing_users", [
            'user_id' => $user2->user_id,
        ]);
        $this->assertDatabaseMissing($this->pName . "_billing_users", [
            'user_id' => '111111'
        ]);
    }

    /** @test */
    public function admins_can_import_users_writing_in_text_box()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $expired = factory($this->card)->create([
            'user_id' => '123456',
        ]);
        $active = factory($this->card)->create([
            'user_id' => '654321',
        ]);


        $this->post("/$this->pName/billing_users/storeTextbox", [
            'ids' => "123456\r\n654321",
            'procedence' => 'Rechazos historicos',
        ]);

        $this->assertDatabaseHas($this->pName . "_billing_users", [
            'user_id' => $expired->user_id,
            'procedence' => 'Rechazos historicos',
        ]);
        $this->assertDatabaseHas($this->pName . "_billing_users", [
            'user_id' => $active->user_id,
        ]);
    }
}

    /** @test * /
    public function expDates_method_divide_users_by_expired_and_vigent()
    {
        $this->card = $this->getPlatformData()['card_model'];
        $this->respuestasBanorte = $this->getPlatformData()['respuestas_banorte_model'];
        $billingUsers = $this->getPlatformData()['billing_users_model'];
        $this->affinitas = substr($this->getPlatformData()['affinitas'], -4);
        $pName = strtolower($this->getPlatformData()['name']);
        $this->signIn();
        $expired1 = factory($billingUsers)->create([
            'exp_date' => '18-02'
        ]);
        $expired2 = factory($billingUsers)->create([
            'exp_date' => '17-12'
        ]);
        $vigent1 = factory($billingUsers)->create([
            'exp_date' => '27-01'
        ]);
        $vigent2 = factory($billingUsers)->create([
            'exp_date' => '29-1'
        ]);
        $vigent3 = factory($billingUsers)->create([
            'exp_date' => '26-12'
        ]);

        $expUsers = (new UrbanoBillingUsersController())->expDates();
        $vigUsers = (new UrbanoBillingUsersController())->vigDates();

        $this->assertCount(2, $expUsers);
        $this->assertCount(3, $vigUsers);
    } */


