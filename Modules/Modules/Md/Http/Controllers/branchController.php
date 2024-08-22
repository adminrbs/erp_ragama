<?php

namespace Modules\Md\Http\Controllers;

use Modules\Md\Entities\branch;
use Illuminate\Http\Request;

use Exception;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class branchController extends Controller
{
    //
    public function savBranch(Request $request)
    {
        try {
            $request->validate([
                'txtName' => 'required'

            ]);
            $br_code = $request->input('code');
            $query = "SELECT COUNT(*) AS count FROM branches WHERE code = $br_code";
            $reuslt = DB::select($query);

            if ($reuslt) {
                if ($reuslt[0]->count > 0) {
                    return response()->json(["status" => false,"message" => "duplicated"]);
                }
            }

            $branch = new branch();
            $branch->branch_name = $request->input('txtName');
            $branch->address = $request->input('txtAddress');
            $branch->fixed_number = $request->input('txtFixed');
            $branch->email = $request->input('txtEmail');
            $branch->is_active = $request->input('chkStatus');
            $branch->prefix = $request->input('txtBranchPrefix');
            $branch->code = $request->input('code');

            if ($branch->save()) {
                return response()->json(["status" => true]);
            } else {
                return response()->json(["status" => false]);
            }
        } catch (Exception $ex) {
            if ($ex instanceof ValidationException) {
                return response()->json(["ValidationException" => ["id" => collect($ex->errors())->keys()[0], "message" => $ex->errors()[collect($ex->errors())->keys()[0]]]]);
            }
            return response()->json(["error" => $ex]);
        }
    }

    public function getBranchDetails()
    {
        try {

            $query = 'SELECT branch_id, branch_name, address, fixed_number, email, IF(is_active=1, "Yes", "No") AS is_active,prefix,code
            FROM branches';

            $branchrDteails = DB::select($query);
            return response()->json((['success' => 'Data loaded', 'data' => $branchrDteails]));
            /*

            $branchrDteails = branch::all();

            if($branchrDteails){
                return response()->json((['success' => 'Data loaded', 'data' => $branchrDteails]));
            } else {
                return response()->json((['error' => 'Data is not loaded']));
            }*/
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function branchEdite($id)
    {
        try {
            $seachLocation = branch::find($id);
            if ($seachLocation) {
                return response()->json((['success' => 'Data loaded', 'data' => $seachLocation]));
            } else {
                return response()->json((['error' => 'Data is not loaded']));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function updatebranch(Request $request, $id)
    {
        try {
            $request->validate([
                'txtName' => 'required',

            ]);
            $br_code = $request->input('code');
            $query = "SELECT COUNT(*) AS count FROM branches WHERE code = $br_code";
            $reuslt = DB::select($query);

            $qry = "SELECT code FROM branches WHERE branches.branch_id = $id";
            $result_code = DB::select($qry);

            if ($reuslt) {
                if ($reuslt[0]->count == 1 && $result_code[0]->code != $request->input('code')) {
                    return response()->json(["status" => false,"message" => "duplicated"]);
                }
            }

            $branch = branch::find($id);
            $branch->branch_name = $request->input('txtName');
            $branch->address = $request->input('txtAddress');
            $branch->fixed_number = $request->input('txtFixed');
            $branch->email = $request->input('txtEmail');
            $branch->is_active = $request->input('chkStatus');
            $branch->prefix = $request->input('txtBranchPrefix');
            $branch->code = $request->input('code');
            if ($branch->update()) {
                return response()->json(["status" => true]);
            } else {
                return response()->json(["status" => false]);
            }
        } catch (Exception $ex) {
            if ($ex instanceof ValidationException) {
                return response()->json(["ValidationException" => ["id" => collect($ex->errors())->keys()[0], "message" => $ex->errors()[collect($ex->errors())->keys()[0]]]]);
            }
            return response()->json(["error" => $ex]);
        }
    }

    public function getBranchview($id)
    {
        try {
            $branch = branch::find($id);
            if ($branch) {
                return response()->json((['success' => 'Data loaded', 'data' => $branch]));
            } else {
                return response()->json((['error' => 'Data is not loaded']));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }






    public function deleteBranch($id)
    {
        $location = branch::find($id);
        if ($location->delete()) {
            return response()->json(["status" => true]);
        } else {
            return response()->json(["status" => false]);
        }
    }
}
