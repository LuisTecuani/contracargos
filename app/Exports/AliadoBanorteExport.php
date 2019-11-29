<?php

namespace App\Exports;

use App\AliadoBillingUsers;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AliadoBanorteExport implements FromCollection, WithMapping, WithHeadings
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $now = now()->format('Ymd');
        $data = AliadoBillingUsers::with('cards')->get()
        ->selectRaw("'AUTH' as CMD_TRANS,'79' as MONTO, 'Cargo unico' as COMENTARIOS, '1' as LOTE,
         user_id as NUMERO_CONTROL, user_id as NUMERO_CONTRATO, 
         'ALIADO_E_TICKET' as REFERENCIA_PROSA, number as NUMERO_TARJETA, exp_date as FECHA_EXP")
            ->where('exp_date', '>=', now()->format('m-y'))
            ->get();
        dd($data);
    }


    /**
     * @param  mixed  $row
     * @return array
*/
    public function map($row) : array
    {
        return [
            $row->a,
            $row->b,
            $row->c,
            $row->d,
            $row->f,
            $row->g,
            $row->h,
            $row->j,
            $row->k,
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
