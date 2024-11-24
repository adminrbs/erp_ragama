<?php

namespace Modules\Cb\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Cb\Entities\CustomerReceiptCheque;
use Modules\Cb\Entities\gl_account;

class ChequeDepositController extends Controller
{
    //load accounts
    public function getAccount()
    {
        try {
            $account = gl_account::all();
            if ($account) {
                return response()->json($account);
            } else {
                return response()->json(['status' => false]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //load cheques for deposit
    public function load_cheques_for_deposit(Request $request)
    {

        try {
            $date = $request->get('date');
           
            $qry = "SELECT 
            customer_receipt_cheques.customer_receipt_cheque_id,
            customer_receipt_cheques.banking_date,
            customer_receipt_cheques.external_number,
            customer_receipt_cheques.amount,
            customer_receipt_cheques.cheque_number,
            customers.customer_name,
            customer_receipts.receipt_date
        FROM 
            customer_receipt_cheques
        INNER JOIN customer_receipts ON customer_receipt_cheques.customer_receipt_id = customer_receipts.customer_receipt_id
        LEFT JOIN customers ON customer_receipts.customer_id = customers.customer_id
        WHERE
            customer_receipts.receipt_status = 0 AND
            customer_receipt_cheques.cheque_status = 0 AND
            banking_date <= '$date'
        ORDER BY
            customer_receipt_cheques.banking_date ASC;
        ";
            $result = DB::select($qry);
            if ($result) {
                return response()->json(["data" => $result]);
            } else {
                return response()->json(["status" => false,"data" => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //deposit cheuqe
    public function deposit_cheque(Request $request,$id)
    {
        try {
            $check_box_array = json_decode($request->input('ids'));
            foreach ($check_box_array as $i) {
                $receipt = CustomerReceiptCheque::find($i);
                $receipt->cheque_status = 1;
                if($receipt->is_returned == 0){
                    $receipt->cheque_deposit_date = date('Y-m-d');
                }else{
                    $receipt->rebanked_date = date('Y-m-d');
                }
                
                $receipt->gl_account_id = $id;
                $receipt->deposited_by = Auth::user()->id;
                $receipt->update();
            }
            return response()->json(["status" => true]);
        } catch (Exception $ex) {
            return $ex;
        }
    }
    
}
