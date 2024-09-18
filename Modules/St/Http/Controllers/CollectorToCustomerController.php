<?php

namespace Modules\St\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Cb\Entities\branch;
use Modules\Sd\Entities\Customer;
use Modules\Sd\Entities\employee;

class CollectorToCustomerController extends Controller
{
    
    public function getRoute_customers($id)
    {
        try {
            //getting customer details. 1 is value of customer in select tag
           
              /*   $customer = Customer::where("route_id","=",$id)->get();
                return response()->json($customer); */
                $query = "SELECT  customer_id , CONCAT(customers.customer_code,'-',customer_name,'-',T.townName) AS customer_name  FROM customers INNER JOIN town_non_administratives T ON customers.town = T.town_id WHERE customers.route_id = $id";

                $itemDetails = DB::select($query);
                
                if (!empty($itemDetails)) {
                    return response()->json($itemDetails);
                } else {
                    return response()->json(['error' => 'No data found for the given ID']);
                } 
     
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //get route customers
    public function getFilterData(Request $request,$id)
    {
        try {
            //getting customer details. 1 is value of customer in select tag
            $employee =  $request->get('selectedemployee');

            
            if ($id == 1) {
                if($employee == null){
                    $query = "SELECT  customer_id , CONCAT(customers.customer_code,'-',customer_name,'-',T.townName) AS customer_name  FROM customers INNER JOIN town_non_administratives T ON customers.town = T.town_id";

                    $itemDetails = DB::select($query);
                    
                    if (!empty($itemDetails)) {
                        return response()->json($itemDetails);
                    } else {
                        return response()->json(['error' => 'No data found for the given ID']);
                    } 
                }else{
                    $query = "SELECT  customer_id , CONCAT(customers.customer_code,'-',customer_name,'-',T.townName) AS customer_name  FROM customers INNER JOIN town_non_administratives T ON customers.town = T.town_id WHERE customer_id NOT IN 
                    (SELECT customer_id  FROM customer_collectors  WHERE employee_id=$employee)";

                    $itemDetails = DB::select($query);
                    
                    if (!empty($itemDetails)) {
                        return response()->json($itemDetails);
                    } else {
                        return response()->json(['error' => 'No data found for the given ID']);
                    } 
                }
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function getLocations()
    {
        try {
            $locations = branch::all();
            return response()->json($locations);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function getEmployees()
    {
        try {
           // $employees = employee::where("desgination_id","=", 7)->get();
           $employees = employee::whereIn('desgination_id', [7, 8])->get();
            return response()->json($employees);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function addEmployeeCustomer(Request $request)
    {
    
            try {

                $option_array = json_decode($request->input('option_array'), true);
                $datatype = $request->input('datatype');
                $emp_id = json_decode($request->input('empId'));
    
                //dd($option_array);

                foreach ($option_array as $user_id) {
                   
                    $existingRecord = DB::table('customer_collectors')
                        ->where('customer_id', $user_id)
                        ->where('employee_id', $emp_id)
                        ->first();
    
                        
    
                    if (!$existingRecord) {
                        
                        DB::table('customer_collectors')->insert([
                            'customer_id' => $user_id,
                            'employee_id' => $emp_id,
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

    public function getCustomerlocationDteails()
    {
        try {

            $query = 'SELECT customers.customer_id,
            locations.location_id,
            customers.customer_name,
            IF(customers.credit_control_type = 1, "Primary credit control",
              IF(customers.credit_control_type = 2, "Location wise credit control",
              IF(customers.credit_control_type = 3, "Product group wise credit control",
              IF(customers.credit_control_type = 4, "Purchase group wise credit control",
              IF(customers.credit_control_type = 5, "Agency wise credit control", ""))))) AS credit_type_name,
            IF(customer_locations.credit_allowed = 1, "Yes", "No") AS credit_allowed,
            locations.location_name
            FROM customers 
            INNER JOIN customer_locations ON customers.customer_id = customer_locations.customer_id
            INNER JOIN locations ON customer_locations.location_id = locations.location_id';
            $result =  DB::select($query);
            return $result;
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //load employee customer details to data table
    public function getEmployeeCustomerDetails()
    {
        try {

            $query = 'SELECT
            customers.customer_id,
            employees.employee_id,
            employees.employee_name,
            CONCAT(customers.customer_name,"-",customers.customer_code) AS customer_name,
            IF(
                customers.credit_control_type = 1, "Primary credit control",
                IF(
                    customers.credit_control_type = 2, "Location wise credit control",
                    IF(
                        customers.credit_control_type = 3, "Product group wise credit control",
                        IF(
                            customers.credit_control_type = 4, "Purchase group wise credit control",
                            IF(customers.credit_control_type = 5, "Agency wise credit control", "")
                        )
                    )
                )
            ) AS credit_type_name, IF(customers.credit_allowed = 1,"Yes","No") AS credit_allowed,
            routes.route_name
        FROM
        customer_collectors
            INNER JOIN customers ON customer_collectors.customer_id = customers.customer_id
            INNER JOIN employees ON customer_collectors.employee_id = employees.employee_id
            LEFT JOIN routes ON routes.route_id = customers.route_id
        ';
            $result =  DB::select($query);
            return $result;
        } catch (Exception $ex) {
            return $ex;
        }
    }


    

    public function deleteEmployeeCustomer(Request $request){
        try{
            $selectedRecords = $request->input('records');

            //dd($selectedRecords);

            foreach ($selectedRecords as $record) {
                // Split the composite primary key into individual values
                $compositeKey = explode('|', $record);
                $id1 = $compositeKey[0]; // employee id
                $id2 = $compositeKey[1]; // customer id
        
                // Perform the delete operation based on the composite primary key
                // Modify this logic based on your specific requirements
                DB::table('customer_collectors')
                    ->where('employee_id', $id1)
                    ->where('customer_id', $id2)
                    ->delete();
            }
        
            return response()->json(['message' => 'Records deleted successfully']);
    

        }catch(Exception $ex){
            return $ex;
        }

    }


    public function getselectuser($id)
    {
        try {
            $query = "SELECT CONCAT(customers.customer_code,'-',customer_name,'-',routes.route_name) AS customer_name, employee_id,customers.customer_id FROM customer_collectors
            LEFT JOIN customers ON customer_collectors.customer_id = customers.customer_id
            LEFT JOIN routes ON customers.route_id = routes.route_id
            WHERE employee_id = '$id'";

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
        
    try {
    
        $branchId = $request->input('branchId');
        $eventValue = $request->input('eventValue');
       
        DB::table('customer_collectors')
                ->where('employee_id', $branchId)
                ->where('customer_id', $eventValue)
                ->delete();

        return response()->json(['message' => 'Records deleted successfully']);
    } catch (Exception $ex) {
        return $ex;
    }

    }


    //get routes
    public function getRoutes()
    {
        try {


            $town = DB::select("SELECT * FROM routes");
            return response()->json($town);
        } catch (Exception $ex) {
            return $ex;
        }
    }
}
