<?php

namespace App\Http\Controllers;

use App\Models\branch;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class sessionBranchController extends Controller
{
    public function getBranches()
    {
        try {
            $user_id = auth()->id();
            $count = DB::table('user_baranchs')
                ->where('user_id', $user_id)
                ->count();
            if ($count > 0) {
                $query = "SELECT branches.branch_id, branches.branch_name FROM branches LEFT JOIN
             user_baranchs ON user_baranchs.user_id = $user_id WHERE user_baranchs.user_id = $user_id
              AND user_baranchs.branch_id = branches.branch_id AND branches.is_active = 1";
                $reuslt = DB::select($query);
                if ($reuslt) {
                    return response()->json($reuslt);
                } else {
                    return response()->json(['status' => false]);
                }
            } else {
                $branches = branch::where("is_active","=",1)->get();
                if ($branches) {
                    return response()->json($branches);
                } else {
                    return response()->json(['status' => false]);
                }
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }
}
