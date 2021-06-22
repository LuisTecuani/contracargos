<?php

namespace Tests\Feature;

use App\ThxPaycypsBill;
use App\ThxPaycypsHistoric;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\UploadedFile as Upfile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThxPaycypsTest extends TestCase
{
    use RefreshDatabase;


    /** @test */

    public function method_store_persist_data_on_contracargos_table()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $charge1 = factory(ThxPaycypsBill::class)->create([
            'tdc' => '5432161111110552',
        ]);
        $charge2 = factory(ThxPaycypsBill::class)->create([
            'tdc' => '5432109876543210',
        ]);
        $charge3 = factory(ThxPaycypsBill::class)->create([
            'tdc' => '4567890123456789',
        ]);

        $this->post('/thx/paycyps/chargeback/store', [
            'cards' => "543216%0552\r\n456789%6789",
            'chargeback_date' => '2020-06-22'
        ]);

        $this->assertDatabaseHas('contracargos_thx_paycyps', [
            'tdc' => $charge1->tdc,
            'chargeback_date' => '2020-06-22',
        ]);
        $this->assertDatabaseMissing('contracargos_thx_paycyps', [
            'tdc' => $charge2->tdc,
        ]);
        $this->assertDatabaseHas('contracargos_thx_paycyps', [
            'tdc' => $charge3->tdc,
            'chargeback_date' => '2020-06-22',
        ]);
    }

    /** @test */
    public function admins_can_browse_to_the_index_page()
    {
        $this->signIn();

        $this->get('/thx/paycyps')
            ->assertOk()
            ->assertSessionHasNoErrors()
            ->assertViewIs('thx.paycyps.index');
    }

    /** @test */
    public function can_import_users_from_csv_to_thx_paycyps_bills()
    {
        $this->signIn();
        $file = UploadedFile::createFromBase(
            (new UpFile(
                __DIR__ . '/files/thx-paycyps-2020-07-13.csv',  //toma el file de urbano-paycyps-2020-07-13 y modificalo
                'thx-paycyps-2020-07-13.csv',
                'text/csv',
                20416,
                0,
                true
            ))
        );

        $this->post('/thx/paycyps/storeCsv', [
            'file' => $file,
            'folio' => 11
        ]);

        $this->assertDatabaseHas('thx_paycyps_bills', [
            'user_id' => '72259',
            'tdc' => '5180049017032192',
            'amount' => 7900,
            'bill_day' => 14,
            'paycyps_id' => '11_2',
            'file_name' => 'thx-paycyps-2020-07-13.csv',
        ]);
        $this->assertEquals(5, ThxPaycypsBill::all()->count());
    }


    /** @test */
    public function a_user_can_update_deleted_at_on_thx_paycyps_bills()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $charge1 = factory(ThxPaycypsBill::class)->create([
            'tdc' => '5432161111110552',
        ]);
        $charge2 = factory(ThxPaycypsBill::class)->create([
            'tdc' => '5432109876543210',
        ]);
        $charge3 = factory(ThxPaycypsBill::class)->create([
            'tdc' => '4567890123456789',
        ]);

        $this->post('/thx/paycyps/update', [
            'cards' => "543216%0552\r\n456789%6789",
            'deleted_at' => '2020-06-22'
        ]);

        $this->assertDatabaseHas('thx_paycyps_bills', [
            'tdc' => $charge1->tdc,
            'deleted_at' => '2020-06-22',
        ]);
        $this->assertDatabaseMissing('thx_paycyps_bills', [
            'tdc' => $charge2->tdc,
            'deleted_at' => '2020-06-22',
        ]);
        $this->assertDatabaseHas('thx_paycyps_bills', [
            'tdc' => $charge3->tdc,
            'deleted_at' => '2020-06-22',
        ]);
    }

    /** @test */
    public function a_user_can_update_deleted_at_with_file_on_thx_paycyps_bills()
    {
        $this->signIn();
        $charge1 = factory(ThxPaycypsBill::class)->create([
            'tdc' => '5491380215318495',
        ]);
        $charge2 = factory(ThxPaycypsBill::class)->create([
            'tdc' => '5432109876543210',
        ]);
        $charge3 = factory(ThxPaycypsBill::class)->create([
            'tdc' => '4213169304175266',
        ]);
        $file = UploadedFile::createFromBase(
            (new UpFile(
                __DIR__ . '/files/thx-paycips-bajas-2021-02-22.csv', // copia y modifica el file de urbano-paycips-bajas-2021-02-22
                'thx-paycips-bajas-2021-02-22.csv',
                'text/csv',
                20416,
                0,
                true
            ))
        );

        $this->post('/thx/paycyps/updateCsv', [
            'file' => $file,
        ]);

        $this->assertDatabaseHas('thx_paycyps_bills', [
            'tdc' => $charge1->tdc,
            'deleted_at' => '2021-02-22',
        ]);
        $this->assertDatabaseMissing('thx_paycyps_bills', [
            'tdc' => $charge2->tdc,
            'deleted_at' => '2021-02-22',
        ]);
        $this->assertDatabaseHas('thx_paycyps_bills', [
            'tdc' => $charge3->tdc,
            'deleted_at' => '2021-02-22',
        ]);
    }

    /** @test */
    public function a_user_can_import_movements_to_thx_paycyps_historics_from_xls_file()
    {
        $this->signIn();
        $file = UploadedFile::createFromBase(
            (new UpFile(
                __DIR__ . '/files/thx-paycips-tran-2021-03-12.xls', //copia y modifica el file de urbano
                'thx-paycips-tran-2021-03-12.xls',
                'text/xls',
                20416,
                0,
                true,
            ))
        );
        $this->assertCount(0, ThxPaycypsHistoric::all());

        $this->post('/thx/paycyps/historic/store', [
            'files' => [$file],
        ]);

        $this->assertDatabaseHas('thx_paycyps_historics', [
            'Folio' => '839334',
            'Fecha_Operacion' => '2021-03-12 00:23:42',
            'Fecha_Liq' => '2021-03-17',
            'Tarjeta' => '4023180959',
            'Banco' => 'IXE',
            'Producto' => 'IXE VISA INFINITE',
            'Importe_Venta' => '79.00',
            'Importe_Original' => '79.00',
            'Divisa' => 'MXP',
            'Comision_Cobrada' => '15.27',
            'Costo' => '',
            'Autorizacion' => '000478',
            'Tipo_Operacion' => 'Venta',
            'Tipo_Bin' => 'Credito',
            'Terminal' => '553 Aliado eTickets',
            'Comercio' => 'REC',
            'Ref2' => '603',
            'Ref3' => '',
            'Ref4' => '',
            'Ticket' => '254008',
            'Codigo_Respuesta' => '00  ',
            'Descripcion' => 'Proceso Completo',
            'file_name' => 'thx-paycips-tran-2021-03-12.xls',
        ]);
        $this->assertCount(7, ThxPaycypsHistoric::all());
    }

    /** @test */
    public function a_user_can_import_liquidations_to_thx_paycips_historics_from_xls_file()
    {
        $this->signIn();
        $file = UploadedFile::createFromBase(
            (new UpFile(
                __DIR__ . '/files/Thx-liq-2021-06-03.xls', //copia y modifica el file de urbano
                'Thx-liq-2021-06-03.xls',
                'text/xls',
                20416,
                0,
                true
            ))
        );
        $this->assertCount(0, ThxPaycypsHistoric::all());

        $this->post('/thx/paycyps/historic/store', [
            'files' => [$file],
        ]);

        $this->assertDatabaseHas('thx_paycyps_historics', [
            'Folio' => '1100109',
            'Fecha_operacion' => '2021-05-15 00:49:54',
            'Fecha_liq' => '2021-05-14',
            'Tarjeta' => '4931739970',
            'Banco' => 'BANORTE BANORTE ORO VISA INTERNACIONAL',
            'Importe_venta' => '(90.00)',
            'Comision_cobrada' => '0.00',
            'Costo' => '(90.00)',
            'Autorizacion' => '009330',
            'Tipo_operacion' => 'Contracargo',
            'Tipo_Bin' => 'Credito',
            "Terminal" => "594 Thx",
            "Comercio" => "REC",
            "Ref3" => "",
            "Ticket" => "6399",
            "file_name" => "Thx-liq-2021-06-03.xls",
        ]);
    }
}
