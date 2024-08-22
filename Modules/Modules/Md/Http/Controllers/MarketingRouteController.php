<?php

namespace Modules\Md\Http\Controllers;

use App\Http\Controllers\UtilityController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Md\Entities\marketingRoute;

class MarketingRouteController extends Controller
{
    //add route
   public function addMarketingRoute(Request $request){
    try{
        $request->validate([
    
            'txtRoute' => 'required'
            
        ]);
        $route_ = new marketingRoute();
        $route_->route_name = $request->input('txtRoute');
        
       
        if($route_->save()){
            return response()->json(["status" => true]);
        }else{
            return response()->json(["status" => false]);
        }
    }catch(Exception $ex){
        return $ex;
    }
   }

   //fetch routes to table
   public function getMarketingRoutes()
    {
        try {


            $routes = DB::select("SELECT * FROM marketing_routes");
            return response()->json($routes);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //get each route to update
    public function getEachMarketingRoute($id){
        try{
            $route = marketingRoute::find($id);
            if($route){
                return response()->json($route);
            }else{
                return response()->json(["status" => false,"data"=>[]]);
            }
        }catch(Exception $ex){
            return $ex;
        }
       }

       //update route
       public function updateMarketingRoute(Request $request,$id){
        try{
            $request->validate([
    
                'txtRoute' => 'required'
                
            ]);
            $result = UtilityController::containsValue_update('marketing_routes','route_name',$request->input('txtRoute'),$id);

            if($result == 0){
                $route_ =  marketingRoute::find($id);

                $route_->route_name = $request->input('txtRoute');
                
                if($route_->update()){
                    return response()->json(["status" => true]);
                }else{
                    return response()->json(["status" => false]);
                }
    

            }else{
                return response()->json(["status" => false, 'message' => "duplicated"]);
            }
          
        }catch(Exception $ex){
            return $ex;
        }

       }
}
