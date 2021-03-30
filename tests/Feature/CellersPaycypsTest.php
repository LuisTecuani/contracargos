<?php

namespace Tests\Feature;

use App\CellersPaycypsHistoric;
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
    /** @test */
    public function a_user_can_import_movements_to_cellers_paycyps_historics_from_xls_file()
    {
        $this->signIn();
        $file = UploadedFile::createFromBase(
            (new UpFile(
                __DIR__ . '/files/cellers-paycips-tran-2021-03-12.xls',
                'cellers-paycips-tran-2021-04-13.xls',
                'text/xls',
                20416,
                0,
                true
            ))
        );
        $this->assertCount(0, CellersPaycypsHistoric::all());

        $this->post('/cellers/paycyps/historic/store', [
            'files' => [$file],
        ]);

        $this->assertDatabaseHas('cellers_paycyps_historics', [
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
            'file_name' => 'cellers-paycips-tran-2021-04-13.xls',
        ]);
        $this->assertCount(7, CellersPaycypsHistoric::all());
    }
}
