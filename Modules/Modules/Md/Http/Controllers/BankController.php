<?php

namespace Modules\Md\Http\Controllers;
use Illuminate\Routing\Controller;
use Modules\Md\Entities\bank;
use Modules\Md\Entities\bank_branch;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class BankController extends Controller
{

    public function searchBank(){
        $result = bank::all();
        return response()->json($result);

    }
    public function searchbranch(){
        $result = bank_branch::all();
        return response()->json($result);

    }



    public function getBankalldata()
{
    try {
        $data = bank::all();
        return response()->json(['success' => 'Data loaded', 'data' => $data]);
    } catch (Exception $ex) {
        if ($ex instanceof ValidationException) {
            return response()->json([

            ]);
        }
    }


}


    public function banksave(Request $request){

        try {
            $bank_code_count = 0;
            $bankCode_ = $request->get('txtBankCode');
            
            $query = "SELECT COUNT(*) AS count FROM banks WHERE banks.bank_code = ?";
            $result = DB::select($query, [$bankCode_]);
            
            if ($result) {
                $bank_code_count = $result[0]->count;
            }
            
            if($bank_code_count > 0){
                return response()->json(['status' => false, 'message' => "exist"]);
            }

            $bank= new bank();
            $bank->bank_code= $request->get('txtBankCode');
            $bank->bank_name= $request->get('txtbankSearch');

           if ($bank->save()) {

                return response()->json(['status' => true]);
            } else {
                Log::error('Error saving common setting: ' . print_r($bank->getErrors(), true));
                return response()->json(['status' => false]);
            }

        } catch (Exception $ex) {
            return response()->json(['status' => false,'error' => $ex]);
        }
    }


public function getbannkEdit($id){
    $data = bank::find($id);
    return response()->json($data);
}

public function bankupdate(Request $request,$id){
    $bank = bank::findOrFail($id);
    $bank->bank_code = $request->input('txtBankCode');
    $bank->bank_name = $request->input('txtbankSearch');

        $bank->update();
        return response()->json($bank);
}



    public function bankStatus(Request $request,$id){
        $bank = bank::findOrFail($id);
        $bank->is_active = $request->status;
        $bank->save();

        return response()->json(' status updated successfully');
    }

    public function deletebank($id){
        $bank = bank::find($id);
            $bank->delete();
        return response()->json(['success'=>'Record has been Delete']);
    }

    //...................Branchers........................


    public function getBranchAlldata($id)
{
    try {
        $data = bank_branch::where('bank_id',$id)->get();
        return response()->json(['success' => 'Data loaded', 'data' => $data]);
    } catch (Exception $ex) {
        if ($ex instanceof ValidationException) {
            return response()->json([

            ]);
        }
    }


}

public function savebranch(Request $request){

    try {


        $bank= new bank_branch();
        $bank->bank_id=$request->get('bank_id');
        $bank->bank_branch_code= $request->get('txtbranchCode');
        $bank->bank_branch_name= $request->get('txtbranchSearch');

       if ($bank->save()) {

            return response()->json(['status' => true]);
        } else {
            Log::error('Error saving common setting: ' . print_r($bank->getErrors(), true));
            return response()->json(['status' => false]);
        }

    } catch (Exception $ex) {
        return response()->json(['status' => false,'error' => $ex]);
    }
}

public function getbranchkEdit($id){
$data = bank_branch::find($id);
return response()->json($data);
}

public function branchupdate(Request $request,$id){
    $bank = bank_branch::findOrFail($id);
    $bank->bank_id=$request->input('bank_id');
    $bank->bank_branch_code = $request->input('txtbranchCode');
    $bank->bank_branch_name = $request->input('txtbranchSearch');

        $bank->update();
        return response()->json($bank);
}

    public function branchStatus(Request $request,$id){
        $bank = bank_branch::findOrFail($id);
        $bank->is_active = $request->status;
        $bank->save();

        return response()->json(' status updated successfully');
    }

    public function deleteBranch($id){
        $bank = bank_branch::find($id);
            $bank->delete();
        return response()->json(['success'=>'Record has been Delete']);
    }
}

