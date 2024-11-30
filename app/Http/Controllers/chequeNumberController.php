<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class chequeNumberController extends Controller
{
    public static function validateChequeNo($chequeNo){
        if(strlen($chequeNo) > 6){
            return False;
        }else{
            return True;
        }
    }
}
