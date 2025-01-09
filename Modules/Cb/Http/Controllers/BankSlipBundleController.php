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
use Modules\Cb\Entities\DirectSlipBundle;
use Modules\Cb\Entities\DirectSlipBundleData;

class BankSlipBundleController extends Controller
{
   public function load_direct_bank_slips_to_create_to_bundle($br_id){
    try {
        $qry = "SELECT
CR.customer_receipt_id,
CR.receipt_date,
CR.external_number,
C.customer_name,
CR.amount,
B.branch_name,
E.employee_name as name,
E_collectors.employee_name AS collector 
FROM
customer_receipts CR
LEFT JOIN branches B ON CR.branch_id = B.branch_id
LEFT JOIN employees E ON CR.cashier_id = E.employee_id
LEFT JOIN employees E_collectors ON CR.collector_id = E_collectors.employee_id
LEFT JOIN customers C ON CR.customer_id = C.customer_id
WHERE
CR.receipt_method_id = 7 
AND CR.is_direct_receipt = 1 
AND CR.is_direct_receipt_collected = 0"; // 0 is still not bundle created, 1 is bundle created, 2 ho recieved

        if ($br_id > 0) {
            $qry .= " AND CR.branch_id = $br_id";
        }
        //dd($qry);
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


   public function create_direct_slip_bundle(Request $request){
    DB::begintransaction();
    try {
        DB::beginTransaction();
            $cash_id_array = json_decode($request->get('slip_id_array'));
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

        $slip_bundle = new DirectSlipBundle();
        $slip_bundle->internal_number = IntenelNumberController::getNextID();
        $slip_bundle->external_number = $externalNumber;
        $slip_bundle->document_number = 2800;
        $slip_bundle->branch_id = $bR_id ;
        //$slip_bundle->book_id = $book_id;
        //$slip_bundle->page_no = $page_no;

        if ($slip_bundle->save()) {
            foreach ($cash_id_array as $id) {
                //dd($cash_id_array);
                $customerReceipt = CustomerReceipt::find($id);

                $bundle_data = new DirectSlipBundleData();
                $bundle_data->direct_slip_bundles_id = $slip_bundle->direct_slip_bundles_id;
                $bundle_data->internal_number = $slip_bundle->internal_number;
                $bundle_data->external_number = $slip_bundle->external_number;
                $bundle_data->branch_id = $slip_bundle->$bR_id;
                $bundle_data->customer_receipt_id = $id;
                $bundle_data->amount = $customerReceipt->amount;
                $bundle_data->cashier_id = $customerReceipt->cashier_id;
                $bundle_data->collector_id = $customerReceipt->collector_id;
                $bundle_data->slip_bundle_date = date('Y-m-d H:i:s');
                if ($bundle_data->save()) {
                    $customerReceipt->is_direct_receipt_collected = 1; 
                    $customerReceipt->update();
                }
            }
            DB::commit();
            return response()->json(["status" => true, "message" => 'success']);
        }
    } catch (Exception $ex) {
        DB::rollback();
        return $ex;
    }
   }


   public function load_direct_slip_bundles($br_id){
    $qry = "SELECT
	DCB.direct_slip_bundles_id,
	DCB.external_number,
	DCB.trans_date,
	SUM( DCBD.amount ) AS amount,
	B.branch_name,
	(
	SELECT
		E.employee_name 
	FROM
		direct_slip_bundle_datas DCBData
		LEFT JOIN employees E ON DCBData.collector_id = E.employee_id 
	WHERE
		DCBData.direct_slip_bundles_id = DCB.direct_slip_bundles_id 
		LIMIT 1 
	) AS collector 
FROM
	direct_slip_bundles DCB
	LEFT JOIN direct_slip_bundle_datas DCBD ON DCBD.direct_slip_bundles_id = DCB.direct_slip_bundles_id
	LEFT JOIN branches B ON DCB.branch_id = B.branch_id 
WHERE
	DCB.ho_Received = 0";


        if ($br_id > 0) {

            $qry .= " AND DCB.branch_id = $br_id ";
        }
        $qry .= " GROUP BY  DCB.direct_slip_bundles_id, 
                    DCB.external_number, 
                    DCB.trans_date 
                     ORDER BY trans_date ASC";
         //dd($qry);
        $result = DB::select($qry);
        if ($result) {
            return response()->json(["status" => true, "data" => $result]);
        } else {
            return response()->json(["status" => false, "data" => []]);
        }
   }
}
