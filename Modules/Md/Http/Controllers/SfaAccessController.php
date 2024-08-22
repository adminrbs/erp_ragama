<?php

namespace Modules\Md\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Md\Entities\employee;

class SfaAccessController extends Controller
{

    public function getEmployee()
    {
        try {
            $quary = "SELECT E.employee_id, E.full_name, E.employee_name 
            FROM employees E
            WHERE E.mobile_user_name IS NULL AND E.mobile_app_password IS NULL";
            $result = DB::select($quary);
            return response()->json($result);
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function saveSfa(Request $request)
    {
        //$username = $request->get('cmbusername');

        if ($username = $request->input('cmbusername')) {
            $query = "SELECT COUNT(*) AS mobile_user_name FROM employees WHERE mobile_user_name = '$username'";
            $reuslt = DB::select($query);
            if ($reuslt) {

                if ($reuslt[0]->mobile_user_name > 0) {
                    return response()->json(["status" => false, "message" => "duplicated"]);
                }
            } else if (strlen($username) < 2) {
                return response()->json(["status" => false, "message" => "length"]);
            }
        }



        $employeeid = $request->get('employee');

        $employee = employee::find($employeeid);

        $employee->mobile_user_name = $request->get('cmbusername');
        $employee->mobile_app_password = Hash::make($request->get('txtuserPassword'));
        if ($employee->save()) {

            return response()->json($employee);
        }
    }

    public function sfallData(Request $request)
    {
        try {

            $pageNumber = ($request->start / $request->length) + 1;
            $pageLength = $request->length;
            $skip = ($pageNumber - 1) * $pageLength;
            $searchValue = request('search.value');

            $query = DB::table('employees as E')
    ->select('E.employee_id', 'E.full_name', 'E.office_email', 'E.employee_name', 'E.mobile_user_name', 'E.mobile_app_password')
    ->whereNotNull('E.mobile_user_name')
    ->whereNotNull('E.mobile_app_password');

    if (!empty($searchValue)) {
           
        $query->Where('employees.employee_name', 'like', '%' . $searchValue . '%')
            ->orWhere('employees.mobile_user_name', 'like', '%' . $searchValue . '%');
          
    }

            /* $quary = "SELECT E.employee_id, E.full_name,E.office_email, E.employee_name, E.mobile_user_name, E.mobile_app_password
                FROM employees E
                WHERE E.mobile_user_name IS NOT NULL AND E.mobile_app_password IS NOT NULL";
            $result = DB::select($quary); */

            $results = $query->take($pageLength)->skip($skip)->get();
            $results->transform(function ($item) {
                $disabled = "disabled";
                $buttons = '<button class="btn btn-primary btn-sm" onclick="edit(' . $item->employee_id . ')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>&#160';
                $buttons .= '<button class="btn btn-success btn-sm" onclick="view(' . $item->employee_id . ')"><i class="fa fa-eye" aria-hidden="true"></i></button>&#160';
                $buttons .= '<button class="btn btn-danger btn-sm" onclick="_delete(' . $item->employee_id . ')"' . $disabled . '><i class="fa fa-trash" aria-hidden="true"></i></button>';
        
                $item->buttons = $buttons;
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

    public function getsfaaccess($id)
    {
        try {
            $quary = "SELECT E.employee_id, E.full_name,E.office_email, E.employee_name, E.mobile_user_name, E.mobile_app_password
            FROM employees E
            WHERE E.mobile_user_name IS NOT NULL AND E.mobile_app_password IS NOT NULL AND E.employee_id=$id";
            $result = DB::select($quary);
            return response()->json($result);
           /* if ($result) {
                return response()->json((['success' => 'Data loaded', 'data' => $result]));
            } else {
                return response()->json((['error' => 'Data is not loaded']));
            }*/
        } catch (Exception $ex) {
            return $ex;
        }
    }

public function updateSFAaccess(Request $request, $id){

    if ($username = $request->get('cmbusername')) {
        $query = "SELECT COUNT(*) AS mobile_user_name FROM employees WHERE mobile_user_name = '" . $username . "'";
        $reuslt = DB::select($query);
        if ($reuslt) {
            if ($reuslt[0]->mobile_user_name >= 1) {
                $qry = "SELECT mobile_user_name FROM employees WHERE employees.employee_id ='" . $id . "'";
                $result = DB::select($qry);
                $emp_username = $result[0]->mobile_user_name;
                if ($emp_username != $username) {
                    return response()->json(["status" => false, "message" => "code_duplicated"]);
                }
            }
        }
    }



    $employeeid = $request->get('employee');

    $employee = employee::find($employeeid);

    $employee->mobile_user_name = $request->get('cmbusername');
    $employee->mobile_app_password = Hash::make($request->get('txtuserPassword'));
    if ($employee->save()) {

        return response()->json($employee);
    }

}

public function deleteSFAaccess($id){
try{
    $employee = Employee::findOrFail($id);
$employee->mobile_user_name = null;
$employee->mobile_app_password = null;
$employee->save(); // Save the changes to the database

return response()->json([
    'message' => 'Employee updated successfully'
], 200);

}catch(Exception $ex){
return $ex;
}
}
}
