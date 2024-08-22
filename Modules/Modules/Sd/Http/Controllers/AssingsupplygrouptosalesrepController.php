<?php

namespace Modules\Sd\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Sd\Entities\employee;
use Modules\Sd\Entities\supply_group;

class AssingsupplygrouptosalesrepController extends Controller
{
    // getting data according to value
    public function getFilterData(Request $request, $id)
    {
        try {
            $selesrep =  $request->get('selectedselesrep');
            //getting customer details. 1 is value of customer in select tag
            if ($id == 1) {
                if ($selesrep == null) {
                    $supplygroup = supply_group::all();
                    return response()->json(['success' => 'Data loaded', 'data' => $supplygroup]);
                } else {

                    $query = "SELECT  supply_group_id , supply_group  FROM supply_groups 
                    WHERE supply_group_id NOT IN 
                    (SELECT supply_group_id   FROM supplygroup_employees  WHERE sales_rep_id= $selesrep)";

                    $itemDetails = DB::select($query);

                    if (!empty($itemDetails)) {
                        return response()->json(['success' => 'Data loaded', 'data' => $itemDetails]);
                    } else {
                        return response()->json(['error' => 'No data found for the given ID']);
                    }
                }
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function getsalesrep()
    {

        try {
            $employee = employee::where("desgination_id","=",7)->get();
            return response()->json($employee);
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function addsupplygrouptoSalesrep(Request $request)
    {
        try {

         
            $datatype = $request->input('datatype');
           

            if ($datatype == "user") {
                $option_array = json_decode($request->input('option_array'), true);
                $locationid = json_decode($request->input('locationid'), true);

                foreach ($option_array as $supplygroup) {

                    $existingRecord = DB::table('supplygroup_employees')
                        ->where('supply_group_id', $supplygroup)
                        ->where('sales_rep_id', $locationid)
                        ->first();



                    if (!$existingRecord) {

                        DB::table('supplygroup_employees')->insert([
                            'supply_group_id' => $supplygroup,
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

    public function getsupplygrouptosalesrepDteail()
    {
        try {

            

            $query = 'SELECT  supply_groups.supply_group_id,
            employees.employee_id,
            supply_groups.supply_group,
            
            employees.employee_name
            FROM supplygroup_employees 
            LEFT JOIN supply_groups ON supply_groups.supply_group_id = supplygroup_employees.supply_group_id
            LEFT JOIN employees ON supplygroup_employees.sales_rep_id  = employees.employee_id';
            $result =  DB::select($query);
            return $result;
        } catch (Exception $ex) {
            return $ex;
        }
    }




    public function deletesupplygrouptosalesrep(Request $request)
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
            DB::table('supplygroup_employees')
                ->where('sales_rep_id', $id1)
                ->where('supply_group_id', $id2)
                ->delete();
        }

        return response()->json(['message' => 'Records deleted successfully']);

    } catch (Exception $ex) {
        return $ex;
    }
    }




    public function getselectsupplygroup($id)
    {
        try {
            $query = "SELECT supply_groups.supply_group, supply_groups.supply_group_id FROM supplygroup_employees
            LEFT JOIN supply_groups ON supplygroup_employees.supply_group_id = supply_groups.supply_group_id
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


    public function selectdeletsupplygroupsalesrep(Request $request)
    {
        try {

        $employee = $request->input('employee');
        $eventValue = $request->input('eventValue');

        

        DB::table('supplygroup_employees')
            ->where('sales_rep_id', $employee)
            ->where('supply_group_id', $eventValue)
            ->delete();

        return response()->json(['message' => 'Records deleted successfully']);

    } catch (Exception $ex) {
        return $ex;
    }
    }


    
    public function copysalesrep(Request $request)
    {
        try {

            $fromemp = $request->input('fromemp');
            $toemp = $request->input('toemp');

            

            $itemsToUpdate = DB::table('supplygroup_employees')
                ->where('sales_rep_id', $fromemp)
                ->get();

               
                 // Initialize an array to store updated items

                // Update the route_id and sales_rep_id for the retrieved records
               
                   
                    foreach ($itemsToUpdate as $item) {
                        // Check if a record with the same 'route_id' and 'sales_rep_id' exists for $toemp
                        $existingRecord = DB::table('supplygroup_employees')
                            ->where('supply_group_id', $item->supply_group_id)
                            ->where('sales_rep_id', $toemp)
                            ->first();
                    
                        // If the record doesn't exist, insert a new one
                        if (!$existingRecord) {
                            DB::table('supplygroup_employees')->insert([
                                'supply_group_id' => $item->supply_group_id,
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
