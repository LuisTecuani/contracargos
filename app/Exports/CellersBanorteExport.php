<?php

namespace App\Exports;

use App\CellersBillingUsers;
use Carbon\Carbon;
use DateTime;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CellersBanorteExport implements FromCollection, WithMapping, WithHeadings
{
    use Exportable;
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return CellersBillingUsers::with('cards')
            ->select("user_id", "number", "exp_date")
            ->distinct()
            ->where([['created_at', 'like', now()->format('Y-m-d').'%'],['procedence', 'like', 'para banorte']])
            ->get();
    }


    /**
     * @param  mixed  $row
     * @return array
     */
    public function map($row) : array
    {
        if($row->exp_date < now()->format('y-m')) {
            $row->exp_date = Carbon::now()->addYears(rand(0, 7))->format('m/y');
        } else {
            $row->exp_date = DateTime::createFromFormat('y-m',$row->exp_date)->format('m/y');
        }
        $now = now()->format('Ymd');
        return [
            'AUTH',
            '90',
            'Cargo unico',
            '1',
            $now.$row->user_id,
            $row->user_id,
            'CELLERS',
            $row->number,
            $row->exp_date,
        ];
    }

    /**
     * @return array
     */
    public function headings() : array
    {
        return [
            'CMD_TRANS',
            'MONTO',
            'COMENTARIOS',
            'LOTE',
            'NUMERO_CONTROL',
            'NUMERO_CONTRATO',
            'REFERENCIA_PROSA',
            'NUMERO_TARJETA',
            'FECHA_EXP',
        ];
    }
}
