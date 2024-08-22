<?php

namespace Modules\Sd\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Sd\Entities\employee;
use Modules\Sd\Entities\route;

class AssingrouttosalesrepController extends Controller
{
    // getting data according to value
    public function getFilterData(Request $request, $id)
    {
        try {
            $selesrep =  $request->get('selectedselesrep');
           
            //getting customer details. 1 is value of customer in select tag
           
                if ($selesrep == null) {
                    $customer = route::all();
                    return response()->json(['success' => 'Data loaded', 'data' => $customer]);
                } else {

                    $query = "SELECT  route_id , route_name  FROM routes 
                    WHERE route_id NOT IN 
                    (SELECT route_id   FROM route_employes  WHERE sales_rep_id= $selesrep)";

                    $itemDetails = DB::select($query);

                    if (!empty($itemDetails)) {
                        return response()->json(['success' => 'Data loaded', 'data' => $itemDetails]);
                    } else {
                        return response()->json(['error' => 'No data found for the given ID']);
                    }
                }
            
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function getsalesrep()
    {

        try {
            $emp = employee::all();
            return response()->json($emp);
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function addroutetoSalesrep(Request $request)
    {
        try {


            $datatype = $request->input('datatype');


            if ($datatype == "user") {
                $option_array = json_decode($request->input('option_array'), true);
                $locationid = json_decode($request->input('locationid'), true);

                foreach ($option_array as $routid) {

                    $existingRecord = DB::table('route_employes')
                        ->where('route_id', $routid)
                        ->where('sales_rep_id', $locationid)
                        ->first();



                    if (!$existingRecord) {

                        DB::table('route_employes')->insert([
                            'route_id' => $routid,
                            'sales_rep_id' => $locationid,
                        ]);
                    }
                }
                //return response()->json();






                /* $option_array = json_decode($request->input('option_array'), true);

                $locationid = json_decode($request->input('locationid'), true);

                $del = DB::table('user_baranchs')->where('branch_id', '=', $locationid)->delete();

                foreach ($option_array as $user_id) {

                    $query = "INSERT INTO user_baranchs (user_id, branch_id) VALUES (?, ?)";

                    DB::insert($query, [$user_id, $locationid]);
                }
                return response()->json();*/
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //load customer location to datatable

    public function getroutetosalesrepDteails()
    {
        try {



            $query = 'SELECT  routes.route_id,
            employees.employee_id,
            routes.route_name,
            
            employees.employee_name
            FROM route_employes 
            LEFT JOIN routes ON routes.route_id = route_employes.route_id
            LEFT JOIN employees ON route_employes.sales_rep_id  = employees.employee_id';
            $result =  DB::select($query);
            return $result;
        } catch (Exception $ex) {
            return $ex;
        }
    }




    public function deleteroutetosalesrep(Request $request)
    {
        try {
        $selectedRecords = $request->input('records');

        foreach ($selectedRecords as $record) {
            // Split the composite primary key into individual values
            $compositeKey = explode('|', $record);

            $id1 = $compositeKey[0]; // location id
            $id2 = $compositeKey[1]; // customer id



            // Perform the delete operation based on the composite primary key
            // Modify this logic based on your specific requirements
            DB::table('route_employes')
                ->where('sales_rep_id', $id1)
                ->where('route_id', $id2)
                ->delete();
        }

        return response()->json(['message' => 'Records deleted successfully']);

    } catch (Exception $ex) {
        return $ex;
    }
    }




    public function getselectroute($id)
    {
        try {
            $query = "SELECT routes.route_name, routes.route_id FROM route_employes
            LEFT JOIN routes ON route_employes.route_id = routes.route_id
            WHERE sales_rep_id= '$id'";

            $itemDetails = DB::select($query);

            if (!empty($itemDetails)) {
                return response()->json(['success' => 'Data loaded', 'data' => $itemDetails]);
            } else {
                return response()->json(['error' => 'No data found for the given ID']);
            }
        } catch (Exception $ex) {
            // Log the exception for debugging
            return response()->json(['error' => 'An error occurred: ' . $ex->getMessage()]);
        }
    }


    public function selectdeletroutesalesrep(Request $request)
    {
        try {

        $salesrep = $request->input('SalesReps');
        $eventValue = $request->input('eventValue');

        //dd($salesrep);

        DB::table('route_employes')
            ->where('sales_rep_id', $salesrep)
            ->where('route_id', $eventValue)
            ->delete();

        return response()->json(['message' => 'Records deleted successfully']);

    } catch (Exception $ex) {
        // Log the exception for debugging
        return response()->json(['error' => 'An error occurred: ' . $ex->getMessage()]);
    }
    }



    public function genewtsalesrep(Request $request)
    {
        try {

            $fromemp = $request->input('fromemp');
            $toemp = $request->input('toemp');

            $itemsToUpdate = DB::table('route_employes')
                ->where('sales_rep_id', $fromemp)
                ->get();

               
                 // Initialize an array to store updated items

                // Update the route_id and sales_rep_id for the retrieved records
               
                   
                    foreach ($itemsToUpdate as $item) {
                        // Check if a record with the same 'route_id' and 'sales_rep_id' exists for $toemp
                        $existingRecord = DB::table('route_employes')
                            ->where('route_id', $item->route_id)
                            ->where('sales_rep_id', $toemp)
                            ->first();
                    
                        // If the record doesn't exist, insert a new one
                        if (!$existingRecord) {
                            DB::table('route_employes')->insert([
                                'route_id' => $item->route_id,
                                'sales_rep_id' => $toemp,
                            ]);
                        }
                    }
                    
                
                    return response()->json(['success' => 'Records updated successfully']);
           
        } catch (Exception $ex) {
            // Log the exception for debugging
            return response()->json(['error' => 'An error occurred: ' . $ex->getMessage()]);
        }
    }
}
