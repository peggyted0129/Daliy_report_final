<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Cdr_hosp;
use App\Models\Events_sales;
use App\Models\Cdrcus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CdrcusController extends Controller
{
    public function index()
    {
        $cdrData = Cdr_hosp::where('mancode', Session::get('userno'))->get();
        return json_encode($cdrData);
    }
    public function show($cuycode)
    {
        $cdrcus = Cdrcus::where('cuycode', $cuycode)
                ->select('cusno', 'cusna_utf8')
                ->get();
        $cdrcus = $cdrcus->sortBy('cusna_utf8');

        $customers = [];
        foreach($cdrcus as $key => $value) {
            $customers[] = ['id' => $value->cusno, 'text' => $value->cusna_utf8];
        }

        return json_encode($customers);
    }
}
