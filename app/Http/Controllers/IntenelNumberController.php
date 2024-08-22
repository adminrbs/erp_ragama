<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IntenelNumberController extends Controller
{
    public static function getNextID(){
        $query = "SELECT IF(ISNULL(MAX(internal_number)),0,MAX(internal_number)) AS id FROM global_settings";
        $id = DB::select($query)[0]->id;
        IntenelNumberController::incrementID();
        return $id;
    }


    public static function incrementID(){
        DB::table('global_settings')
        ->update(['internal_number' => DB::raw('internal_number + 1')]);
    }
}
