<?php

namespace Tests\Feature;

use App\AliadoPaycypsBill;
use App\AliadoPaycypsHistoric;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\UploadedFile as Upfile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AliadoPaycypsTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    public function method_store_persist_data_on_contracargos_table()
    {
        $this->signIn();
        $charge1 = factory(AliadoPaycypsBill::class)->create([
            'tdc' => '5432161111110552',
        ]);
        $charge2 = factory(AliadoPaycypsBill::class)->create([
            'tdc' => '5432109876543210',
        ]);
        $charge3 = factory(AliadoPaycypsBill::class)->create([
            'tdc' => '4567890123456789',
        ]);

        $this->post('/aliado/paycyps/chargeback/store', [
            'cards' => "543216%0552\r\n456789%6789",
            'chargeback_date' => '2020-06-22'
        ]);

        $this->assertDatabaseHas('contracargos_aliado_paycyps', [
            'tdc' => $charge1->tdc,
            'chargeback_date' => '2020-06-22',
        ]);
        $this->assertDatabaseMissing('contracargos_aliado_paycyps', [
            'tdc' => $charge2->tdc,
        ]);
        $this->assertDatabaseHas('contracargos_aliado_paycyps', [
            'tdc' => $charge3->tdc,
            'chargeback_date' => '2020-06-22',
        ]);
    }

    /** @test */
    public function admins_can_browse_to_the_index_page()
    {
        $this->signIn();

        $this->get('/aliado/paycyps')
            ->assertOk()
            ->assertSessionHasNoErrors()
            ->assertViewIs('aliado.paycyps.index');
    }

    /** @test */
    public function can_import_users_from_csv_to_aliado_paycyps_bills()
    {
        $this->signIn();
        $file = UploadedFile::createFromBase(
            (new UpFile(
                __DIR__ . '/files/aliado-paycyps-2020-07-13.csv',
                'aliado-paycyps-2020-07-13.csv',
                'text/csv',
                20416,
                0,
                true
            ))
        );

        $this->post('/aliado/paycyps/storeCsv', [
            'file' => $file,
            'folio' => 11
        ]);

        $this->assertDatabaseHas('aliado_paycyps_bills', [
            'user_id' => '72259',
            'tdc' => '5180049017032192',
            'amount' => 7900,
            'bill_day' => 14,
            'paycyps_id' => '11_2',
            'file_name' => 'aliado-paycyps-2020-07-13.csv',
        ]);
        $this->assertEquals(5, AliadoPaycypsBill::all()->count());
    }


    /** @test */
    public function a_user_can_update_deleted_at_with_card_number_on_aliado_paycyps_bills()
    {
        $this->signIn();
        $charge1 = factory(AliadoPaycypsBill::class)->create([
            'tdc' => '5432161111110552',
        ]);
        $charge2 = factory(AliadoPaycypsBill::class)->create([
            'tdc' => '5432109876543210',
        ]);
        $charge3 = factory(AliadoPaycypsBill::class)->create([
            'tdc' => '4567890123456789',
        ]);

        $this->post('/aliado/paycyps/update', [
            'cards' => "543216%0552\r\n456789%6789",
            'deleted_at' => '2020-06-22'
        ]);

        $this->assertDatabaseHas('aliado_paycyps_bills', [
            'tdc' => $charge1->tdc,
            'deleted_at' => '2020-06-22',
        ]);
        $this->assertDatabaseMissing('aliado_paycyps_bills', [
            'tdc' => $charge2->tdc,
            'deleted_at' => '2020-06-22',
        ]);
        $this->assertDatabaseHas('aliado_paycyps_bills', [
            'tdc' => $charge3->tdc,
            'deleted_at' => '2020-06-22',
        ]);
    }

    /** @test */
    public function a_user_can_update_deleted_at_with_file_on_aliado_paycyps_bills()
    {
        $this->signIn();
        $charge1 = factory(AliadoPaycypsBill::class)->create([
            'tdc' => '5432161111110552',
        ]);
        $charge2 = factory(AliadoPaycypsBill::class)->create([
            'tdc' => '5432109876543210',
        ]);
        $charge3 = factory(AliadoPaycypsBill::class)->create([
            'tdc' => '4567890123456789',
        ]);
        $file = UploadedFile::createFromBase(
            (new UpFile(
                __DIR__ . '/files/aliado-paycips-bajas-2021-02-22.csv',
                'aliado-paycips-bajas-2021-02-22.csv',
                'text/csv',
                20416,
                0,
                true
            ))
        );

        $this->post('/aliado/paycyps/updateCsv', [
            'file' => $file,
        ]);

        $this->assertDatabaseHas('aliado_paycyps_bills', [
            'tdc' => $charge1->tdc,
            'deleted_at' => '2021-02-22',
        ]);
        $this->assertDatabaseMissing('aliado_paycyps_bills', [
            'tdc' => $charge2->tdc,
            'deleted_at' => '2021-02-22',
        ]);
        $this->assertDatabaseHas('aliado_paycyps_bills', [
            'tdc' => $charge3->tdc,
            'deleted_at' => '2021-02-22',
        ]);
    }

    /** @test */
    public function a_user_can_import_movements_to_aliado_paycyps_historics()
    {
        $this->signIn();
        $file = UploadedFile::createFromBase(
            (new UpFile(
                __DIR__ . '/files/aliado-paycips-liq-2021-03-09.csv',
                'aliado-paycips-liq-2021-03-09.csv',
                'text/csv',
                20416,
                0,
                true
            ))
        );
        $this->assertCount(0, AliadoPaycypsHistoric::all());

        $this->post('/aliado/paycyps/historic/storeFolios', [
            'files' => [$file],
        ]);

        $this->assertDatabaseHas('aliado_paycyps_historics', [
            'Folio' => '828992',
            'Fecha_Operacion' => '2020-12-13 12:00',
            'Fecha_Liq' => '2020-12-11',
            'Tarjeta' => '5204162693',
            'Banco' => 'BANAMEX CUENTA PERFILES',
            'Importe_Venta' => '(79.00)',
            'Comision_Cobrada' => '0',
            'Costo' => '(79.00)',
            'Autorizacion' => '1180',
            'Tipo_Operacion' => 'Contracargo',
            'Tipo_Bin' => 'Débito',
            'Terminal' => '553 Aliado eTickets',
            'Comercio' => 'REC',
            'Ticket' => '254488',
            'file_name' => 'aliado-paycips-liq-2021-03-09.csv',
        ]);
        $this->assertCount(4, AliadoPaycypsHistoric::all());
    }

    /** @test */
    public function a_user_can_import_movements_to_aliado_paycyps_historics_from_xls_file()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $file = UploadedFile::createFromBase(
            (new UpFile(
                __DIR__ . '/files/aliado-paycips-tran-2021-03-12.xls',
                'aliado-paycips-tran-2021-03-12.xls',
                'text/xls',
                20416,
                0,
                true
            ))
        );
        $this->assertCount(0, AliadoPaycypsHistoric::all());

        $this->post('/aliado/paycyps/historic/store', [
            'files' => [$file],
        ]);

        $this->assertDatabaseHas('aliado_paycyps_historics', [
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
            'file_name' => 'aliado-paycips-tran-2021-03-12.xls',
        ]);
        $this->assertCount(7, AliadoPaycypsHistoric::all());
    }
}



