<?php

namespace Modules\Md\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Md\Entities\GlAccountAnalysis;

class GlAccountAnalysisController extends Controller
{
    public function saveAnalysis(Request $request){
        try{
            $request->validate([
                'gl_account_analyses' => 'unique:gl_account_analyses,gl_account_analyse_name',
            ], [
                'gl_account_analyses.unique' => 'record duplicated',  
            ]);
                $Acc = new GlAccountAnalysis();
                $Acc->gl_account_analyse_name = $request->input('analysis');
                
                if($Acc->save()){
                    return response()->json((['status' => true]));
                }else{
                    return response()->json((['status' => false]));
                }
        }catch(Exception $ex){
            return $ex;
        }
    }

    public function loadGlAccountAnalysis(){
        $acc =  GlAccountAnalysis::all();
        if($acc){
            return response()->json((['status' => true,'data' => $acc]));
        }else{
            return response()->json((['status' => false,'data' => []]));
        }
    }

    public function loadEachGlAccountAnalysis($id){
        $acc =  GlAccountAnalysis::find($id);
        if($acc){
            return response()->json((['status' => true,'data' => $acc]));
        }else{
            return response()->json((['status' => false,'data' => []]));
        }
    }

    public function updateGlAccountAnalysis(Request $request,$id){
        try{
            //dd($id);
            $request->validate([
                'AccountGroupLevelName' => 'unique:gl_account_analyses,gl_account_analyse_name,' . $id . ',gl_account_analyse_id',
            ], [
                'AccountGroupLevelName.unique' => 'Record duplicated',
            ]);
            
                $acc = GlAccountAnalysis::find($id);
                //dd($LevelOne);
                $acc->gl_account_analyse_name = $request->input('analysis');
                
                if($acc->update()){
                    return response()->json((['status' => true]));
                }else{
                    return response()->json((['status' => false]));
                }
        }catch(Exception $ex){
            return $ex;
        }
    }

    public function deleteAnalysis($id){
        $acc = GlAccountAnalysis::find($id);
        if($acc->delete()){
            return response()->json((['status' => true]));
        }else{
            return response()->json((['status' => false]));
        }
    }
}
