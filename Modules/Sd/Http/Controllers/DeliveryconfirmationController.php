<?php

namespace Modules\Sd\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Sd\Entities\Deliveryconfirmation;


class DeliveryconfirmationController extends Controller
{
    //get delivery confirmation data to data table (list)
    public function getDeliveryConfirmationData($id)
    {
        try {
            $baseQuery = "SELECT 
                sales_invoices.sales_invoice_Id,
                sales_invoices.external_number AS external_number,
                sales_invoices.order_date_time,
                sales_invoices.total_amount,
                CASE 
                    WHEN sales_invoices.picking_list_id IS NULL THEN ''
                    ELSE sales_invoices.picking_list_id 
                END AS picking_list_id,
                sales_orders.external_number AS SO_external_number,
                SI_user.name AS SI_user,
                SO_user.name AS SO_user,
                customers.customer_name,
                employees.employee_name,
                routes.route_name,
                deliveryconfirmations.delivered,
                deliveryconfirmations.Seal,
                deliveryconfirmations.Signature,
                deliveryconfirmations.Cash,
                deliveryconfirmations.Cheque,
                deliveryconfirmations.noSeal,
                deliveryconfirmations.cancel
            FROM sales_invoices
            LEFT JOIN customers ON sales_invoices.customer_id = customers.customer_id
            LEFT JOIN employees ON sales_invoices.employee_id = employees.employee_id
            LEFT JOIN routes ON customers.route_id = routes.route_id
            LEFT JOIN sales_orders ON sales_invoices.sales_order_Id = sales_orders.sales_order_Id
            LEFT JOIN users AS SI_user ON sales_invoices.prepaired_by = SI_user.id
            LEFT JOIN users AS SO_user ON sales_orders.prepaired_by = SO_user.id
            LEFT JOIN deliveryconfirmations ON sales_invoices.sales_invoice_Id = deliveryconfirmations.sales_invoice_Id WHERE (deliveryconfirmations.status = 0 OR deliveryconfirmations.sales_invoice_Id IS NULL)";
    
            if ($id == 0) {
               // $query = $baseQuery . "OR deliveryconfirmations.sales_invoice_Id IS NULL";
                $result = DB::select($baseQuery);
                return response()->json((['success' => 'Data loaded', 'data' => $result]));
            } else if($id > 0) {
                $query = $baseQuery . " AND sales_invoices.delivery_plan_id =".$id;
                
                $result = DB::select($query);
                return response()->json((['success' => 'Data loaded', 'data' => $result]));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }
    


    //add delivery confirmation or update
    public function addDeliveryConfirmation(Request $request, $id)
    {
        try {

            /*   $checkboxValues = json_decode($request->input('check_box_values')); */


            $existingCount = DB::table('deliveryconfirmations')
                ->where('sales_invoice_Id', $id)
                ->count();

            if ($existingCount <= 0) {

                $delivery_confrimation = new deliveryconfirmation();
                $delivery_confrimation->sales_invoice_Id = $id;
                $delivery_confrimation->delivered = $request->input('deliver');
                $delivery_confrimation->Seal = $request->input('seal');
                $delivery_confrimation->Signature = $request->input('signature');
                $delivery_confrimation->Cash = $request->input('cash');
                $delivery_confrimation->Cheque = $request->input('cheque');
                $delivery_confrimation->noSeal = $request->input('noSeal');
                $delivery_confrimation->cancel = $request->input('cancel');
                $delivery_confrimation->created_by = Auth::user()->id;
                $delivery_confrimation->received = $request->input('received');
                $delivery_confrimation->save();

            } else if ($existingCount > 0) {

                $record = deliveryconfirmation::where("sales_invoice_Id", "=", $id)->get();
                $primaryID = $record[0]->deliveryconfirmations_id;
                $delivery_confrimation = deliveryconfirmation::find($primaryID);
                $delivery_confrimation->sales_invoice_Id = $id;
                $delivery_confrimation->delivered = $request->input('deliver');
                $delivery_confrimation->Seal = $request->input('seal');
                $delivery_confrimation->Signature = $request->input('signature');
                $delivery_confrimation->Cash = $request->input('cash');
                $delivery_confrimation->Cheque = $request->input('cheque');
                $delivery_confrimation->noSeal = $request->input('noSeal');
                $delivery_confrimation->cancel = $request->input('cancel');
                $delivery_confrimation->created_by = Auth::user()->id;
                $delivery_confrimation->received = $request->input('received');
                $delivery_confrimation->update();
            }


            return response()->json(['message' => 'Delivery confirmation added successfully', 'status' => true]);
        } catch (Exception $ex) {

            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }


    //load delivery confirmation
    public function loadDeliveryPlans()
    {
        try {
            $deliveryPlans = DB::select("SELECT DISTINCT DP.delivery_plan_id, DP.external_number
            FROM delivery_plans DP
            INNER JOIN sales_invoices SI ON DP.delivery_plan_id = SI.delivery_plan_id
            WHERE SI.sales_invoice_Id NOT IN (SELECT sales_invoice_Id FROM deliveryconfirmations);
           
            ");
           // $deliveryPlans = DeliveryPlan::all();
            if ($deliveryPlans) {
                return response()->json($deliveryPlans);
            } else {
                return response()->json(['status' => false]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function confirm_all(Request $request){
        try {
            $id_array = json_decode($request->input('confirm_data_array'));
    
           
            foreach ($id_array as $id) {
                deliveryconfirmation::where('sales_invoice_Id', '=', $id)
                    ->update(['status' => 1]);
            }
    
            return response()->json(['status' => true]);
    
        } catch (Exception $ex) {
           
            return response()->json(['status' => false, 'error' => $ex->getMessage()]);
        }
    }

   public function deleteDeliveryConfirmationRecord($id){
    try {
        deliveryconfirmation::where("sales_invoice_Id", "=", $id)->delete();
        return response()->json(['status' => true]);

    } catch (Exception $ex) {
       
        return response()->json(['status' => false, 'error' => $ex->getMessage()]);
    }

   } 
    
}
