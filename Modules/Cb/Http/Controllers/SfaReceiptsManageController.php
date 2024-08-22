<?php

namespace Modules\Cb\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Cb\Entities\sfa_receipt;
use Modules\Cb\Entities\SfaReceiptCheques;

class SfaReceiptsManageController extends Controller
{
    //Load All SFA Receipts
    public function load_sfa_receipts($br_id,$rep_id,)
    {
        try {
            $qry = "SELECT DISTINCT
  SR.customer_receipt_id,
	SR.external_number,
	SR.receipt_date,
	SR.amount,
	C.customer_name,
	E.employee_name,
	SRC.cheque_number,
	IF(SR.receipt_method_id = 1,'Cash','Cheque') as payment_method,
	IF(SR.receipt_status = 0,'New','Used') as `status`
FROM
	sfa_receipts SR
	INNER JOIN customers C ON SR.customer_id = C.customer_id
	
    INNER JOIN employees E ON SR.collector_id  = E.employee_id
	LEFT JOIN sfa_receipt_cheques SRC ON SR.customer_receipt_id = SRC.customer_receipt_id
WHERE
	SR.receipt_status = 0 

";

            if ($rep_id > 0) {
                $qry .= " AND SR.branch_id = $br_id";
            }

            if ($br_id > 0) {
                $qry .= " AND E.employee_id = $rep_id";
            }
           // dd($qry);
            $result = DB::select($qry);
            if ($qry) {
                return response()->json(["status" => true, "data" => $result]);
            } else {
                return response()->json(["status" => false, "data" => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function load_sfa_reciepts_for_change($id)
    {
        try {
            $qry = "SELECT SR.receipt_date,SR.external_number,SR.amount,IF(SR.receipt_method_id = 1,'Cash','Cheque') AS payment_method, IF(SR.receipt_method_id = 1,'Cheque','Cash') AS change_to FROM sfa_receipts SR WHERE SR. customer_receipt_id = $id";

            $result = DB::select($qry);
            if ($qry) {
                return response()->json(["status" => true, "data" => $result]);
            } else {
                return response()->json(["status" => false, "data" => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //change the type
    public function changeType(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $sfa_rcpt = sfa_receipt::find($id);
            $remark = $request->input('remark');
            
            if ($sfa_rcpt->receipt_status != 0) {
                return response()->json(["status" => false, "msg" => "used"]);
            } else {
                
                if ($sfa_rcpt->receipt_method_id == 1) {
                  
                    //Now cash->change to cheque
                    $bank_id = $request->input('bank_id');
                    $bank_branch_id = $request->input('bank_branch_id');
                    $chq_no = $request->input('cheque_no');
                    $banking_date = $request->input('banking_Date');
                    //dd($chq_no);
                    $sfa_cheque_receipt = new SfaReceiptCheques();
                    $sfa_cheque_receipt->customer_receipt_id = $id;
                    $sfa_cheque_receipt->cheque_number = $chq_no;
                    $sfa_cheque_receipt->banking_date = $banking_date;
                    $sfa_cheque_receipt->amount = $sfa_rcpt->amount;
                    $sfa_cheque_receipt->bank_id = $bank_id;
                    $sfa_cheque_receipt->bank_branch_id = $bank_branch_id;
                    if ($sfa_cheque_receipt->save()) {
                        
                        $sfa_rcpt->receipt_method_id = 2;
                        $sfa_rcpt->update();
                        DB::commit();
                        return response()->json(["status" => true, "msg" => "changed"]);
                    }
                   
                } else {
                    //Now check->change to cash
                    $sfa_rcpt->receipt_method_id = 1;
                    $sfa_rcpt->remark = $remark;
                    if ($sfa_rcpt->update()) {
                        $sfa_cheque = SfaReceiptCheques::where("customer_receipt_id", "=", $id)->delete();
                        DB::commit();
                        return response()->json(["status" => true, "msg" => "changed"]);
                    }
                }
            }

            
        } catch (Exception $ex) {
            DB::rollBack();
            return $ex;
        }
    }

    public function cancelReceipt(Request $request,$id){
        try {
            DB::beginTransaction();
            $sfa_rcpt = sfa_receipt::find($id);
            $remark = $request->input('remark');
            if ($sfa_rcpt->receipt_status != 0) {
                return response()->json(["status" => false, "msg" => "used"]);
            } else {
                $sfa_rcpt->receipt_status = 3;
                $sfa_rcpt->remark = $remark;
                $sfa_rcpt->update();
                DB::commit();
                return response()->json(["status" => true, "msg" => "success"]);
            }

            
        } catch (Exception $ex) {
            DB::rollBack();
            return $ex;
        }
    }
}
