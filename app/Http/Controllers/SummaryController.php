<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\DB;
use App\Models\Job;
use Illuminate\Support\Facades\Session;

class SummaryController extends Controller
{
    public function index()
    {
        return view('summary', ['utype'=>Session::get('utype'), 'pdepno'=>Session::get('pdepno')]);
    }

    public function show(Request $request)
    {
        $start = $request->start;
        $end = $request->end;
        $pdepno = $request->summaryPdepno;

        if( $start > $end ){
            return redirect()->route('summary.index');
        }

        if( Session::get('userno')=='S045' ) {
            if ($pdepno!="") { // 如果有選取指定部門搜尋...
                $titles = DB::table('events') // 計算個機關拜訪次數
                    ->select(DB::raw('userno, location1, count(*) as count'))
                    ->where('pdepno', $pdepno)
                    ->where('start', '>=', $start)
                    ->where('end', '<=', $end)
                    ->groupBy('userno')->groupBy('location1')
                    ->get();
                $events = DB::table('events') // 撈取區間的 events
                    ->where('pdepno', $pdepno)
                    ->where('start', '>=', $start)
                    ->where('end', '<=', $end)
                    ->orderBy('pdepno')
                    ->orderBy('userno')
                    ->orderBy('location1')
                    ->orderBy('title')
                    ->get();    
            } else {
                $titles = DB::table('events') // 計算個機關拜訪次數
                    ->select(DB::raw('userno, location1, count(*) as count'))
                    ->where('pdepno', 'like', 'MN%')
                    ->where('start', '>=', $start)
                    ->where('end', '<=', $end)
                    ->groupBy('userno')
                    ->groupBy('location1')
                    ->get();
                $events = DB::table('events') // 撈取區間的 events
                    ->where('pdepno', 'like', 'MN%')
                    ->where('start', '>=', $start)
                    ->where('end', '<=', $end)
                    ->orderBy('pdepno')
                    ->orderBy('userno')
                    ->orderBy('location1')
                    ->orderBy('title')
                    ->get(); 
            }
        } elseif( Session::get('userno')=='S102' ) {
            if ($pdepno!="") { // 如果有選取指定部門搜尋...
                $titles = DB::table('events') // 計算個機關拜訪次數
                    ->select(DB::raw('userno, location1, count(*) as count'))
                    ->where('pdepno', $pdepno)
                    ->where('start', '>=', $start)
                    ->where('end', '<=', $end)
                    ->groupBy('userno')->groupBy('location1')
                    ->get();
                $events = DB::table('events') // 撈取區間的 events
                    ->where('pdepno', $pdepno)
                    ->where('start', '>=', $start)
                    ->where('end', '<=', $end)
                    ->orderBy('pdepno')
                    ->orderBy('userno')
                    ->orderBy('location1')
                    ->orderBy('title')
                    ->get();    
            } else {
                $titles = DB::table('events') // 計算個機關拜訪次數
                    ->select(DB::raw('userno, location1, count(*) as count'))
                    ->where('pdepno', 'like', 'MS%')
                    ->where('start', '>=', $start)
                    ->where('end', '<=', $end)
                    ->groupBy('userno')
                    ->groupBy('location1')
                    ->get();
                $events = DB::table('events') // 撈取區間的 events
                    ->where('pdepno', 'like', 'MS%')
                    ->where('start', '>=', $start)
                    ->where('end', '<=', $end)
                    ->orderBy('pdepno')
                    ->orderBy('userno')
                    ->orderBy('location1')
                    ->orderBy('title')
                    ->get(); 
            }
        } elseif ( Session::get('userno')=='C105' ) {
            if ($pdepno!="") { // 如果有選取指定部門搜尋...
                $titles = DB::table('events') // 計算個機關拜訪次數
                    ->select(DB::raw('userno, location1, count(*) as count'))
                    ->where('pdepno', $pdepno)
                    ->where('start', '>=', $start)
                    ->where('end', '<=', $end)
                    ->groupBy('userno')->groupBy('location1')
                    ->get();
                $events = DB::table('events') // 撈取區間的 events
                    ->where('pdepno', $pdepno)
                    ->where('start', '>=', $start)
                    ->where('end', '<=', $end)
                    ->orderBy('pdepno')
                    ->orderBy('userno')
                    ->orderBy('location1')
                    ->orderBy('title')
                    ->get();    
            } else {
                $titles = DB::table('events') // 計算個機關拜訪次數
                    ->select(DB::raw('userno, location1, count(*) as count'))
                    ->where('pdepno', 'like', 'MC%')
                    ->where('start', '>=', $start)
                    ->where('end', '<=', $end)
                    ->groupBy('userno')
                    ->groupBy('location1')
                    ->get();
                $events = DB::table('events') // 撈取區間的 events
                    ->where('pdepno', 'like', 'MC%')
                    ->where('start', '>=', $start)
                    ->where('end', '<=', $end)
                    ->orderBy('pdepno')
                    ->orderBy('userno')
                    ->orderBy('location1')
                    ->orderBy('title')
                    ->get(); 
            }
        } elseif (session('pdepno')=='001' || session('userno')=='S059') {
            if ($pdepno!="") { // 如果有選取指定部門搜尋...
                $titles = DB::table('events') // 計算個機關拜訪次數
                    ->select(DB::raw('userno, location1, count(*) as count'))
                    ->where('pdepno', $pdepno)
                    ->where('start', '>=', $start)
                    ->where('end', '<=', $end)
                    ->groupBy('userno')->groupBy('location1')
                    ->get();
                $events = DB::table('events') // 撈取區間的 events
                    ->where('pdepno', $pdepno)
                    ->where('start', '>=', $start)
                    ->where('end', '<=', $end)
                    ->orderBy('pdepno')
                    ->orderBy('userno')
                    ->orderBy('location1')
                    ->orderBy('title')
                    ->get();    
            } else {
                $titles = DB::table('events') // 計算個機關拜訪次數
                    ->select(DB::raw('userno, location1, count(*) as count'))
                    ->where('start', '>=', $start)
                    ->where('end', '<=', $end)
                    ->groupBy('userno')->groupBy('location1')
                    ->get();
                $events = DB::table('events') // 撈取區間的 events
                    ->where('start', '>=', $start)
                    ->where('end', '<=', $end)
                    ->orderBy('pdepno')
                    ->orderBy('userno')
                    ->orderBy('location1')
                    ->orderBy('title')
                    ->get(); 
            }
        } else {
            $titles = DB::table('events') // 計算個機關拜訪次數
                ->select(DB::raw('userno, location1, count(*) as count'))
                ->where('pdepno', Session::get('pdepno'))
                ->where('start', '>=', $start)
                ->where('end', '<=', $end)
                ->groupBy('userno')->groupBy('location1')
                ->orderBy('userno')->orderBy('location1')
                ->get();
            $events = DB::table('events') // 撈取區間的 events
                ->where('pdepno', Session::get('pdepno'))
                ->where('start', '>=', $start)
                ->where('end', '<=', $end)
                ->orderBy('pdepno')
                ->orderBy('userno')
                ->orderBy('location1')
                ->get();   
        }
        // dd($events);

        foreach($titles as $key => $title) {
            $usernoMandarin = new \App\UDClasses\UsernoToMandarin();
            $usernoMandarin->process($title->userno);
            $titles[$key]->userno_mandarin = $usernoMandarin->returnString();

            $location1Mandarin = new \App\UDClasses\Location1ToMandarin();            
            $location1Mandarin->process($title->location1);            
            $titles[$key]->location1_mandarin = $location1Mandarin->returnString();
        }    
        // dd($titles);

        $items = [];
        foreach($events as $key => $event) {
            $items[$key]['userno'] = $event->userno;
            $items[$key]['location1'] = $event->location1;
            $jobs = explode(',', $event->jobs); // 為一陣列
            $items[$key]['jobs'] = $jobs;
        }

        // dd($items);

        $results = [];
        $current_userno = '';
        $current_location1 = '';
        foreach($items as $key => $item){
            if( $key == 0 ){ //第一筆資料
            
                $itemsJobs = $items[$key]['jobs']; // ["7", "11"]
                $jobAry = []; // [ "7" => 1 , "11" => 1 ]
                foreach($itemsJobs as $val){ // 轉成計算紀錄 $job 工作項目次數的陣列
                    $jobAry[$val] = 1;
                }

                $results[] = Array(
                    'userno' => $items[$key]['userno'],
                    'location1' => $items[$key]['location1'],
                    'jobs' => $jobAry
                );

                $current_userno = $items[$key]['userno'];
                $current_location1 = $items[$key]['location1'];
                $index = 0;

                continue;  
            }
            
            // 當同個業務且拜訪機關相同時，計算 $job 工作項目次數
            if( $current_userno == $items[$key]['userno'] && $current_location1 == $items[$key]['location1'] ){

                foreach($items[$key]['jobs'] as $val){ // 轉成計算 $job 次數
                    if (array_key_exists($val, $results[$index]['jobs'])){
                        $results[$index]['jobs'][$val] += 1;
                    }else{
                        $results[$index]['jobs'][$val] = 1;
                    }
                }

                $current_userno = $items[$key]['userno'];
                $current_location1 = $items[$key]['location1'];

                continue;  
            }

            if( $current_userno == $items[$key]['userno'] ){
                $index++;
                $results[$index]['userno'] = $items[$key]['userno'];
                $results[$index]['location1'] = $items[$key]['location1'];

                $itemsJobs = $items[$key]['jobs']; // ["7", "11"]
                $jobAry = []; // [ "7" => 1 , "11" => 1 ]
                foreach($itemsJobs as $val){ // 轉成計算紀錄 $job 工作項目次數的陣列
                    $jobAry[$val] = 1;
                }

                $results[$index] = Array(
                    'userno' => $items[$key]['userno'],
                    'location1' => $items[$key]['location1'],
                    'jobs' => $jobAry
                );

                $current_userno = $items[$key]['userno'];
                $current_location1 = $items[$key]['location1'];

                continue;  
            }

            // 另一位業務人員
            $index++;
            $results[$index]['userno'] = $items[$key]['userno'];
            $results[$index]['location1'] = $items[$key]['location1'];
            $itemsJobs = $items[$key]['jobs']; // ["7", "11"]
            $jobAry = []; // [ "7" => 1 , "11" => 1 ]
            foreach($itemsJobs as $val){ // 轉成計算紀錄 $job 工作項目次數的陣列
                $jobAry[$val] = 1;
            }

            $results[$index] = Array(
                'userno' => $items[$key]['userno'],
                'location1' => $items[$key]['location1'],
                'jobs' => $jobAry
            );

            $current_userno = $items[$key]['userno'];
            $current_location1 = $items[$key]['location1'];

        }

        foreach($results as $key => $result){
            $location1Mandarin = new \App\UDClasses\Location1ToMandarin();
            $usernoMandarin = new \App\UDClasses\UsernoToMandarin();

            $usernoMandarin->process($result['userno']);
            $results[$key]['userno_mandarin'] = $usernoMandarin->returnString();

            $location1Mandarin->process($result['location1']);
            $results[$key]['location1_mandarin'] = $location1Mandarin->returnString();
        }

        // dd($results[0]['jobs'][7] + 5);
        // dd($results);
        // dd($current_userno, $current_location1);
        // dd($items[$key]['jobs']);
      
        $jobs = DB::table('jobs')
            ->orderBy('id')
            ->get();
        $jobs_map = [];
        foreach($jobs as $key => $job){
            $jobs_map[$key + 1] = $job->name;
        }
        // dd($jobs_map);
        // dd($jobs);

        return view('summary', ['utype'=>Session::get('utype'),
            'pdepno'=>Session::get('pdepno'),
            'titles'=>$titles,
            'results'=>$results,
            'jobs_map'=>$jobs_map
           ]
        );
    }
}
