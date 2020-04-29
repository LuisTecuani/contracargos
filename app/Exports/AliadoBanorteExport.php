<?php

namespace App\Exports;

use App\AliadoBillingUsers;
use App\AliadoBlacklist;
use App\AliadoCancelAccountAnswer;
use App\AliadoUserCancellation;
use App\Repsaliado;
use App\RespuestasBanorteAliado;
use Carbon\Carbon;
use DateTime;
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
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', '240');

        $dates = RespuestasBanorteAliado::select('fecha')->groupBy('fecha')
            ->orderBy('fecha', 'desc')->limit(4)->get();

        $blacklist = AliadoBlacklist::select('user_id')->whereNotNull('user_id');

        $userCancellations = AliadoUserCancellation::select('user_id')
            ->whereIn('reason_id', ['1','2','3','4','5','6','7','8','9','17','26','40','42','43','44'])
        ->get()->map(function ($item) {
                return $item->user_id;
            });

        $cancelAnswers = AliadoCancelAccountAnswer::select('user_id')->get()->map(function ($item) {
            return $item->user_id;
        })->concat($userCancellations)->unique();

        $query2 = Repsaliado::select('user_id as id')
            ->where('fecha', '>=', $dates[3]->fecha)
            ->whereNotIn('motivo_rechazo', ['Ingrese un monto menor','Fondos insuficientes', 'Supera el monto límite permitido', 'Límite diario excedido', 'Imposible autorizar en este momento']);

        return AliadoBillingUsers::with('cards')
            ->select("user_id","number", "exp_date")
            ->where([['created_at', 'like', now()->format('Y-m-d').'%'],['procedence', 'like', 'para banorte']])
            ->whereNotIn('user_id', $blacklist)
            ->whereNotIn('user_id', $query2)
            ->whereNotIn('user_id', $cancelAnswers)
            ->distinct()
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
            '79',
            'Cargo unico',
            '1',
            $now.$row->user_id,
            $row->user_id,
            'ALIADO_E_TICKET',
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
