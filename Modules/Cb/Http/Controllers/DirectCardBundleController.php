<?php

namespace Modules\Cb\Http\Controllers;

use App\Http\Controllers\IntenelNumberController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Cb\Entities\CustomerReceipt;
use Modules\Cb\Entities\DirectCardBundle;
use Modules\Cb\Entities\DirectCardBundleData;

class DirectCardBundleController extends Controller
{
    public function  load_direct_credit_card_to_create_to_bundle($br_id){
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
    CR.receipt_method_id = 8 
    AND CR.is_direct_receipt = 1 
    AND CR.is_direct_receipt_collected = 0"; // 0 is still not bundle created, 1 is bundle created, 2 ho received
    
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


    public function create_direct_card_bundle(Request $request){
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
    
            $card_bundle = new DirectCardBundle();
            $card_bundle->internal_number = IntenelNumberController::getNextID();
            $card_bundle->external_number = $externalNumber;
            $card_bundle->document_number = 2800;
            $card_bundle->branch_id = $bR_id ;
            
    
            if ($card_bundle->save()) {
                foreach ($cash_id_array as $id) {
                    //dd($cash_id_array);
                    $customerReceipt = CustomerReceipt::find($id);
    
                    $bundle_data = new DirectCardBundleData();
                    $bundle_data->direct_card_bundles_id = $card_bundle->direct_card_bundles_id;
                    $bundle_data->internal_number = $card_bundle->internal_number;
                    $bundle_data->external_number = $card_bundle->external_number;
                    $bundle_data->branch_id = $card_bundle->$bR_id;
                    $bundle_data->customer_receipt_id = $id;
                    $bundle_data->amount = $customerReceipt->amount;
                    $bundle_data->cashier_id = $customerReceipt->cashier_id;
                    $bundle_data->collector_id = $customerReceipt->collector_id;
                    $bundle_data->card_bundle_date = date('Y-m-d H:i:s');
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
    
    
}
