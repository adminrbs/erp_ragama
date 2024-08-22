<?php

namespace Modules\Sd\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Sd\Entities\route;
use Modules\Sd\Entities\route_town;
use Modules\Sd\Entities\TownNonAdministrative;

class RouteController extends Controller
{
       public function addRoute(Request $request){
        try{
            $request->validate([
    
                'txtRoute' => 'required'
                
            ]);

            $route_ = new route();
            $route_->route_name = $request->input('txtRoute');
            if($request->input('txtNOrderNum')){
                $route_->route_order = $request->input('txtNOrderNum');
            }
           
            if($route_->save()){
                return response()->json(["status" => true]);
            }else{
                return response()->json(["status" => false]);
            }

        }catch(Exception $ex){
            return $ex;
        }
       }


       public function getRoutes(){
        try{
            $towns = "SELECT * FROM routes ORDER BY route_order ASC";
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


       public function getEachRoute($id){
        try{
            $route = route::find($id);
            if($route){
                return response()->json($route);
            }else{
                return response()->json(["status" => false,"data"=>[]]);
            }
        }catch(Exception $ex){
            return $ex;
        }
       }


       public function updateRoute(Request $request,$id){
        try{
            $request->validate([
    
                'txtRoute' => 'required'
                
            ]);

            $route_ =  route::find($id);
            $route_->route_name = $request->input('txtRoute');
            if($request->input('txtNOrderNum')){
                $route_->route_order = $request->input('txtNOrderNum');
            }
            if($route_->update()){
                return response()->json(["status" => true]);
            }else{
                return response()->json(["status" => false]);
            }

        }catch(Exception $ex){
            return $ex;
        }

       }


       public function deleteRoute($id){
        try{
            $route = route::find($id);
            if($route->delete()){
                return response()->json(["status" => true]);
            }else{
                return response()->json(["status" => false]);
            }
        }catch(Exception $ex){
            return $ex;
        }
       }

       //load non admin town
       public function load_non_admin_towns($id){
        try{
            $non_admin_town = $result = DB::select('
            SELECT town_non_administratives.town_id, town_non_administratives.townName
            FROM town_non_administratives
            LEFT JOIN route_towns ON town_non_administratives.town_id = route_towns.town_id AND route_towns.route_id = ?
            WHERE route_towns.town_id IS NULL
        ', [$id]);
        
            if($non_admin_town){
                return response()->json(["status" => true,"data"=>$non_admin_town]);
            }else{
                return response()->json(["status" => false]);
            }
        }catch(Exception $ex){
            return $ex;
        }
       }

       public function add_route_town(Request $request, $id){
        try{
            $town_array = $request->input('townArray');
           $record = route_town::where('route_id','=',$id)->delete();
            foreach($town_array as $townID ){
                $route_town = new route_town();
                $route_town->town_id = $townID;
                $route_town->route_id = $id;
                $route_town->save();
            }
            return response()->json(["status" => true]);


        }catch(Exception $ex){
            return $ex;
        }
       }

       //load selected town
       public function loadSelectedtowns($id){
        try{
            $qry = DB::select("SELECT route_towns.town_id, town_non_administratives.townName FROM route_towns INNER JOIN town_non_administratives ON route_towns.town_id = town_non_administratives.town_id WHERE route_towns.route_id = $id ");
            if($qry){
                return response()->json(["status" => true,"data"=>$qry]);
            }else{
                return response()->json(["status" => true,"data"=>[]]);
            }
        }catch(Exception $ex){
            return $ex;
        }
       }
}
