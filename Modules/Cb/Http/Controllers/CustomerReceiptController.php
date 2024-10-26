<?php

namespace Modules\Cb\Http\Controllers;

use App\Http\Controllers\ChequeReferenceNumberController;
use App\Http\Controllers\CompanyDetailsController;
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
use Modules\Cb\Entities\CustomerReceiptBankSlip;
use Modules\Cb\Entities\CustomerReceiptCheque;
use Modules\Cb\Entities\DebtorsLedger;
use Modules\Cb\Entities\DebtorsLedgerSetoff;
use Modules\Md\Entities\CustomerPaymentMode;

class CustomerReceiptController extends Controller
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
            $qry = 'SELECT employees.employee_id, employees.employee_name,desgination_id FROM employees';
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
            $method = CustomerPaymentMode::all();
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



    public function loadSetoffTable($customer_id)
    {

        try {
            /* $query = "SELECT  * FROM 

            ( SELECT  
                        A.internal_number ,
                        A.reference_internal_number ,
                        A.external_number ,
                        A.reference_external_number ,
                        A.reference_document_number ,
                        A.document_number ,
                        A.description, 
                        A.trans_date , 
                        A.amount ,
                        P.paid_amount ,
                        0 AS return_amount,
                        (A.amount-(A.amount - P.paid_amount))AS balance_amount  
                        
                         FROM 
                        ( SELECT  internal_number,reference_internal_number,reference_external_number ,trans_date , external_number,description  , document_number, reference_document_number , amount    FROM  debtors_ledger_setoffs
                        WHERE amount>0  AND customer_id = '" . $customer_id . "' ) A    
                        
                        LEFT JOIN 
                        
                        ( 
                        SELECT  reference_internal_number 
                        , SUM(amount) AS paid_amount  
                        FROM debtors_ledger_setoffs
                        WHERE customer_id='" . $customer_id . "' 
                        GROUP BY reference_internal_number
                        ) P  ON  A.internal_number=P.reference_internal_number ) DATA 
            
            WHERE `DATA`.paid_amount + `DATA`.return_amount >0 AND `DATA`.document_number = '210'";*/

            $query = "SELECT 
            A.internal_number ,
            A.internal_number AS reference_internal_number ,
            A.external_number ,
            A.external_number AS reference_external_number ,
            A.document_number ,
            A.document_number AS reference_document_number ,
            A.description, 
            A.trans_date , 
            A.amount ,
            A.paidamount AS paid_amount,
            A.debtors_ledger_id,
            0 AS return_amount,
            (A.amount - A.paidamount)AS balance_amount   
            FROM  debtors_ledgers AS A  WHERE  A.amount<> A.paidamount AND A.customer_id='" . $customer_id . "' AND (A.document_number = '210' OR A.document_number = '1600')";
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
                return $this->saveCustomerReceipt($request);
            }
        } catch (Exception $ex) {
            return response()->json(["status" => false, "exception" => $ex]);
        }
    }




    public function saveCustomerReceipt(Request $request)
    {
        //dd($request->input('receipt_data'));
        DB::beginTransaction();
        try {
            /* if($request->get('receipt_method_id') == 7){
                $payment_slip = json_decode($request->get('payment_slip'));
                $request->validate([
                    'reference' => 'required|unique:payment_slips,'.$payment_slip->txtSlipRef
                    
                ]);

            } */
            

            $receipt_data = json_decode($request->get('receipt_data'), true); // Decode as array

            if ($request->get('advance') == 0) {
                $sumSetOffAmount = 0;
                $amount = $request->get('amount');
                $amountFloat = floatval(str_replace(',', '', $amount));
                $setOffAmountSum = 0;
                foreach ($receipt_data as $receiptJson) {
                    
                    $receipt = json_decode($receiptJson, true);
                    
                    
                       
                        $cleanedAmount = preg_replace('/[^0-9.]/', '', $receipt['set_off_amount']);
                       
                        $setOffAmountSum += floatval($cleanedAmount);
                    
                }

             

                if ($setOffAmountSum < $amountFloat) {
                   
                    return response()->json(["msg" => "advanceError"]);
                }
            }


            if ($request->get('receipt_method_id') == 2) {
                $single_cheque = json_decode($request->get('single_cheque'));
                $cheque_number = $single_cheque->cheque_number;
                $bank_id = $single_cheque->bank_id;

                $qry = DB::select("SELECT COUNT(*) as count 
                        FROM customer_receipt_cheques CRC 
                        WHERE CRC.bank_id = $bank_id 
                        AND CRC.cheque_number = $cheque_number
                        AND DATE(CRC.created_at) >= DATE_SUB(CURDATE(), INTERVAL 2 MONTH);");

                if ($qry[0]->count > 0) {
                    DB::rollBack();
                    return response()->json(["duplicate" => "duplicate"]);
                }
            }


            $referencenumber = $request->input('external_number');
            // dd($referencenumber);
            $bR_id = $request->get('branch_id');

            $data = DB::table('branches')->where('branch_id', $bR_id)->get();

            $EXPLODE_ID = explode("-", $referencenumber);

            $externalNumber  = '';
            if ($data->count() > 0) {
                $documentPrefix = $data[0]->prefix;
                $externalNumber  = $documentPrefix . "-" . $EXPLODE_ID[0] . "-" . $EXPLODE_ID[1];
            }
            $customerObj = Customer::find($request->get('customer_id'));
            $receipt = new CustomerReceipt();
            $receipt->internal_number = IntenelNumberController::getNextID();
            $receipt->external_number = $externalNumber;
            $receipt->branch_id = $request->get('branch_id');
            $receipt->customer_id = $request->get('customer_id');
            $receipt->collector_id = $request->get('collector_id');
            $receipt->cashier_id = $request->get('cashier_id');
            $receipt->receipt_date = $request->get('receipt_date');
            $receipt->receipt_method_id = $request->get('receipt_method_id');
            $receipt->gl_account_id = $request->get('gl_account_id');
            $receipt->amount = $request->get('amount');
            $receipt->discount = $request->get('discount');
            $receipt->round_up = $request->get('round_up');
            $receipt->advance = $request->get('advance');
            $receipt->document_number = 500;
            $receipt->is_direct_receipt = 1;
            if($request->get('your_ref')){
                $receipt->your_reference = $request->get('your_ref');
            }
            if ($receipt->save()) {
                $receipt_data = json_decode($request->get('receipt_data'));
                //dd($receipt_data);

                $single_cheque = json_decode($request->get('single_cheque'));
                $payment_slip = json_decode($request->get('payment_slip'));
               // dd($payment_slip);
                $this->save_update_DebtorLedger($receipt, $customerObj, $receipt_data);
                /* $this->saveCustomerReceiptData($receipt->customer_receipt_id, $receipt->internal_number, $receipt->external_number, $receipt->receipt_date, $receipt->branch_id, $receipt->customer_id, $request->get('customer_code'), $receipt_data); */
                $this->saveCustomerReceiptCheque($receipt->customer_receipt_id, $receipt->internal_number, $receipt->external_number, $single_cheque);
                if($receipt->receipt_method_id == 7){
                    $this->CustomerReceiptsaveBankSLip($receipt,$payment_slip);
                }
               
                DB::commit();
                array_push($this->response_data, true);
                return response()->json(["data" => $this->response_data]);
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json(["status" => false, "exception" => $ex]);
        }
    }


    public function CustomerReceiptsaveBankSLip($receipt,$paymentSlip){
        try{
            
          
                
                //$slip_data = json_decode($data);
                //dd($$data);
                $slip = new CustomerReceiptBankSlip();
                $slip->customer_receipt_id = $receipt->customer_receipt_id;
                $slip->internal_number = $receipt->internal_number;
                $slip->external_number = $receipt->external_number;
                $slip->reference = $paymentSlip->cheque_referenceNo;
                $slip->slip_time = $paymentSlip->slip_time;
                $slip->slip_date = $paymentSlip->slip_date;
                $slip->save();
            
        }catch (Exception $ex) {
            array_push($this->response_data, $ex);
        }
    }

    public function saveCustomerReceiptData($dl, $receipt_id, $internal_number, $external_number, $trans_date, $branch_id, $customer_id, $customer_code, $receiptData)
    {


        try {

            foreach ($receiptData  as $data) {
                $setoff_data = json_decode($data);
                //dd($setoff_data->reference_internal_number);
                $setoff = new CustomerReceiptSetoffData();
                $setoff->customer_receipt_id = $receipt_id;
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
                $setoff->debtors_ledger_id = $setoff_data->debtors_ledger_id;
                if ($setoff_data->set_off_amount > 0) {
                    if ($setoff->save()) {
                        $this->saveDebtorLedgerSetoff($internal_number, $external_number, 500, $setoff_data->reference_internal_number, $setoff_data->reference_external_number, $setoff_data->reference_document_number, $trans_date, $branch_id, $customer_id, $customer_code, $setoff_data->set_off_amount, $setoff_data->set_off_amount, $setoff->debtors_ledger_id);
                        $new_ledger_obj = DebtorsLedger::find($dl->debtors_ledger_id);
                        //$new_ledger_obj->paidamount = $new_ledger_obj->paidamount + $setoff->set_off_amount;
                        $new_ledger_obj->paidamount = -abs($new_ledger_obj->paidamount + $setoff->set_off_amount);

                        $new_ledger_obj->update();
                        array_push($this->response_data, true);
                    }
                }
            }
        } catch (Exception $ex) {
            array_push($this->response_data, $ex);
        }
    }



    public function saveCustomerReceiptCheque($receipt_id, $internal_number, $external_number, $receiptCheque)
    {


        try {
            $cheque = new CustomerReceiptCheque();
            $cheque->customer_receipt_id = $receipt_id;
            $cheque->internal_number = $internal_number;
            $cheque->external_number = $external_number;
            $cheque->bank_code = $receiptCheque->bank_code;
            $cheque->cheque_referenceNo = $receiptCheque->cheque_referenceNo;
            $cheque->cheque_number = $receiptCheque->cheque_number;
            $cheque->customer_cheque_reference_number = ChequeReferenceNumberController::customerChequeReferenceGenerator();
            $cheque->banking_date = $receiptCheque->banking_date;
            $cheque->amount = $receiptCheque->amount;
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




    public function saveDebtorLedgerSetoff($internal_number, $external_number, $document_number, $reference_internal_number, $reference_external_number, $reference_document_number, $trans_date, $branch_id, $customer_id, $customer_code, $amount, $paidamount, $ledger_id)
    {

        //dd($reference_internal_number);
        try {
            if ($amount > 0) {
                $ledger = new DebtorsLedgerSetoff();
                $ledger->internal_number = $internal_number;
                $ledger->external_number = $external_number;
                $ledger->document_number = $document_number;
                $ledger->reference_internal_number = $reference_internal_number;
                $ledger->reference_external_number = $reference_external_number;
                $ledger->reference_document_number = $reference_document_number;
                $ledger->trans_date = $trans_date;
                $ledger->description = "Customer Receipt";
                $ledger->branch_id = $branch_id;
                $ledger->customer_id = $customer_id;
                $ledger->customer_code = $customer_code;
                $ledger->amount = -$amount;
                // dd($ledger->internal_number);
                if ($ledger->save()) {
                    // $this->save_update_DebtorLedger($ledger, $paidamount,$ledger_id);
                    $ledger_update = DebtorsLedger::find($ledger_id);
                    if ($ledger_update) {
                        $ledger_update->paidamount += $paidamount;
                        $response2 =  $ledger_update->update();
                        array_push($this->response_data, $response2);
                    }
                    array_push($this->response_data, true);
                }
            }
        } catch (Exception $ex) {
            array_push($this->response_data, $ex);
        }
    }



    public function save_update_DebtorLedger($receipt, $cus_obj, $receipt_data)
    {

        //dd($ledger_setoff->reference_internal_number);
        try {

            $ledger =  new DebtorsLedger();
            $ledger->internal_number = $receipt->internal_number;
            $ledger->external_number = $receipt->external_number;
            $ledger->document_number = $receipt->document_number;
            $ledger->trans_date = $receipt->receipt_date;
            $ledger->description = "Customer Receipt";
            $ledger->branch_id = $receipt->branch_id;
            $ledger->customer_id = $receipt->customer_id;
            $ledger->customer_code = $cus_obj->customer_code;
            $ledger->amount = -$receipt->amount;
            //$ledger->paidamount = -$paidamount; // changed . mr.janaka 23/09

            // $ledger->paidamount = $ledger->amount;
            $response1 =  $ledger->save();

            $this->saveCustomerReceiptData($ledger, $receipt->customer_receipt_id, $receipt->internal_number, $receipt->external_number, $receipt->receipt_date, $receipt->branch_id, $receipt->customer_id, $ledger->customer_code, $receipt_data);
            array_push($this->response_data, $response1);
            //dd($ledger_setoff->reference_internal_number);
            //$ledger_update =  DebtorsLedger::where('internal_number', '=', $ledger_setoff->reference_internal_number)->first();

            /* $ledger_update = DebtorsLedger::find($ledger_id); */
            /* if ($ledger_update) {
                $ledger_update->paidamount += $paidamount;
                $response2 =  $ledger_update->update();
                array_push($this->response_data, $response2);
            } */
        } catch (Exception $ex) {
            array_push($this->response_data, $ex);
        }
    }




    public function getCustomerReceipt($id)
    {

        try {
            $customer_receipt = CustomerReceipt::find($id);
            //dd($customer_receipt);
            $cashier_id = $customer_receipt->cashier_id;
            //dd($cashier_id);
            //$cashier_user_id_qry = DB::select('SELECT user_id FROM users WHERE id ='.$cashier_id);
            //dd($cashier_user_id_qry);
            $cashier_user_id = $cashier_id;
            /*  if(count($cashier_user_id_qry) > 0){
                $cashier_user_id = $cashier_user_id_qry[0]->user_id;

            } */

            if ($customer_receipt) {
                $customer_receipt->receipt_cheque = CustomerReceiptCheque::where('customer_receipt_id', '=', $id)->get();

                $customer_receipt->receipt_data = $this->getCustomerReceiptSetoffData($customer_receipt->customer_id, $id);
                //dd($customer_receipt);
                $customer_receipt->customer_name = "";
                $customer_receipt->customer_code = "";
                $customer_receipt->cashier_user_id = $cashier_user_id;
                $customer = Customer::find($customer_receipt->customer_id);
                if ($customer) {
                    $customer_receipt->customer_code = $customer->customer_code;
                    $customer_receipt->customer_name = $customer->customer_name;
                }
            }
            //dd($customer_receipt);
            return response()->json(["status" => true, "data" => $customer_receipt]);
        } catch (Exception $ex) {
            //dd($ex);
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

    public function generateReceiptReport($id)
    {
        try {
            return response()->json(['success' => true, 'data' => [
                'customerRecipthedder' => $this->customerRecipthedder($id),
                'customerRecipt' => $this->customerRecipt($id),
                'company' => CompanyDetailsController::CompanyName(),
                'adderess' => CompanyDetailsController::CompanyAddress(),
                'phoneNumber' => CompanyDetailsController::CompanyContactDetails(),

            ]]);
        } catch (Exception $ex) {
            return response()->json(['status' => false, 'error' => $ex->getMessage()]);
        }
    }

    private function customerRecipthedder($id)
    {
        try {
           $data = DB::select("SELECT B.branch_name,B.address AS Baddress,CR.external_number,CR.receipt_date ,C.customer_name,C.primary_address FROM customer_receipts CR
INNER JOIN branches B ON B.branch_id= CR.branch_id
INNER JOIN customers C ON C.customer_id= CR.customer_id
WHERE CR.customer_receipt_id='$id'");
            return ($data);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    private function customerRecipt($id)
    {
        try {
            $receipt_data = DB::select('SELECT DISTINCT CRS.*,DATEDIFF(CURRENT_DATE,DL.trans_date) AS age , DL.description  FROM customer_receipt_setoff_data CRS LEFT JOIN sales_invoices SI ON CRS.reference_internal_number = SI.internal_number LEFT JOIN debtors_ledgers DL ON CRS.internal_number = DL.internal_number WHERE CRS.customer_receipt_id = ' . $id . '');
            return ($receipt_data);
        } catch (Exception $ex) {
            return $ex;
        }
    }

}
