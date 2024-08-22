<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UtilityController extends Controller
{
    //
    public function containsValue($table, $column, $value)
    {

        $query = "SELECT COUNT(1) AS count FROM `" . $table . "` WHERE INSTR(`" . $column . "`, REPLACE(LOWER('" . $value . "'),' ','')) > 0";
        return DB::select($query)[0]->count;
    }

    //use for update validation]
    public static function containsValue_update($table, $column, $value,$id)
    {

        $query = "SELECT COUNT(1) AS count FROM `" . $table . "` WHERE INSTR(`" . $column . "`, REPLACE(LOWER('" . $value . "'),' ','')) > 0";
        $count = DB::select($query)[0]->count;
        if($count == 1){
            $id_qry = DB::select("SELECT marketing_route_id FROM `" . $table . "` WHERE INSTR(`" . $column . "`, REPLACE(LOWER('" . $value . "'),' ','')) > 0");
            if($id_qry[0]->marketing_route_id == $id){
                return 0;
            }else{
                return 1;
            }
        }else if($count > 1){
            return 1;
        }else{
            return 0;
        }

    }

    
}
