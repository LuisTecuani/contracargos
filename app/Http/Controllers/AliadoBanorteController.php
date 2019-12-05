<?php

namespace App\Http\Controllers;

use App\AliadoBillingUsers;
use App\AliadoBlacklist;
use App\AliadoCancelAccountAnswer;
use App\AliadoUser;
use App\AliadoUserCancellation;
use App\Exports\AliadoBanorteExport;
use App\RespuestaBanorteAliado;
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

    public function ftp(Request $request)
    {
        $procedence = $request->procedence;

        $file = $request->file('file');

        $rows = preg_grep("/(801089727)/", file($file));;

        foreach ($rows as $row) {
            $id = substr($row, 9, 6);

            $data = UserTdcAliado::select("exp_month", "exp_year", "number")
                ->where('user_id', '=', $id)
                ->latest()
                ->first();

            $d = $data->exp_month . substr($data->exp_year, -2);

            AliadoBillingUsers::create([
                'user_id' => $id,

                'procedence' => $procedence,

                'exp_date' => DateTime::createFromFormat('y-m', substr($d, -2, 2)
                    . '-' . substr($d, 0, -2))->format('y-m'),

                'number' => $data->number
            ]);
        }

        $expUsers = count($this->expDates());
        $vigUsers = count($this->vigDates());

        return view('/aliado/banorte/results', compact('expUsers', 'vigUsers'));
    }

    public function billingRejected(Request $request)
    {
        $procedence = $request->procedence;

        $date = $request->date;

        $users = RespuestaBanorteAliado::select('user_id as id')
            ->whereIn('detalle_mensaje', ['Fondos insuficientes', 'Supera el monto límite permitido', 'Límite diario excedido', 'Imposible autorizar en este momento'])
            ->where(
                'fecha', 'like', $date
            )->get();

        foreach ($users as $user) {

            $data = UserTdcAliado::select("exp_month", "exp_year", "number")
                ->where('user_id', '=', $user->id)
                ->latest()
                ->first();

            $d = $data->exp_month . substr($data->exp_year, -2);

            AliadoBillingUsers::create([
                'user_id' => $user->id,

                'procedence' => $procedence,

                'exp_date' => DateTime::createFromFormat('y-m', substr($d, -2, 2)
                    . '-' . substr($d, 0, -2))->format('y-m'),

                'number' => $data->number
            ]);
        }
        $expUsers = count($this->expDates());
        $vigUsers = count($this->vigDates());

        return view('/aliado/banorte/results', compact('expUsers', 'vigUsers'));
    }

    public function usersTextbox(Request $request)
    {
        $procedence = $request->procedence;

        $ids = preg_split("[\r\n]", $request->ids);

        foreach ($ids as $id) {

            $data = UserTdcAliado::select("exp_month", "exp_year", "number")
                ->where('user_id', '=', $id)
                ->latest()
                ->first();

            $d = $data->exp_month . substr($data->exp_year, -2);

            AliadoBillingUsers::create([
                'user_id' => $id,

                'procedence' => $procedence,

                'exp_date' => DateTime::createFromFormat('y-m', substr($d, -2, 2)
                    . '-' . substr($d, 0, -2))->format('y-m'),

                'number' => $data->number
            ]);
        }
        $expUsers = count($this->expDates());
        $vigUsers = count($this->vigDates());

        return view('/aliado/banorte/results', compact('expUsers', 'vigUsers'));
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

    public function expDates()
    {
        return AliadoBillingUsers::select('user_id')
            ->where([
                ['exp_date', '<', now()->format('y-m')],
                ['created_at', 'like', now()->format('Y-m-d') . '%']])
            ->get();
    }

    public function vigDates()
    {
        return AliadoBillingUsers::select('user_id')
            ->where([
                ['exp_date', '>=', now()->format('y-m')],
                ['created_at', 'like', now()->format('Y-m-d') . '%']])
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
