<?php

namespace App\Http\Controllers;

use App\CellersBillingUsers;
use App\CellersBlacklist;
use App\CellersCancelAccountAnswer;
use App\CellersUser;
use App\CellersUserCancellation;
use App\CellersTdc;
use App\RespuestasBanorteCellers;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CellersBanorteController extends Controller
{
    public function index()
    {
        return view('cellers.banorte.index');
    }

    public function ftp(Request $request)
    {
        $procedence = $request->procedence;

        $file = $request->file('file');

        $rows = preg_grep("/(809295030)/", file($file));;

        foreach ($rows as $row) {
            $id = substr($row, 9, 6);

            $data = CellersTdc::select("exp_date", "number")
                ->where('user_id', '=', $id)
                ->latest()
                ->first();

            if (is_numeric($data->exp_date)) {
                $date = DateTime::createFromFormat('y-m', substr($data->exp_date, -2, 2)
                . '-' . substr($data->exp_date, 0, -2))
                    ->format('y-m');
            } else {
                $date = 1111;
            }

            CellersBillingUsers::create([
                'user_id' => $id,

                'procedence' => $procedence,

                'exp_date' => $date,

                'number' => $data->number
            ]);
        }

        $expUsers = count($this->expDates());
        $vigUsers = count($this->vigDates());

        return view('/cellers/banorte/results', compact('expUsers', 'vigUsers'));
    }

    public function billingRejected(Request $request)
    {
        $procedence = $request->procedence;

        $users = RespuestasBanorteCellers::select('user_id as id')
            ->whereIn('detalle_mensaje', ['Fondos insuficientes', 'Supera el monto límite permitido', 'Límite diario excedido', 'Imposible autorizar en este momento'])
            ->where(
                'fecha', 'like', $request->date
            )->get();

        foreach ($users as $user) {

            $data = CellersTdc::select("exp_date", "number")
                ->where('user_id', '=', $user->id)
                ->latest()
                ->first();

            if (is_numeric($data->exp_date)) {
                $date = DateTime::createFromFormat('y-m', substr($data->exp_date, -2, 2)
                    . '-' . substr($data->exp_date, 0, -2))
                    ->format('y-m');
            } else {
                $date = 1111;
            }

            CellersBillingUsers::create([
                'user_id' => $user->id,

                'procedence' => $procedence,

                'exp_date' => $date,

                'number' => $data->number
            ]);
        }
        $expUsers = count($this->expDates());
        $vigUsers = count($this->vigDates());

        return view('/cellers/banorte/results', compact('expUsers', 'vigUsers'));
    }

    public function usersTextbox(Request $request)
    {
        $procedence = $request->procedence;

        $ids = preg_split("[\r\n]", $request->ids);

        foreach ($ids as $id) {

            $data = CellersTdc::select("exp_date", "number")
                ->where('user_id', '=', $id)
                ->latest()
                ->first();

            if (is_numeric($data->exp_date)) {
                $date = DateTime::createFromFormat('y-m', substr($data->exp_date, -2, 2)
                    . '-' . substr($data->exp_date, 0, -2))
                    ->format('y-m');
            } else {
                $date = 1111;
            }

            CellersBillingUsers::create([
                'user_id' => $id,

                'procedence' => $procedence,

                'exp_date' => $date,

                'number' => $data->number
            ]);
        }
        $expUsers = count($this->expDates());
        $vigUsers = count($this->vigDates());

        return view('/cellers/banorte/results', compact('expUsers', 'vigUsers'));
    }

    public function ftpProsa()
    {
        $expUsers = $this->expDates();
        $verified = $this->notInBlacklists($expUsers);
        //   $text = $this->ftpText($verified);

        return view('/cellers/banorte/paraFTP', compact('verified'));

    }

    public function csvBanorte()
    {
        $vigUsers = $this->vigDates();
        $verified = $this->notInBlacklists($vigUsers);


        return view('/cellers/banorte/paraFTP', compact('verified'));

    }

  /*  public function notInBlacklists($ids)
    {
        return CellersUser::select('id')->whereIn('id',$ids)->whereNull('deleted_at')->get()
            ->diff(CellersCancelAccountAnswer::select('user_id as id')->get())
            ->diff(CellersBlacklist::select('user_id as id')->get())
            ->diff(CellersUserCancellation::select('user_id as id')->get());
    }

    public function notCancelled($ids)
    {
        return DB::table('cellers.users as u')
            ->leftJoin('cellers.user_cancellations as au', 'au.user_id', '=', 'u.id')
            ->select('u.id')
            ->whereIn('u.id', $ids)
            ->whereNull(['au.user_id', 'u.deleted_at'])
            ->get();
    } */

    public function expDates()
    {
        return CellersBillingUsers::select('user_id')
            ->where([
                ['exp_date', '<', now()->format('y-m')],
                ['created_at', 'like', now()->format('Y-m-d') . '%']])
            ->get();
    }

    public function vigDates()
    {
        return CellersBillingUsers::select('user_id')
            ->where([
                ['exp_date', '>=', now()->format('y-m')],
                ['created_at', 'like', now()->format('Y-m-d') . '%']])
            ->get();
    }


    public function ftpText($verified)
    {
        $query = CellersTdc::selectRaw("concat('801089727', user_id,'                 ', number,'   00000000079.0000', user_id, '              ')")
            ->whereIn('user_id', $verified);

        $ftpText = CellersTdc::selectRaw("concat(DATE_FORMAT(CURDATE(), '%d%m%Y'),'100101',LPAD(count(user_id), 6, '0'),LPAD(count(user_id)*79, 13, '0'),'.00                                                   ') as row")
            ->whereIn('user_id', $verified)
            ->union($query)
            ->get();
        dd($ftpText);
    }
}
