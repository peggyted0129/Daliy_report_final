<?php

namespace App\UDClasses;
use App\Models\Cdrcus;


class CustomersToMandarin
{
    protected $code = [];
    protected $mandarin = [];


    public function splitString($string) {
        $this->code = explode(",", $string);
    }

    public function process() {
        foreach ($this->code as $key => $value) {
            $chinese = Cdrcus::where('cusno', $value)->get();
            foreach($chinese as $value2) {
                array_push($this->mandarin, $value2->cusna_utf8);
            }
        }
    }

    public function returnString() {
        return implode(",", $this->mandarin);
    }
}