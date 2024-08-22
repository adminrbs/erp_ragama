<?php

namespace Modules\Md\Http\Controllers;

use Illuminate\Routing\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Cb\Entities\branch;
use Modules\Md\Entities\Customer;
use Modules\Md\Entities\Customer_grade;
use Modules\Md\Entities\employee;
use Modules\Md\Entities\location;

class dataController extends Controller
{
    // getting data according to value
    public function getFilterData($id)
    {
        try {
            //getting customer details. 1 is value of customer in select tag
            if ($id == 1) {
                $customer = Customer::all();
                return response()->json($customer);
            } else if ($id == 2) {
                //getting customer details. 2 is value of customer grade in select tag
                $cusotmerGrade = Customer_grade::all();
                return response()->json($cusotmerGrade);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function getLocations()
    {
        try {
            $branch = branch::all();
            return response()->json($branch);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function getEmployees()
    {
        try {
            $employees = employee::all();
            return response()->json($employees);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function addCustomerLocation(Request $request)
    {
        try {

            $option_array = json_decode($request->input('option_array'), true);
            $datatype = $request->input('datatype');
            $locationid = json_decode($request->input('locationid'), true);

            //dd($locationid);


            foreach ($option_array as $user_id) {
                   
                $existingRecord = DB::table('customer_locations')
                    ->where('customer_id', $user_id)
                    ->where('location_id', $locationid)
                    ->first();

                    

                if (!$existingRecord) {
                    
                    DB::table('customer_locations')->insert([
                        'customer_id' => $user_id,
                        'location_id' => $locationid,
                    ]);
                }
            }
           
    
           /* foreach ($option_array as $val) {

                $query = "INSERT INTO customer_locations(customer_id ,branch_id,credit_allowed,credit_amount_alert_limit,credit_amount_hold_limit,credit_period_alert_limit,credit_period_hold_limit,pd_cheque_allowed,pd_cheque_limit,pd_cheque_max_period) 

                SELECT  customer_id , " . $locationid . " AS location,credit_allowed,credit_amount_alert_limit,credit_amount_hold_limit,credit_period_alert_limit,credit_period_hold_limit,pd_cheque_allowed,pd_cheque_limit,pd_cheque_max_period  FROM customers C
                WHERE    NOT   EXISTS 
                (
                 SELECT customer_id FROM customer_locations LC  
                 WHERE    LC.customer_id = " . $val . "  AND LC.branch_id=" . $locationid . " 
                
                )";
                  DB::insert($query);  
                /* return $query;  */
            //}*/



            /*  return response("true"); */
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function addEmployeeCustomer(Request $request)
    {/* 
        try {

            $option_array = json_decode($request->input('option_array'), true);
            $datatype = $request->input('datatype');
            $locationid = $request->input('locationid');
    
            foreach ($option_array as $val) {

                $query = "INSERT INTO customer_locations(customer_id ,branch_id,credit_allowed,credit_amount_alert_limit,credit_amount_hold_limit,credit_period_alert_limit,credit_period_hold_limit,pd_cheque_allowed,pd_cheque_limit,pd_cheque_max_period) 

                SELECT  customer_id , " . $locationid . " AS location,credit_allowed,credit_amount_alert_limit,credit_amount_hold_limit,credit_period_alert_limit,credit_period_hold_limit,pd_cheque_allowed,pd_cheque_limit,pd_cheque_max_period  FROM customers C
                WHERE    NOT   EXISTS 
                (
                 SELECT customer_id FROM customer_locations LC  
                 WHERE    LC.customer_id = " . $val . "  AND LC.branch_id=" . $locationid . " 
                
                )";
                  DB::insert($query);  
              
            }



            
        } catch (Exception $ex) {
            return $ex;
        } */
    }

    //load customer location to datatable

    public function getCustomerlocationDteails(Request $request)
    {
        try {

            $pageNumber = ($request->start / $request->length) + 1;
            $pageLength = $request->length;
            $skip = ($pageNumber - 1) * $pageLength;
            $searchValue = request('search.value');

            $query = DB::table('customer_locations')
    ->select(
        'customers.customer_id',
        'branches.branch_id',
        'customers.customer_name',
        DB::raw('IF(customers.credit_control_type = 1, "Primary credit control",
                  IF(customers.credit_control_type = 2, "Location wise credit control",
                  IF(customers.credit_control_type = 3, "Product group wise credit control",
                  IF(customers.credit_control_type = 4, "Purchase group wise credit control",
                  IF(customers.credit_control_type = 5, "Agency wise credit control", ""))))) AS credit_type_name'),
        DB::raw('IF(customer_locations.credit_allowed = 1, "Yes", "No") AS credit_allowed'),
        'branches.branch_name'
    )
    ->leftJoin('customers', 'customers.customer_id', '=', 'customer_locations.customer_id')
    ->leftJoin('branches', 'customer_locations.location_id', '=', 'branches.branch_id');
            
    if (!empty($searchValue)) {

        $query->where('branches.branch_name', 'like', '%' . $searchValue . '%')
            ->orWhere('customers.customer_name', 'like', '%' . $searchValue . '%');
            
    }
    $results = $query->take($pageLength)->skip($skip)->get();
    
    $results->transform(function ($item) {
        $statusLabel = '<label class="badge badge-pill bg-danger"></label>';

        if ($item->credit_allowed == "Yes") {
            $statusLabel = '<label class="badge badge-pill bg-success">Yes</label>';
        } else{
            $statusLabel = '<label class="badge badge-pill bg-danger">No</label>';
        }

        $item->statusLabel = $statusLabel;

        $checkbox = '<input class="form-check-input" type="checkbox" name="record[]" value="' . $item->branch_id . '|' . $item->customer_id . '">';
        $item->checkbox = $checkbox;

        return $item;
    });

    return response()->json([
        'data' => $results,
        'draw' => request('draw'),
    ]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //load employee customer details to data table
    public function getEmployeeCustomerDetails()
    {
        try {

            $query = 'SELECT customers.customer_id,
            employees.employee_id,
            customers.customer_name,
            IF(customers.credit_control_type = 1, "Primary credit control",
              IF(customers.credit_control_type = 2, "Location wise credit control",
              IF(customers.credit_control_type = 3, "Product group wise credit control",
              IF(customers.credit_control_type = 4, "Purchase group wise credit control",
              IF(customers.credit_control_type = 5, "Agency wise credit control", ""))))) AS credit_type_name,
            IF(customer_locations.credit_allowed = 1, "Yes", "No") AS credit_allowed,
            locations.location_name
            FROM customers 
            INNER JOIN customer_locations ON customers.customer_id = employee_customer.customer_id
            INNER JOIN employees ON employee_customer.employee_id = employees.employee_id';
            $result =  DB::select($query);
            return $result;
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function deleteCustomerLocation(Request $request)
    {
        $selectedRecords = $request->input('records');

        foreach ($selectedRecords as $record) {
            // Split the composite primary key into individual values
            $compositeKey = explode('|', $record);
            $id1 = $compositeKey[0]; // location id
            $id2 = $compositeKey[1]; // customer id
    
           
            DB::table('customer_locations')
                ->where('location_id', $id1)
                ->where('customer_id', $id2)
                ->delete();
        }
    
        return response()->json(['message' => 'Records deleted successfully']);

    }

    public function deleteEmployeeCustomer(Request $request){
        try{
            $selectedRecords = $request->input('records');

            foreach ($selectedRecords as $record) {
                // Split the composite primary key into individual values
                $compositeKey = explode('|', $record);
                $id1 = $compositeKey[0]; // employee id
                $id2 = $compositeKey[1]; // customer id
        
                // Perform the delete operation based on the composite primary key
                // Modify this logic based on your specific requirements
                DB::table('employee_customer')
                    ->where('employee_id', $id1)
                    ->where('customer_id', $id2)
                    ->delete();
            }
        
            return response()->json(['message' => 'Records deleted successfully']);
    

        }catch(Exception $ex){
            return $ex;
        }

    }


    public function getselectcustomer($id) {
        try {
            $query = "SELECT customer_name, location_id,customers.customer_id FROM `customer_locations`
                LEFT JOIN customers ON customer_locations.customer_id = customers.customer_id
                WHERE location_id = '$id'";
            
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
       
        DB::table('customer_locations')
                ->where('location_id', $branchId)
                ->where('customer_id', $eventValue)
                ->delete();

        return response()->json(['message' => 'Records deleted successfully']);

    }
}

