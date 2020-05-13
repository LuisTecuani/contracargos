<?php

namespace Tests\Unit\Http\Controllers;

use App\CellersBillingUsers;
use App\CellersUser;
use App\Exports\CellersBanorteExport;
use App\UserTdcCellers;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class CellersFileMakingControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admins_can_browse_to_the_index_page()
    {
        $this->signIn();

        $this->get('/cellers/file_making')
            ->assertOk()
            ->assertSessionHasNoErrors()
            ->assertViewIs('cellers.file_making.index');
    }

    /** @test */
    public function it_can_build_a_valid_csv_file()
    {
        Excel::fake();
        $this->signIn();
        $user1 = factory(CellersBillingUsers::class)->create([
            'exp_date' => '18-10',
            'procedence' => 'para banorte',
        ]);
        $user2 = factory(CellersBillingUsers::class)->create([
            'exp_date' => '17-01',
            'procedence' => 'para banorte',
        ]);
        $user3 = factory(CellersBillingUsers::class)->create([
            'exp_date' => '27-01',
            'procedence' => 'prosa',
        ]);
        factory(UserTdcCellers::class)->create([
            'user_id' => $user1->user_id,
            'number' => $user1->number,
        ]);
        factory(UserTdcCellers::class)->create([
            'user_id' => $user2->user_id,
            'number' => $user2->number,
        ]);
        factory(UserTdcCellers::class)->create([
            'user_id' => $user3->user_id,
            'number' => $user3->number,
        ]);

        $this->get('/cellers/file_making/exportBanorte');

        Excel::assertDownloaded('cellers-banorte-'.now()->format('Y-m-d').'.csv', function (CellersBanorteExport $export) {
            return $export->collection()->count() == 2;
        });
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
