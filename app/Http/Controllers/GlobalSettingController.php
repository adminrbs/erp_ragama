<?php

namespace App\Http\Controllers;

use App\Models\global_setting;
use Illuminate\Http\Request;

class GlobalSettingController extends Controller
{
    public function checkPOpickStatus(){
        $global_setting = global_setting::first();
        //dd($global_setting->direct_grn);
      
        return response()->json(['status' => $global_setting->direct_grn]);
    }
}
