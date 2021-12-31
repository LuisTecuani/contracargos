<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\UploadedFile as Upfile;

trait BlacklistContractTests
{
    use RefreshDatabase;

    /** @test */
    public function admins_can_browse_to_the_blacklist_index_page()
    {
        $this->signIn();

        $this->get("/$this->pName/blacklist")
            ->assertOk()
            ->assertSessionHasNoErrors()
            ->assertViewIs("$this->pName.blacklist.index");
    }



    /** @test */
    public function store_method_persist_emails_on_blacklist_table()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $fakeUser = factory($this->user_model)->create([
            'email' => 'verapatino@hotmail.com'
        ]);

        $this->post("/$this->pName/blacklist/store", [
            'emails' => "dilaurys95@hotmail.com\r\nverapatino@hotmail.com",
        ]);

        $this->assertDatabaseHas($this->pName.'_blacklist', [
            'email' => 'dilaurys95@hotmail.com',
            'user_id' => null,
        ]);
        $this->assertDatabaseHas($this->pName.'_blacklist', [
            'email' => 'verapatino@hotmail.com',
            'user_id' => $fakeUser->id,
        ]);
    }



    /** test */
    public function storeReps_method_persist_billing_info_on_reps_model()
    {
        $this->signIn();
        $file = UploadedFile::createFromBase(
            (new UpFile(
                __DIR__ . '/Files/CE20191211171004801' . $this->affinitas . '.rep',
                'CE20191211171004801' . $this->affinitas . '.rep',
                'text/plain',
                20416,
                0,
                true
            ))
        );

        $this->post("/$this->pName/responses/storeReps", [
            'files' => [$file],
        ]);

        $this->assertDatabaseHas('reps'. $this->pName, [
            'user_id' => '288781',
            'autorizacion' => '062932'
        ]);
        $this->assertDatabaseHas('reps'. $this->pName, [
            'user_id' => '234993',
            'detalle_mensaje' => 'Rechazada'
        ]);
    }

}
