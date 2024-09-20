<?php

namespace Modules\Md\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Md\Entities\AccountGroupLevelFour;
use Modules\Md\Entities\AccountGroupLevelOne;
use Modules\Md\Entities\AccountGroupLevelThree;
use Modules\Md\Entities\AccountGroupLevelTwo;

class AccountGroupLevelController extends Controller
{
    //Save account group level one
    public function saveAccountLevelOne(Request $request){
        try{
            $request->validate([
                'AccountGroupLevelName' => 'unique:account_group_level_ones,account_group_level_one_name',
            ], [
                'AccountGroupLevelName.unique' => 'record duplicated',  
            ]);
                $LevelOne = new AccountGroupLevelOne();
                $LevelOne->account_group_level_one_name = $request->input('AccountGroupLevelName');
                $LevelOne->created_by = Auth::user()->id;
                if($LevelOne->save()){
                    return response()->json((['status' => true]));
                }else{
                    return response()->json((['status' => false]));
                }
        }catch(Exception $ex){
            return $ex;
        }
    }

    //Load level one data to table in colapse
    public function loadAccountGroupLevelOne(){
        $LevelOne =  AccountGroupLevelOne::all();
        if($LevelOne){
            return response()->json((['status' => true,'data' => $LevelOne]));
        }else{
            return response()->json((['status' => false,'data' => []]));
        }
    }

    //load each level one to view or edit
    public function loadEachAccountGroupLevelOne($id){
        $LevelOne =  AccountGroupLevelOne::find($id);
        if($LevelOne){
            return response()->json((['status' => true,'data' => $LevelOne]));
        }else{
            return response()->json((['status' => false,'data' => []]));
        }
    }

    public function updateAccountGroupLevelOne(Request $request ,$id){
        try{
            //dd($id);
            $request->validate([
                'AccountGroupLevelName' => 'unique:account_group_level_ones,account_group_level_one_name,' . $id . ',account_group_level_one_id',
            ], [
                'AccountGroupLevelName.unique' => 'Record duplicated',
            ]);
            
                $LevelOne = AccountGroupLevelOne::find($id);
                //dd($LevelOne);
                $LevelOne->account_group_level_one_name = $request->input('AccountGroupLevelName');
                $LevelOne->created_by = Auth::user()->id;
                if($LevelOne->update()){
                    return response()->json((['status' => true]));
                }else{
                    return response()->json((['status' => false]));
                }
        }catch(Exception $ex){
            return $ex;
        }
    }

    public function deleteLevelOne($id){
        $LevelOne = AccountGroupLevelOne::find($id);
        if($LevelOne->delete()){
            return response()->json((['status' => true]));
        }else{
            return response()->json((['status' => false]));
        }
    }


    //Save level two
    public function saveAccountLevelTwo(Request $request){
        try{
            $request->validate([
                'AccountGroupLevelTwo' => 'unique:account_group_level_twos,account_group_level_two_name',
            ], [
                'AccountGroupLevelTwo.unique' => 'record duplicated',  
            ]);
                $LevelTwo = new AccountGroupLevelTwo();
                $LevelTwo->account_group_level_one_id = $request->input('AccountGroupLevelOne');
                $LevelTwo->account_group_level_two_name = $request->input('AccountGroupLevelTwo');
                $LevelTwo->created_by = Auth::user()->id;
                if($LevelTwo->save()){
                    return response()->json((['status' => true]));
                }else{
                    return response()->json((['status' => false]));
                }
        }catch(Exception $ex){
            return $ex;
        }
    }

    public function loadAccountGroupLevelTwo(){
        $LevelTwo =  DB::select('SELECT AGLO.account_group_level_one_name,AGLT.account_group_level_two_id, AGLT.account_group_level_two_name FROM account_group_level_twos AGLT INNER JOIN account_group_level_ones AGLO ON AGLT.account_group_level_one_id = AGLO.account_group_level_one_id');
        if($LevelTwo){
            return response()->json((['status' => true,'data' => $LevelTwo]));
        }else{
            return response()->json((['status' => false,'data' => []]));
        }
    }

    public function loadEachAccountGroupLevelTwo($id){
        $LevelTwo =  DB::select('SELECT AGLO.account_group_level_one_id,AGLT.account_group_level_two_id, AGLT.account_group_level_two_name FROM account_group_level_twos AGLT INNER JOIN account_group_level_ones AGLO ON AGLT.account_group_level_one_id = AGLO.account_group_level_one_id  WHERE AGLT.account_group_level_two_id = '.$id);
        if($LevelTwo){
            return response()->json((['status' => true,'data' => $LevelTwo]));
        }else{
            return response()->json((['status' => false,'data' => []]));
        }
    }

    public function updateAccountGroupLevelTwo(Request $request,$id){
        try{
            //dd($id);
            $request->validate([
                'AccountGroupLevelTwo' => 'unique:account_group_level_twos,account_group_level_two_name,' . $id . ',account_group_level_two_id',
            ], [
                'AccountGroupLevelTwo.unique' => 'Record duplicated',
            ]);
            
                $LevelTwo = AccountGroupLevelTwo::find($id);
                //dd($LevelOne);
                $LevelTwo->account_group_level_one_id = $request->input('AccountGroupLevelOne');
                $LevelTwo->account_group_level_two_name = $request->input('AccountGroupLevelTwo');
                $LevelTwo->created_by = Auth::user()->id;
                if($LevelTwo->update()){
                    return response()->json((['status' => true]));
                }else{
                    return response()->json((['status' => false]));
                }
        }catch(Exception $ex){
            return $ex;
        }
    }

    public function deleteLevelTwo($id){
        $LevelTwo = AccountGroupLevelTwo::find($id);
        if($LevelTwo->delete()){
            return response()->json((['status' => true]));
        }else{
            return response()->json((['status' => false]));
        }
    }



    public function saveAccountLevelThree(Request $request){
        try{
            $request->validate([
                'AccountGroupLevelThree' => 'unique:account_group_level_threes,account_group_level_three_name',
            ], [
                'AccountGroupLevelThree.unique' => 'record duplicated',  
            ]);
                $LevelThree = new AccountGroupLevelThree();
                $LevelThree->account_group_level_two_id = $request->input('AccountGroupLevelTwo');
                $LevelThree->account_group_level_three_name = $request->input('AccountGroupLevelThree');
                $LevelThree->created_by = Auth::user()->id;
                if($LevelThree->save()){
                    return response()->json((['status' => true]));
                }else{
                    return response()->json((['status' => false]));
                }
        }catch(Exception $ex){
            return $ex;
        }
    }

    public function loadAccountGroupLevelThree(){
        $LevelThree =  DB::select('SELECT AGLT.account_group_level_three_name,AGLT.account_group_level_three_id,AGLTW.account_group_level_two_name FROM account_group_level_threes AGLT INNER JOIN  account_group_level_twos AGLTW ON AGLT.account_group_level_two_id = AGLTW.account_group_level_two_id ');
        if($LevelThree){
            return response()->json((['status' => true,'data' => $LevelThree]));
        }else{
            return response()->json((['status' => false,'data' => []]));
        }
    }

    public function loadEachAccountGroupLevelThree($id){
        $LevelThree =  DB::select('SELECT account_group_level_two_id,account_group_level_three_id,account_group_level_three_name	 FROM account_group_level_threes AGLT WHERE AGLT.account_group_level_three_id = '.$id);
        if($LevelThree){
            return response()->json((['status' => true,'data' => $LevelThree]));
        }else{
            return response()->json((['status' => false,'data' => []]));
        }
    }

    public function updateAccountGroupLevelThree(Request $request,$id){
        try{
            //dd($id);
            $request->validate([
                'AccountGroupLevelThree' => 'unique:account_group_level_threes,account_group_level_three_name,' . $id . ',account_group_level_three_id',
            ], [
                'AccountGroupLevelThree.unique' => 'Record duplicated',
            ]);
            
                $LevelThree = AccountGroupLevelThree::find($id);
               // dd($request);
                $LevelThree->account_group_level_two_id = $request->input('AccountGroupLevelTwo');
                $LevelThree->account_group_level_three_name = $request->input('AccountGroupLevelThree');
                $LevelThree->created_by = Auth::user()->id;
                if($LevelThree->update()){
                    return response()->json((['status' => true]));
                }else{
                    return response()->json((['status' => false]));
                }
        }catch(Exception $ex){
            return $ex;
        }
    }

    public function deleteLevelThree($id){
        $LevelThree = AccountGroupLevelThree::find($id);
        if($LevelThree->delete()){
            return response()->json((['status' => true]));
        }else{
            return response()->json((['status' => false]));
        }
    }


    public function saveAccountLevelFour(Request $request){
        try{
           // dd($request);
            $request->validate([
                'AccountGroupLevelFour' => 'unique:account_group_level_fours,account_group_level_four_name',
            ], [
                'AccountGroupLevelFour.unique' => 'record duplicated',  
            ]);
                $LevelFour = new AccountGroupLevelFour();
                $LevelFour->account_group_level_three_id = $request->input('AccountGroupLevelThree');
                $LevelFour->account_group_level_four_name = $request->input('AccountGroupLevelFour');
                $LevelFour->created_by = Auth::user()->id;
                if($LevelFour->save()){
                    return response()->json((['status' => true]));
                }else{
                    return response()->json((['status' => false]));
                }
        }catch(Exception $ex){
            return $ex;
        }
    }

    public function loadAccountGroupLevelFour(){
        $LevelFour =  DB::select('SELECT AGLF.account_group_level_four_id,AGLF.account_group_level_four_name,AGLT.account_group_level_three_name FROM account_group_level_fours AGLF INNER JOIN account_group_level_threes AGLT ON AGLF.account_group_level_three_id = AGLT.account_group_level_three_id ');
        if($LevelFour){
            return response()->json((['status' => true,'data' => $LevelFour]));
        }else{
            return response()->json((['status' => false,'data' => []]));
        }
    }

    public function loadEachAccountGroupLevelFour($id){
        $LevelFour =  DB::select('SELECT account_group_level_three_id,account_group_level_FOUR_id,account_group_level_four_name FROM account_group_level_fours AGLF WHERE AGLF.account_group_level_four_id = '.$id);
        if($LevelFour){
            return response()->json((['status' => true,'data' => $LevelFour]));
        }else{
            return response()->json((['status' => false,'data' => []]));
        }
    }

    public function updateAccountGroupLevelFour(Request $request,$id){
        try{
            //dd($id);
            $request->validate([
                'AccountGroupLevelFour' => 'unique:account_group_level_fours,account_group_level_four_name,' . $id . ',account_group_level_four_id',
            ], [
                'AccountGroupLevelFour.unique' => 'Record duplicated',
            ]);
            
                $LevelThree = AccountGroupLevelFour::find($id);
               // dd($request);
                $LevelThree->account_group_level_three_id = $request->input('AccountGroupLevelThree');
                $LevelThree->account_group_level_four_name = $request->input('AccountGroupLevelFour');
                $LevelThree->created_by = Auth::user()->id;
                if($LevelThree->update()){
                    return response()->json((['status' => true]));
                }else{
                    return response()->json((['status' => false]));
                }
        }catch(Exception $ex){
            return $ex;
        }
    }

    public function deleteLevelFour($id){
        $LevelFour = AccountGroupLevelFour::find($id);
        if($LevelFour->delete()){
            return response()->json((['status' => true]));
        }else{
            return response()->json((['status' => false]));
        }
    }


}
