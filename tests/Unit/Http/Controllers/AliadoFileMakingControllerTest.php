<?php

namespace Tests\Unit\Http\Controllers;

use App\AliadoBillingUsers;
use App\AliadoCancelAccountAnswer;
use App\AliadoUser;
use App\Exports\AliadoBanorteExport;
use App\RespuestasBanorteAliado;
use App\UserTdcAliado;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class AliadoFileMakingControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admins_can_browse_to_the_index_page()
    {
        $this->signIn();

        $this->get('/aliado/file_making')
            ->assertOk()
            ->assertSessionHasNoErrors()
            ->assertViewIs('aliado.file_making.index');
    }

    /** test  */
    public function it_can_build_a_valid_ftp_file()
    {
        $this->signIn();
        $this->withoutExceptionHandling();
        $user1 = factory(AliadoBillingUsers::class)->create([
            'exp_date' => '18-10',
        ]);
        $user2 = factory(AliadoBillingUsers::class)->create([
            'exp_date' => '17-01',
        ]);
        $user3 = factory(AliadoBillingUsers::class)->create([
            'exp_date' => '27-01',
        ]);
        factory(UserTdcAliado::class)->create([
            'user_id' => $user1->user_id,
            'number' => $user1->number,
        ]);
        factory(UserTdcAliado::class)->create([
            'user_id' => $user2->user_id,
            'number' => $user2->number,
        ]);
        factory(UserTdcAliado::class)->create([
            'user_id' => $user3->user_id,
            'number' => $user3->number,
        ]);
        factory(AliadoCancelAccountAnswer::class)->create([
            'user_id' => $user3->user_id,
        ]);

        $response = $this->get('/aliado/file_making/export0897');

        $this->assertFileExist("SCAENT0897D" . now()->format('ymd') . "ER01.ftp");
    }




/*

namespace Tests\Unit\Exports;

    use App\Exports\ProsaExport;
    use App\Imports\Importer;
    use App\Models\FileEntry;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Illuminate\Support\Facades\Event;
    use Tests\TestCase;

class BanorteExportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();
        Event::fake();
        $this->billing_datetime = now();
        $file = factory(FileEntry::class)->states('mediakey_source')->create();
        $charges = Importer::toCollection($file, $file->name, 'withoutFunds');

        $this->export = new ProsaExport(
            $charges,
            'mediakey',
            $this->billing_datetime->toDateTimeString()
        );
    }



    /** @test */     /*
    public function can_set_the_right_billing_charge_count_format()
    {
        $charges_count = $this->export->setChargesCount(325);

        $this->assertEquals('000325', $charges_count);
    }

    /** @test */ /*
    public function can_set_the_right_billing_total_amount_format()
    {
        $data = collect([
            ['amount' => 199],
            ['amount' => 139],
            ['amount' => 100],
            ['amount' => 1154],
            ['amount' => 597],
        ]);

        $total_amount = $this->export->setTotalAmount($data);

        $this->assertEquals('0000000002189.00', $total_amount);
    }

    /** @test */ /*
    public function can_set_the_right_customer_id_format()
    {
        $id = 197984;

        $customer_id = $this->export->setCustomerId($id);

        $this->assertEquals('197984                 ', $customer_id);
    }

    /** @test */ /*
    public function can_set_the_right_card_format()
    {
        $card = 4772103004306080;

        $formatted_card = $this->export->setCard($card);

        $this->assertEquals('4772103004306080   ', $formatted_card);
    }

    /** @test */ /*
    public function can_set_the_right_charge_amount_format()
    {
        $amount = 199;

        $formatted_amount = $this->export->setChargeAmount($amount);

        $this->assertEquals('00000000199.00', $formatted_amount);
    }

    /** @test */ /*
    public function can_set_the_right_contract_format()
    {
        $id = 197984;

        $formatted_contract = $this->export->setContract($id);

        $this->assertEquals('197984              ', $formatted_contract);
    }

    /** @test */ /*
    public function can_get_the_billing_file_name()
    {
        $date = $this->billing_datetime->format('ymd');

        $this->assertEquals("SCAENT6873D{$date}ER01.ftp", $this->export->fileName);
    }

    /** @test */ /*
    public function can_set_the_billing_file_header()
    {
        $file_header = $this->export->setHeader();
        $date = $this->billing_datetime->format('dmYhis');

        $this->assertEquals(
            "{$date}0000040000000000556.00                                                   " . PHP_EOL,
            $file_header
        );
    }

    /** @test */ /*
    public function can_set_the_billing_file_content()
    {
        $content = $this->export->fileContents();

        $header = $this->export->setHeader();
        $body = $this->export->setBody();

        $this->assertEquals($content, "{$header}{$body}");
    }
}
*/

    /** @test */
    public function it_can_build_a_valid_csv_file()
    {
        Excel::fake();
        $this->signIn();
        $user1 = factory(AliadoBillingUsers::class)->create([
            'exp_date' => '18-10',
            'procedence' => 'para banorte',
        ]);
        $user2 = factory(AliadoBillingUsers::class)->create([
            'exp_date' => '17-01',
            'procedence' => 'para banorte',
        ]);
        $user3 = factory(AliadoBillingUsers::class)->create([
            'exp_date' => '27-01',
            'procedence' => 'prosa',
        ]);
        //5 random reps
        factory(RespuestasBanorteAliado::class, 5)->create();
        factory(UserTdcAliado::class)->create([
            'user_id' => $user1->user_id,
            'number' => $user1->number,
        ]);
        factory(UserTdcAliado::class)->create([
            'user_id' => $user2->user_id,
            'number' => $user2->number,
        ]);
        factory(UserTdcAliado::class)->create([
            'user_id' => $user3->user_id,
            'number' => $user3->number,
        ]);

        $this->get('/aliado/file_making/exportBanorte');

        Excel::assertDownloaded('aliado-banorte-'.now()->format('Y-m-d').'.csv', function (AliadoBanorteExport $export) {
            return $export->collection()->count() == 2;
        });
    }

}
