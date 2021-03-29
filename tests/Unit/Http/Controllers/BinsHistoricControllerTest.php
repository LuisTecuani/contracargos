<?php

namespace Tests\Unit\Http\Controllers;

use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\File\UploadedFile as Upfile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BinsHistoricControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admins_can_browse_to_the_index_page()
    {
        $this->signIn();

        $this->get('/bins/historic')
            ->assertOk()
            ->assertSessionHasNoErrors()
            ->assertViewIs('bins.historic.index');
    }

    /** test */
    public function store_method_persist_data_on_bins_table()
    {
        $this->signIn();

        $this->post('/bins', [
            'data' => "American Express,American Express,mexico,370700\r\nBanregio,Visa,usa,436401",
        ]);

        $this->assertDatabaseHas('bins', [
            'bank' => 'American Express',
            'network' => 'American Express',
            'country' => 'mexico',
            'bin' => '370700',
        ]);
        $this->assertDatabaseHas('bins', [
            'bank' => 'Banregio',
            'network' => 'Visa',
            'country' => 'usa',
            'bin' => '436401',
        ]);
    }

    /** @test */
    public function import_method_can_persist_data_from_csv()
    {
        Excel::fake();
        $this->signIn();
        $file = UploadedFile::createFromBase(
            (new UpFile(
                __DIR__ . '/files/historic-bins.csv',
                'historic-bins.csv',
                'text/csv',
                20416,
                0,
                true
            ))
        );

        $this->post('/bins/historic/import',['file' => $file]);

        Excel::assertImported('historic-bins.csv');
    }
}
