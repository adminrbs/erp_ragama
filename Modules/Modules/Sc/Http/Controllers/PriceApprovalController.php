<?php

namespace Modules\Sc\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Prc\Entities\ItemHistorySetOff;
use Modules\Sd\Entities\item_history_setOff;

class PriceApprovalController extends Controller
{
    //load price approval list
    public function load_price_approval_details($location_id){
        try{
            $data = DB::select('SELECT IHS.item_history_setoff_id,IHS.transaction_date, IHS.external_number, IHS.quantity,IHS.whole_sale_price,IHS.retial_price, IT.Item_code, IT.item_Name, IT.package_unit,L.location_name 
            FROM item_history_set_offs IHS 
            INNER JOIN items IT ON IHS.item_id = IT.item_id 
            INNER JOIN locations L ON IHS.location_id = L.location_id 
            WHERE IHS.location_id = '.$location_id.' AND IHS.price_status = 1');
            if($data){
                return response()->json(["status" => true, "data" => $data]);

            }else{
                return response()->json(["status" => true, "data" => []]);
            }
        }catch(Exception $ex){
            return $ex;
        }
    }

    //get location
    public function getLocation_price_confirm(Request $request){
        try{
            $locations = [];
            $id_array = $request->input('id_array');
            foreach($id_array as $id){
                $location = DB::select('SELECT L.location_id,L.location_name FROM locations L WHERE L.branch_id ='.$id.'
                ');
            if($location){
                array_push($locations,$location);
            }
            
            }

            return $locations;
        }catch(Exception $ex){
            return $ex;
        }

    }

    //approve price
    public function approve_price($id){
        try{
            $item_history_setoff = item_history_setOff::find($id);
            $item_history_setoff->price_status = 0;
            if($item_history_setoff->update()){
                return response()->json(["status" => true]);
            }else{
                return response()->json(["status" => false]);
            }
            
        }catch(Exception $ex){
            return $ex;
        }
    }
}
