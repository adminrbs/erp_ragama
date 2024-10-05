<?php

namespace Modules\Cb\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Cb\Entities\Payee;
use Modules\Md\Entities\GlAccountAnalysis;

class PaymentVoucherController extends Controller
{
    public function loadPayee(){
        $payee = Payee::all();
        if($payee){
            
            return response()->json(["status"=>true,"data"=>$payee]);
        }else{
            return response()->json(["status"=>false,"data"=>[]]);
        }
    }


    public function loadAccounts()
    {
        $val = 1;
        try {
            
            $items = DB::table('gl_accounts')
            ->select('account_id', 'account_title','account_code')
            ->get();
            $collection = [];
            foreach ($items as $item) {
                array_push($collection, ["hidden_id" => $item->account_id, "id" =>  $item->account_title, "value" =>  $item->account_code, "collection" => [$item->account_id, $item->account_title, $item->account_code]]);
            }
            return response()->json(['success' => true, 'data' => $collection]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function loadAccountAnalysisData(){
        try {
            
            $analysis = GlAccountAnalysis::all();
            return response()->json(['success' => true, 'data' => $analysis]);
        } catch (Exception $ex) {
            return $ex;
        }
    }
}
