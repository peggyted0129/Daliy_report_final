<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Cdr_hosp;
use App\Models\Events_sales;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class EventController extends Controller
{
    public function index() 
    {
        $data = [];
        $events = Event::where('userno', Session::get('userno'))
            ->where('start', '>=', '2020-07-01 00:00:00') // 只呈現 2021-01-01 以後的紀錄
            ->get();
        
        foreach($events as $value) {
            $id = $value->id;
            $title = $value->title;
            $start = $value->start;
            $end = $value->end;
            $allDay = $value->allDay;

            $cdr_hosp = Cdr_hosp::where('hos_no', $value->location1)->get();
            foreach($cdr_hosp as $location1) {
                $data[$id]['hospname_utf8'] = $location1->hospname_utf8;
            }

            $data[$id]['id'] = $id;
            $data[$id]['title'] = $title;
            $data[$id]['start'] = $start;
            $data[$id]['end'] = $end;
            $data[$id]['allDay'] = $allDay;
            
        }
        
        return json_encode($data);
    }

    public function store(Request $request) // 單純寫入 events 資料表
    {
        $event = new Event();
        $event->title = request('title');
        $event->start = request('start');
        $event->end = request('end');
        $event->allDay = request('allDay');
        $event->location1 = request('location1') ?? '00';
        $event->location2 = request('location2');

        $customers = request('customers');
        if (is_null($customers)) {
            $event->customers = null;
        } else {
            $event->customers = implode(",", $customers);
        }
        
        $jobs = request('jobs');
        $event->jobs = implode(",", $jobs);
        
        $event->description = request('description');
        $event->userno = Session::get('userno');
        $event->pdepno = Session::get('pdepno');
        $event->sales = request('sales_un') ?? 0; // 小點業績

        $cdr_hosp = Cdr_hosp::where('hos_no', $event->location1)->get();
        foreach($cdr_hosp as $location1) {
            $ret['hospname_utf8'] = $location1->hospname_utf8;
        }

        /*
         * start日期判斷在events_sales是否已有當日業績資料
         * 1. 如果有 : events_sales 的 id 欄位(表身) 對應 events 的 sales_id 欄位(表頭)
         * 2. 如果沒有 : events 的 sales_id 欄位(表頭) 對應 events_sales 的 id 欄位(表身)
         * 3. 若當天有多個事件，則以最新 "儲存" 的那筆資料 (施巴業績 & SC業績) 寫入資料表 events_sales
         */
        $sales_date = date('Y-m-d', strtotime($event->start));
        $e_sales = Events_sales::where(['sales_date'=>$sales_date, 'userno'=>Session::get('userno')])->get();
        if(isset($e_sales)) { //insert:尚未有當日業績資料
            $eloquent_sales = new Events_sales();
            $eloquent_sales->sales_date = $sales_date;
            $eloquent_sales->sales = $request->input('sales'); // 施巴當日業績
            $eloquent_sales->sales_sc = $request->input('sales_sc'); 
            $eloquent_sales->userno = Session::get('userno');
            $eloquent_sales->pdepno = Session::get('pdepno');
            $eloquent_sales->save();

            $event->sales_id = $eloquent_sales->id;
            $event->save();
        } else { //update:已有當日業績
            foreach($e_sales as $value) {
                $events_sales_id = $value->id;
            }
            $eloquent_sales = Events_sales::find($events_sales_id);
            if (!empty($request->input('sales'))) {  // user有輸入數值
                $eloquent_sales->sales = $request->input('sales');
            }
            if (!empty($request->input('sales_sc'))) {  // user有輸入數值
                $eloquent_sales->sales_sc = $request->input('sales_sc');
            }

            $eloquent_sales->save();

            $event->sales_id = $eloquent_sales->id;
            $event->save();
        }     

        $ret['id'] = $event->id;
        $ret['title'] = $event->title;
        $ret['start'] = $event->start;
        $ret['end'] = $event->end;
        $ret['allDay'] = $event->allDay;
        return json_encode($ret);
       
        // return redirect('/calendar');
    }

    public function show($id)
    {
        $event = Event::findOrFail($id);
        $event['sales_un'] = $event['sales']; // 小點業績
        $hospname_utf8 = Cdr_hosp::where('hos_no', $event['location1'])
                                    ->select('hospname_utf8')
                                    ->get();
        foreach($hospname_utf8 as $value) {
            $event['hospname'] = $value->hospname_utf8;
        }

        /*
         * start日期判斷在events_sales是否已有當日業績資料
         */
        $sales_date = date('Y-m-d', strtotime($event['start']));
        $e_sales = Events_sales::where(['sales_date'=>$sales_date, 'userno'=>Session::get('userno')])->get();
        foreach($e_sales as $value) {
            $event['sales'] = $value->sales;
            $event['sales_sc'] = $value->sales_sc;  
        }
        return json_encode($event);
    }

    public function update(Request $request, $id)
    {
        $event = Event::find($id);
        $event->title = $request->input('title');
        $event->location1 = $request->input('location1') ?? '00';
        $event->location2 = $request->input('location2');
        $event->start = $request->input('start');
        $event->end = $request->input('end');
        $event->allDay = $request->input('allDay');

        $customers = $request->input('customers');
        if (is_null($customers)) {
            $event->customers = null;
        } else {
            $event->customers = implode(",", $customers);
        }


        $jobs = $request->input('jobs');
        $event->jobs = implode(",", $jobs);

        $event->description = $request->input('description');

        $event->sales = $request->input('sales_un') ?? 0;

        $status = $event->save();

        $cdr_hosp = Cdr_hosp::where('hos_no', $event->location1)->get();
        foreach($cdr_hosp as $location1) {
            $ret['hospname_utf8'] = $location1->hospname_utf8;
        }

        /*
         * start日期判斷在events_sales是否已有當日業績資料 sales_un
         */
        $sales_date = date('Y-m-d', strtotime($request->input('start')));
        $e_sales = Events_sales::where(['sales_date'=>$sales_date, 'userno'=>Session::get('userno')])->get();
        if($e_sales->isEmpty()) {
            //insert:尚未有當日業績資料
            $eloquent_sales = new Events_sales();
            $eloquent_sales->sales_date = $sales_date;
            $eloquent_sales->sales = $request->input('sales');
            $eloquent_sales->sales_sc = $request->input('sales_sc');
            $eloquent_sales->userno = Session::get('userno');
            $eloquent_sales->pdepno = Session::get('pdepno');
            $eloquent_sales->save();

            $event->sales_id = $eloquent_sales->id;
            $event->save();
        } else {
            //update:已有當日業績
            foreach($e_sales as $value) {
                $events_sales_id = $value->id;
            }
            $eloquent_sales = Events_sales::find($events_sales_id);
            $eloquent_sales->sales = $request->input('sales');
            $eloquent_sales->sales_sc = $request->input('sales_sc');
            $eloquent_sales->save();

            $event->sales_id = $eloquent_sales->id;
            $event->save();
        }

        $ret['id'] = $id;
        $ret['title'] = $event->title;
        $ret['start'] = $event->start;
        $ret['end'] = $event->end;
        $ret['allDay'] = $event->allDay;

        return json_encode($ret);
    }

    public function destroy($id)
    {
        $event = Event::find($id);
        $status = $event->delete();
        return json_encode($status);
    }
}
