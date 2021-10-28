<?php

namespace App\UDClasses;
use App\Models\Cdr_hosp;

class Location1ToMandarin {
    protected $mandarin;

    public function process($code) {
        $chinese = Cdr_hosp::where('hos_no', $code)->get();
        foreach($chinese as $value) {
            $this->mandarin = $value->hospname_utf8;
        }
    }

    public function returnString() {
        return $this->mandarin;
    }
}