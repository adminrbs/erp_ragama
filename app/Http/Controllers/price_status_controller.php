<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class price_status_controller extends Controller
{
    public static function validte_whole_sale_price($branch_id,$location_id,$item_id,$whole_sale_price){
        try{
            $result = DB::select('SELECT COUNT(*) as count FROM item_history_set_offs IHS WHERE IHS.branch_id = '.$branch_id.' AND IHS.location_id = '.$location_id.' AND IHS.item_id = '.$item_id.' AND IHS.whole_sale_price <> '.$whole_sale_price.'');
            if($result){
                
                if($result[0]->count > 0){
                    return 1;
                }else{
                    return 0;
                }
            }
        }catch(Exception $ex){
            return $ex;
        }

    }
}
