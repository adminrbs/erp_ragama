<?php

namespace Modules\Sl\Http\Controllers;

use App\Http\Controllers\IntenelNumberController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Prc\Entities\creditors_ledger;
use Modules\Sl\Entities\creditor_ledger_setoff;
use Modules\Sl\Entities\supplier_transaction_alocation;
use Modules\Sl\Entities\supplier_transaction_alocations_setoff;

class SupplierTransactionAllocationController extends Controller
{
    //load supplier data
    public function load_supplier_data($code)
    {
        try {
            $sup_id_qry = DB::select("SELECT S.supplier_id FROM suppliers S WHERE S.supplier_code = '$code'");

            $sup_id = $sup_id_qry[0]->supplier_id;

            $sup_header_details = DB::select('SELECT
            S.supplier_id,
            S.supplier_name,
            S.primary_address,
            COALESCE((SELECT SUM(CL.amount - CL.paidamount) FROM creditors_ledger CL WHERE CL.supplier_id = S.supplier_id), 0) AS outstanding
        FROM
        suppliers S
        WHERE S.supplier_id = ' . $sup_id);





            //transaction table
            $transaction_data_qry = DB::select('SELECT
            CL.creditors_ledger_id,
            CL.trans_date,
            CL.external_number,
            CL.amount,
            CL.paidamount,
            (CL.amount - CL.paidamount) AS balance,
            CL.branch_id,
            GD.use_for AS description

            FROM
            creditors_ledger CL
            LEFT JOIN global_documents GD ON CL.document_number = GD.document_number
            WHERE
            CL.amount < 0 AND CL.amount <> CL.paidamount AND CL.supplier_id = ' . $sup_id);


            //set off
            $set_off_data = DB::select('SELECT
            CL.creditors_ledger_id,
            CL.trans_date,
            CL.external_number,
            CL.amount,
            (CL.amount - CL.paidamount) AS balance,
            GD.use_for AS description
  
        FROM
            creditors_ledger CL
                LEFT JOIN global_documents GD ON CL.document_number = GD.document_number
 
        WHERE
            CL.amount > 0 AND CL.amount > CL.paidamount AND CL.supplier_id =' . $sup_id);

            return response()->json(["header" => $sup_header_details, "transaction" => $transaction_data_qry, "set_off_data"=>$set_off_data]);
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //save transaction allocation
    public function save_supplier_transaction_allocation(Request $request,$br_id)
    {

        try {
            DB::beginTransaction();
            $collection = json_decode($request->input('collection'));
           // dd($collection);
            $referencenumber = $request->input('LblexternalNumber');
            $supplier_id = $request->input('supplier_id');
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
                    $set_off_cl_id = $data->cl_set_off_id;
                    $qry = DB::select('SELECT (CL.amount - CL.paidamount) AS balance FROM creditors_ledger CL WHERE CL.creditors_ledger_id ='.$set_off_cl_id);
                    $balance = $qry[0]->balance;
                   // dd(abs(floatval($balance)));
                    if(floatval($data->set_off_amount) > abs(floatval($balance))){
                        return response()->json(["status" => false,"msg" => 'insuf']);
                    }

                }
                

                $supplier_transaction_allocation = new supplier_transaction_alocation();
                $supplier_transaction_allocation->internal_number = IntenelNumberController::getNextID();
                $supplier_transaction_allocation->external_number = $externalNumber;
                $supplier_transaction_allocation->document_number = 2600;
                $supplier_transaction_allocation->amount = $total_amount;
                $supplier_transaction_allocation->supplier_id = $supplier_id;
                $supplier_transaction_allocation->branch_id = $br_id;
                $supplier_transaction_allocation->created_by = Auth::user()->id;
                $supplier_transaction_allocation->save();

            foreach ($collection as $data) {
               // $bR_id = $data->branch_id;
                $setoff_id = $data->cl_set_off_id;
                $cl_id = $data->cl_id;
                $set_off_amount = $data->set_off_amount;
               // dd($setoff_id);
               $reference_data = DB::select('SELECT external_number,internal_number,document_number FROM creditors_ledger WHERE creditors_ledger.creditors_ledger_id ='.$setoff_id);

                $supplier_transaction_allocation_setoff = new supplier_transaction_alocations_setoff();
                $supplier_transaction_allocation_setoff->creditor_ledger_id = $cl_id;
                $supplier_transaction_allocation_setoff->supplier_transaction_alocation_id =  $supplier_transaction_allocation->supplier_transaction_alocation_id;
                $supplier_transaction_allocation_setoff->reference_internal_number = $reference_data[0]->internal_number;
                $supplier_transaction_allocation_setoff->reference_external_number = $reference_data[0]->external_number;
                $supplier_transaction_allocation_setoff->reference_document_number = $reference_data[0]->document_number;
                $supplier_transaction_allocation_setoff->reference_creditor_ledger_id = $setoff_id;
                $supplier_transaction_allocation_setoff->set_off_amount = $set_off_amount;
                $supplier_transaction_allocation_setoff->save();

                //updating cl amount
                $creditor_ledger = creditors_ledger::find($supplier_transaction_allocation_setoff->creditor_ledger_id);
                $creditor_ledger->paidamount = floatval($creditor_ledger->paidamount) + floatval($set_off_amount);
                $creditor_ledger->update();

                
                //updating reference cl paid amount
                $creditor_ledger_setoff_record = creditors_ledger::find($supplier_transaction_allocation_setoff->reference_creditor_ledger_id);
                //dd($supplier_transaction_allocation_setoff->reference_creditor_ledger_id);
                $creditor_ledger_setoff_record->paidamount = floatval($creditor_ledger_setoff_record->paidamount) + -floatval($set_off_amount);
                $creditor_ledger_setoff_record->update();

            }
            DB::commit();
            return response()->json(["status" => true,"message"=>"success"]);

        } catch (Exception $ex) {
            DB::rollback();
            return $ex;
        }
    }


    public function get_transaction_allocation_details(){
        try{
            $qry = DB::select('SELECT
            CTA.supplier_transaction_alocation_id,
            CTA.external_number,
            CTA.amount,
            DATE(CTA.created_at) AS created_date,
            E.employee_name,
            B.branch_name,
            CUS.supplier_code,
            CUS.supplier_name
        FROM
            supplier_transaction_alocations AS CTA
            INNER JOIN suppliers CUS ON CTA.supplier_id = CUS.supplier_id
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

    public function load_info($id){
        try{
            $qry = DB::select("SELECT IFNULL(CL.external_number, CS.reference_external_number) AS reference_external_number, CS.set_off_amount
            FROM supplier_transaction_alocations_setoffs CS
            INNER JOIN creditors_ledger CL ON CS.reference_creditor_ledger_id = CL.creditors_ledger_id
            WHERE CS.supplier_transaction_alocation_id = ".$id);
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
