<?php

namespace Modules\Md\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Md\Entities\Payee;

class PayeeController extends Controller
{
    public function savePayee(Request $request){
        try{
            $request->validate([
                'payee_name' => 'unique:payees,payee_name',
            ], [
                'payee_name.unique' => 'record duplicated',  
            ]);
                $Payee = new Payee();
                $Payee->payee_name = $request->input('payeeName');
                
                if($Payee->save()){
                    return response()->json((['status' => true]));
                }else{
                    return response()->json((['status' => false]));
                }
        }catch(Exception $ex){
            return $ex;
        }
    }

    public function loadPayee(){
        $Payee = Payee::all();
        if($Payee){
            return response()->json((['status' => true,'data'=>$Payee]));
        }else{
            return response()->json((['status' => true,'data'=>[]]));
        }
    }

    public function loadEachPayee($id){
        $Payee = Payee::find($id);
        if($Payee){
            return response()->json((['status' => true,'data'=>$Payee]));
        }else{
            return response()->json((['status' => true,'data'=>[]]));
        }
    }
}
