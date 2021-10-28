<?php

namespace App\UDClasses;
use App\Models\Job;

class JobsToMandarin {
    protected $code = [];
    protected $mandarin = [];


    public function splitString($string) {
        $this->code = explode(",", $string);
    }

    public function process() {
        foreach ($this->code as $key => $value) {
            $chinese = Job::where('id', $value)->get();
            foreach($chinese as $value2) {
                array_push($this->mandarin, $value2->name);
            }
        }
    }

    public function returnString() {
        return implode(",", $this->mandarin);
    }
}