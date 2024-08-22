<?php

namespace Modules\Sl\Http\Controllers;

use App\Http\Controllers\IntenelNumberController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Cb\Entities\branch;
use Modules\Cb\Entities\CustomerReceipt;
use Modules\Cb\Entities\CustomerReceiptSetoffData;
use Modules\Cb\Entities\bank;
use Modules\Cb\Entities\bank_branch;
use Modules\Cb\Entities\Customer;
use Modules\Cb\Entities\CustomerReceiptCheque;

use Modules\Md\Entities\CustomerPaymentMode;
use Modules\Sl\Entities\creditor_ledger_setoff;
use Modules\Sl\Entities\creditors_ledger;
use Modules\Sl\Entities\supplier;
use Modules\Sl\Entities\supplier_payment_cheques;
use Modules\Sl\Entities\supplier_payment_setoff_data;
use Modules\Sl\Entities\supplier_payments;
use Modules\Sl\Entities\supplierPaymentMethod;

class supplierPaymentController extends Controller
{
    protected $response_data = [];

    public function getCustomers()
    {

        $customer_source = [];
        try {
            $qry = 'SELECT customers.customer_id AS hidden_id, customers.customer_code AS value, customers.customer_name AS id FROM customers';
            $customer_source = DB::select($qry);
        } catch (Exception $ex) {
            return response()->json(["status" => false, "exception" => $ex]);
        }
        return response()->json(["status" => true, "data" => $customer_source]);
    }


    public function getEmployees()
    {

        $employees = [];
        try {
            $qry = 'SELECT employees.employee_id, employees.employee_name FROM employees';
            $employees = DB::select($qry);
        } catch (Exception $ex) {
            return response()->json(["status" => false, "exception" => $ex]);
        }
        return response()->json(["status" => true, "data" => $employees]);
    }



    public function getBranch()
    {

        $branches = [];
        try {
            $branches = branch::all();
        } catch (Exception $ex) {
            return response()->json(["status" => false, "exception" => $ex]);
        }
        return response()->json(["status" => true, "data" => $branches]);
    }


    public function getBank()
    {

        $banks = [];
        try {
            $banks = bank::all();
        } catch (Exception $ex) {
            return response()->json(["status" => false, "exception" => $ex]);
        }
        return response()->json(["status" => true, "data" => $banks]);
    }


    public function getReceiptMethod()
    {

        $method = [];
        try {
            $method = supplierPaymentMethod::whereIn("supplier_payment_method_id", [2, 3])->get();
            //  dd($method);
        } catch (Exception $ex) {
            return response()->json(["status" => false, "exception" => $ex]);
        }
        return response()->json(["status" => true, "data" => $method]);
    }


    public function getBankBranch($bank_id)
    {

        $banks = [];
        try {
            $banks = bank_branch::where('bank_id', '=', $bank_id)->get();
        } catch (Exception $ex) {
            return response()->json(["status" => false, "exception" => $ex]);
        }
        return response()->json(["status" => true, "data" => $banks]);
    }



    public function getAutoSelectBankBranch($bank_code, $branch_code)
    {

        $banks = [];
        try {
            $bank = bank::where('bank_code', '=', $bank_code)->get();
            $bank_id = 0;
            $bank_name = "";
            if ($bank) {
                $bank_id = $bank[0]->bank_id;
                $bank_name = $bank[0]->bank_name;
            }
            $branch = bank_branch::where('bank_branch_code', '=', $branch_code)->get();
            $branch_id = 0;
            $branch_name = "";
            if ($branch) {
                $branch_id = $branch[0]->bank_branch_id;
                $branch_name = $branch[0]->bank_branch_name;
            }
            return response()->json(["status" => true, "data" => ["bank_id" => $bank_id, "bank_name" => $bank_name, "branch_id" => $branch_id, "branch_name" => $branch_name]]);
        } catch (Exception $ex) {
            return response()->json(["status" => false, "exception" => $ex]);
        }
    }



    public function loadSetoffTable($sup_id)
    {

        try {


            $query = "SELECT  
            A.internal_number ,
            A.internal_number AS reference_internal_number ,
            A.external_number ,
            A.external_number AS reference_external_number ,
            A.document_number ,
            A.document_number AS reference_document_number ,
            A.description, 
            A.trans_date , 
            ABS(A.amount) as amount,
            A.paidamount AS paid_amount ,
            0 AS return_amount,
            (ABS(A.amount) - A.paidamount)AS balance_amount   
            FROM  creditors_ledger AS A  WHERE  A.amount<> A.paidamount AND A.supplier_id='" . $sup_id . "' AND A.document_number = '120'";
            // dd($query);
            $data = DB::select($query);
            return response()->json(["status" => true, "data" => $data]);
        } catch (Exception $ex) {
            return response()->json(["status" => false, "exception" => $ex]);
        }
    }



    public function updateCustomerReceipt(Request $request, $id)
    {
        try {
            $receipt = CustomerReceipt::find($id);
            if ($receipt->delete()) {
                CustomerReceiptCheque::where('customer_receipt_id', '=', $id)->delete();
                CustomerReceiptSetoffData::where('customer_receipt_id', '=', $id)->delete();
                return $this->save_supplier_receipt($request);
            }
        } catch (Exception $ex) {
            return response()->json(["status" => false, "exception" => $ex]);
        }
    }




    public function save_supplier_receipt(Request $request)
    {

        DB::beginTransaction();
        try {

            $referencenumber = $request->input('external_number');
            $bR_id = $request->input('branch_id');

            $data = DB::table('branches')->where('branch_id', $bR_id)->get();

            $EXPLODE_ID = explode("-", $referencenumber);
            // dd($referencenumber);
            $externalNumber  = '';
            if ($data->count() > 0) {
                $documentPrefix = $data[0]->prefix;
                $externalNumber  = $documentPrefix . "-" . $EXPLODE_ID[0] . "-" . $EXPLODE_ID[1];
            }

            $receipt = new supplier_payments();
            $receipt->internal_number = IntenelNumberController::getNextID();
            $receipt->external_number = $externalNumber;
            $receipt->branch_id = $request->get('branch_id');
            $receipt->supplier_id = $request->get('supplier_id');
            $receipt->collector_id = $request->get('collector_id');
            $receipt->cashier_id = $request->get('cashier_id');
            $receipt->receipt_date = $request->get('receipt_date');
            $receipt->receipt_method_id = $request->get('receipt_method_id');
            $receipt->gl_account_id = $request->get('gl_account_id');
            $receipt->amount = $request->get('amount');
            $receipt->discount = $request->get('discount');
            $receipt->round_up = $request->get('round_up');
            $receipt->advance = $request->get('advance');
            $receipt->document_number = 2500;
            if ($receipt->save()) {
                $receipt_data = json_decode($request->get('receipt_data'));
                $this->saveSupplierReceiptData($receipt->supplier_payment_id, $receipt->internal_number, $receipt->external_number, $receipt->receipt_date, $receipt->branch_id, $receipt->supplier_id, $request->get('supplier_code'), $receipt_data);
                //  dd($request->get('single_cheque'));
                $single_cheque = json_decode($request->get('single_cheque'));
                //dd($single_cheque);
                if ($request->get('receipt_method_id') == 3) {
                    $this->saveSupplierReceiptCheque($receipt->supplier_payment_id, $receipt->internal_number, $receipt->external_number, $single_cheque);
                }



                array_push($this->response_data, true);
                DB::commit();
                return response()->json(["data" => $this->response_data]);
            }
        } catch (Exception $ex) {
            DB::rollback();
            return response()->json(["status" => false, "exception" => $ex]);
        }
    }




    public function saveSupplierReceiptData($receipt_id, $internal_number, $external_number, $trans_date, $branch_id, $supplier_id, $supplier_code, $receiptData)
    {


        try {

            foreach ($receiptData  as $data) {
                $setoff_data = json_decode($data);
                $setoff = new supplier_payment_setoff_data();
                $setoff->supplier_payments_id = $receipt_id;
                $setoff->internal_number = $internal_number;
                $setoff->external_number = $external_number;
                //setoff->internal_number = $setoff_data->internal_number;
                //$setoff->external_number = $setoff_data->external_number;
                $setoff->reference_internal_number = $setoff_data->reference_internal_number;
                $setoff->reference_external_number = $setoff_data->reference_external_number;
                $setoff->reference_document_number = $setoff_data->reference_document_number;
                $setoff->amount = $setoff_data->amount;
                $setoff->paid_amount = $setoff_data->paid_amount;
                $setoff->return_amount = $setoff_data->return_amount;
                $setoff->balance = $setoff_data->balance;
                $setoff->set_off_amount = $setoff_data->set_off_amount;
                $setoff->date = $setoff_data->date;
                if ($setoff_data->set_off_amount > 0) {
                    if ($setoff->save()) {
                        $this->saveCreditorLedgerSetoff($internal_number, $external_number, 500, $setoff_data->reference_internal_number, $setoff_data->reference_external_number, $setoff_data->reference_document_number, $trans_date, $branch_id, $supplier_id, $supplier_code, $setoff_data->set_off_amount, $setoff_data->set_off_amount);
                        array_push($this->response_data, true);
                    }
                }
            }
        } catch (Exception $ex) {
            array_push($this->response_data, $ex);
        }
    }



    public function saveSupplierReceiptCheque($receipt_id, $internal_number, $external_number, $receiptCheque)
    {


        try {
            $cheque = new supplier_payment_cheques();
            $cheque->supplier_payment_id = $receipt_id;
            $cheque->internal_number = $internal_number;
            $cheque->external_number = $external_number;
            $cheque->bank_code = $receiptCheque->bank_code;
            $cheque->cheque_referenceNo = $receiptCheque->cheque_referenceNo;
            $cheque->cheque_number = $receiptCheque->cheque_number;
            $cheque->banking_date = $receiptCheque->banking_date;
            $cheque->amount = -$receiptCheque->amount;
            $cheque->bank_id = $receiptCheque->bank_id;
            $cheque->bank_branch_id = $receiptCheque->bank_branch_id;
            $cheque->cheque_status = $receiptCheque->cheque_status;
            //$cheque->cheque_deposit_date = $receiptCheque->cheque_deposit_date;
            //$cheque->cheque_dishonoured_date = $receiptCheque->cheque_dishonoured_date;
            if ($receiptCheque->cheque_referenceNo && $receiptCheque->cheque_number && $receiptCheque->amount) {
                $response = $cheque->save();
                array_push($this->response_data, $response);
            }
        } catch (Exception $ex) {
            array_push($this->response_data, $ex);
        }
    }




    public function saveCreditorLedgerSetoff($internal_number, $external_number, $document_number, $reference_internal_number, $reference_external_number, $reference_document_number, $trans_date, $branch_id, $supplier_id, $supplier_code, $amount, $paidamount)
    {


        try {
            if ($amount > 0) {
                $sup = supplier::find($supplier_id);
                $ledger = new creditor_ledger_setoff();
                $ledger->internal_number = $internal_number;
                $ledger->external_number = $external_number;
                $ledger->document_number = $document_number;
                $ledger->reference_internal_number = $reference_internal_number;
                $ledger->reference_external_number = $reference_external_number;
                $ledger->reference_document_number = $reference_document_number;
                $ledger->trans_date = $trans_date;
                $ledger->description = "Supplier Payment to" . $sup->supplier_name;
                $ledger->branch_id = $branch_id;
                $ledger->supplier_id = $supplier_id;
                $ledger->supplier_code = $supplier_code;
                $ledger->amount = -$amount;
                if ($ledger->save()) {
                    $this->save_update_creditor_ledger($ledger, $paidamount);
                    array_push($this->response_data, true);
                }
            }
        } catch (Exception $ex) {
            array_push($this->response_data, $ex);
        }
    }



    public function save_update_creditor_ledger($ledger_setoff, $paidamount)
    {


        try {
            $sup = supplier::find($ledger_setoff->supplier_id);
            $ledger =  new creditors_ledger();
            $ledger->internal_number = $ledger_setoff->internal_number;
            $ledger->external_number = $ledger_setoff->external_number;
            $ledger->document_number = $ledger_setoff->document_number;
            $ledger->trans_date = $ledger_setoff->trans_date;
            $ledger->description = "Supplier Payment" . $sup->supplier_name;
            $ledger->branch_id = $ledger_setoff->branch_id;
            $ledger->supplier_id = $ledger_setoff->supplier_id;
            $ledger->supplier_code = $ledger_setoff->supplier_code;
            $ledger->amount = -$ledger_setoff->amount;
            $ledger->paidamount = $paidamount;
            $response1 =  $ledger->save();
            array_push($this->response_data, $response1);

            $ledger_update =  creditors_ledger::where('internal_number', '=', $ledger_setoff->reference_internal_number)->first();
            if ($ledger_update) {
                $ledger_update->paidamount += $paidamount;
                $response2 =  $ledger_update->update();
                array_push($this->response_data, $response2);
            }
        } catch (Exception $ex) {
            array_push($this->response_data, $ex);
        }
    }




    public function getReceipt($id)
    {

        try {
            $supplier_receipt = supplier_payments::find($id);
            $cashier_id = $supplier_receipt->cashier_id;
            $cashier_user_id_qry = DB::select('SELECT user_id FROM users WHERE id =' . $cashier_id);
            $cashier_user_id = $cashier_user_id_qry[0]->user_id;

            if ($supplier_receipt) {
                $supplier_receipt->receipt_cheque = supplier_payment_cheques::where('supplier_payment_id', '=', $id)->get();
                $supplier_receipt->receipt_data = $this->getCustomerReceiptSetoffData($supplier_receipt->customer_id, $id);//
                $supplier_receipt->customer_name = "";
                $supplier_receipt->customer_code = "";
                $supplier_receipt->cashier_user_id = $cashier_user_id;
                $customer = Customer::find($supplier_receipt->customer_id);
                if ($customer) {
                    $supplier_receipt->customer_code = $customer->customer_code;
                    $supplier_receipt->customer_name = $customer->customer_name;
                }
            }
            return response()->json(["status" => true, "data" => $supplier_receipt]);
        } catch (Exception $ex) {
            return response()->json(["status" => false, "exception" => $ex]);
        }
    }



    private function getCustomerReceiptSetoffData($customer_id, $receipt_id)
    {
        /* $receipt_data = CustomerReceiptSetoffData::where('customer_receipt_id', '=', $receipt_id)->get(); */
        $receipt_data = DB::select('SELECT DISTINCT CRS.*,IFNULL(SI.manual_number,CRS.reference_external_number) AS manual_number, DL.return_amount FROM customer_receipt_setoff_data CRS LEFT JOIN sales_invoices SI ON CRS.reference_internal_number = SI.internal_number LEFT JOIN debtors_ledgers DL ON CRS.internal_number = DL.internal_number WHERE CRS.customer_receipt_id = ' . $receipt_id . '');

        if ($receipt_data) {
            return $receipt_data;
        }
    }




    public function getReceiptList()
    {
        try {
            $query = "SELECT supplier_payments.supplier_payment_id,
            supplier_payments.external_number,
            supplier_payments.receipt_date,
            supplier_payments.amount,
            supplier_payment_cheques.banking_date,
            supplier_payment_cheques.cheque_number,
            suppliers.supplier_name,
            CASE
        WHEN supplier_payments.receipt_method_id = 2 THEN 'cash'
        ELSE 'cheque'
    END AS payment_mode FROM supplier_payments
            LEFT JOIN supplier_payment_cheques ON supplier_payments.supplier_payment_id = supplier_payment_cheques.supplier_payment_id
            INNER JOIN suppliers ON supplier_payments.supplier_id = suppliers.supplier_id ORDER BY supplier_payments.external_number DESC";
            $data = DB::select($query);
            return response()->json(["status" => true, "data" => $data]);
        } catch (Exception $ex) {
            return response()->json(["status" => false, "exception" => $ex]);
        }
    }
}
