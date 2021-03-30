<?php

namespace Tests\Feature;

use App\AliadoPaycypsHistoric;
use App\CellersPaycypsBill;
use App\CellersPaycypsHistoric;
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
                __DIR__ . '/files/Cellers-paycips-tran-2021-05-15.xls',
                'Cellers-paycips-tran-2021-05-15.xls',
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
            "id" => "1",
            "Folio" => "1007995",
            "Fecha_Operacion" => "2021-05-15 00:10:26",
            "Fecha_Liq" => "2020-05-18",
            "Tarjeta" => "4178497546",
            "Banco" => "LIVERPOOL P.C.",
            "Producto" => "LIVERPOOL PREMIUM CARD",
            "Importe_Venta" => "99.00",
            "Importe_Original" => "99.00",
            "Divisa" => "MXP",
            "Comision_Cobrada" => "28.95",
            "Costo" => "",
            "Autorizacion" => "",
            "Tipo_Operacion" => "Venta",
            "Tipo_Bin" => "Credito",
            "Terminal" => "543 Websam de Mexico",
            "Comercio" => "REC",
            "Ref2" => "",
            "Ref3" => "",
            "Ref4" => "0",
            "Ticket" => "22678",
            "Codigo_Respuesta" => "51  ",
            "Descripcion" => "Fondos insuficientes",
            "file_name" => "Cellers-paycips-tran-2021-05-15.xls",
        ]);
        $this->assertCount(5, CellersPaycypsHistoric::all());
    }

    /**  @test */
    public function a_user_can_import_liquidations_to_cellers_paycips_historics_from_xls_file()
    {
        $this->signIn();
        $file = UploadedFile::createFromBase(
            (new UpFile(
                __DIR__ . '/files/Cellers-liq-2021-04-23.xls',
                'Cellers-liq-2021-04-23.xls',
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

            "Folio" => "939516",
            "Fecha_Operacion" => "2021-01-27 18:45:29",
            "Fecha_Liq" => "2021-01-27",
            "Tarjeta" => "5188531493",
            "Banco" => "BANAMEX TELETON",
            "Importe_Venta" => "(90.00)",
            "Comision_Cobrada" => "0.00",
            "Costo" => "(90.00)",
            "Autorizacion" => "084818",
            "Tipo_Operacion" => "Contracargo",
            "Tipo_Bin" => "Credito",
            "Terminal" => "543 Websam de Mexico",
            "Comercio" => "REC",
            "Ref3" => "",
            "Ticket" => "101748",
            "file_name" => "Cellers-liq-2021-04-23.xls",
        ]);
    }
}

