<?php

namespace Tests\Feature;

use App\CellersPaycypsBill;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CellersPaycypsChargebackControllerTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    public function method_store_persist_data_on_contracargos_table()
    {
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

        $this->post('/cellers/paycyps/chargeback/store', [
            'cards' => "543216%0552\r\n456789%6789",
            'chargeback_date' => '2020-06-22'
        ]);

        $this->assertDatabaseHas('contracargos_cellers_paycyps', [
            'tdc' => $charge1->tdc,
            'chargeback_date' => '2020-06-22',
        ]);
        $this->assertDatabaseMissing('contracargos_cellers_paycyps', [
            'tdc' => $charge2->tdc,
        ]);
        $this->assertDatabaseHas('contracargos_cellers_paycyps', [
            'tdc' => $charge3->tdc,
            'chargeback_date' => '2020-06-22',
        ]);
    }
}
