<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() // 個人特休
    {
      $events = DB::table('palhad')
        ->join('paldta', function($join) {
            $join->on('palhad.mancode', '=', 'paldta.mancode')
                ->on('palhad.enterdate', '=', 'paldta.enterdate');
        })
        ->where('palhad.mancode', '=', Session::get('userno'))
        ->where('palhad.enterdate', '>', '2020-01-01')
        ->where('paldta.dayofftype', '=', '99') // dayofftype 為特休
        ->whereRaw('palhad.apprsta in ("3", "9")') // 若價單是 "核准" 或 "結案"
        ->select('paldta.dayoffdate', 'paldta.starttime', 'paldta.endtime', 'palhad.mancode')
        ->get();

        $id = 99;
        $allDay = 0;
        $data = [];
        if ($events) {
            foreach($events as $value) {
                $title = '特休';
                $dayoffdate = $value->dayoffdate;
                $starttime = $value->starttime;
                $start = $dayoffdate . ' ' . $starttime;
                $endtime = $value->endtime;
                $end = $dayoffdate . ' ' . $endtime;

                $data[$id]['id'] = $id;
                $data[$id]['title'] = $title;
                $data[$id]['start'] = $start;
                $data[$id]['end'] = $end;
                $data[$id]['allDay'] = $allDay;
                $id++;
            }
        } else {
            $data['status'] = false;
        }
        // return json_encode($events);
        return json_encode($data);
    }

    public function all() { // 全部門員工特休
        $events = DB::table('palhad')
            ->join('paldta', function($join) {
                $join->on('palhad.mancode', '=', 'paldta.mancode')
                    ->on('palhad.enterdate', '=', 'paldta.enterdate');
            })
            ->where('palhad.depno', '=', Session::get('pdepno'))
            ->where('palhad.enterdate', '>', '2020-01-01')
            ->where('paldta.dayofftype', '=', '99')
            ->whereRaw('palhad.apprsta in ("3", "9")')
            ->select('paldta.dayoffdate', 'paldta.starttime', 'paldta.endtime', 'palhad.mancode')
            ->get();

        $id = 99;
        $allDay = 0;
        $data = [];
        if ($events) {
            foreach($events as $value) {
                $title = '特休';
                $dayoffdate = $value->dayoffdate;
                $starttime = $value->starttime;
                $start = $dayoffdate . ' ' . $starttime;
                $endtime = $value->endtime;
                $end = $dayoffdate . ' ' . $endtime;

                $data[$id]['id'] = $id;
                $data[$id]['title'] = $title;
                $data[$id]['start'] = $start;
                $data[$id]['end'] = $end;
                $data[$id]['allDay'] = $allDay;

                $usernoMandarin = new \App\UDClasses\UsernoToMandarin();
                $usernoMandarin->process($value->mancode);
                $data[$id]['userno_mandarin'] = $usernoMandarin->returnString();

                $id++;
            }
        } else {
            $data['status'] = false;
        }

        return json_encode($data);

    }
}
