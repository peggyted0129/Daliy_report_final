<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Event;
use App\Models\Secuser;

class ReportCalController extends Controller
{
    public function index()
    {
        return view('report_cal');
    }

    public function all() {
        $events = DB::select('select distinct userno, DATE(start) as start
        from events where start>=DATE_ADD(NOW(),INTERVAL -60 DAY) and pdepno=?
        order by userno', [session('pdepno')]);
        
        $report_cal = [];
        foreach($events as $key => $value) {
            $usernoMandarin = new \App\UDClasses\UsernoToMandarin();
            $usernoMandarin->process($value->userno);

            $report_cal[$key]['userno_mandarin'] = $usernoMandarin->returnString();
            $report_cal[$key]['userno'] = $value->userno;
            $report_cal[$key]['start'] = $value->start;
        }
        return $report_cal;
    }
}
