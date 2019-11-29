<?php

namespace Tests\Unit\Http\Controllers\Exports;

use App\AliadoBillingUsers;
use App\Exports\AliadoBanorteExport;
use App\UserTdcAliado;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AliadoBanorteControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function csv_billing_method_triggers_an_export()
    {
        $this->signIn();
        $this->withoutExceptionHandling();

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
        factory(UserTdcAliado::class)->create([
            'user_id' => $expired1->user_id,

        ]);
        factory(UserTdcAliado::class)->create([
            'user_id' => $expired2->user_id,
        ]);
        factory(UserTdcAliado::class)->create([
            'user_id' => $vigent1->user_id,
        ]);
        factory(UserTdcAliado::class)->create([
            'user_id' => $vigent2->user_id,
        ]);
        factory(UserTdcAliado::class)->create([
            'user_id' => $vigent3->user_id,
        ]);
        $today = now()->format('Y-m-d');

        $activated = $this->get('/aliado/banorte/csvBilling');

        $this->assertInstanceOf(AliadoBanorteExport::class, $activated);
    }

}
