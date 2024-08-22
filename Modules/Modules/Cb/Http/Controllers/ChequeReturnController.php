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
use Modules\Cb\Entities\cheque_return;
use Modules\Cb\Entities\Customer;
use Modules\Cb\Entities\CustomerReceiptCheque;
use Modules\Cb\Entities\DebtorsLedger;
use Modules\Cb\Entities\DebtorsLedgerSetoff;
use Modules\Dl\Entities\debit_note;

class ChequeReturnController extends Controller
{
    //load cheques
    public function load_cheques($cusID){
        try{
            $qry = "SELECT DISTINCT CRC.customer_receipt_cheque_id,CRC.external_number,CRC.cheque_number,CR.customer_id,CR.receipt_method_id,CR.collector_id,
            CRC.amount,CR.receipt_date,CRC.banking_date,CRC.cheque_deposit_date  
            FROM customer_receipt_cheques CRC INNER JOIN customer_receipts CR 
            ON CRC.customer_receipt_id = CR.customer_receipt_id WHERE CR.receipt_method_id = 2 AND CR.customer_id = $cusID AND CRC.cheque_status = 1";
           // dd($qry);
            $result = DB::select($qry);
            //dd($result);
            if($result){
                return response()->json(["status" => true,"data"=>$result]);
            }

        }catch(Exception $ex){
            return $ex;
        }
    }

    //add chq return
    public function add_chq_return(Request $request, $id){
        DB::beginTransaction();
        try{

            $referencenumber = $request->input('LblexternalNumber');
           // dd($referencenumber);
                $bR_id = $request->input('cmbBranch');

                $data = DB::table('branches')->where('branch_id', $bR_id)->get();

                $EXPLODE_ID = explode("-", $referencenumber);
                $externalNumber  = '';
                if ($data->count() > 0) {
                    $documentPrefix = $data[0]->prefix;
                    
                    $externalNumber  = $documentPrefix . "-" . $EXPLODE_ID[0] . "-" . $EXPLODE_ID[1];
                }
            $chq_ = CustomerReceiptCheque::find($id);
           $chq_rturn = new cheque_return(); 
           $chq_rturn->internal_number = IntenelNumberController::getNextID();
           $chq_rturn->external_number =  $externalNumber;
           $chq_rturn->returned_date =  $request->input('returned_on');
           $chq_rturn->branch_id =  $request->input('cmbBranch');
           $chq_rturn->customer_id =  $request->input('customerID');
           $chq_rturn->cheque_number =  $request->input('txtChqNo');
           $chq_rturn->bank_charges =  $request->input('txtbank_charges');
           $chq_rturn->document_number =  1900;
           $chq_rturn->is_redeposit_allowed =  $request->input('re_deposit');
           $chq_rturn->cheque_dishonur_reason_id =  $request->input('return_reason');
           $chq_rturn->bank_charges_paid_by_customer =  $request->input('pay_by_customer');
           $chq_rturn->returned_by =  Auth::user()->id;
           $chq_rturn->customer_receipt_cheque_id = $id;
           $chq_rturn->amount = $chq_->amount;
           if($chq_rturn->save()){
                $chq = CustomerReceiptCheque::find($id);
                 $chq->cheque_status = 2;
                 $chq->cheque_dishonoured_date = $request->input('returned_on');
                 $chq->dishonoured_by = $chq_rturn->returned_by;
                 $chq->cheque_dishonur_reason_id = $chq_rturn->cheque_dishonur_reason_id;
                 $chq->bank_charges = $chq_rturn->bank_charges;
                 $chq->update();

                 //debtors ledger for chq return
                 $customer = Customer::find($chq_rturn->customer_id);
                            
                 $customer_name = $customer->customer_name;
                 $customer_code = $customer->customer_code;
                            $debtors_ledger_rtn = new DebtorsLedger();
                            $debtors_ledger_rtn->internal_number = $chq_rturn->internal_number;
                            $debtors_ledger_rtn->external_number = $chq_rturn->external_number;
                            $debtors_ledger_rtn->document_number = $chq_rturn->document_number;
                            $debtors_ledger_rtn->trans_date = $chq_rturn->created_at;
                            $debtors_ledger_rtn->description = "Cheque Return for " . $customer_name;
                            $debtors_ledger_rtn->branch_id = $chq_rturn->branch_id;
                            $debtors_ledger_rtn->customer_id = $chq_rturn->customer_id;
                            $debtors_ledger_rtn->customer_code = $customer_code;
                            $debtors_ledger_rtn->amount = $chq_rturn->amount;
                            $debtors_ledger_rtn->employee_id =$request->input('cmbEmp');
                          
                            if ($debtors_ledger_rtn->save()) {
                                $debtors_ledger_setoff_rtn = new DebtorsLedgerSetoff();
                                $debtors_ledger_setoff_rtn->internal_number = $debtors_ledger_rtn->internal_number;
                                $debtors_ledger_setoff_rtn->external_number = $debtors_ledger_rtn->external_number;
                                $debtors_ledger_setoff_rtn->document_number = $debtors_ledger_rtn->document_number;
                                $debtors_ledger_setoff_rtn->reference_internal_number = $debtors_ledger_rtn->internal_number;
                                $debtors_ledger_setoff_rtn->reference_external_number = $debtors_ledger_rtn->external_number;
                                $debtors_ledger_setoff_rtn->reference_document_number = $debtors_ledger_rtn->document_number;
                                $debtors_ledger_setoff_rtn->trans_date = $debtors_ledger_rtn->trans_date;
                                $debtors_ledger_setoff_rtn->description = $debtors_ledger_rtn->description;
                                $debtors_ledger_setoff_rtn->branch_id = $debtors_ledger_rtn->branch_id;
                                $debtors_ledger_setoff_rtn->customer_id = $debtors_ledger_rtn->customer_id;
                                $debtors_ledger_setoff_rtn->customer_code = $debtors_ledger_rtn->customer_code;
                                $debtors_ledger_setoff_rtn->amount = $debtors_ledger_rtn->amount;
                                $debtors_ledger_setoff_rtn->save();
                                    
                                
                            }

                 //debit note for the customer
                 if($request->input('pay_by_customer') == 1){
                    try {
                     //   DB::beginTransaction();
                        $referencenumber = $request->input('LblexternalNumber');
                        $bR_id = $request->input('cmbBranch');
            
                        $data = DB::table('branches')->where('branch_id', $bR_id)->get();
            
                        $EXPLODE_ID = explode("-", $referencenumber);
                        $externalNumber  = '';
                        if ($data->count() > 0) {
                            $documentPrefix = $data[0]->prefix;
                            $externalNumber  = $documentPrefix . "-" . $EXPLODE_ID[0] . "-" . $EXPLODE_ID[1];
                        }
            
                        $debit_note =  new debit_note();
                        $debit_note->internal_number = IntenelNumberController::getNextID();
                        $debit_note->external_number = $data[0]->prefix.'-'.$this->createExternal_number();
                        $debit_note->branch_id = $bR_id;
                        $debit_note->customer_id = $request->input('customerID');
                        $debit_note->employee_id = $request->input('cmbEmp');
                        $debit_note->amount = $chq_rturn->bank_charges;
                        $debit_note->trans_date = $request->input('returned_on');
                        $debit_note->narration_for_account = "Chque return charges";
                        $debit_note->description = "Chque return charges";
                        $debit_note->created_by = $chq_rturn->returned_by;
                        $debit_note->document_number = 1600;
                        $debit_note->cheque_returns_id = $chq_rturn->cheque_returns_id;
                        if ($debit_note->save()) {
                            $customer = Customer::find($debit_note->customer_id);
                            
                            $customer_name = $customer->customer_name;
                            $customer_code = $customer->customer_code;
            
                            $debtors_ledger = new DebtorsLedger();
                            $debtors_ledger->internal_number = $debit_note->internal_number;
                            $debtors_ledger->external_number = $debit_note->external_number;
                            $debtors_ledger->document_number = $debit_note->document_number;
                            $debtors_ledger->trans_date = $debit_note->trans_date;
                            $debtors_ledger->description = "Debit note for " . $customer_name;
                            $debtors_ledger->branch_id = $debit_note->branch_id;
                            $debtors_ledger->customer_id = $debit_note->customer_id;
                            $debtors_ledger->customer_code = $customer_code;
                            $debtors_ledger->amount = $debit_note->amount;
                            $debtors_ledger->employee_id = $debit_note->employee_id;
                          
                            if ($debtors_ledger->save()) {
                                $debtors_ledger_setoff = new DebtorsLedgerSetoff();
                                $debtors_ledger_setoff->internal_number = $debtors_ledger->internal_number;
                                $debtors_ledger_setoff->external_number = $debtors_ledger->external_number;
                                $debtors_ledger_setoff->document_number = $debtors_ledger->document_number;
                                $debtors_ledger_setoff->reference_internal_number = $debtors_ledger->internal_number;
                                $debtors_ledger_setoff->reference_external_number = $debtors_ledger->external_number;
                                $debtors_ledger_setoff->reference_document_number = $debtors_ledger->document_number;
                                $debtors_ledger_setoff->trans_date = $debtors_ledger->trans_date;
                                $debtors_ledger_setoff->description = $debtors_ledger->description;
                                $debtors_ledger_setoff->branch_id = $debtors_ledger->branch_id;
                                $debtors_ledger_setoff->customer_id = $debtors_ledger->customer_id;
                                $debtors_ledger_setoff->customer_code = $debtors_ledger->customer_code;
                                $debtors_ledger_setoff->amount = $debtors_ledger->amount;
                                $debtors_ledger_setoff->save();
                                    
                                
                            }
            
                            DB::commit();
                            return response()->json(["status" => true]);
                        }
                        
                    } catch (Exception $ex) {
                        DB::rollBack();
                        return $ex;
                    }
                 }

                /*  if($request->input('re_deposit') == 1){


                 } */
           }

           DB::commit();
           return response()->json(["status" => true]);
        }catch(Exception $ex){
            DB::rollBack();
            return $ex;
        }
    }



    public function createExternal_number()
    {
        /* $exter_num = $this->reference_number->CustomerReceipt_referenceID('customer_receipts', 500); */
        $exter_num = ReferenceIdController::newReferenceNumber_chqReturn_new('debit_notes', 1600);
       // dd($exter_num);
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

    public function load_data_through_chq_no($checkNo)
    {
        try {
            $qry = "SELECT CRC.customer_receipt_cheque_id,
            CRC.external_number,
            CRC.cheque_number,
            CRC.banking_date,
            CRC.cheque_deposit_date,
            CRC.amount,
            CR.receipt_date,
            CR.collector_id,
            CR.customer_id,
            C.customer_code,
            C.customer_name
     FROM customer_receipt_cheques CRC
     INNER JOIN customer_receipts CR ON CRC.customer_receipt_id = CR.customer_receipt_id
     INNER JOIN customers C ON CR.customer_id = C.customer_id
     WHERE CRC.cheque_number = $checkNo AND CRC.cheque_status = 1
     ORDER BY CRC.customer_receipt_cheque_id DESC 
     LIMIT 1;  
     ";
     $result = DB::select($qry);

     if ($result) {
        return response()->json(["status" => true, "data"=>$result]);
    } else {
        return response()->json(["status" => false, "data" => []]);
    }
           
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function cancel_return($id){
        try{
           
            $return = cheque_return::find($id);
            if($return->is_cancelled == 0){
                $return->is_cancelled = 1;
                $return->update();
                return response()->json(["status" => true]);
            }else{
                return response()->json(["msg"=>"used","status" => false]);
            }
            
           
        }catch(Exception $ex){

            return $ex;
        }
    }

    public function load_dishonoured_cheques_canceled(){
        try{
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
            cheque_dishonur_reasons CDR ON CR.cheque_dishonur_reason_id = CDR.cheque_dishonur_reason_id
        WHERE
            CR.is_cancelled = 1";
            $reuslt = DB::select($qry);
            if ($reuslt) {
                return response()->json(["status" => true, "data" => $reuslt]);
            } else {
                return response()->json(["status" => false, "data" => []]);
            }
            
            
           
        }catch(Exception $ex){

            return $ex;
        }
    }


    public function approve_return_cancelation($id,$type){
        try{
           
            $return = cheque_return::find($id);
            if($type == 1){
                if($return->is_cancelled == 1){
                    $debtors_ledger = DebtorsLedger::where("internal_number","=",$return->internal_number)->first();
                   //dd($debtors_ledger);
                    $debtors_ledger_setoff = DebtorsLedgerSetoff::where("internal_number","=",$return->internal_number)->first();
                   // dd($debtors_ledger->amount - $debtors_ledger->paidamount);
                    if(($debtors_ledger->amount - $debtors_ledger->paidamount) == $debtors_ledger->amount){
                        
                       $debtors_ledger->delete();
                       $debtors_ledger_setoff->delete();
                       $debit_n = debit_note::where("cheque_returns_id","=",$id)->first();
                       $debit_n->amount = 0;
                       $debit_n->update();
                       return response()->json(["status" => true]);
                    }
                    
                }else{
                   
                    return response()->json(["msg"=>"used","status" => false]);
                }
                

            }else{
                $return->is_cancelled = 0;
                $return->update();
                return response()->json(["msg"=>"rejected","status" => true]);
            }
            
           
        }catch(Exception $ex){

            return $ex;
        }
    }
}
