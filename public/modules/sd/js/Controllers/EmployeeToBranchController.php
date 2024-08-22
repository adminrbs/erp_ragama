<?php

namespace Modules\Sd\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Cb\Entities\branch;
use Modules\Sd\Entities\employee;

class EmployeeToBranchController extends Controller
{
    public function getFilterData($id)
    {
        try {
            //getting customer details. 1 is value of customer in select tag
            if ($id == 1) {
                $query = ' SELECT employees.employee_id,employees.employee_name FROM `employees` ';
                $result =  DB::select($query);
                return response()->json($result);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function getBranch()
    {
        try {
            $locations = branch::all();
            return response()->json($locations);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    

    public function addEmployeeBranch(Request $request)
    {
    
            try {

                $option_array = json_decode($request->input('option_array'), true);
                $datatype = $request->input('datatype');
                $locationid = json_decode($request->input('locationid'));
    
                //dd($locationid);

                foreach ($option_array as $user_id) {
                   
                    $existingRecord = DB::table('employee_branches')
                        ->where('employee_id', $user_id)
                        ->where('branch_id', $locationid)
                        ->first();
    
                        
    
                    if (!$existingRecord) {
                        
                        DB::table('employee_branches')->insert([
                            'employee_id' => $user_id,
                            'branch_id' => $locationid,
                        ]);
                    }
                }
    

          /*  $option_array = json_decode($request->input('option_array'), true);
            $datatype = $request->input('datatype');
            $locationid = $request->input('locationid');
    
            foreach ($option_array as $val) {

                $query = "INSERT INTO customer_locations(customer_id ,location_id,credit_allowed,credit_amount_alert_limit,credit_amount_hold_limit,credit_period_alert_limit,credit_period_hold_limit,pd_cheque_allowed,pd_cheque_limit,pd_cheque_max_period) 

                SELECT  customer_id , " . $locationid . " AS location,credit_allowed,credit_amount_alert_limit,credit_amount_hold_limit,credit_period_alert_limit,credit_period_hold_limit,pd_cheque_allowed,pd_cheque_limit,pd_cheque_max_period  FROM customers C
                WHERE    NOT   EXISTS 
                (
                 SELECT customer_id FROM customer_locations LC  
                 WHERE    LC.customer_id = " . $val . "  AND LC.location_id=" . $locationid . " 
                
                )";
                  DB::insert($query);  
                /* return $query;  */
           // }



            /*  return response("true"); */
        } catch (Exception $ex) {
            return $ex;
        }
    }

    
    //load customer location to datatable

   

    //load employee customer details to data table
    public function getEmployeeBranchDetails()
    {
        try {

            $query = 'SELECT      
            employees.employee_id,
            branches .branch_id,
            employees.employee_name,
            
            branches.branch_name
            FROM employee_branches 
            LEFT JOIN employees ON employees.employee_id = employee_branches.employee_id
            LEFT JOIN branches ON employee_branches.branch_id  = branches.branch_id';
            $result =  DB::select($query);
            return $result;
        } catch (Exception $ex) {
            return $ex;
        }
    }


    

    public function deleteEmployeeBranch(Request $request){
        try{
            $selectedRecords = $request->input('records');

            //dd($selectedRecords);

            foreach ($selectedRecords as $record) {
               
                $compositeKey = explode('|', $record);
                $id1 = $compositeKey[0]; // employee id
                $id2 = $compositeKey[1]; // customer id
        
                // Perform the delete operation based on the composite primary key
                // Modify this logic based on your specific requirements
                DB::table('employee_branches')
                    ->where('employee_id', $id1)
                    ->where('branch_id', $id2)
                    ->delete();
            }
        
            return response()->json(['message' => 'Records deleted successfully']);
    

        }catch(Exception $ex){
            return $ex;
        }

    }


    public function getselectbranch($id)
    {
        try {
            $query = "SELECT employee_name, branch_id ,employees.employee_id FROM employee_branches
            LEFT JOIN employees ON employee_branches.employee_id = employees.employee_id
            WHERE branch_id ='$id'";

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

        
       
        DB::table('employee_branches')
                ->where('employee_id', $eventValue)
                ->where('branch_id', $branchId)
                ->delete();

        return response()->json(['message' => 'Records deleted successfully']);

    }
}
