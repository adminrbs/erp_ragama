<?php

namespace Modules\Md\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Md\Entities\gl_account;
use Modules\Md\Entities\gl_account_type;
use Modules\Md\Entities\GlAccountAnalysis;

class GlAccountController extends Controller
{
    public function glaccountType()
    {
        try {
            $glaccountType = gl_account_type::all();
            return response()->json($glaccountType);
        } catch (Exception $ex) {
            return $ex;
        }
    }
    public function save_glaccount(Request $request)
    {


        try {
            $sent_account_code = $request->get('txtAccountCode');
            $qry_code = DB::select("SELECT COUNT(*) as count FROM gl_accounts WHERE gl_accounts.account_code = '" . $sent_account_code . "'");
            // dd($qry_code);
            if ($qry_code[0]->count > 0) {
                return response()->json(['status' => false, 'message' => 'duplicated']);
            }
            $glaccoun = new gl_account();
            $glaccoun->account_code  = $sent_account_code;
            $glaccoun->account_title = $request->get('txtAccountTitle');
            $glaccoun->account_type_id  = $request->get('cmdAccountType');



            if ($glaccoun->save()) {

                $Acc = new GlAccountAnalysis();
                $Acc->account_id = $glaccoun->account_id;
                $Acc->gl_account_analyse_name = "Not Applicable";
                

                return response()->json(['status' => $Acc->save()]);

            } else {

                return response()->json(['status' => false]);
            }
        } catch (Exception $ex) {
            return  $ex;
        }
    }

    public function allglaccountdata()
    {

        try {
            $quary = "SELECT gl.account_id,gl.account_code,gl.account_title , glt.gl_account_type FROM gl_accounts gl
      LEFT JOIN gl_account_types glt ON glt.gl_account_type_id= gl.account_type_id";
            $result = DB::select($quary);
            return response()->json($result);
        } catch (Exception $ex) {
            return $ex;
        }
    }
    public function getglaccount($id)
    {
        try {
            $glaccount = gl_account::find($id);
            return response()->json($glaccount);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function updateglAccount(Request $request, $id)
    {
        try {
           
            $sent_account_code = $request->get('txtAccountCode');
            $qry_code = DB::select("SELECT COUNT(*) as count FROM gl_accounts WHERE gl_accounts.account_code = '" . $sent_account_code . "'");
            if ($qry_code[0]->count == 1) {
                $qry_check_with_id = DB::select("SELECT account_id FROM gl_accounts WHERE gl_accounts.account_code = '" . $sent_account_code . "'");
                if ($qry_check_with_id[0]->account_id != $id) {
                    return response()->json(['status' => false, 'message' => 'duplicated']);
                }
            }
            $glaccount = gl_account::find($id);
            $glaccount->account_code = $request->input('txtAccountCode');
            $glaccount->account_title = $request->input('txtAccountTitle');
            $glaccount->account_type_id = $request->input('cmdAccountType');


            if ($glaccount->update()) {

                /* $analisys_name = $request->input('analysisName');
                if($analisys_name){
                    foreach($analisys_name as $name){
                        $Acc = new GlAccountAnalysis();
                        $Acc->account_id = $id;
                        $Acc->gl_account_analyse_name = $request->$name;
                        $Acc->save();
                    }
                } */

                return response()->json(["status" => true]);
            } else {
                return response()->json(["status" => false]);
            }
        } catch (Exception $ex) {

            return response()->json(["error" => $ex]);
        }
    }

    public function addglAccountAnalysis($id, Request $request){
        try{
            $Acc = new GlAccountAnalysis();
            $Acc->account_id = $id;
            $Acc->gl_account_analyse_name = $request->input('txtGlAccountAnalysis');
            return response()->json(["status" => $Acc->save()]);

        }catch(Exception $ex){
            return $ex;
        }
    }

    public function loadAnalysisAcc($id){
        try{
            $Acc = GlAccountAnalysis::where("account_id","=",$id)->get();
            if($Acc){
                return response()->json(["data" => $Acc]);  
            }

           

        }catch(Exception $ex){
            return $ex;
        }
    }

    public function delete_analysys($id){
        $gl_account_analysis = GlAccountAnalysis::findOrFail($id);
        if($gl_account_analysis->gl_account_analyse_name != 'Not Applicable'){
            return response()->json(["status" =>$gl_account_analysis->delete()]);
        }else{
            return response()->json(["msg"=>"unabletoDelete"]);
        }

        
    }
    public function  glAccounDelete($id)
    {

        $gl_account = gl_account::findOrFail($id);
        $gl_account->delete();

        return response()->json($gl_account);
    }
}
