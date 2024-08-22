<?php

namespace Modules\Cb\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Cb\Entities\CustomerReceipt;

class AuditController extends Controller
{
    //load cash receipts for audit
    public function load_cash_receipts_for_audit($br_id, $collector_id_,)
    {
        $qry = DB::select('SELECT customer_receipts.customer_receipt_id,
        customer_receipts.receipt_date,
        customer_receipts.external_number,
        
        customer_receipts.receipt_status,
        customers.customer_name,
        town_non_administratives.townName,
        debtors_ledgers.external_number as EX_num,
        sales_invoices.order_date_time,
        debtors_ledgers.debtors_ledger_id,
        debtors_ledgers.trans_date,
        debtors_ledgers.manual_number,
        customer_receipt_setoff_data.customer_receipt_setoff_data_id,
        customer_receipt_setoff_data.set_off_amount,
        DATEDIFF(customer_receipts.receipt_date,debtors_ledgers.trans_date) AS Gap
 FROM customer_receipts
 LEFT JOIN customers ON customer_receipts.customer_id = customers.customer_id
 LEFT JOIN customer_receipt_setoff_data ON customer_receipt_setoff_data.customer_receipt_id = customer_receipts.customer_receipt_id 
 LEFT JOIN debtors_ledgers ON customer_receipt_setoff_data.debtors_ledger_id = debtors_ledgers.debtors_ledger_id
 LEFT JOIN town_non_administratives ON customers.town = town_non_administratives.town_id
 LEFT JOIN sales_invoices ON debtors_ledgers.external_number = sales_invoices.external_number
 WHERE customer_receipts.branch_id = ' . $br_id . ' AND customer_receipts.collector_id = ' . $collector_id_ . ' AND customer_receipts.receipt_method_id = 1 AND customer_receipts.audit = 0');

        if ($qry) {
            return response()->json(["status" => true, "data" => $qry]);
        } else {
            return response()->json(["status" => false, "data" => []]);
        }
    }


    //update audit
    public function update_audit_cash(Request $request)
    {
        try {

            $check_box_array = json_decode($request->input('values'));
            foreach ($check_box_array as $i) {
                $receipt = CustomerReceipt::find($i);
                if ($receipt->audit == 0 || $receipt->audit == NULL || $receipt->audit == null) {
                    $receipt->audit = 1;
                    $receipt->update();
                } else {
                    return response()->json(["status" => false, "message" => 'used']);
                }
            }

            return response()->json(["status" => true, "message" => 'success']);
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //load cheque receipts for audit
    public function load_cheque_receipt_for_audit($br_id, $collector_id_,)
    {
        $qry = DB::select('SELECT customer_receipts.customer_receipt_id,
         customer_receipts.receipt_date,
         customer_receipts.external_number,
         customer_receipts.receipt_status,
         customer_receipt_cheques.cheque_number,
         customer_receipt_cheques.amount,
         customer_receipt_cheques.banking_date,
         banks.bank_code,
         bank_branches.bank_branch_code,
         customers.customer_name
  FROM customer_receipts
  LEFT JOIN customer_receipt_cheques ON customer_receipts.customer_receipt_id = customer_receipt_cheques.customer_receipt_id
  LEFT JOIN banks ON customer_receipt_cheques.bank_id = banks.bank_id
  LEFT JOIN bank_branches ON customer_receipt_cheques.bank_branch_id = bank_branches.bank_branch_id
  LEFT JOIN customers ON customer_receipts.customer_id = customers.customer_id
  WHERE customer_receipts.branch_id = ' . $br_id . ' AND customer_receipts.collector_id =' . $collector_id_ . '  AND customer_receipts.receipt_method_id = 2 AND customer_receipts.audit = 0');

        if ($qry) {
            return response()->json(["status" => true, "data" => $qry]);
        } else {
            return response()->json(["status" => false, "data" => []]);
        }
    }



    //update audit- cheque
    public function update_audit_cheque(Request $request)
    {
        try {

            $check_box_array = json_decode($request->input('chq_id_array'));

            /*  $receipt = CustomerReceipt::find($receipt_id); */
            /*   if(!$receipt){
                return response()->json(["status" => false, "message" => 'error']);
            } */
            foreach ($check_box_array as $i) {
                $receipt = CustomerReceipt::find($i);
                if ($receipt->audit == 0 || $receipt->audit == NULL || $receipt->audit == null) {
                    $receipt->audit = 1;
                    $receipt->update();
                } else {
                    return response()->json(["status" => false, "message" => 'used']);
                }
            }
            return response()->json(["status" => true, "message" => 'success']);
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //cash audit list
    public function load_audited_cash_receipts($br_id, $collector_id_)
    {
        try {
            if ($collector_id_ == 0) {
                $qry = DB::select('SELECT customer_receipts.customer_receipt_id,
        customer_receipts.receipt_date,
        customer_receipts.external_number,
        customer_receipts.receipt_status,
        customers.customer_name,
        town_non_administratives.townName,
        debtors_ledgers.external_number as EX_num,
        sales_invoices.order_date_time,
        debtors_ledgers.debtors_ledger_id,
        debtors_ledgers.trans_date,
        debtors_ledgers.manual_number,
        customer_receipt_setoff_data.customer_receipt_setoff_data_id,
        customer_receipt_setoff_data.set_off_amount,
        DATEDIFF(customer_receipts.receipt_date,debtors_ledgers.trans_date) AS Gap
 FROM customer_receipts
 LEFT JOIN customers ON customer_receipts.customer_id = customers.customer_id
 LEFT JOIN customer_receipt_setoff_data ON customer_receipt_setoff_data.customer_receipt_id = customer_receipts.customer_receipt_id 
 LEFT JOIN debtors_ledgers ON customer_receipt_setoff_data.debtors_ledger_id = debtors_ledgers.debtors_ledger_id
 LEFT JOIN town_non_administratives ON customers.town = town_non_administratives.town_id
 LEFT JOIN sales_invoices ON debtors_ledgers.external_number = sales_invoices.external_number
 WHERE customer_receipts.branch_id = ' . $br_id . ' AND customer_receipts.receipt_method_id = 1 AND customer_receipts.audit = 1');

                if ($qry) {
                    return response()->json(["status" => true, "data" => $qry]);
                } else {
                    return response()->json(["status" => false, "data" => []]);
                }
            } else {
                $qry = DB::select('SELECT customer_receipts.customer_receipt_id,
        customer_receipts.receipt_date,
        customer_receipts.external_number,
        customer_receipts.receipt_status,
        customers.customer_name,
        town_non_administratives.townName,
        debtors_ledgers.external_number as EX_num,
        sales_invoices.order_date_time,
        debtors_ledgers.debtors_ledger_id,
        debtors_ledgers.trans_date,
        debtors_ledgers.manual_number,
        customer_receipt_setoff_data.customer_receipt_setoff_data_id,
        customer_receipt_setoff_data.set_off_amount,
        DATEDIFF(customer_receipts.receipt_date,debtors_ledgers.trans_date) AS Gap
 FROM customer_receipts
 LEFT JOIN customers ON customer_receipts.customer_id = customers.customer_id
 LEFT JOIN customer_receipt_setoff_data ON customer_receipt_setoff_data.customer_receipt_id = customer_receipts.customer_receipt_id 
 LEFT JOIN debtors_ledgers ON customer_receipt_setoff_data.debtors_ledger_id = debtors_ledgers.debtors_ledger_id
 LEFT JOIN town_non_administratives ON customers.town = town_non_administratives.town_id
 LEFT JOIN sales_invoices ON debtors_ledgers.external_number = sales_invoices.external_number
 WHERE customer_receipts.branch_id = ' . $br_id . ' AND customer_receipts.collector_id = ' . $collector_id_ . ' AND customer_receipts.receipt_method_id = 1 AND customer_receipts.audit = 1');

                if ($qry) {
                    return response()->json(["status" => true, "data" => $qry]);
                } else {
                    return response()->json(["status" => false, "data" => []]);
                }
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }




    //audited cheques
    public function load_audited_cheque($br_id, $collector_id_,)
    {
        if ($collector_id_ == 0) {
            $qry = DB::select('SELECT customer_receipts.customer_receipt_id,
            customer_receipts.receipt_date,
            customer_receipts.external_number,
            customer_receipts.receipt_status,
            customer_receipt_cheques.cheque_number,
            customer_receipt_cheques.amount,
            customer_receipt_cheques.banking_date,
            banks.bank_code,
            bank_branches.bank_branch_code,
            customers.customer_name
     FROM customer_receipts
     LEFT JOIN customer_receipt_cheques ON customer_receipts.customer_receipt_id = customer_receipt_cheques.customer_receipt_id
     LEFT JOIN banks ON customer_receipt_cheques.bank_id = banks.bank_id
     LEFT JOIN bank_branches ON customer_receipt_cheques.bank_branch_id = bank_branches.bank_branch_id
     LEFT JOIN customers ON customer_receipts.customer_id = customers.customer_id
     WHERE customer_receipts.branch_id = ' . $br_id . ' AND customer_receipts.receipt_method_id = 2 AND customer_receipts.audit = 1');

            if ($qry) {
                return response()->json(["status" => true, "data" => $qry]);
            } else {
                return response()->json(["status" => false, "data" => []]);
            }
        } else {
            $qry = DB::select('SELECT customer_receipts.customer_receipt_id,
         customer_receipts.receipt_date,
         customer_receipts.external_number,
         customer_receipts.receipt_status,
         customer_receipt_cheques.cheque_number,
         customer_receipt_cheques.amount,
         customer_receipt_cheques.banking_date,
         banks.bank_code,
         bank_branches.bank_branch_code,
         customers.customer_name
  FROM customer_receipts
  LEFT JOIN customer_receipt_cheques ON customer_receipts.customer_receipt_id = customer_receipt_cheques.customer_receipt_id
  LEFT JOIN banks ON customer_receipt_cheques.bank_id = banks.bank_id
  LEFT JOIN bank_branches ON customer_receipt_cheques.bank_branch_id = bank_branches.bank_branch_id
  LEFT JOIN customers ON customer_receipts.customer_id = customers.customer_id
  WHERE customer_receipts.branch_id = ' . $br_id . ' AND customer_receipts.collector_id =' . $collector_id_ . '  AND customer_receipts.receipt_method_id = 2 AND customer_receipts.audit = 1');

            if ($qry) {
                return response()->json(["status" => true, "data" => $qry]);
            } else {
                return response()->json(["status" => false, "data" => []]);
            }
        }
    }
}
