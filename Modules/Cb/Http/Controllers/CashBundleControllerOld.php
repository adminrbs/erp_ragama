<?php

namespace Modules\Cb\Http\Controllers;

use App\Http\Controllers\IntenelNumberController;
use App\Http\Controllers\ReferenceIdController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Cb\Entities\bank;
use Modules\Cb\Entities\cash_bundle;
use Modules\Cb\Entities\cash_bundle_datas;
use Modules\Cb\Entities\Customer;
use Modules\Cb\Entities\CustomerReceipt;
use Modules\Cb\Entities\CustomerReceiptCheque;
use Modules\Cb\Entities\CustomerReceiptSetoffData;
use Modules\Cb\Entities\DebtorsLedger;
use Modules\Cb\Entities\DebtorsLedgerSetoff;
use Modules\Cb\Entities\sfa_receipt;
use Modules\Cb\Entities\SfaReceiptCheques;
use Modules\Cb\Entities\SfaReceiptSetOffData;

class CashBundleControllerOld extends Controller
{



    //save cash bundle - branch
    public function add_cash_bundle(Request $request)
    {

        DB::begintransaction();
        try {
            $branch_id_ = "";
            $dataArray = json_decode($request->input('dataArray'));
            $book_id = $request->input('book_id');
            $page_no = $request->input('page_no');
            foreach ($dataArray as $i) {
                $item = $i;
                $branch_id_ = $item->branch_id;
            }
            $internal_number = IntenelNumberController::getNextID();

            $referencenumber = $request->input('LblexternalNumber');
            $bR_id = $branch_id_;

            $data = DB::table('branches')->where('branch_id', $bR_id)->get();

            $EXPLODE_ID = explode("-", $referencenumber);
            $externalNumber  = '';
            if ($data->count() > 0) {
                $documentPrefix = $data[0]->prefix;
                $externalNumber  = $documentPrefix . "-" . $EXPLODE_ID[0] . "-" . $EXPLODE_ID[1];
            }
            $cash_bundle = new cash_bundle();
            $cash_bundle->internal_number = $internal_number;
            $cash_bundle->external_number = $externalNumber;
            $cash_bundle->document_number = 900;
            $cash_bundle->book_id = $book_id;
            $cash_bundle->page_no = $page_no;

            if ($cash_bundle->save()) {
                foreach ($dataArray as $i) {
                    //cash bundles
                    $item = $i;
                    $cash_bundle_datas = new cash_bundle_datas();
                    $cash_bundle_datas->cash_bundles_id =  $cash_bundle->cash_bundles_id;
                    $cash_bundle_datas->internal_number = $internal_number;
                    $cash_bundle_datas->external_number = $externalNumber;
                    $cash_bundle_datas->branch_id = $item->branch_id;
                    $cash_bundle_datas->customer_receipt_id = $item->customer_receipt_id;
                    $cash_bundle_datas->customer_receipt_setoff_data_id = $item->customer_receipt_set_of_id;
                    $cash_bundle_datas->amount = floatval(str_replace(',', '', $item->amount));
                    $cash_bundle_datas->cashier_id = Auth::user()->id;
                    $cash_bundle_datas->collector_id = $item->collector_id;
                    $cash_bundle_datas->cash_bundle_date = date("Y-m-d");
                    $cash_bundle_datas->sales_invoice_Id = $item->debtor_ledger_id; // Debtor ledger id save on sales invoice id
                    $cash_bundle_datas->remarks = $item->remark;

                    // $cash_bundle_datas->document_number = 900;
                    /* $cash_bundle_datas->save(); */
                    $cash_bundle_datas->save();
                }
                DB::commit();
                return response()->json(["status" => true, "message" => 'success']);
            }
        } catch (Exception $ex) {
            DB::rollback();
            return $ex;
        }
    }

    //create external number
    public function createExternal_number()
    {
        /* $exter_num = $this->reference_number->CustomerReceipt_referenceID('customer_receipts', 500); */
        $exter_num = ReferenceIdController::CustomerReceipt_referenceID_cash_bundle('customer_receipts', 500);
        $id_ = $exter_num['id'];
        $prefix_ = $exter_num['prefix'];

        $pattern = [
            1 => "0000",
            2 => "000",
            3 => "00",
            4 => "0",

        ];
        $id_len = strlen($id_);
        return $prefix_ . $pattern[$id_len] . $id_;
    }

    //load invoices
    public function loadInvoices_cash_ho($id)
    {
        try {
            /* $query = "SELECT cash_bundles_datas.remarks,sales_invoices.manual_number,sales_invoices.order_date_time,sales_invoices.total_amount,employees.employee_name FROM cash_bundles_datas LEFT JOIN sales_invoices ON cash_bundles_datas.sales_invoice_Id = sales_invoices.sales_invoice_Id
            LEFT JOIN employees ON sales_invoices.employee_id = employees.employee_id LEFT JOIN cash_bundles ON cash_bundles_datas.cash_bundles_id = cash_bundles.cash_bundles_id WHERE cash_bundles_datas.internal_number = $id AND cash_bundles.status = 0"; */
            $query = "SELECT
            cash_bundles_datas.remarks,
            IFNULL(sales_invoices.manual_number,debtors_ledgers.external_number) AS  manual_number  ,
            IFNULL(sales_invoices.order_date_time ,debtors_ledgers.trans_date ) AS  order_date_time ,
            cash_bundles_datas.amount AS  total_amount,
            employees.employee_name
        FROM
            cash_bundles_datas
        INNER JOIN cash_bundles ON cash_bundles_datas.cash_bundles_id = cash_bundles.cash_bundles_id
        INNER JOIN debtors_ledgers ON debtors_ledgers.debtors_ledger_id=cash_bundles_datas.sales_invoice_Id    
        LEFT JOIN sales_invoices ON cash_bundles_datas.sales_invoice_Id = sales_invoices.sales_invoice_Id
        LEFT JOIN employees ON cash_bundles_datas.collector_id = employees.employee_id
        
        WHERE
            cash_bundles_datas.internal_number = $id
        AND cash_bundles.status = 0";

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


    //get cash bundle to receipt
    public function getCashBundle_receipt()
    {
        try {
            $user_id = auth()->id();
            $query = "SELECT cash_bundles.cash_bundles_id,
             cash_bundles.external_number,
             SUM(cash_bundles_datas.amount) AS total_amount,
             cash_bundles_datas.cash_bundle_date,
             cash_bundles.internal_number,
             sfa_receipts.customer_receipt_id,
             users.name,
             (SELECT COUNT(*) FROM cash_bundles_datas WHERE cash_bundles_datas.cash_bundles_id = cash_bundles.cash_bundles_id) AS cash_bundles_datas_count
             
            FROM cash_bundles
            LEFT JOIN cash_bundles_datas ON cash_bundles.cash_bundles_id = cash_bundles_datas.cash_bundles_id
            LEFT JOIN sfa_receipts ON cash_bundles_datas.customer_receipt_id = sfa_receipts.customer_receipt_id
            LEFT JOIN users ON cash_bundles_datas.cashier_id = users.id WHERE sfa_receipts.receipt_status = 1 AND cash_bundles.receipt_created = 0 GROUP BY external_number";
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

    //create customer receipt(erp)
    public function create_rcpt(Request $request)
    {
        try {
            $bundle_id = $request->input('b_id');
            $qry_check_bundle_status = "SELECT receipt_created FROM cash_bundles WHERE cash_bundles_id = $bundle_id";
            $CBD = cash_bundle_datas::where("cash_bundles_id", "=", $bundle_id)->first();
            $br_id = $CBD->branch_id;
            $branchdata = DB::table('branches')->where('branch_id', $br_id)->get();
            $receipt_created_ = DB::select($qry_check_bundle_status);
            if ($receipt_created_) {
                if ($receipt_created_[0]->receipt_created != 0) {
                    return response()->json(["status" => false, "message" => "used"]);
                }
            }
            $cus_rcpt_id = "";
            $qry = "SELECT DISTINCT customer_receipt_id FROM cash_bundles_datas WHERE cash_bundles_id= $bundle_id";
            $result = DB::select($qry);
            $cus_rcpt_ids = [];
            if ($result) {
                foreach ($result as $res) {
                    array_push($cus_rcpt_ids, $res->customer_receipt_id);
                }

                /*  $cus_rcpt_id = $result[0]->customer_receipt_id; */
            }


            foreach ($cus_rcpt_ids as $cus_rcpt_id) {
                $cus_recpt = sfa_receipt::find($cus_rcpt_id);

                if ($cus_recpt) {
                    $cus_recpt_data =  sfaReceiptSetOffData::where('customer_receipt_id', '=', $cus_rcpt_id)->get();
                    // $cus_recpt->customer_recipt_data = $cus_recpt_data;
                    $customer_receipt = new CustomerReceipt();
                    $customer_receipt->internal_number = IntenelNumberController::getNextID();
                    $customer_receipt->external_number = $branchdata[0]->prefix.$this->createExternal_number();
                    $customer_receipt->branch_id = $cus_recpt->branch_id;
                    $customer_receipt->customer_id = $cus_recpt->customer_id;
                    $customer_receipt->collector_id = $cus_recpt->collector_id;
                    $customer_receipt->cashier_id = Auth::user()->id;
                    $customer_receipt->receipt_date = date('Y-m-d');
                    
                            foreach ($cus_recpt_data as $data) {
                                $dl = DebtorsLedger::find($data->$customer_receipt->receipt_method_id = $cus_recpt->receipt_method_id;
                    $customer_receipt->gl_account_id = 0; // need to change
                    $customer_receipt->amount = $cus_recpt->amount;
                    if ($cus_recpt->discount) {
                        $customer_receipt->discount = $cus_recpt->discount;
                    } else {
                        $customer_receipt->discount = 0;
                    }
                    $customer_receipt->round_up = $cus_recpt->round_up;

                    if ($cus_recpt->advance) {
                        $customer_receipt->advance = $cus_recpt->advance;
                    } else {
                        $customer_receipt->advance = 0;
                    }
                    $customer_receipt->document_number = 500;
                    if ($customer_receipt->save()) {

                        /* $customer_receipt->receipt_status = 0; */
                        if ($customer_receipt->save()) {debtors_ledger_id);
                                $dl_amount = $dl->amount;
                                //$bal_amount = floatval($data->dl_amount) - floatval($data->paidamount);
                                $bal_amount = floatval($dl_amount) - floatval($data->set_off_amount);
                                $customer_receipt_set_off_data = new CustomerReceiptSetoffData();
                                $customer_receipt_set_off_data->customer_receipt_id = $customer_receipt->customer_receipt_id;
                                $customer_receipt_set_off_data->internal_number = $customer_receipt->internal_number;
                                $customer_receipt_set_off_data->external_number = $customer_receipt->external_number;
                                $customer_receipt_set_off_data->reference_internal_number = $dl->internal_number; //debtors leger details
                                $customer_receipt_set_off_data->reference_external_number = $dl->external_number;
                                $customer_receipt_set_off_data->reference_document_number = $dl->document_number;
                                $customer_receipt_set_off_data->amount = $dl->amount; //dl amount
                                $customer_receipt_set_off_data->paid_amount = $dl->paidamount;  // dl paid amount
                                /* $customer_receipt_set_off_data->return_amount = $row->amount; */
                                $customer_receipt_set_off_data->balance = $bal_amount; // (debtors ledger amount - paid amount - set off amount) new formular =() debtors ledger amount - paid amount) edited 29-12-23 
                                $customer_receipt_set_off_data->set_off_amount = $data->set_off_amount; // sfa sett off amount (after all set_off_amount + debtor ledger paid amount)
                                $customer_receipt_set_off_data->debtors_ledger_id = $data->debtors_ledger_id;
                                $customer_receipt_set_off_data->date = date('Y-m-d');

                                //update dl paid amount
                                if ($customer_receipt_set_off_data->save()) {
                                    $dl->paidamount = $dl->paidamount +  $customer_receipt_set_off_data->set_off_amount;
                                    $dl->update();


                                    //add new dl record
                                    $customer = Customer::find($customer_receipt->customer_id);
                                    $deb_ledger = new DebtorsLedger();
                                    $deb_ledger->internal_number =  $customer_receipt->internal_number;
                                    $deb_ledger->external_number = $customer_receipt->external_number;
                                    $deb_ledger->document_number = $customer_receipt->document_number;
                                    $deb_ledger->trans_date = $customer_receipt->receipt_date;
                                    $deb_ledger->description = "Customer Receipt";
                                    $deb_ledger->branch_id = $customer_receipt->branch_id;
                                    $deb_ledger->customer_id = $customer_receipt->customer_id;
                                    $deb_ledger->customer_code = $customer->customer_code;
                                    $deb_ledger->amount = -$cus_recpt->amount;
                                    $deb_ledger->paidamount = -$cus_recpt->amount;
                                    if ($deb_ledger->save()) {
                                        $deb_ledger_setOff = new DebtorsLedgerSetoff();
                                        $deb_ledger_setOff->internal_number =  $customer_receipt->internal_number;
                                        $deb_ledger_setOff->external_number = $customer_receipt->external_number;
                                        $deb_ledger_setOff->document_number = $customer_receipt->document_number;
                                        $deb_ledger_setOff->reference_internal_number = $customer_receipt_set_off_data->reference_internal_number;
                                        $deb_ledger_setOff->reference_external_number = $customer_receipt_set_off_data->reference_external_number;
                                        $deb_ledger_setOff->reference_document_number = $customer_receipt_set_off_data->reference_document_number;
                                        $deb_ledger_setOff->trans_date = $deb_ledger->trans_date;
                                        $deb_ledger_setOff->description = $deb_ledger->description;
                                        $deb_ledger_setOff->branch_id =  $deb_ledger->branch_id;
                                        $deb_ledger_setOff->customer_id = $deb_ledger->customer_id;
                                        $deb_ledger_setOff->customer_code = $deb_ledger->customer_code;
                                        $deb_ledger_setOff->amount = -$data->set_off_amount;;
                                        $deb_ledger_setOff->save();
                                    }
                                }
                            }
                        }
                    }
                }

                $cash_bundle = cash_bundle::find($bundle_id);
                $cash_bundle->receipt_created = 1;
                /* $cash_bundle->status = 1; */
                $cash_bundle->update();

                $cus_recpt->receipt_status = 2;
                $cus_recpt->update();
            }
            return response()->json(["status" => true, "message" => "saved"]);
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //save customer receipt using sfa recipt data
    /*  public function saveCustomerReceipt($rcpt_id)
     {
         try {
             $qry = "SELECT sfa_receipts.branch_id,sfa_receipts.customer_id,sfa_receipts.collector_id,sfa_receipts.receipt_method_id,
             sfa_receipts.amount,sfa_receipts.discount,sfa_receipts.round_up,sfa_receipts.advance,
             sfa_receipts.advance,sfa_receipt_setoff_data.customer_receipt_id,
             sfa_receipt_setoff_data.debtors_ledger_id,sfa_receipt_setoff_data.set_off_amount,
             sales_invoices.external_number as inv_extr,sales_invoices.internal_number as inv_intr,sales_invoices.document_number as inv_doc
             FROM sfa_receipts INNER JOIN sfa_receipt_setoff_data ON sfa_receipts.customer_receipt_id = sfa_receipt_setoff_data.customer_receipt_id
             LEFT JOIN debtors_ledgers ON sfa_receipt_setoff_data.debtors_ledger_id = debtors_ledgers.debtors_ledger_id
             LEFT JOIN sales_invoices ON debtors_ledgers.external_number = sales_invoices.external_number
             WHERE sfa_receipts.customer_receipt_id = $rcpt_id";
             $result = DB::select($qry);
 
             if ($result) {
                 foreach ($result as $row) {
                     $customer_receipt = new CustomerReceipt();
                     $customer_receipt->internal_number = IntenelNumberController::getNextID();
                     $customer_receipt->external_number = $this->createExternal_number();
                     $customer_receipt->branch_id = $row->branch_id;
                     $customer_receipt->customer_id = $row->customer_id;
                     $customer_receipt->collector_id = $row->collector_id;
                     $customer_receipt->cashier_id = Auth::user()->id;
                     $customer_receipt->receipt_date = date('Y-m-d');
                     $customer_receipt->receipt_method_id = Auth::user()->id;
                     $customer_receipt->gl_account_id = 0; // need to change
                     $customer_receipt->amount = $row->amount;
                     $customer_receipt->discount = $row->discount;
                     $customer_receipt->round_up = $row->round_up;
                     $customer_receipt->advance = $row->advance;
                     $customer_receipt->document_number = 500; */
    /* $customer_receipt->receipt_status = 0; */
    /*  if ($customer_receipt->save()) {
                         $customer_receipt_set_off_data = new CustomerReceiptSetoffData();
                         $customer_receipt_set_off_data->customer_receipt_id = $customer_receipt->customer_receipt_id;
                         $customer_receipt_set_off_data->internal_number = $customer_receipt->internal_number;
                         $customer_receipt_set_off_data->external_number = $customer_receipt->external_number;
                         $customer_receipt_set_off_data->reference_internal_number = $row->inv_intr; //debtors leger details
                         $customer_receipt_set_off_data->reference_external_number = $row->inv_extr;
                         $customer_receipt_set_off_data->reference_document_number = $row->inv_doc;
                         $customer_receipt_set_off_data->amount = $row->amount; //debtors ledger amount
                         $customer_receipt_set_off_data->paid_amount = $row->amount; // // paid amount */
    /* $customer_receipt_set_off_data->return_amount = $row->amount; */
    /*   $customer_receipt_set_off_data->balance = $row->amount; // (debtors ledger amount - paid amount - set off amount)
                         $customer_receipt_set_off_data->set_off_amount = $row->amount; // sett off amount (after all set_off_amount + debtor ledger paid amount)
                         $customer_receipt_set_off_data->debtors_ledger_id = $row->debtors_ledger_id;
                         $customer_receipt_set_off_data->date = date('Y-m-d'); // need to change
                         $customer_receipt_set_off_data->save();
                     }
                 }
             }
         } catch (Exception $ex) {
             return $ex;
         }
     } */


    //load invoices rcpt
    public function loadInvoices_recipt($id)
    {
        try {
            $query = "SELECT cash_bundles_datas.remarks,sfa_receipts.external_number,sfa_receipts.receipt_date,employees.employee_name,cash_bundles_datas.amount FROM cash_bundles_datas INNER JOIN sfa_receipts ON cash_bundles_datas.customer_receipt_id = sfa_receipts.customer_receipt_id
            LEFT JOIN employees ON sfa_receipts.collector_id = employees.employee_id LEFT JOIN cash_bundles ON cash_bundles_datas.cash_bundles_id = cash_bundles.cash_bundles_id WHERE cash_bundles.internal_number = $id AND cash_bundles.status = 0";
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


    //create rcpt cheque
    public function create_rcpt_cheque(Request $request)
    {
        try {
            $rcpt_id = $request->input('r_id');
            $qry_check_rcpt_status = "SELECT receipt_status FROM sfa_receipts WHERE customer_receipt_id = $rcpt_id";
            $receipt_created_ = DB::select($qry_check_rcpt_status);
            if ($receipt_created_) {
                if ($receipt_created_[0]->receipt_status != 1) {
                    return response()->json(["status" => false, "message" => "used"]);
                }
            }

            $cus_recpt = sfa_receipt::find($rcpt_id);
            if ($cus_recpt) {
                $cus_recpt_data =  sfaReceiptSetOffData::where('customer_receipt_id', '=', $rcpt_id)->get();
                $cus_chq_data = SfaReceiptCheques::where('customer_receipt_id', '=', $rcpt_id)->get();

                $customer_receipt = new CustomerReceipt();
                $customer_receipt->internal_number = IntenelNumberController::getNextID();
                $customer_receipt->external_number = $this->createExternal_number();
                $customer_receipt->branch_id = $cus_recpt->branch_id;
                $customer_receipt->customer_id = $cus_recpt->customer_id;
                $customer_receipt->collector_id = $cus_recpt->collector_id;
                $customer_receipt->cashier_id = Auth::user()->id;
                $customer_receipt->receipt_date = date('Y-m-d');
                $customer_receipt->receipt_method_id = $cus_recpt->receipt_method_id;
                $customer_receipt->gl_account_id = 0; // need to change
                $customer_receipt->amount = $cus_recpt->amount;

                if ($cus_recpt->discount) {
                    $customer_receipt->discount = $cus_recpt->discount;
                } else {
                    $customer_receipt->discount = 0;
                }
                $customer_receipt->round_up = $cus_recpt->round_up;

                if ($cus_recpt->advance) {
                    $customer_receipt->advance = $cus_recpt->advance;
                } else {
                    $customer_receipt->advance = 0;
                }
                $customer_receipt->document_number = 500;
                /* $customer_receipt->receipt_status = 0; */
                if ($customer_receipt->save()) {

                     //add new dl record
                     $customer = Customer::find($customer_receipt->customer_id);
                     $deb_ledger = new DebtorsLedger();
                     $deb_ledger->internal_number =  $customer_receipt->internal_number;
                     $deb_ledger->external_number = $customer_receipt->external_number;
                     $deb_ledger->document_number = $customer_receipt->document_number;
                     $deb_ledger->trans_date = $customer_receipt->receipt_date;
                     $deb_ledger->description = "Customer Receipt";
                     $deb_ledger->branch_id = $customer_receipt->branch_id;
                     $deb_ledger->customer_id = $customer_receipt->customer_id;
                     $deb_ledger->customer_code = $customer->customer_code;
                     $deb_ledger->amount = -$cus_recpt->amount;
                     $deb_ledger->paidamount = -$cus_recpt->amount;
                     $deb_ledger->save();
                    foreach ($cus_recpt_data as $row) {
                        $dl = DebtorsLedger::find($row->debtors_ledger_id);
                        $dl_amount = $dl->amount;
                        $bal_amount = floatval($row->dl_amount) - floatval($row->paidamount) - floatval($row->set_off_amount);
                        $customer_receipt_set_off_data = new CustomerReceiptSetoffData();
                        $customer_receipt_set_off_data->customer_receipt_id = $customer_receipt->customer_receipt_id;
                        $customer_receipt_set_off_data->internal_number = $customer_receipt->internal_number;
                        $customer_receipt_set_off_data->external_number = $customer_receipt->external_number;
                        $customer_receipt_set_off_data->reference_internal_number = $dl->internal_number; //debtors leger details
                        $customer_receipt_set_off_data->reference_external_number = $dl->external_number;
                        $customer_receipt_set_off_data->reference_document_number = $dl->document_number;
                        $customer_receipt_set_off_data->amount = $dl_amount; //dl amount
                        $customer_receipt_set_off_data->paid_amount = $dl->paidamount;  // dl paid amount
                        /* $customer_receipt_set_off_data->return_amount = $row->amount; */
                        $customer_receipt_set_off_data->balance = $bal_amount; // (debtors ledger amount - paid amount - set off amount)
                        $customer_receipt_set_off_data->set_off_amount = $row->set_off_amount; // sfa sett off amount (after all set_off_amount + debtor ledger paid amount)
                        $customer_receipt_set_off_data->debtors_ledger_id = $row->debtors_ledger_id;
                        $customer_receipt_set_off_data->date = date('Y-m-d');
                        //update dl paid amount
                        if ($customer_receipt_set_off_data->save()) {
                            $dl->paidamount = $dl->paidamount +  $customer_receipt_set_off_data->set_off_amount;
                            $dl->update();
                        }
                           
                            
                                $deb_ledger_setOff = new DebtorsLedgerSetoff();
                                $deb_ledger_setOff->internal_number =  $customer_receipt->internal_number;
                                $deb_ledger_setOff->external_number = $customer_receipt->external_number;
                                $deb_ledger_setOff->document_number = $customer_receipt->document_number;
                                $deb_ledger_setOff->reference_internal_number = $customer_receipt_set_off_data->reference_internal_number;
                                $deb_ledger_setOff->reference_external_number = $customer_receipt_set_off_data->reference_external_number;
                                $deb_ledger_setOff->reference_document_number = $customer_receipt_set_off_data->reference_document_number;
                                $deb_ledger_setOff->trans_date = $deb_ledger->trans_date;
                                $deb_ledger_setOff->description = $deb_ledger->description;
                                $deb_ledger_setOff->branch_id =  $deb_ledger->branch_id;
                                $deb_ledger_setOff->customer_id = $deb_ledger->customer_id;
                                $deb_ledger_setOff->customer_code = $deb_ledger->customer_code;
                                $deb_ledger_setOff->amount = -$row->set_off_amount;
                                $deb_ledger_setOff->save();
                            
                        
                    }

                    foreach ($cus_chq_data as $row) {
                        $bank_ = bank::find($row->bank_id);

                        $customer_receipt_cheque = new CustomerReceiptCheque();
                        /* $customer_receipt_cheque->cheque_referenceNo */
                        $customer_receipt_cheque->customer_receipt_id = $customer_receipt->customer_receipt_id;
                        $customer_receipt_cheque->internal_number = $customer_receipt->internal_number;
                        $customer_receipt_cheque->external_number = $customer_receipt->external_number;
                        $customer_receipt_cheque->cheque_number = $row->cheque_number;
                        $customer_receipt_cheque->bank_code = $bank_->bank_code;
                        $customer_receipt_cheque->banking_date = $row->banking_date;
                        $customer_receipt_cheque->amount = $row->amount;
                        $customer_receipt_cheque->bank_id = $row->bank_id;
                        $customer_receipt_cheque->bank_branch_id = $row->bank_branch_id;
                        $customer_receipt_cheque->cheque_status = 0; // need to check
                        /* $customer_receipt_cheque->cheque_deposit_date = $row->bank_branch_id; */
                        /*  $customer_receipt_cheque->cheque_dishonoured_date = $row->bank_branch_id; */

                        $customer_receipt_cheque->save();
                    }
                }


                $sfa_rcpt = sfa_receipt::find($rcpt_id);
                $sfa_rcpt->receipt_status = 2;
                $sfa_rcpt->update();

                return response()->json(["status" => true, "message" => "saved"]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }
}
