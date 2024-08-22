<?php

namespace Modules\Md\Http\Controllers;

use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Md\Entities\TownNonAdministrative;

class TownNonadministrativeController extends Controller
{
   public function addTownNonAdministration(Request $request){
        try{
            $request->validate([
    
                'txtTown' => 'required'
                
            ]);

            $town = new TownNonAdministrative();
            $town->townName = $request->input('txtTown');
            $town->district_id = $request->input('cmbDistrict');
            if($town->save()){
                return response()->json(["status" => true]);
            }else{
                return response()->json(["status" => false]);
            }   

        }catch(Exception $ex){
            return $ex;
        }

   }

   //get non-admin town to customer page
   public function getTownNonAdmin($id){
    try {
        $town = DB::select("SELECT *FROM town_non_administratives WHERE town_non_administratives.district_id = '".$id."'");
        return response()->json($town);
    } catch (Exception $ex) {
         return $ex;
    }
   }

   //town list
   public function getTownList(){
    try{
        $towns = "SELECT town_non_administratives.townName,town_non_administratives.town_id,districts.district_name FROM town_non_administratives INNER JOIN districts ON town_non_administratives.district_id = districts.district_id ";
        $result = DB::select($towns);
        if($result){
            return response()->json(["status" => true,"data"=>$result]);
        }else{
            return response()->json(["status" => true,"data"=>[]]);
        }
    }catch(Exception $ex){
        return $ex;
    }
   }

   public function getEachTowninfo($id){
    try{
        $town = TownNonAdministrative::find($id);
        if($town){
            return response()->json($town);
        }else{
            return response()->json(["status" => false,"data"=>[]]);
        }
    }catch(Exception $ex){
        return $ex;
    }
   }


   public function deleteTownN($id){
    try{
        $town = TownNonAdministrative::find($id);
        if($town->delete()){
            return response()->json(["status" => true]);
        }else{
            return response()->json(["status" => false]);
        }
    }catch(Exception $ex){
        return $ex;
    }
   }


   public function updateTownNonAdministrative(Request $request,$id){
    try{
        $request->validate([

            'txtTown' => 'required'
            
        ]);

        $town =  TownNonAdministrative::find($id);
        $town->townName = $request->input('txtTown');
        $town->district_id = $request->input('cmbDistrict');
        if($town->update()){
            return response()->json(["status" => true]);
        }else{
            return response()->json(["status" => false]);
        }   

    }catch(Exception $ex){
        return $ex;
    }

   }
}
