<?php

namespace Tests\Feature;

use App\CellersPaycypsBill;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\UploadedFile as Upfile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BillingCellersPaycypsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admins_can_browse_to_the_index_page()
    {
        $this->signIn();

        $this->get('/cellers/paycyps')
            ->assertOk()
            ->assertSessionHasNoErrors()
            ->assertViewIs('cellers.paycyps.index');
    }

    /** @test */
    public function can_import_users_from_csv_to_cellers_paycyps_bills()
    {
        $this->signIn();
        $file = UploadedFile::createFromBase(
            (new UpFile(
                __DIR__ . '/files/cellers-paycyps-2020-07-13.csv',
                'cellers-paycyps-2020-07-13.csv',
                'text/csv',
                20416,
                0,
                true
            ))
        );

        $this->post('/cellers/paycyps/storeCsv', [
            'file' => $file,
            'folio' => 11
        ]);

        $this->assertDatabaseHas('cellers_paycyps_bills', [
            'user_id' => '72259',
            'tdc' => '5180049017032192',
            'amount' => 9000,
            'bill_day' => 14,
            'paycyps_id' => '11_2',
            'file_name' => 'cellers-paycyps-2020-07-13.csv',
        ]);
        $this->assertEquals(5, CellersPaycypsBill::all()->count());
    }

    /** @test */
    public function a_user_can_update_deleted_at_on_cellers_paycyps_bills()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $charge1 = factory(CellersPaycypsBill::class)->create([
            'tdc' => '5432161111110552',
        ]);
        $charge2 = factory(CellersPaycypsBill::class)->create([
            'tdc' => '5432109876543210',
        ]);
        $charge3 = factory(CellersPaycypsBill::class)->create([
            'tdc' => '4567890123456789',
        ]);

        $this->post('/cellers/paycyps/update', [
            'cards' => "543216%0552\r\n456789%6789",
            'deleted_at' => '2020-06-22'
        ]);

        $this->assertDatabaseHas('cellers_paycyps_bills', [
            'tdc' => $charge1->tdc,
            'deleted_at' => '2020-06-22',
        ]);
        $this->assertDatabaseMissing('cellers_paycyps_bills', [
            'tdc' => $charge2->tdc,
            'deleted_at' => '2020-06-22',
        ]);
        $this->assertDatabaseHas('cellers_paycyps_bills', [
            'tdc' => $charge3->tdc,
            'deleted_at' => '2020-06-22',
        ]);
    }
}
