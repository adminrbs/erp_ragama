<?php

namespace Modules\Sc\Http\Controllers;

use App\Http\Controllers\IntenelNumberController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Sc\Entities\item_history;
use Modules\Prc\Entities\item_history_setOff;
use Modules\Sc\Entities\internal_order;
use Modules\Sc\Entities\sample_dispatch;
use Modules\Sc\Entities\sample_dispatch_items;
use Modules\Sc\Entities\sample_dispatch_items_setoff;

class SampleDispatchController extends Controller
{
    //add sample dispatch
    public function addSampleDispatch(Request $request,$order_id){
        $status = true;
        try {
            
        
            $collection = json_decode($request->input('collection'));
            $setOffArray = json_decode($request->input('setOffArray'));
            $branch_id_ = $request->input('cmbBranch');
            $location_id = $request->input('cmbLocation');
            $wholeSalePrice = 0;
            $retail_price = 0;
            $cost_price = 0;
            $sales_order_id = 0;

            //validate set off array

            foreach ($setOffArray as $i) {
                $item = json_decode($i);
                $itemID = $item->item_id;
                $setOffqty = $item->setoff_quantity;
                $wholeSalePrice = $item->wholesale_price;
                $retail_price = $item->retail_price;
                $cost_price = $item->cost_price;
                $query = "SELECT IF(ISNULL(SUM(quantity-setoff_quantity)), 0, SUM(quantity-setoff_quantity)) AS balance 
                FROM item_history_set_offs 
                WHERE whole_sale_price = '" . $wholeSalePrice . "' 
                  AND item_id = '" . $itemID . "' 
                  AND branch_id = '" . $branch_id_ . "' 
                  AND location_id = " . $location_id . "
                  AND price_status = 0
                  AND quantity> 0";




                $balance = DB::select($query);
                if ($balance) {
                    $stockBalance = $balance[0]->balance;

                    $formatted_stockBalance = floatval(str_replace(',', '', $stockBalance));
                    $formatted_qty = floatval(str_replace(',', '', $setOffqty));

                    if ($formatted_stockBalance < $formatted_qty) {
                        $status = false;
                        return response()->json(["message" => "insuficent"]);
                    }
                }
            }

            //validate collection array
            foreach ($collection as $i) {
                $item = json_decode($i);
                $itemID = $item->item_id;
                $qty = $item->qty;
                $foc = 0;
                if (is_nan($qty) || $qty == "" || $qty == 0) {
                    return response()->json(["message" => "qty_zero"]);
                }
                if (is_nan($foc) || $foc == "") {
                    $foc = 0;
                }
                $total_ = floatval($qty) + floatval($foc);
                $query = "SELECT IF(ISNULL(SUM(quantity-setoff_quantity)), 0, SUM(quantity-setoff_quantity)) AS Balance
                FROM item_history_set_offs
                WHERE item_id = '" . $itemID . "' AND branch_id = '" . $branch_id_ . "' AND location_id ='" . $location_id . "' AND price_status = 0 AND quantity>0";

                $balance_ = DB::select($query);
                if ($balance_) {
                    $stockBalance = $balance_[0]->Balance;

                    $formatted_stockBalance = floatval(str_replace(',', '', $stockBalance));
                    $formatted_qty = floatval(str_replace(',', '', $setOffqty));

                    if (floatval($formatted_stockBalance) < floatval($total_)) {
                        $status = false;
                        return response()->json(["message" => "insuficent"]);
                    }
                }
            }

            //saving invoice
            if ($status) {
                

                $referencenumber = $request->input('LblexternalNumber');
                $bR_id = $request->input('cmbBranch');

                $data = DB::table('branches')->where('branch_id', $bR_id)->get();

                $EXPLODE_ID = explode("-", $referencenumber);
                $externalNumber  = '';
                if ($data->count() > 0) {
                    $documentPrefix = $data[0]->prefix;
                    $externalNumber  = $documentPrefix . "-" . $EXPLODE_ID[0] . "-" . $EXPLODE_ID[1];
                }

               
               // $code = $request->input('code');
                $branch_code = $request->input('branch_code');
               
             
               


                $PreparedBy = Auth::user()->id;
                $collection = json_decode($request->input('collection'));
                $sample_dispatch = new sample_dispatch();
                $sample_dispatch->internal_number = IntenelNumberController::getNextID();
                $sample_dispatch->external_number = $externalNumber; // need to change 
                $sample_dispatch->order_date_time = $request->input('invoice_date_time');
                $sample_dispatch->branch_id = $request->input('cmbBranch');
                $sample_dispatch->location_id = $request->input('cmbLocation');
               /*  $sample_dispatch->employee_id = $request->input('cmbEmp'); */
                $sample_dispatch->customer_id = $request->input('customerID');
               /*  $sample_dispatch->total_amount = $request->input('grandTotal'); */
                /* $Purchase_order->Approval_status = $request->input('Approval_status'); */
              /*   $sample_dispatch->discount_percentage = $request->input('txtDiscountPrecentage');
                $sample_dispatch->discount_amount = $request->input('txtDiscountAmount');
                $sample_dispatch->payment_term_id = $request->input('cmbPaymentTerm'); */
                $sample_dispatch->document_number = 1300;
                $sample_dispatch->remarks = $request->input('txtRemarks');
               /*  $sample_dispatch->delivery_instruction = $request->input('txtDeliveryInst');
                $sample_dispatch->payment_method_id = $request->input('cmbPaymentMethod'); */

                
               /*  $sample_dispatch->your_reference_number = $request->input('txtYourReference'); */

                $sample_dispatch->prepaired_by = $PreparedBy;
                if ($sample_dispatch->save()) {
                    $cus_name = DB::select("SELECT customers.customer_name FROM sample_dispatches INNER JOIN customers ON sample_dispatches.customer_id = customers.customer_id WHERE sample_dispatches.sample_dispatch_id = '" . $sample_dispatch->sample_dispatch_id . "'");


                    //looping ifrst array
                    foreach ($collection as $i) {
                        $item = json_decode($i);
                        $location_id = $request->input('locationID');
                        $item_id = $item->item_id;
                        $itemQty = $item->qty;
                        $itemFoc = $item->free_quantity;

                        $formatted_qty = floatval(str_replace(',', '', $itemQty));
                      /*   $formatted_foc = floatval(str_replace(',', '', $itemFoc)); */

                        $dispatch_item = new sample_dispatch_items();
                        $dispatch_item->sample_dispatch_id = $sample_dispatch->sample_dispatch_id;
                        $dispatch_item->internal_number = $sample_dispatch->internal_number;
                        $dispatch_item->external_number = $sample_dispatch->external_number; // need to change
                        $dispatch_item->item_id = $item->item_id;
                        $dispatch_item->item_name = $item->item_name;

                        $dispatch_item->quantity = -$formatted_qty;
                        

                        $dispatch_item->unit_of_measure = $item->uom;
                        $dispatch_item->package_unit = $item->PackUnit;
                        $dispatch_item->package_size = $item->PackSize;
                       // $dispatch_item->price = $item->price;
                       /*  if ($item->discount_percentage) {
                            $dispatch_item->discount_percentage = $item->discount_percentage;
                        } else {
                            $dispatch_item->discount_percentage = 0;
                        }
                        if ($item->discount_amount) {
                            $dispatch_item->discount_amount = $item->discount_amount;
                        } else {
                            $dispatch_item->discount_amount = 0;
                        } */
                        $dispatch_item->whole_sale_price = $wholeSalePrice;
                        $dispatch_item->retial_price = $retail_price;
                        $dispatch_item->cost_price = $cost_price;

                        if ($dispatch_item->save()) {

                            $item_history = new item_history();
                            $item_history->internal_number = $dispatch_item->internal_number;
                            $item_history->external_number = $dispatch_item->external_number;
                            $item_history->external_number = $dispatch_item->external_number;
                            $item_history->branch_id = $sample_dispatch->branch_id;
                            $item_history->location_id = $sample_dispatch->location_id;
                            $item_history->document_number = $sample_dispatch->document_number;
                            $item_history->transaction_date = $sample_dispatch->order_date_time;
                            $item_history->description = "Sample dispatch " . $cus_name[0]->customer_name;
                            $item_history->item_id =  $dispatch_item->item_id;
                            $item_history->quantity = floatVal($dispatch_item->quantity) + floatval($dispatch_item->free_quantity);
                            $item_history->free_quantity = $dispatch_item->free_quantity;
                            $item_history->whole_sale_price = $dispatch_item->whole_sale_price;
                            $item_history->retial_price = $dispatch_item->retial_price;
                            $item_history->cost_price = $dispatch_item->cost_price;
                            $item_history->manual_number = $sample_dispatch->manual_number;
                            $item_history->save();
                        }

                        foreach ($setOffArray as $j) {

                            $SetOff_item = json_decode($j);
                            if ($SetOff_item->item_id == $item->item_id) {

                                $setOff = new sample_dispatch_items_setoff();
                                $setOff->internal_number = $sample_dispatch->internal_number;
                                $setOff->external_number = $sample_dispatch->external_number;
                                $setOff->sample_dispatch_item_id = $dispatch_item->sample_dispatch_item_id;
                                $setOff->item_history_setoff_id = $SetOff_item->history_id;
                                $setOff->item_id = $SetOff_item->item_id;
                                $setOff->set_off_qty = $SetOff_item->setoff_quantity;
                                $setOff->cost_price = $SetOff_item->cost_price;
                                $setOff->whole_sale_price = $SetOff_item->wholesale_price;
                                $setOff->retail_price = $SetOff_item->retail_price;
                                $setOff->batch_number = $SetOff_item->batch_no;
                                if($setOff->save()){

                                    $item_history_set_offed_old = item_history_setOff::find($setOff->item_history_setoff_id);
                                    $item_history_set_offed_old->setoff_quantity = $item_history_set_offed_old->setoff_quantity + $setOff->set_off_qty;
                                    if($item_history_set_offed_old->update()){
                                        $item_history_set_off = new item_history_setOff();
                                        $item_history_set_off->internal_number = $setOff->internal_number;
                                        $item_history_set_off->external_number = $setOff->external_number;
                                        $item_history_set_off->document_number = $sample_dispatch->document_number;
                                        $item_history_set_off->batch_number = $setOff->batch_number;
                                        $item_history_set_off->branch_id = $sample_dispatch->from_branch_id;
                                        $item_history_set_off->location_id = $sample_dispatch->from_location_id;
                                        $item_history_set_off->transaction_date = $sample_dispatch->goods_transfer_date;
                                        $item_history_set_off->item_id =  $setOff->item_id;
                                        $item_history_set_off->whole_sale_price = $setOff->whole_sale_price;
                                        $item_history_set_off->retial_price = $setOff->retail_price;
                                        $item_history_set_off->cost_price = $setOff->cost_price;
                                       /*  $item_history_set_off->quantity = -$setOff->set_off_qty; */
                                        $item_history_set_off->setoff_quantity = -$setOff->set_off_qty;
                                        $item_history_set_off->reference_internal_number = $item_history_set_offed_old->internal_number;
                                        $item_history_set_off->reference_external_number = $item_history_set_offed_old->external_number;
                                        $item_history_set_off->reference_document_number = $item_history_set_offed_old->document_number;
                                        $item_history_set_off->setoff_id = $setOff->item_history_setoff_id;
                                        $item_history_set_off->save();
                                    }


                                    
                                }
                                

                            }
                        }
                    }

                   $order = internal_order::find($order_id);
                   if($order){
                    $order->status = 1;
                    $order->update();
                   }

                    return response()->json(["status" => true, "primaryKey" => $sample_dispatch->sample_dispatch_id]);
                } else {

                    return response()->json(["status" => false]);
                }
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //get sample dispatch
    public function get_sample_dispatch(){
        try{
            $dispatch_details = DB::select('SELECT SDP.sample_dispatch_id,SDP.external_number,SDP.order_date_time,SDP.branch_id,SDP.location_id,C.customer_name,R.route_name FROM sample_dispatches SDP INNER JOIN customers C ON SDP.customer_id = C.customer_id INNER JOIN routes R ON C.route_id = R.route_id');
            if($dispatch_details){
                return response()->json(["status" => true, "data" => $dispatch_details]);
            }else{
                return response()->json(["status" => true, "data" =>[]]);
            }
        }catch(Exception $ex){
            return $ex;
        }
    }

    //load each sample dispatch
    public function get_each_sample_dispatch($id){
        try{
            $dispatch_header_details = DB::select('SELECT SDP.sample_dispatch_id,SDP.external_number,SDP.location_id,SDP.branch_id,SDP.order_date_time,C.customer_name,C.customer_code,C.primary_address FROM sample_dispatches SDP INNER JOIN customers C ON SDP.customer_id = C.customer_id WHERE SDP.sample_dispatch_id ='.$id);
            $dispatch_item = DB::select('
    SELECT 
        sample_dispatch_items.*,
        sample_dispatch_items_setoffs.set_off_qty,
        items.Item_code 
    FROM 
        sample_dispatch_items 
    INNER JOIN 
        sample_dispatch_items_setoffs 
    ON 
        sample_dispatch_items.sample_dispatch_item_id = sample_dispatch_items_setoffs.sample_dispatch_item_id 
    INNER JOIN 
        items 
    ON 
        sample_dispatch_items.item_id = items.item_id 
    WHERE 
        sample_dispatch_items.sample_dispatch_id =' . $id
);

            
            if($dispatch_header_details){
                return response()->json(["status" => true, "header" => $dispatch_header_details,"item"=>$dispatch_item]);
            }else{
                return response()->json(["status" => true, "data" =>[]]);
            }
        }catch(Exception $ex){
            return $ex;
        }
    }
}
