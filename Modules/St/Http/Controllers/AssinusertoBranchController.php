<?php

namespace Modules\St\Http\Controllers;

use App\Models\User;
use App\Models\UserRole;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Cb\Entities\branch;

class AssinusertoBranchController extends Controller
{
    // getting data according to value
    public function getFilterData($id)
    {
        try {
            //getting customer details. 1 is value of customer in select tag
            if ($id == 1) {
                $customer = User::all();
                return response()->json($customer);
            } else if ($id == 2) {
                $query = 'SELECT name,id FROM roles'; 
                
                $result =  DB::select($query);

                //dd($result);
                return response()->json($result);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function getBranch()
    {

        try {
            $branch = branch::all();
            return response()->json($branch);
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function addUsetBranch(Request $request)
    {
        try {

            $option_array = json_decode($request->input('option_array'), true);
            $datatype = $request->input('datatype');
            $locationid = json_decode($request->input('locationid'));


            if ($datatype == "user_role") {
                foreach ($option_array as $option) {
                    $role_id = $option;
                
                   
                    $userRoles = DB::table('users_roles')
                        ->where('role_id', $role_id)
                        ->get();
                
                    foreach ($userRoles as $userRole) {
                       
                        $user_id = $userRole->user_id;
                
                       
                        $existingRecord = DB::table('user_baranchs')
                            ->where('user_id', $user_id)
                            ->where('branch_id', $locationid)
                            ->first();

                             if (!$existingRecord) {
                         
                                DB::table('user_baranchs')->insert([
                                    'user_id' => $user_id,
                                    'branch_id' => $locationid,
                                ]);
                                return response()->json(['status' => true]);
                                
                            }
  
                    }
                    //return response()->json();
                }
            







                //$del = DB::table('user_baranchs')->where('branch_id', '=', $locationid  )->delete();




                


                    /*  $query = "INSERT INTO user_baranchs (user_id, branch_id) 
                        SELECT U.user_id, $locationid
                        FROM users U 
                        INNER JOIN users_roles UR ON U.user_id = UR.user_id 
                        WHERE UR.role_id = $option";
              
             
                      DB::insert($query);*/
                
                //return response()->json();




            } elseif ($datatype == "user") {
                 $option_array = json_decode($request->input('option_array'), true);
                $locationid = json_decode($request->input('locationid'), true);

                foreach ($option_array as $user_id) {
                   
                    $existingRecord = DB::table('user_baranchs')
                        ->where('user_id', $user_id)
                        ->where('branch_id', $locationid)
                        ->first();

                        

                    if (!$existingRecord) {
                        
                        DB::table('user_baranchs')->insert([
                            'user_id' => $user_id,
                            'branch_id' => $locationid,
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

    public function getUserBranchDteails()
    {
        try {

            /*  $query = 'SELECT customers.customer_id,
            branches .branch_id,
            customers.customer_name,
            IF(customers.credit_control_type = 1, "Primary credit control",
              IF(customers.credit_control_type = 2, "Location wise credit control",
              IF(customers.credit_control_type = 3, "Product group wise credit control",
              IF(customers.credit_control_type = 4, "Purchase group wise credit control",
              IF(customers.credit_control_type = 5, "Agency wise credit control", ""))))) AS credit_type_name,
            IF(customer_locations.credit_allowed = 1, "Yes", "No") AS credit_allowed,
            branches.branch_name
            FROM customer_locations 
            LEFT JOIN customers ON customers.customer_id = customer_locations.customer_id
            LEFT JOIN branches ON customer_locations.location_id  = branches.branch_id';
            $result =  DB::select($query);*/

            $query = 'SELECT      users.id,
            branches .branch_id,
            users.name,
            
            branches.branch_name
            FROM user_baranchs 
            LEFT JOIN users ON users.id = user_baranchs.user_id
            LEFT JOIN branches ON user_baranchs.branch_id  = branches.branch_id';
            $result =  DB::select($query);
            return $result;
        } catch (Exception $ex) {
            return $ex;
        }
    }

   


    public function deleteuserBranch(Request $request)
    {
        $selectedRecords = $request->input('records');

        foreach ($selectedRecords as $record) {
            // Split the composite primary key into individual values
            $compositeKey = explode('|', $record);

            $id1 = $compositeKey[0]; // location id
            $id2 = $compositeKey[1]; // customer id

            // Perform the delete operation based on the composite primary key
            // Modify this logic based on your specific requirements
            DB::table('user_baranchs')
                ->where('branch_id', $id1)
                ->where('user_id', $id2)
                ->delete();
        }

        return response()->json(['message' => 'Records deleted successfully']);
    }




    public function getselectuser($id)
    {
        try {
            $query = "SELECT name, branch_id,users.id FROM user_baranchs
            LEFT JOIN users ON user_baranchs.user_id = users.id
            WHERE branch_id= '$id'";

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


    public function selectdeletuserBranch(Request $request){

        $branchId = $request->input('branchId');
        $eventValue = $request->input('eventValue');
       
        DB::table('user_baranchs')
                ->where('branch_id', $branchId)
                ->where('user_id', $eventValue)
                ->delete();

        return response()->json(['message' => 'Records deleted successfully']);

    }
}
