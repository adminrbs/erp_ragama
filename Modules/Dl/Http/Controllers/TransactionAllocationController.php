<?php

namespace Modules\Dl\Http\Controllers;

use App\Http\Controllers\IntenelNumberController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Dl\Entities\customer_transaction_alocation;

use Modules\Dl\Entities\customer_transaction_alocations_setoff;
use Modules\Dl\Entities\DebtorsLedger as EntitiesDebtorsLedger;
use Modules\Dl\Entities\DebtorsLedger;


class TransactionAllocationController extends Controller
{
    //load customer data
    public function load_customer_data($code)
    {
        try {
            $cus_id_qry = DB::select("SELECT C.customer_id FROM customers C WHERE C.customer_code = '$code'");

            $cus_id = $cus_id_qry[0]->customer_id;
            //header
            $cus_header_details = DB::select('SELECT
        C.customer_id,
        C.customer_name,
        C.primary_address,
        T.townName,
        R.route_name,
        COALESCE((SELECT SUM(DL.amount - DL.paidamount) FROM debtors_ledgers DL WHERE DL.customer_id = C.customer_id), 0) AS outstanding
    FROM
        customers C
    LEFT JOIN
        town_non_administratives T ON C.town = T.town_id
    LEFT JOIN
        routes R ON C.route_id = R.route_id
    
    WHERE C.customer_id = ' . $cus_id);

            //transaction table
            $transaction_data_qry = DB::select('SELECT
     DL.debtors_ledger_id,
     DL.trans_date,
     DL.external_number AS external_number,
     DL.amount,
     DL.paidamount,
     (DL.amount - DL.paidamount) AS balance,
     DL.branch_id,
     GD.use_for AS description
    
 FROM
     debtors_ledgers DL
 LEFT JOIN global_documents GD ON DL.document_number = GD.document_number
 WHERE
     DL.amount > 0 AND DL.amount > DL.paidamount AND DL.customer_id = ' . $cus_id);

//set off
            $set_off_data = DB::select('SELECT
     DL.debtors_ledger_id,
     DL.trans_date,
     IFNULL(DL.manual_number, DL.external_number) AS external_number,
     DL.amount,
     (DL.amount - DL.paidamount) AS balance,
     GD.use_for AS description
  
 FROM
     debtors_ledgers DL
     LEFT JOIN global_documents GD ON DL.document_number = GD.document_number
 
 WHERE
     DL.amount < 0 AND DL.amount < DL.paidamount AND DL.customer_id =' . $cus_id);
            return response()->json(["header" => $cus_header_details, "transaction" => $transaction_data_qry, "set_off_Data" => $set_off_data]);
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //save transaction allocation
    public function save_customer_transaction_allocation(Request $request,$br_id)
    {

        try {
            $collection = json_decode($request->input('collection'));
           // dd($collection);
            $referencenumber = $request->input('LblexternalNumber');
            $customer_id = $request->input('customer_id');
            //need to get session branch
            $data = DB::table('branches')->where('branch_id', $br_id)->get();

                $EXPLODE_ID = explode("-", $referencenumber);
                $externalNumber  = '';
                if ($data->count() > 0) {
                    $documentPrefix = $data[0]->prefix;
                    $externalNumber  = $documentPrefix . "-" . $EXPLODE_ID[0] . "-" . $EXPLODE_ID[1];
                }
                $total_amount = 0;
                foreach ($collection as $data) {
                    $total_amount = floatval($total_amount) + floatval($data->set_off_amount);
                    $set_off_dl_id = $data->dl_set_off_id;
                    $qry = DB::select('SELECT (DL.amount - DL.paidamount) AS balance FROM debtors_ledgers DL WHERE DL.debtors_ledger_id ='.$set_off_dl_id);
                    $balance = $qry[0]->balance;
                   // dd(abs(floatval($balance)));
                    if(floatval($data->set_off_amount) > abs(floatval($balance))){
                        return response()->json(["status" => false,"msg" => 'insuf']);
                    }

                }
                

                $customer_transaction_allocation = new customer_transaction_alocation();
                $customer_transaction_allocation->internal_number = IntenelNumberController::getNextID();
                $customer_transaction_allocation->external_number = $externalNumber;
                $customer_transaction_allocation->document_number = 1400;
                $customer_transaction_allocation->amount = $total_amount;
                $customer_transaction_allocation->customer_id = $customer_id;
                $customer_transaction_allocation->branch_id = $br_id;
                $customer_transaction_allocation->created_by = Auth::user()->id;
                $customer_transaction_allocation->save();

            foreach ($collection as $data) {
               // $bR_id = $data->branch_id;
                $setoff_id = $data->dl_set_off_id;
                $dl_id = $data->dl_id;
                $set_off_amount = $data->set_off_amount;
               // dd($setoff_id);
               $reference_data = DB::select('SELECT external_number,internal_number,document_number FROM debtors_ledgers WHERE debtors_ledgers.debtors_ledger_id ='.$setoff_id);

                $customer_transaction_allocation_setoff = new customer_transaction_alocations_setoff();
                $customer_transaction_allocation_setoff->debtor_ledger_id = $dl_id;
                $customer_transaction_allocation_setoff->customer_transaction_alocation_id =  $customer_transaction_allocation->customer_transaction_alocation_id;
                $customer_transaction_allocation_setoff->reference_internal_number = $reference_data[0]->internal_number;
                $customer_transaction_allocation_setoff->reference_external_number = $reference_data[0]->external_number;
                $customer_transaction_allocation_setoff->reference_document_number = $reference_data[0]->document_number;
                $customer_transaction_allocation_setoff->reference_debtor_ledger_id = $setoff_id;
                $customer_transaction_allocation_setoff->set_off_amount = $set_off_amount;
                $customer_transaction_allocation_setoff->save();

                //updating dl amount
                $debtor_ledger = DebtorsLedger::find($customer_transaction_allocation_setoff->debtor_ledger_id);
                $debtor_ledger->paidamount = floatval($debtor_ledger->paidamount) + floatval($set_off_amount);
                $debtor_ledger->update();


                //updating reference dl paid amount
                $debtor_ledger_setoff_record = DebtorsLedger::find($customer_transaction_allocation_setoff->reference_debtor_ledger_id);
                $debtor_ledger_setoff_record->paidamount = floatval($debtor_ledger_setoff_record->paidamount) + -floatval($set_off_amount);
                $debtor_ledger_setoff_record->update();

            }

            return response()->json(["status" => true,"message"=>"success"]);

        } catch (Exception $ex) {
            return $ex;
        }
    }

    //load transaction allocation data to list
    public function get_transaction_allocation_details(){
        try{
            $qry = DB::select('SELECT
            CTA.customer_transaction_alocation_id,
            CTA.external_number,
            CTA.amount,
            DATE(CTA.created_at) AS created_date,
            E.employee_name,
            B.branch_name,
            CUS.customer_code,
            CUS.customer_name
        FROM
            customer_transaction_alocations AS CTA
            INNER JOIN customers CUS ON CTA.customer_id = CUS.customer_id
            LEFT JOIN branches B ON CTA.branch_id = B.branch_id
           LEFT JOIN users U ON CTA.created_by = U.id
           LEFT JOIN employees E ON U.user_id = E.employee_id
        ');
            if($qry){
                return response()->json(["status" => true,"data"=>$qry]);
            }else{
                return response()->json(["status" => true,"data"=>[]]);
            }
        }catch(Exception $ex){
            return $ex;
        }
    }


    //load info
    public function load_info($id){
        try{
            $qry = DB::select("SELECT IFNULL(DL.manual_number, CS.reference_external_number) AS reference_external_number, CS.set_off_amount
            FROM customer_transaction_alocations_setoffs CS
            INNER JOIN debtors_ledgers DL ON CS.reference_debtor_ledger_id = DL.debtors_ledger_id
            WHERE CS.customer_transaction_alocation_id = ".$id
            );
            if($qry){
                return response()->json(["status" => true,"data"=>$qry]);
            }else{
                return response()->json(["status" => true,"data"=>[]]);
            }
        }catch(Exception $ex){
            return $ex;
        }

    }
}