<?php

namespace Modules\Cb\Http\Controllers;

use App\Models\branch;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Cb\Entities\FundTransfer;

class FundTransferController extends Controller
{

    public function getGLAccounts()
    {
        $val = 1;
        try {

            $items = DB::table('gl_accounts')
                ->select(
                    'gl_accounts.account_id',
                    'gl_accounts.account_title',
                    'gl_accounts.account_code',
                    'gl_account_types.gl_account_type_id'
                )
                ->join(
                    'gl_account_types',
                    'gl_accounts.account_type_id',
                    '=',
                    'gl_account_types.gl_account_type_id'
                )
                ->get();

            $collection = [];
            foreach ($items as $item) {
                array_push($collection, ["hidden_id" => $item->account_id, "id" =>  $item->account_title, "value" =>  $item->account_code, "type_id" => $item->gl_account_type_id, "collection" => [$item->account_id, $item->account_title, $item->account_code]]);
            }
            return response()->json(['success' => true, 'data' => $collection]);
        } catch (Exception $ex) {
            return $ex;
        }
    }




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
                $branches = branch::where("is_active", "=", 1)->get();
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


    public function saveFundTransfer(Request $request)
    {
        try {
            //$reference_no = $request->get("reference_no");
            $date = $request->get("date");
            $amount = $request->get("amount");
            $source_account = $request->get("source_account");
            $destination_account = $request->get("destination_account");
            $source_branch = $request->get("source_branch");
            $destination_branch = $request->get("destination_branch");
            $description = $request->get("description");
            $created_by = $request->get("created_by");
            if (Auth::user()->user_id != null) {
                $created_by = Auth::user()->user_id;
            }
            $approved_by = $request->get("approved_by");
            $approval_status = $request->get("approval_status");

            $fundTransfer = new FundTransfer();
            //$fundTransfer->reference_no = $reference_no;
            $fundTransfer->transaction_date = $date;
            $fundTransfer->amount = $amount;
            $fundTransfer->source_account_id = $source_account;
            $fundTransfer->destination_account_id = $destination_account;
            $fundTransfer->source_branch_id = $source_branch;
            $fundTransfer->destination_branch_id = $destination_branch;
            $fundTransfer->description = $description;
            $fundTransfer->created_by = $created_by;
            $fundTransfer->approved_by = $approved_by;
            $fundTransfer->approval_status = $approval_status;

            if ($fundTransfer->save()) {
                return response()->json(["success" => true]);
            }
            return response()->json(["success" => false]);
        } catch (Exception $ex) {
            //dd($ex);
            return response()->json(["success" => false]);
        }
    }



    public function updateFundTransfer(Request $request, $id)
    {
        try {
            //$reference_no = $request->get("reference_no");
            $date = $request->get("date");
            $amount = $request->get("amount");
            $source_account = $request->get("source_account");
            $destination_account = $request->get("destination_account");
            $source_branch = $request->get("source_branch");
            $destination_branch = $request->get("destination_branch");
            $description = $request->get("description");
            $created_by = $request->get("created_by");
            if (Auth::user()->user_id != null) {
                $created_by = Auth::user()->user_id;
            }
            $approved_by = $request->get("approved_by");
            $approval_status = $request->get("approval_status");

            $fundTransfer =  FundTransfer::find($id);
            //$fundTransfer->reference_no = $reference_no;
            $fundTransfer->transaction_date = $date;
            $fundTransfer->amount = $amount;
            $fundTransfer->source_account_id = $source_account;
            $fundTransfer->destination_account_id = $destination_account;
            $fundTransfer->source_branch_id = $source_branch;
            $fundTransfer->destination_branch_id = $destination_branch;
            $fundTransfer->description = $description;
            $fundTransfer->created_by = $created_by;
            $fundTransfer->approved_by = $approved_by;
            $fundTransfer->approval_status = $approval_status;

            if ($fundTransfer->update()) {
                return response()->json(["success" => true]);
            }
            return response()->json(["success" => false]);
        } catch (Exception $ex) {
            //dd($ex);
            return response()->json(["success" => false]);
        }
    }




    public function getAllFundTransfer()
    {

        try {

            $query = "SELECT fund_transfers.fund_transfer_id,
            fund_transfers.transaction_date,
            fund_transfers.description,
            branches.branch_name,
            employees.employee_name AS created_by,
            fund_transfers.approval_status
            FROM fund_transfers
            INNER JOIN branches ON fund_transfers.source_branch_id = branches.branch_id
            LEFT JOIN employees ON fund_transfers.created_by = employees.employee_id";

            $result = DB::select($query);
            return response()->json(["data" => $result]);
        } catch (Exception $ex) {
            return response()->json(["success" => false]);
        }
    }



    public function getFundTransfer($id)
    {

        $fundTransfer = FundTransfer::find($id);
        return response()->json(["header" => $fundTransfer]);
    }


    public function approvalFundTransfer(Request $request, $id)
    {

        try {

            $status = $request->get("status");
            $fundTransfer =  FundTransfer::find($id);
            if ($fundTransfer) {
                $fundTransfer->approval_status = $status;
                $fundTransfer->approved_by = Auth::user()->user_id;

                if ($fundTransfer->update()) {
                    return response()->json(["success" => true]);
                }
            }
            return response()->json(["success" => false]);
        } catch (Exception $ex) {
            //dd($ex);
            return response()->json(["success" => false]);
        }
    }
}
