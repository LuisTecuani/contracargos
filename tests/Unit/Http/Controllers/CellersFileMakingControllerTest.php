<?php

namespace Tests\Unit\Http\Controllers;

use App\CellersBillingUsers;
use App\CellersBlacklist;
use App\CellersCancellation;
use App\CellersUser;
use App\Exports\CellersBanorteExport;
use App\Repscellers;
use App\RespuestasBanorteCellers;
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

    /** @test  */
    public function exportBanorte_download_a_banorte_csv_file()
    {
        Excel::fake();
        $this->signIn();
        $date = now()->format('Y-m-d');
        //gives 4 previous billing dates
        factory(RespuestasBanorteCellers::class,4)->create();
        //user in blacklist not in final csv
        $inBlacklist = factory(CellersBlacklist::class)->create();
        factory(CellersBillingUsers::class)->create([
            'user_id' => $inBlacklist->user_id,
            'procedence' => 'para banorte'
        ]);
        //user cancelled with a terminal reason not in final csv
        $inUserCancellation = factory(CellersCancellation::class)->create([
            'reason_id' => '1'
        ]);
        factory(CellersBillingUsers::class)->create([
            'user_id' => $inUserCancellation->user_id,
            'procedence' => 'para banorte'
        ]);
        //user with accepted reps charge not in final csv
        $acceptedRep = factory(Repscellers::class)->create([
            'fecha' => $date,
            'estatus' => 'Aceptada',
            'detalle_mensaje' => 'Aceptado'
        ]);
        factory(CellersBillingUsers::class)->create([
            'user_id' => $acceptedRep->user_id,
            'procedence' => 'para banorte'
        ]);
        //user rejected reps not due founds not in final csv
        $rejectedByRechazarRep = factory(Repscellers::class)->create([
            'fecha' => $date,
            'estatus' => 'Rechazada',
            'detalle_mensaje' => 'Rechazar'
        ]);
        factory(CellersBillingUsers::class)->create([
            'user_id' => $rejectedByRechazarRep->user_id,
            'procedence' => 'para banorte'
        ]);
        //user with accepted banorte charge not in final csv
        $acceptedBanorte = factory(RespuestasBanorteCellers::class)->create([
            'fecha' => $date,
            'estatus' => 'Aceptada',
            'detalle_mensaje' => 'Aceptado'
        ]);
        factory(CellersBillingUsers::class)->create([
            'user_id' => $acceptedBanorte->user_id,
            'procedence' => 'para banorte'
        ]);
        //user rejected banorte not due founds not in final csv
        $rejectedByRechazarBanorte = factory(Repscellers::class)->create([
            'fecha' => $date,
            'estatus' => 'Rechazada',
            'detalle_mensaje' => 'Rechazar'
        ]);
        factory(CellersBillingUsers::class)->create([
            'user_id' => $rejectedByRechazarBanorte->user_id,
            'procedence' => 'para banorte'
        ]);
        //only user apropriate to be in csv
        $rejectedDueFounds = factory(CellersBillingUsers::class)->create([
            'procedence' => 'para banorte'
        ]);

        $this->get('/cellers/file_making/exportBanorte');

        Excel::assertDownloaded('cellers-banorte-'.$date.'.csv', function (CellersBanorteExport $export) use ($rejectedDueFounds) {
            return $export->collection()->first()->user_id == $rejectedDueFounds->user_id
                && $export->collection()->count() == 1;
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
