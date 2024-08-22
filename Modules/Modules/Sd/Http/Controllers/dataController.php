<?php

namespace Modules\Sd\Http\Controllers;

use Exception;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Sd\Entities\Customer;
use Modules\Sd\Entities\customer_group;
use Modules\Sd\Entities\customer_grade;
use Modules\Sd\Entities\customerLocation;
use Modules\Sd\Entities\location;
use Illuminate\Support\Facades\DB;
use Modules\Sd\Entities\employee;

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
                $cusotmerGrade = customer_grade::all();
                return response()->json($cusotmerGrade);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function getLocations()
    {
        try {
            $locations = location::all();
            return response()->json($locations);
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
            }



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

                $query = "INSERT INTO customer_locations(customer_id ,location_id,credit_allowed,credit_amount_alert_limit,credit_amount_hold_limit,credit_period_alert_limit,credit_period_hold_limit,pd_cheque_allowed,pd_cheque_limit,pd_cheque_max_period) 

                SELECT  customer_id , " . $locationid . " AS location,credit_allowed,credit_amount_alert_limit,credit_amount_hold_limit,credit_period_alert_limit,credit_period_hold_limit,pd_cheque_allowed,pd_cheque_limit,pd_cheque_max_period  FROM customers C
                WHERE    NOT   EXISTS 
                (
                 SELECT customer_id FROM customer_locations LC  
                 WHERE    LC.customer_id = " . $val . "  AND LC.location_id=" . $locationid . " 
                
                )";
                  DB::insert($query);  
              
            }



            
        } catch (Exception $ex) {
            return $ex;
        } */
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
            customers.customer_name,
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
            ) AS credit_type_name, IF(customers.credit_allowed = 1,"Yes","No") AS credit_allowed
        FROM
            employee_customers
            INNER JOIN customers ON employee_customers.customer_id = customers.customer_id
            INNER JOIN employees ON employee_customers.employee_id = employees.employee_id
        ';
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
    
            // Perform the delete operation based on the composite primary key
            // Modify this logic based on your specific requirements
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
                DB::table('employee_customers')
                    ->where('employee_id', $id1)
                    ->where('customer_id', $id2)
                    ->delete();
            }
        
            return response()->json(['message' => 'Records deleted successfully']);
    

        }catch(Exception $ex){
            return $ex;
        }

    }
}

