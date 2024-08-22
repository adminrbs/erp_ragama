<?php

namespace Modules\Cb\Http\Controllers;

use App\Http\Controllers\IntenelNumberController;
use App\Http\Controllers\ReferenceIdController;
use App\Models\DebtorsLedger;
use App\Models\DebtorsLedgerSetoff;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Cb\Entities\Customer;
use Modules\Cb\Entities\CustomerReceipt;
use Modules\Cb\Entities\CustomerReceiptCheque;

class ChequeDishonourController extends Controller
{
    public function load_deposited_cheques_for_dishonor()
    {
        try {
            $qry = "SELECT customer_receipt_cheques.customer_receipt_cheque_id,customer_receipt_cheques.banking_date,customer_receipt_cheques.cheque_deposit_date,customer_receipt_cheques.external_number,customer_receipt_cheques.amount,customer_receipt_cheques.cheque_number,customers.customer_name,customer_receipts.receipt_date,banks.bank_name FROM customer_receipt_cheques INNER JOIN customer_receipts on customer_receipt_cheques.customer_receipt_id = customer_receipts.customer_receipt_id LEFT JOIN customers ON customer_receipts.customer_id = customers.customer_id LEFT JOIN banks ON customer_receipt_cheques.bank_id = banks.bank_id WHERE customer_receipt_cheques.cheque_status = 1";
            $reuslt = DB::select($qry);
            if ($reuslt) {
                return response()->json(["status" => true, "data" => $reuslt]);
            } else {
                return response()->json(["status" => false, "data" => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //dishonour cheque
    public function dishonour_cheque_return(Request $request, $id)
{
    try {
        $reason_id = $request->get('selectVal');
        $charges = $request->get('inputVal');
           
            /* $id = $request->input('id'); */
            $receipt = CustomerReceiptCheque::find($id);
            $receipt->cheque_status = 2;
            $receipt->cheque_dishonoured_date = date('Y-m-d');
            $receipt->dishonoured_by = Auth::user()->id;
            $receipt->cheque_dishonur_reason_id = $reason_id;
            $receipt->bank_charges = $charges;
            
            if ($receipt->update()) {
                $cus_rcpt = CustomerReceipt::find($receipt->customer_receipt_id);
                $customer = Customer::find($cus_rcpt->customer_id);
                $deb_ledger = new DebtorsLedger();
                $deb_ledger->internal_number =  IntenelNumberController::getNextID();
                $deb_ledger->external_number = $receipt->external_number;
                $deb_ledger->document_number = 1000;
                $deb_ledger->trans_date =  $receipt->cheque_dishonoured_date;
                $deb_ledger->description = "Cheque Return -" . $receipt->cheque_number;
                $deb_ledger->branch_id = $cus_rcpt->branch_id;
                $deb_ledger->customer_id = $cus_rcpt->customer_id;
                $deb_ledger->customer_code = $customer->customer_code;
                $deb_ledger->amount = $receipt->amount;
                $deb_ledger->paidamount = 0;

                if ($deb_ledger->save()) {
                    $deb_ledger_setOff = new DebtorsLedgerSetoff();
                    $deb_ledger_setOff->internal_number = $deb_ledger->internal_number;
                    $deb_ledger_setOff->external_number = $deb_ledger->external_number;
                    $deb_ledger_setOff->document_number = 1000;
                    $deb_ledger_setOff->reference_internal_number = $deb_ledger_setOff->internal_number;
                    $deb_ledger_setOff->reference_external_number = $deb_ledger_setOff->external_number;
                    $deb_ledger_setOff->reference_document_number = $deb_ledger_setOff->document_number;
                    $deb_ledger_setOff->trans_date = $deb_ledger->trans_date;
                    $deb_ledger_setOff->description = $deb_ledger->description;
                    $deb_ledger_setOff->branch_id =  $deb_ledger->branch_id;
                    $deb_ledger_setOff->customer_id = $deb_ledger->customer_id;
                    $deb_ledger_setOff->customer_code = $deb_ledger->customer_code;
                    $deb_ledger_setOff->amount =  $deb_ledger->amount;
                    $deb_ledger_setOff->save();
                }
            }
            return response()->json(["status" => true]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //load dishonour reasons
    public function load_dishonour_reasons()
    {
        try {
            $qry = "SELECT cheque_dishonur_reasons.cheque_dishonur_reason_id,cheque_dishonur_reasons.cheque_dishonur_reason FROM cheque_dishonur_reasons";
            $result = DB::select($qry);
            if ($result) {
                return response()->json($result);
            } else {
                return response()->json(["status" => false, "data" => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //get dishonoured ch
    /* public function load_dishonoured_cheques()
    {
        try {
            $qry = "SELECT
            customer_receipt_cheques.customer_receipt_cheque_id,
            customer_receipt_cheques.cheque_dishonoured_date,
            customer_receipt_cheques.external_number,
            customer_receipt_cheques.amount,
            customer_receipt_cheques.cheque_number,
            customer_receipt_cheques.cheque_deposit_date,
            customers.customer_name,
            users.name
        FROM
            customer_receipt_cheques
        INNER JOIN customer_receipts ON customer_receipt_cheques.customer_receipt_id = customer_receipts.customer_receipt_id
        LEFT JOIN customers ON customer_receipts.customer_id = customers.customer_id
        LEFT JOIN users ON customer_receipt_cheques.dishonoured_by = users.id
        WHERE customer_receipt_cheques.cheque_status = 2; ";
            $reuslt = DB::select($qry);
            if ($reuslt) {
                return response()->json(["status" => true, "data" => $reuslt]);
            } else {
                return response()->json(["status" => false, "data" => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    } */

    public function load_dishonoured_cheques()
    {
        try {
            $qry = "SELECT
            CR.cheque_returns_id,
            CR.external_number,
            CR.customer_receipt_cheque_id,
            CR.returned_date,
            CR.cheque_number,
            CR.amount,
            CR.is_cancelled,
            C_rec.receipt_date,
            CRC.cheque_deposit_date,
            CRC.banking_date,
            B.bank_name,
            BB.bank_branch_name,
            C.customer_name,
            U.name,
            CDR.cheque_dishonur_reason
        FROM
            cheque_returns CR
        INNER JOIN
            customer_receipt_cheques CRC ON CR.customer_receipt_cheque_id = CRC.customer_receipt_cheque_id
        INNER JOIN
            banks B ON CRC.bank_id = B.bank_id
        INNER JOIN
            customer_receipts C_rec ON CRC.customer_receipt_id = C_rec.customer_receipt_id
        INNER JOIN
            customers C ON CR.customer_id = C.customer_id
        INNER JOIN
            bank_branches BB ON CRC.bank_branch_id = BB.bank_branch_id
        LEFT JOIN
            users U ON CR.returned_by = U.id
        INNER JOIN
            cheque_dishonur_reasons CDR ON CR.cheque_dishonur_reason_id = CDR.cheque_dishonur_reason_id;
            
        ";
            $reuslt = DB::select($qry);
            if ($reuslt) {
                return response()->json(["status" => true, "data" => $reuslt]);
            } else {
                return response()->json(["status" => false, "data" => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    
}
