<?php

namespace App\Http\Controllers;

use App\Models\Secuser;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Event;
use App\Models\Events_sales;

class ReportController extends Controller
{
    protected $utype;
    protected $pdepno;
    protected $userno;

    public function index() {
        return view('report', ['utype'=>Session::get('utype'), 'pdepno'=>Session::get('pdepno')]);
    }

    // 選出不重複的部門
    public function getPdepno() {
        if( (Session::get('userno')=='S045') or (Session::get('userno')=='S102') or (Session::get('userno')=='C105') ){
            $pdepno = Session::get('pdepno'); // 得到 'MSA'
            $allPdepno = substr($pdepno, 0, 2) . '%'; // 得到 'MS%'
            $getPdepno = Secuser::select('pdepno')->distinct() // 選出不重複的部門 'MS%'
                ->where('pdepno', 'like', $allPdepno)
                ->orderBy('pdepno', 'ASC')
                ->get(); // 得到 'MSA'~'MSE'
        } elseif (Session::get('pdepno') == '001') {
            $getPdepno = Secuser::select('pdepno')->distinct() // 選出不重複的部門 'MS%'
                ->where('pdepno', 'like', 'MN%')
                ->orWhere('pdepno', 'like', 'MS%')
                ->orWhere('pdepno', 'like', 'MC%')
                ->orderBy('pdepno', 'ASC')
                ->get(); // 得到 'MCX'、'MNX'、'MSX'
        }else {
            $getPdepno = Secuser::select('pdepno')->distinct()
            ->where('pdepno', '=', Session::get('pdepno'))
            ->get();
        }
       
        // makeJson 參數位置對應順序
        if(isset($getPdepno) && count($getPdepno) > 0){ // 如果 $getPdepno 不是空的，且筆數多於一筆
            // 參數第二個 data 設定開頭名稱為 getPdepno
            return $this->makeJson(1, ['getPdepno' => $getPdepno], '成功得到資料'); // 成功:1 ， 沒有 $msg (設定 null)
        }else{
            return $this->makeJson(0, null, '找不到資料'); // 失敗:0 ， 設定 $msg 為 null
        }
    }

    // 選出所選取部門的員工名字
    public function getPSRs($pdepno) {
      $getPSRs = Secuser::select('pdepno', 'username_utf8')
        ->where('pdepno', '=', $pdepno)
        ->where('enabled', '=', 'Y')
        ->get();
    
      // makeJson 參數位置對應順序
      if(isset($getPSRs) && count($getPSRs) > 0){ 
        return $this->makeJson(1, ['getPSRs' => $getPSRs], '成功得到資料'); 
      }else{
        return $this->makeJson(0, null, '找不到資料'); 
      }
    }

    public function show(Request $request) {
        $start = $request->start;
        $end = $request->end;
        $pdepno = $request->filterPdepno; // 部門
        $psr = $request->psr; // 員工姓名
        $getPsrnoData = Secuser::select('userno') // 得到員工工號
            ->where('username_utf8', '=', $psr)
            ->where('pdepno', '=', $pdepno)
            ->where('enabled', '=', 'Y')
            ->get();
        foreach($getPsrnoData as $value) {
            $getPsrno = $value->userno;
        }

        if( $start>$end || is_null($pdepno) || is_null($psr) ){
            return redirect()->route('report.index');
        }

        if ( (Session::get('userno')=='S045') or (Session::get('userno')=='S102') or (Session::get('userno')=='C105') or (Session::get('pdepno')=='001') ) {
            $events = Event::leftJoin('events_sales', 'events.sales_id', '=', 'events_sales.id')
                ->select(DB::raw('events.*,
                    CASE WHEN events_sales.sales_date IS NULL THEN DATE(events.start) ELSE events_sales.sales_date END AS sales_date,
                    CASE WHEN events_sales.sales IS NULL THEN 0 ELSE events_sales.sales END AS sales_seba,
                    CASE WHEN events_sales.sales_sc IS NULL THEN 0 ELSE events_sales.sales_sc END AS sales_sc'))
                ->where('events.start', '>=', $start)
                ->where('events.end', '<=', $end)
                ->where('events.pdepno', '=', $pdepno)
                ->where('events.userno', '=', $getPsrno)
                ->orderBy('events.pdepno')
                ->orderBy('sales_date')
                ->orderBy('events.userno')                
                ->get();
        } else {
            $events = Event::leftJoin('events_sales', 'events.sales_id', '=', 'events_sales.id')
                ->select(DB::raw('events.*,
                    CASE WHEN events_sales.sales_date IS NULL THEN DATE(events.start) ELSE events_sales.sales_date END AS sales_date,
                    CASE WHEN events_sales.sales IS NULL THEN 0 ELSE events_sales.sales END AS sales_seba,
                    CASE WHEN events_sales.sales_sc IS NULL THEN 0 ELSE events_sales.sales_sc END AS sales_sc'))
                ->where('events.pdepno', '=', Session::get('pdepno'))
                ->where('events.start', '>=', $start)
                ->where('events.end', '<=', $end)
                ->where('events.userno', '=', $getPsrno)            
                ->orderBy('sales_date')
                ->orderBy('events.userno')
                ->get();
        }
        // dd($events);
        // dd($getPsrnoData); // 得到資料表裡的員工資料
        // dd($getPsrno); // 對 $getPsrnoData 使用迴圈取出員工工號
        // dd($request->all());

        foreach($events as $key => $value) {
            $jobsMandarin = new \App\UDClasses\JobsToMandarin();
            $location1Mandarin = new \App\UDClasses\Location1ToMandarin();
            $customersMandarin = new \App\UDClasses\CustomersToMandarin();
            $usernoMandarin = new \App\UDClasses\UsernoToMandarin();

            $jobsMandarin->splitString($value->jobs);
            $jobsMandarin->process();
            //$events[$key]['jobs_mandarin'] = $jobsMandarin->returnString();
            $events[$key]->jobs_mandarin = $jobsMandarin->returnString();

            $location1Mandarin->process($value->location1);
            //$events[$key]['location1_mandarin'] = $location1Mandarin->returnString();
            $events[$key]->location1_mandarin = $location1Mandarin->returnString();

            $usernoMandarin->process($value->userno);
            //$events[$key]['userno_mandarin'] = $usernoMandarin->returnString();
            $events[$key]->userno_mandarin = $usernoMandarin->returnString();

            if ($value->customers) {
                $customersMandarin->splitString($value->customers);
                $customersMandarin->process();
                //$events[$key]['customers_mandarin'] = $customersMandarin->returnString();
                $events[$key]->customers_mandarin = $customersMandarin->returnString();
            }
        }

        //dd($events);
        return view('report', ['utype'=>session('utype'),
            'pdepno'=>session('pdepno'),
            'events'=>$events]
        );
    }

    // 用來生成 JSON 字串 
    private function makeJson($status, $data=null, $msg=null)
    {
        return response()->json(['status' => $status, 'message' => $msg, 'data' => $data])       
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

}
