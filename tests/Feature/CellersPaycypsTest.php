<?php

namespace Tests\Feature;

use App\CellersPaycypsBill;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\UploadedFile as Upfile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CellersPaycypsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_update_deleted_at_with_file_on_cellers_paycyps_bills()
    {
        $this->signIn();
        $charge1 = factory(CellersPaycypsBill::class)->create([
            'tdc' => '4134060000709712',
        ]);
        $charge2 = factory(CellersPaycypsBill::class)->create([
            'tdc' => '5432109876543210',
        ]);
        $charge3 = factory(CellersPaycypsBill::class)->create([
            'tdc' => '5496390001018485',
        ]);
        $file = UploadedFile::createFromBase(
            (new UpFile(
                __DIR__ . '/files/cellers-paycips-bajas-2021-02-22.csv',
                'cellers-paycips-bajas-2021-02-22.csv',
                'text/csv',
                20416,
                0,
                true
            ))
        );

        $this->post('/cellers/paycyps/updateCsv', [
            'file' => $file,
        ]);

        $this->assertDatabaseHas('cellers_paycyps_bills', [
            'tdc' => $charge1->tdc,
            'deleted_at' => '2021-02-22',
        ]);
        $this->assertDatabaseMissing('cellers_paycyps_bills', [
            'tdc' => $charge2->tdc,
            'deleted_at' => '2021-02-22',
        ]);
        $this->assertDatabaseHas('cellers_paycyps_bills', [
            'tdc' => $charge3->tdc,
            'deleted_at' => '2021-02-22',
        ]);
    }
}
