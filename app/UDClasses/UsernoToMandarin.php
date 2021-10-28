<?php

namespace App\UDClasses;
use App\Models\Secuser;

class UsernoToMandarin {
    protected $mandarin;

    public function process($code) {
        $chinese = Secuser::where('userno', $code)->get();
        foreach($chinese as $value) {
            $this->mandarin = $value->username_utf8;
        }
    }

    public function returnString() {
        return $this->mandarin;
    }
}