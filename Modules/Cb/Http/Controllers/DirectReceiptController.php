<?php

namespace Modules\Cb\Http\Controllers;

use App\Http\Controllers\IntenelNumberController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Cb\Entities\CustomerReceipt;
use Modules\Cb\Entities\DirectCashBundle;
use Modules\Cb\Entities\DirectCashBundleData;

class DirectReceiptController extends Controller
{
    //load direct receipt
    public function load_direct_cash_create_to_bundle($br_id)
    {
        try {
            $qry = "SELECT
    CR.customer_receipt_id,
	CR.receipt_date,
	CR.external_number,
    C.customer_name,
	CR.amount,
	B.branch_name,
    E.employee_name as name 
FROM
	customer_receipts CR
	LEFT JOIN branches B ON CR.branch_id = B.branch_id
	LEFT JOIN employees E ON CR.cashier_id = E.employee_id
    LEFT JOIN customers C ON CR.customer_id = C.customer_id
WHERE
	CR.receipt_method_id = 1 
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


    //update direct cash ho collected status
    public function create_direct_cash_bundle(Request $request)
    {
        try {
            DB::beginTransaction();
            $cash_id_array = json_decode($request->get('cash_id_array'));
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

            $cash_bundle =  new DirectCashBundle();
            $cash_bundle->internal_number = IntenelNumberController::getNextID();
            $cash_bundle->external_number = $externalNumber;
            $cash_bundle->document_number = 900;
            $cash_bundle->trans_date = date('Y-m-d H:i:s');
            $cash_bundle->branch_id = $bR_id;
            if ($cash_bundle->save()) {
                foreach ($cash_id_array as $id) {
                    //dd($cash_id_array);
                    $customerReceipt = CustomerReceipt::find($id);

                    $bundle_data = new DirectCashBundleData();
                    $bundle_data->direct_cash_bundles_id = $cash_bundle->direct_cash_bundle_id;
                    $bundle_data->internal_number = $cash_bundle->internal_number;
                    $bundle_data->external_number = $cash_bundle->external_number;
                    $bundle_data->branch_id = $cash_bundle->$bR_id;
                    $bundle_data->customer_receipt_id = $id;
                    $bundle_data->amount = $customerReceipt->amount;
                    $bundle_data->cashier_id = $customerReceipt->cashier_id;
                    $bundle_data->collector_id = $customerReceipt->collector_id;
                    $bundle_data->cash_bundle_date = date('Y-m-d H:i:s');
                    if ($bundle_data->save()) {
                        $customerReceipt->is_direct_receipt_collected = 1; // direct cash bundle created
                        $customerReceipt->update();
                    }
                }
            }



            DB::commit();
            return response()->json(["status" => true]);
        } catch (Exception $ex) {
            DB::rollBack();
            return $ex;
        }
    }

    //load cash bundles
    public function load_direct_cash_bundles($br_id)
    {
        $qry = "SELECT DCB.direct_cash_bundle_id,DCB.external_number, 
        DCB.trans_date,SUM(DCBD.amount) AS amount,B.branch_name FROM direct_cash_bundles DCB 
        LEFT JOIN direct_cash_bundle_datas DCBD ON DCBD.direct_cash_bundles_id = DCB.direct_cash_bundle_id 
			LEFT JOIN branches B ON DCB.branch_id = B.branch_id WHERE ho_Received = 0";


        if ($br_id > 0) {

            $qry .= " AND DCB.branch_id = $br_id ";
        }
        $qry .= " GROUP BY direct_cash_bundle_id ORDER BY trans_date ASC";
        // dd($qry);
        $result = DB::select($qry);
        if ($result) {
            return response()->json(["status" => true, "data" => $result]);
        } else {
            return response()->json(["status" => false, "data" => []]);
        }
    }

    //load direct receipts to the modal
    public function loadDirectReciptsToModal($id)
    {
        $qry = "SELECT DCBD.amount, CR.external_number,CR.receipt_date FROM 
        direct_cash_bundle_datas DCBD LEFT JOIN customer_receipts CR 
        ON DCBD.customer_receipt_id = CR.customer_receipt_id 
        WHERE DCBD.direct_cash_bundles_id = $id";

        $result =  DB::select($qry);
        if ($result) {
            return response()->json(["status" => true, "data" => $result]);
        } else {
            return response()->json(["status" => false, "data" => []]);
        }
    }


    public function received_direct_Bundle_head_office(Request $request)
    {
        DB::beginTransaction();
        try {

            $checkedIds = $request->input('checkedIds');
            foreach ($checkedIds as $id) {
                $bundle = DirectCashBundle::find($id);
                $bundle->ho_Received = 1;
                if ($bundle->update()) {
                    $bundle_data = DirectCashBundleData::where("direct_cash_bundles_id", "=", $id)->get();
                    if ($bundle_data) {
                        foreach ($bundle_data as $bd) {
                            //dd($bd);
                            $customerReceipt = CustomerReceipt::find($bd->customer_receipt_id);
                            //dd($customerReceipt);
                            $customerReceipt->is_direct_receipt_collected = 2;
                            $customerReceipt->update();
                        }
                    }
                }
            }
            DB::commit();
            return response()->json(["status" => true]);
        } catch (Exception $ex) {
            DB::rollBack();
            return $ex;
        }
    }
}
