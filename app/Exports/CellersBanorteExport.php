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
            ->selectRaw("'AUTH' as CMD_TRANS,'79' as MONTO, 'Cargo unico' as COMENTARIOS, '1' as LOTE, user_id,
                'CELLERS' as REFERENCIA_PROSA, number as NUMERO_TARJETA, exp_date as FECHA_EXP")
            ->where([['created_at', 'like', now()->format('Y-m-d').'%'],['procedence', 'like', 'para banorte']])
            ->get();
    }


    /**
     * @param  mixed  $row
     * @return array
     */
    public function map($row) : array
    {
        if($row->FECHA_EXP < now()->format('y-m')) {
            $row->FECHA_EXP = Carbon::now()->addYears(rand(0, 7))->format('m/y');
        } else {
            $row->FECHA_EXP = DateTime::createFromFormat('y-m',$row->FECHA_EXP)->format('m/y');
        }
        $now = now()->format('Ymd');
        return [
            $row->CMD_TRANS,
            $row->MONTO,
            $row->COMENTARIOS,
            $row->LOTE,
            $now.$row->user_id,
            $row->user_id,
            $row->REFERENCIA_PROSA,
            $row->NUMERO_TARJETA,
            $row->FECHA_EXP,
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
