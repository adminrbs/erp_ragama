<?php

namespace Modules\Cb\Http\Controllers;

use App\Http\Controllers\IntenelNumberController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Cb\Entities\CustomerReceipt;
use Modules\Cb\Entities\DirectChequeCollection;

class DirectChequeController extends Controller
{
    //load direct receipt
    public function load_direct_cheque_create_to_bundle($br_id)
    {
        try {
            $qry = "SELECT
	CR.customer_receipt_id,
	CR.receipt_date,
	CR.external_number,
	CR.amount,
	B.branch_name,
	E.employee_name AS name,
	CRC.cheque_number,
	BK.bank_name,
	BK.bank_code,
	BB.bank_branch_name 
FROM
	customer_receipts CR
	INNER JOIN branches B ON CR.branch_id = B.branch_id
	INNER JOIN employees E ON CR.cashier_id = E.employee_id
	LEFT JOIN customer_receipt_cheques CRC ON CR.customer_receipt_id = CRC.customer_receipt_id
	LEFT JOIN banks BK ON CRC.bank_id = BK.bank_id
	LEFT JOIN bank_branches BB ON CRC.bank_branch_id = BB.bank_branch_id
     
WHERE
	CR.receipt_method_id = 2 
	AND CR.is_direct_receipt = 1 
	AND CR.is_direct_receipt_collected = 0"; // 0 is still not bundle created, 1 is bundle created, 2 ho recieved

            if ($br_id > 0) {
                $qry .= " AND CR.branch_id = $br_id";
            }
            // dd($qry);
            $result = DB::select($qry);

            if ($result) {
                return response()->json(["status" => true, "data" => $result]);
            } else {
                return response()->json(["status" => false, "data" => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //save direct cheque bundle
    public function create_direct_cheque_bundle(Request $request)
    {
        try {
            DB::beginTransaction();
            $cheque_id_array = json_decode($request->get('cheque_id_array'));
            $referencenumber = $request->input('LblexternalNumber');
            //dd($referencenumber);
            $bR_id = $request->input('br_id');

            $data = DB::table('branches')->where('branch_id', $bR_id)->get();
            //dd($data);
            $EXPLODE_ID = explode("-", $referencenumber);
            //dd($EXPLODE_ID[1]);
            $externalNumber  = '';
            if ($data->count() > 0) {
                $documentPrefix = $data[0]->prefix;
                $externalNumber  = $documentPrefix . "-" . $EXPLODE_ID[0] . "-" . $EXPLODE_ID[1];
            }

            $cheque_collection =  new DirectChequeCollection();
            $cheque_collection->internal_number = IntenelNumberController::getNextID();
            $cheque_collection->external_number = $externalNumber;
            $cheque_collection->document_number = 900;
            $cheque_collection->trans_date = date('Y-m-d H:i:s');
            $cheque_collection->branch_id = $bR_id;
            $cheque_collection->created_by = Auth::user()->id;
            if ($cheque_collection->save()) {
                foreach ($cheque_id_array as $id) {
                    //dd($cash_id_array);
                    $customerReceipt = CustomerReceipt::find($id);
                    $customerReceipt->is_direct_receipt_collected = 1; // direct cash/cheque bundle created
                    $customerReceipt->cheque_collection_id = $cheque_collection->direct_cheque_collection_id;
                    $customerReceipt->update();
                }
            }



            DB::commit();
            return response()->json(["status" => true]);
        } catch (Exception $ex) {
            DB::rollBack();
            return $ex;
        }
    }

    //load direct cheque collection
    public function load_direct_cheque_collection($br_id)
    {
        $qry = "SELECT
	DCC.direct_cheque_collection_id,
	DCC.external_number,
	DCC.trans_date,
	SUM( CR.amount ) AS amount,
	B.branch_name,
    E.employee_name 
FROM
	direct_cheque_collections DCC
INNER JOIN customer_receipts CR ON DCC.direct_cheque_collection_id = CR.cheque_collection_id
INNER JOIN branches B ON DCC.branch_id = B.branch_id
INNER JOIN employees E ON CR.collector_id = E.employee_id 
WHERE
	DCC.ho_Received = 0";


        if ($br_id > 0) {

            $qry .= " AND DCC.branch_id = $br_id ";
        }
        $qry .= " GROUP BY direct_cheque_collection_id ORDER BY trans_date ASC";
        // dd($qry);
        $result = DB::select($qry);
        if ($result) {
            return response()->json(["status" => true, "data" => $result]);
        } else {
            return response()->json(["status" => false, "data" => []]);
        }
    }

    //load receipts to model
    public function loadDirectChequeReciptsToModal($id){
        $qry = "SELECT
	CR.amount,
	CR.external_number,
	CR.receipt_date 
FROM
	direct_cheque_collections DCC
	INNER JOIN customer_receipts CR ON DCC.direct_cheque_collection_id = CR.cheque_collection_id 
WHERE
	DCC.direct_cheque_collection_id = $id";

        $result =  DB::select($qry);
        if ($result) {
            return response()->json(["status" => true, "data" => $result]);
        } else {
            return response()->json(["status" => false, "data" => []]);
        }
    }

    //receive direct check collection ho
    public function received_direct_cheque_collection_head_office(Request $request)
    {
        DB::beginTransaction();
        try {

            $checkedIds = $request->input('checkedIds');
            foreach ($checkedIds as $id) {
                $bundle = DirectChequeCollection::find($id);
                $bundle->ho_Received = 1;
                if ($bundle->update()) {
                    $receipt_data = CustomerReceipt::where("cheque_collection_id", "=", $id)->get();
                    if ($receipt_data) {
                        foreach ($receipt_data as $bd) {
                            $customerReceipt = CustomerReceipt::find($bd->customer_receipt_id);
                            $customerReceipt->is_direct_receipt_collected = 2;
                            $customerReceipt->update();
                        }
                    }
                }
            }
            DB::commit();
            return response()->json(["status"=>true]);
        } catch (Exception $ex) {
            DB::rollBack();
            return $ex;
        }
    }
}
