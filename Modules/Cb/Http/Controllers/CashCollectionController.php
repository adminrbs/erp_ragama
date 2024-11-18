<?php

namespace Modules\Cb\Http\Controllers;

use App\Http\Controllers\IntenelNumberController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Cb\Entities\cash_bundle;
use Modules\Cb\Entities\cheque_collection;
use Modules\Cb\Entities\CustomerReceipt;
use Modules\Cb\Entities\sfa_receipt;
use Modules\Cb\Entities\SfaReceiptCheques;
use Nette\Utils\Json;

class CashCollectionController extends Controller
{
    //get cash-branch records to the list
    public function loadCustomerReceipts_cash_branch($branchId, $collector_id)
    {
        try {
            $user_id = auth()->id();
            $query = "SELECT sfa_receipts.customer_receipt_id,
            sfa_receipts.receipt_date,
            sfa_receipts.external_number,
            
            sfa_receipts.receipt_status,
            customers.customer_name,
            town_non_administratives.townName,
            debtors_ledgers.external_number as EX_num,
            sales_invoices.order_date_time,
            debtors_ledgers.debtors_ledger_id,
            debtors_ledgers.trans_date,
            debtors_ledgers.external_number AS manual_number,
            sfa_receipt_setoff_data.customer_receipt_setoff_data_id,
            sfa_receipt_setoff_data.set_off_amount,
            DATEDIFF(sfa_receipts.receipt_date,debtors_ledgers.trans_date) AS Gap,
            E.employee_name as rep
     FROM sfa_receipts
     LEFT JOIN customers ON sfa_receipts.customer_id = customers.customer_id
     LEFT JOIN sfa_receipt_setoff_data ON sfa_receipt_setoff_data.customer_receipt_id = sfa_receipts.customer_receipt_id 
     LEFT JOIN debtors_ledgers ON sfa_receipt_setoff_data.debtors_ledger_id = debtors_ledgers.debtors_ledger_id
     LEFT JOIN town_non_administratives ON customers.town = town_non_administratives.town_id
     LEFT JOIN sales_invoices ON debtors_ledgers.external_number = sales_invoices.external_number
    LEFT JOIN employees E ON sfa_receipts.collector_id = E.employee_id
     WHERE receipt_status = 0 AND sfa_receipts.receipt_method_id = 1 ";
            if($collector_id > 0){
                $query .= "AND sfa_receipts.collector_id = $collector_id ";
            }
            $query .= "ORDER BY sfa_receipts.customer_receipt_id DESC";
            //dd($query);
            $result = DB::select($query);
            if ($result) {
                return response()->json(["status" => true, "data" => $result]);
            } else {
                return response()->json(["status" => false, "data" => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //update receipt status
    public function update_status_calculation(Request $request)
    {
        try {

            $check_box_array = json_decode($request->input('values'));
            $branch_id = $request->input('branch_id');
           
            /*  $receipt = CustomerReceipt::find($receipt_id); */
            /*   if(!$receipt){
                return response()->json(["status" => false, "message" => 'error']);
            } */
            foreach ($check_box_array as $i) {
                $receipt = sfa_receipt::find($i);
                $receipt->receipt_status = 1;
                $receipt->received_branch_id = $branch_id;
                $receipt->update();
            }
            return response()->json(["status" => true, "message" => 'success']);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //get cash-ho records
    public function loadCustomerReceipts_cash_ho()
    {
        try {
            $user_id = auth()->id();
            $query = "SELECT cash_bundles.cash_bundles_id,
            cash_bundles.external_number,
            SUM(cash_bundles_datas.amount) AS total_amount,
            cash_bundles_datas.cash_bundle_date,
            cash_bundles.internal_number,
            cash_bundles.page_no,
            sfa_receipts.customer_receipt_id,
            users.name,
            CONCAT(books.book_name,'-',books.book_number) AS book
     FROM cash_bundles
     
     LEFT JOIN cash_bundles_datas ON cash_bundles.cash_bundles_id = cash_bundles_datas.cash_bundles_id
     LEFT JOIN books ON cash_bundles.book_id = books.book_id
     LEFT JOIN sfa_receipts ON cash_bundles_datas.customer_receipt_id = sfa_receipts.customer_receipt_id
     LEFT JOIN users ON cash_bundles_datas.cashier_id = users.id WHERE (sfa_receipts.receipt_status = 1 OR sfa_receipts.receipt_status = 2) AND cash_bundles.status = 0 GROUP BY external_number";
            $result = DB::select($query);
            if ($result) {
                return response()->json(["status" => true, "data" => $result]);
            } else {
                return response()->json(["status" => false, "data" => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }



    //update cash-ho receipt status
    public function update_status_calculation_cash_ho(Request $request, $receipt_id)
    {
        try {

            $status_ = $request->input('status');
            $bundle_id = $request->input('cash_bundle_id');
            $remark = $request->input('remark');
            /* $receipt = CustomerReceipt::find($request->input('cash_bundle_id'));
            if (!$receipt) {
                return response()->json(["status" => false, "message" => 'error']);
            } else {}
                $status = $receipt->receipt_status;
                $receipt->receipt_status = $request->input('status'); */
            /* if ($receipt->update()) {} */
            if (floatval($status_) == 2) {
                $cash_bundle = cash_bundle::find($bundle_id);
                $cash_bundle->ho_remarks = $remark;
                $cash_bundle->status = 2;
                $cash_bundle->update();
            } else {
                $cash_bundle = cash_bundle::find($bundle_id);
                $cash_bundle->ho_remarks = null;
                $cash_bundle->status = 1;
                $cash_bundle->update();
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //update cash recpt ho
    public function update_cash_ho(Request $request)
    {
        try {

            $bundle_id = '';
            $cash_id_array = json_decode($request->input('cash_id_array'));
            foreach ($cash_id_array as $i) {
                $parts = explode("|", $i);
                $bundle_id = $parts[0];
                $remark = $parts[1];
                $cash_bundle = cash_bundle::find($bundle_id);
                $cash_bundle->ho_remarks = $remark;
                $cash_bundle->status = 2;
                $cash_bundle->update();
            }
            return response()->json(["status" => true]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //get check for rcpt 
    public function loadCustomerReceipts_cheque_branch($branchId)
    {
        try {
            $user_id = auth()->id();
            $query = "SELECT sfa_receipts.customer_receipt_id,
            sfa_receipts.receipt_date,
            sfa_receipts.external_number,
            sfa_receipts.receipt_status,
            sfa_receipt_cheques.cheque_number,
            sfa_receipt_cheques.amount,
            sfa_receipt_cheques.banking_date,
            banks.bank_code,
            bank_branches.bank_branch_code,
            customers.customer_name,
            CC.external_number AS col_external_number
     FROM sfa_receipts
     INNER JOIN cheque_collections CC ON sfa_receipts.cheque_collection_id = CC.cheque_collection_id
     LEFT JOIN sfa_receipt_cheques ON sfa_receipts.customer_receipt_id = sfa_receipt_cheques.customer_receipt_id
     LEFT JOIN customers ON sfa_receipts.customer_id = customers.customer_id
     LEFT JOIN banks ON sfa_receipt_cheques.bank_id = banks.bank_id
     LEFT JOIN bank_branches ON sfa_receipt_cheques.bank_branch_id = bank_branches.bank_branch_id
     WHERE sfa_receipts.received_branch_id = $branchId AND sfa_receipts.receipt_status = 1 AND sfa_receipts.receipt_method_id = 2";


            $result = DB::select($query);
            if ($result) {
                return response()->json(["status" => true, "data" => $result]);
            } else {
                return response()->json(["status" => false, "data" => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //get check for collect from sfa - branch
    public function loadCustomerReceipts_cheque_branch_for_collect($branchId, $collector_id)
    {
        try {
            $user_id = auth()->id();
            $query = "SELECT sfa_receipts.customer_receipt_id,
              sfa_receipts.receipt_date,
              sfa_receipts.external_number,
              sfa_receipts.receipt_status,
              sfa_receipt_cheques.cheque_number,
              sfa_receipt_cheques.amount,
              sfa_receipt_cheques.banking_date,
              banks.bank_code,
              bank_branches.bank_branch_code,
              customers.customer_name,
              E.employee_name AS rep
       FROM sfa_receipts
       LEFT JOIN sfa_receipt_cheques ON sfa_receipts.customer_receipt_id = sfa_receipt_cheques.customer_receipt_id
       LEFT JOIN banks ON sfa_receipt_cheques.bank_id = banks.bank_id
       LEFT JOIN bank_branches ON sfa_receipt_cheques.bank_branch_id = bank_branches.bank_branch_id
       LEFT JOIN customers ON sfa_receipts.customer_id = customers.customer_id
       LEFT JOIN employees E ON sfa_receipts.collector_id = E.employee_id
       WHERE sfa_receipts.receipt_status = 0 AND sfa_receipts.receipt_method_id = 2 ";
            if($collector_id > 0){
                $query .= " AND sfa_receipts.collector_id = $collector_id";
            }
            


            $result = DB::select($query);
            if ($result) {
                return response()->json(["status" => true, "data" => $result]);
            } else {
                return response()->json(["status" => false, "data" => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    ///update chque branch
    public function update_status_calculation_cheque_branch(Request $request, $receipt_id)
    {
        try {

            $receipt = sfa_receipt::find($receipt_id);
            if (!$receipt) {
                return response()->json(["status" => false, "message" => 'error']);
            }
            if ($receipt->receipt_status == 2) {
                return response()->json(["status" => false, "message" => 'error']);
            }
            $receipt->receipt_status = $request->input('status');
            $receipt->update();
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function update_chq_branch(Request $request)
    {
        try {
            
            $referencenumber = $request->input('LblexternalNumber');
                $bR_id = $request->input('cmbBranch');
               
                $data = DB::table('branches')->where('branch_id', $bR_id)->get();
                
                $EXPLODE_ID = explode("-",$referencenumber);
                $externalNumber  = '';
            if ($data->count() > 0) {
                $documentPrefix = $data[0]->prefix;
                $externalNumber  =$documentPrefix."-".$EXPLODE_ID[0]."-".$EXPLODE_ID[1];
            }
            $cheque_collection = new cheque_collection();
            $cheque_collection->internal_number = IntenelNumberController::getNextID();
            $cheque_collection->external_number = $externalNumber;
            $cheque_collection->document_number = 950;
           /*  $cheque_collection->book_id = $request->input('book_id');
            $cheque_collection->page_no = $request->input('page_no'); */
            $cheque_collection->created_by = Auth::user()->id;
            if($cheque_collection->save()){

                $chq_id_array = json_decode($request->get('chq_id_array'));
            foreach ($chq_id_array as $i) {
                $receipt = sfa_receipt::find($i);
                if (!$receipt) {
                    return response()->json(["status" => false, "message" => 'error']);
                }
                $receipt->receipt_status = 1;
                $receipt->cheque_collection_id = $cheque_collection->cheque_collection_id;
                $receipt->received_branch_id = $bR_id;
                $receipt->update();
            }
            }
            return response()->json(["status" => true]);

            
        } catch (Exception $ex) {
            return $ex;
        }
    }



    //load ho check data
    public function loadCustomerReceipts_cheque_ho($branchId)
    {
        try {
            $user_id = auth()->id();
            $query = "SELECT sfa_receipts.customer_receipt_id,
            sfa_receipts.receipt_date,
            sfa_receipts.external_number,
            sfa_receipts.receipt_status,
            sfa_receipt_cheques.cheque_number,
            sfa_receipt_cheques.amount,
            sfa_receipt_cheques.banking_date,
            sfa_receipt_cheques.customer_receipt_cheque_id,
            
            banks.bank_code,
            bank_branches.bank_branch_code,
            customers.customer_name,
            cheque_collections.page_no,
            CONCAT(books.book_name,'-',books.book_number) AS book
     FROM sfa_receipts
     LEFT JOIN sfa_receipt_cheques ON sfa_receipts.customer_receipt_id = sfa_receipt_cheques.customer_receipt_id
     LEFT JOIN customers ON sfa_receipts.customer_id = customers.customer_id
     LEFT JOIN cheque_collections ON cheque_collections.cheque_collection_id = sfa_receipts.cheque_collection_id
     LEFT JOIN books ON cheque_collections.book_id = books.book_id
     LEFT JOIN banks ON sfa_receipt_cheques.bank_id = banks.bank_id
     LEFT JOIN bank_branches ON sfa_receipt_cheques.bank_branch_id = bank_branches.bank_branch_id
     WHERE sfa_receipts.received_branch_id = $branchId AND sfa_receipts.receipt_status = 2 AND sfa_receipts.receipt_method_id = 2 AND sfa_receipt_cheques.chq_ho_received = 0;";
   // dd($query);
            $result = DB::select($query);
            if ($result) {
                return response()->json(["status" => true, "data" => $result]);
            } else {
                return response()->json(["status" => false, "data" => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //update cash-ho receipt status
    public function update_status_calculation_cheque_ho(Request $request, $receipt_id)
    {
        try {

            $receipt = CustomerReceipt::find($receipt_id);
            if (!$receipt) {
                return response()->json(["status" => false, "message" => 'error']);
            } else {
                $status = $receipt->receipt_status;
                /*   if($status == 1){ */
                $receipt->receipt_status = $request->input('status');
                $receipt->update();
                /*  *//* else if($status == 2 && $request->input('status') == 1){
                        $receipt->receipt_status = $request->input('status');
                        $receipt->update();
                       
                    } *//* else{
                        return response()->json(["status" => false, "message" => 'error']);
                    } */
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //update chq status ho
    public function update_chq_ho(Request $request){
        try {
            $cheque_id_array = json_decode($request->get('cheque_id_array'));
            foreach ($cheque_id_array as $i) {
                $receipt = SfaReceiptCheques::find($i);
                if (!$receipt) {
                    return response()->json(["status" => false, "message" => 'error']);
                }
                $receipt->chq_ho_received = 1;
                $receipt->update();
            }
            return response()->json(["status" => true, "message" => 'success']);


        } catch (Exception $ex) {
            return $ex;
        }

    }


    //load cash books
    public function load_cash_BookNumber(){
        try {
            $qry = "SELECT CONCAT(books.book_name,'-',books.book_number) AS book_name,books.book_id FROM books WHERE books.is_active = 1 AND book_type_id = 2";
            $books = DB::select($qry);

            if ($books) {
                return response()->json(['status' => true, 'data' => $books]);
            } else {
                return response()->json(['status' => false, 'data' => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }

    }


    //load cheque books
    public function load_cheque_BookNumber(){
        try {
            $qry = "SELECT CONCAT(books.book_name,'-',books.book_number) AS book_name,books.book_id FROM books WHERE books.is_active = 1 AND book_type_id = 3";
            $books = DB::select($qry);

            if ($books) {
                return response()->json(['status' => true, 'data' => $books]);
            } else {
                return response()->json(['status' => false, 'data' => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }

    }

}
