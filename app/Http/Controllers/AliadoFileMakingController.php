<?php

namespace App\Http\Controllers;

use App\AliadoBillingUsers;
use App\AliadoBlacklist;
use App\AliadoCancelAccountAnswer;
use App\AliadoUserCancellation;
use App\Exports\AliadoBanorteExport;
use App\Repsaliado;

class AliadoFileMakingController extends Controller
{
    public function index()
    {
        return view("aliado.file_making.index");
    }

    public function exportBanorte()
    {
        return (new AliadoBanorteExport)
            ->download('aliado-banorte-'.now()->format('Y-m-d').'.csv');
    }

    public function export0897()
    {
        $cancellAcount = AliadoCancelAccountAnswer::select('user_id')->get();

        $blacklist = AliadoBlacklist::select('user_id')->whereNotNull('user_id')->get();

        $userCancellations = (new AliadoUserCancellation)->getImmovableCancel();

        $reps = Repsaliado::select('user_id');

        $users = AliadoBillingUsers::with('cards')
            ->select('user_id', 'number')
            ->where('created_at', 'like', now()->format('Y-m-d').'%')
            ->whereNotIn('user_id', $cancellAcount)
            ->whereNotIn('user_id', $blacklist)
            ->whereNotIn('user_id', $userCancellations)
            ->whereNotIn('user_id', $reps)
            ->get();

    }
}

/*
select concat(DATE_FORMAT(CURDATE(), '%d%m%Y'),'100101',LPAD(count(user_id), 6, '0'),LPAD(count(user_id)*79, 13, '0'),'.00                                                   ') from aliado.user_tdc
where user_id in ()
union all
select concat('801089727', user_id,'                 ', number,'   00000000079.0000', user_id, '              ') as cobro from aliado.user_tdc
where user_id in ()


            ->whereNotIn("left(number, 6)", ['402029','527333','473701','447800','465828','476684','404360','520830','418090','537830','546832','462974','535943','446137','444087','486290','639484'])
and left(number, 6) not in ('402029','527333','473701','447800','465828','476684','404360','520830','418090','537830','546832','462974','535943','446137','444087','486290','639484')

        where user_id not in (select user_id from aliado.cancel_account_answers)
        and user_id not in (select user_id from aliado_blacklist where user_id is not null)
        and user_id not in (select user_id from aliado.user_cancellations where reason_id in ('1','2','3','4','5','6','7','8','9','17','26','40','42','43','44'))
and user_id not in (select user_id from repsaliado where fecha >= (select fecha from repsaliado where source_file like '%3918' group by fecha order by fecha desc limit 4,1) and estatus = 'Aprobada')
and user_id not in (select user_id from respuestas_banorte_aliado where fecha >= (select fecha from respuestas_banorte_aliado group by fecha order by fecha desc limit 4,1) and estatus = 'Aprobada')
and created_at like concat(curdate(),'%') */
