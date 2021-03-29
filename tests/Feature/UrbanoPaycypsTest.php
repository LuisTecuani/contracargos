<?php

namespace Tests\Feature;

use App\UrbanoPaycypsBill;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\UploadedFile as Upfile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UrbanoPaycypsTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    public function method_store_persist_data_on_contracargos_table()
    {
        $this->signIn();
        $charge1 = factory(UrbanoPaycypsBill::class)->create([
            'tdc' => '5432161111110552',
        ]);
        $charge2 = factory(UrbanoPaycypsBill::class)->create([
            'tdc' => '5432109876543210',
        ]);
        $charge3 = factory(UrbanoPaycypsBill::class)->create([
            'tdc' => '4567890123456789',
        ]);

        $this->post('/urbano/paycyps/chargeback/store', [
            'cards' => "543216%0552\r\n456789%6789",
            'chargeback_date' => '2020-06-22'
        ]);

        $this->assertDatabaseHas('contracargos_urbano_paycyps', [
            'tdc' => $charge1->tdc,
            'chargeback_date' => '2020-06-22',
        ]);
        $this->assertDatabaseMissing('contracargos_urbano_paycyps', [
            'tdc' => $charge2->tdc,
        ]);
        $this->assertDatabaseHas('contracargos_urbano_paycyps', [
            'tdc' => $charge3->tdc,
            'chargeback_date' => '2020-06-22',
        ]);
    }

    /** @test */
    public function admins_can_browse_to_the_index_page()
    {
        $this->signIn();

        $this->get('/urbano/paycyps')
            ->assertOk()
            ->assertSessionHasNoErrors()
            ->assertViewIs('urbano.paycyps.index');
    }

    /** @test */
    public function can_import_users_from_csv_to_urbano_paycyps_bills()
    {
        $this->signIn();
        $file = UploadedFile::createFromBase(
            (new UpFile(
                __DIR__ . '/files/urbano-paycyps-2020-07-13.csv',
                'urbano-paycyps-2020-07-13.csv',
                'text/csv',
                20416,
                0,
                true
            ))
        );

        $this->post('/urbano/paycyps/storeCsv', [
            'file' => $file,
            'folio' => 11
        ]);

        $this->assertDatabaseHas('urbano_paycyps_bills', [
            'user_id' => '72259',
            'tdc' => '5180049017032192',
            'amount' => 7900,
            'bill_day' => 14,
            'paycyps_id' => '11_2',
            'file_name' => 'urbano-paycyps-2020-07-13.csv',
        ]);
        $this->assertEquals(5, UrbanoPaycypsBill::all()->count());
    }


    /** @test */
    public function a_user_can_update_deleted_at_on_urbano_paycyps_bills()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $charge1 = factory(UrbanoPaycypsBill::class)->create([
            'tdc' => '5432161111110552',
        ]);
        $charge2 = factory(UrbanoPaycypsBill::class)->create([
            'tdc' => '5432109876543210',
        ]);
        $charge3 = factory(UrbanoPaycypsBill::class)->create([
            'tdc' => '4567890123456789',
        ]);

        $this->post('/urbano/paycyps/update', [
            'cards' => "543216%0552\r\n456789%6789",
            'deleted_at' => '2020-06-22'
        ]);

        $this->assertDatabaseHas('urbano_paycyps_bills', [
            'tdc' => $charge1->tdc,
            'deleted_at' => '2020-06-22',
        ]);
        $this->assertDatabaseMissing('urbano_paycyps_bills', [
            'tdc' => $charge2->tdc,
            'deleted_at' => '2020-06-22',
        ]);
        $this->assertDatabaseHas('urbano_paycyps_bills', [
            'tdc' => $charge3->tdc,
            'deleted_at' => '2020-06-22',
        ]);
    }

    /** @test */
    public function a_user_can_update_deleted_at_with_file_on_urbano_paycyps_bills()
    {
        $this->signIn();
        $charge1 = factory(UrbanoPaycypsBill::class)->create([
            'tdc' => '5491380215318495',
        ]);
        $charge2 = factory(UrbanoPaycypsBill::class)->create([
            'tdc' => '5432109876543210',
        ]);
        $charge3 = factory(UrbanoPaycypsBill::class)->create([
            'tdc' => '4213169304175266',
        ]);
        $file = UploadedFile::createFromBase(
            (new UpFile(
                __DIR__ . '/files/urbano-paycips-bajas-2021-02-22.csv',
                'urbano-paycips-bajas-2021-02-22.csv',
                'text/csv',
                20416,
                0,
                true
            ))
        );

        $this->post('/urbano/paycyps/updateCsv', [
            'file' => $file,
        ]);

        $this->assertDatabaseHas('urbano_paycyps_bills', [
            'tdc' => $charge1->tdc,
            'deleted_at' => '2021-02-22',
        ]);
        $this->assertDatabaseMissing('urbano_paycyps_bills', [
            'tdc' => $charge2->tdc,
            'deleted_at' => '2021-02-22',
        ]);
        $this->assertDatabaseHas('urbano_paycyps_bills', [
            'tdc' => $charge3->tdc,
            'deleted_at' => '2021-02-22',
        ]);
    }
}
