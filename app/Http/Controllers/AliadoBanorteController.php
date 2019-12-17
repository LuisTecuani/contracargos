<?php

namespace App\Http\Controllers;

use App\AliadoBillingUsers;
use App\AliadoBlacklist;
use App\AliadoCancelAccountAnswer;
use App\AliadoUser;
use App\AliadoUserCancellation;
use App\Exports\AliadoBanorteExport;
use App\RespuestasBanorteAliado;
use App\UserTdcAliado;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AliadoBanorteController extends Controller
{
    public function index()
    {
        return view('aliado.banorte.index');
    }


    public function ftpProsa()
    {
        $expUsers = $this->expDates();
        $verified = $this->notInBlacklists($expUsers);
     //   $text = $this->ftpText($verified);

        return view('/aliado/banorte/paraFTP', compact('verified'));

    }

    public function csvBanorte()
    {
        $vigUsers = $this->vigDates();
        $verified = $this->notInBlacklists($vigUsers);


        return view('/aliado/banorte/paraFTP', compact('verified'));

    }

    public function notInBlacklists($ids)
    {
        return AliadoUser::select('id')->whereIn('id',$ids)->whereNull('deleted_at')->get()
            ->diff(AliadoCancelAccountAnswer::select('user_id as id')->get())
            ->diff(AliadoBlacklist::select('user_id as id')->get())
            ->diff(AliadoUserCancellation::select('user_id as id')->get());
    }

    public function notCancelled($ids)
    {
        return DB::table('aliado.users as u')
            ->leftJoin('aliado.user_cancellations as au', 'au.user_id', '=', 'u.id')
            ->select('u.id')
            ->whereIn('u.id', $ids)
            ->whereNull(['au.user_id', 'u.deleted_at'])
            ->get();
    }


    public function ftpText($verified)
    {
        $query = UserTdcAliado::selectRaw("concat('801089727', user_id,'                 ', number,'   00000000079.0000', user_id, '              ')")
            ->whereIn('user_id', $verified);

        $ftpText = UserTdcAliado::selectRaw("concat(DATE_FORMAT(CURDATE(), '%d%m%Y'),'100101',LPAD(count(user_id), 6, '0'),LPAD(count(user_id)*79, 13, '0'),'.00                                                   ') as row")
            ->whereIn('user_id', $verified)
            ->union($query)
            ->get();
        dd($ftpText);
    }
}
