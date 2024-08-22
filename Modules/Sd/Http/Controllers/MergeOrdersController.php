<?php

namespace Modules\Sd\Http\Controllers;

use App\Http\Controllers\IntenelNumberController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Sd\Entities\sales_oder_item;
use Modules\Sd\Entities\sales_order;

class MergeOrdersController extends Controller
{
    //load duplicate orders
    public function load_duplicate_orders($branch){
        try{
            $qry = "SELECT SO.customer_id, SO.employee_id, CONCAT(C.customer_code,'-', C.customer_name,'-',T.townName) AS customer_code,C.customer_code as code, E.employee_name, R.route_name, COUNT(*) as order_count 
            FROM sales_orders SO
            INNER JOIN customers C ON SO.customer_id = C.customer_id
            INNER JOIN employees E ON SO.employee_id = E.employee_id
            LEFT JOIN town_non_administratives T ON C.town = T.town_id
            LEFT JOIN routes R ON C.route_id = R.route_id
            WHERE order_status_id = 1 AND branch_id = $branch AND SO.order_status_id = 1
            GROUP BY customer_id, employee_id
            HAVING COUNT(*) > 1;
            ";
           // dd($qry);
            $result = DB::select($qry);
            if($result){
                return response()->json(['success' => 'Data loaded', 'data' => $result]);
            }else{
                return response()->json(['success' => 'Data not loaded', 'data' => []]);
            }
        }catch(Exception $ex){
            return $ex;
        }
    }


    //load orders
    public function loadOrders($branch,$id){
        try{
            $parts = explode('_', $id);
            $cus_id = $parts[0];
            $emp_id = $parts[1];
            $qry = "SELECT SO.sales_order_Id,SO.external_number,SO.total_amount FROM sales_orders SO WHERE order_status_id = 1 AND branch_id = $branch AND SO.customer_id = $cus_id AND SO.employee_id = $emp_id AND SO.order_status_id = 1";
            $result = DB::select($qry);
            if($result){
                return response()->json(['success' => 'Data loaded', 'data' => $result]);
            }else{
                return response()->json(['success' => 'Data loaded', 'data' => []]);
            }
        }catch(Exception $ex){
            return $ex;
        }
    } 

    //merge order
    public function merger_order_save(Request $request){
        
        
        try {
            DB::beginTransaction();
            $total_amount = 0;
            $location_id = "";
            $employee_id = "";
            $customer_id = "";
            $payment_term_id = "";
            $deliver_type_id = "";
            $expected_date_time = "";
            $your_reference_number = "";
            $sales_order_id_array = $request->input('checkedCheckboxes');
            
            //looping so id array
            foreach($sales_order_id_array as $id){
              $ref_so =  sales_order::find($id);
              
              if($ref_so->merged_order_id > 0){
                $extr = $ref_so->external_number;
                return response()->json(["status" => true, "message" => "used","order"=>$extr]);
              }
              //sum total amount
                $total_amount = $total_amount + $ref_so->total_amount;

                //getting other values(use only one record)
                $location_id = $ref_so->location_id;
                $employee_id = $ref_so->employee_id;
                $customer_id = $ref_so->customer_id;
                $payment_term_id = $ref_so->payment_term_id;
                $deliver_type_id = $ref_so->deliver_type_id;
                $expected_date_time = $ref_so->expected_date_time;
                $your_reference_number = $ref_so->your_reference_number;
            }
            
            //reference number
            $referencenumber = $request->input('referanceID');
            $bR_id = $request->input('branch_id');
           // dd($referencenumber  );
            $data = DB::table('branches')->where('branch_id', $bR_id)->get();

            $EXPLODE_ID = explode("-", $referencenumber);
            $externalNumber  = '';
            if ($data->count() > 0) {
                $documentPrefix = $data[0]->prefix;
                $externalNumber  = $documentPrefix . "-" . $EXPLODE_ID[0] . "-" . $EXPLODE_ID[1];
            }
           
            $PreparedBy = Auth::user()->id;
        
            //save
            $Sales_order = new sales_order();
            $Sales_order->internal_number = IntenelNumberController::getNextID();
            $Sales_order->external_number = $externalNumber;
            $Sales_order->order_date_time = now();
            $Sales_order->location_id = $location_id;
            $Sales_order->employee_id = $employee_id;
            $Sales_order->customer_id = $customer_id;
            $Sales_order->order_status_id = 1;
            $Sales_order->total_amount = $total_amount; 
            
            $Sales_order->discount_percentage = 0;
            $Sales_order->discount_amount = 0; // according to mr.janaka
            $Sales_order->payment_term_id = $payment_term_id;
            $Sales_order->deliver_type_id = $deliver_type_id;
            $Sales_order->remarks = "Merged order";
            /* $Sales_order->delivery_instruction =  */
            $Sales_order->expected_date_time = $expected_date_time;
            $Sales_order->prepaired_by = $PreparedBy;
            $Sales_order->document_number = 200;
            $Sales_order->your_reference_number = $your_reference_number;
            $Sales_order->branch_id =  $bR_id;
           
            if ($Sales_order->save()) {

                //looping order id array to save merged order items
                $hashMap = array(); //associative array
                foreach ($sales_order_id_array as $i) {
                    //updating merged order id to sales order
                    $ref_so =  sales_order::find($i);
                    $ref_so->merged_order_id = $Sales_order->sales_order_Id;
                    $ref_so->order_status_id = 4;
                    $ref_so->order_type = 7;
                    $ref_so->update();

                    //getting sales order items
                    $SO_item_array = sales_oder_item::where("sales_order_Id","=",$i)->get();
                    foreach ($SO_item_array as $item) {
                        $item_id = $item->item_id;
                        if (array_key_exists($item_id, $hashMap)) {
                            // Item_id already exists, update qty and foc data
                            $hashMap[$item_id]['quantity'] += $item->quantity;
                            $hashMap[$item_id]['free_quantity'] += $item->free_quantity;
                        }else {
                            // Item_id does not exist, create a new entry
                            $hashMap[$item_id] = [
                                'item_id'=>$item_id,
                                'item_name'=>$item->item_name,
                                'quantity' => $item->quantity,
                                'free_quantity' => $item->free_quantity,
                                'unit_of_measure'=>$item->unit_of_measure,
                                'package_unit'=>$item->package_unit,
                                'package_size'=>$item->package_size,
                                'price'=>$item->price
                            ];
                        }
                        
                       
                    }
                }

                //looping map to save order items
                foreach ($hashMap as $item_id => $itemData) {
                    $SO_item = new sales_oder_item();
                    $SO_item->sales_order_Id = $Sales_order->sales_order_Id;
                    $SO_item->internal_number = $Sales_order->internal_number;
                    $SO_item->external_number = $Sales_order->external_number;
                    $SO_item->item_id = $itemData['item_id'];
                    $SO_item->item_name = $itemData['item_name'];
                    $SO_item->quantity = $itemData['quantity'];

                    if ($itemData['free_quantity']) {
                        $SO_item->free_quantity = $itemData['free_quantity'];
                    } else {
                        $SO_item->free_quantity = 0;
                    }

                    if ($itemData['unit_of_measure']) {
                        $SO_item->unit_of_measure = $itemData['unit_of_measure'];
                    } else {
                        $SO_item->unit_of_measure = 0;
                    }

                    if ($itemData['package_unit']) {
                        $SO_item->package_unit = $itemData['package_unit'];
                    } else {
                        $SO_item->package_unit = 0;
                    }

                    if ($itemData['package_size']) {
                        $SO_item->package_size = $itemData['package_size'];
                    } else {
                        $SO_item->package_size = 0;
                    }

                    if ($itemData['price']){
                        $SO_item->price = $itemData['price'];
                    } else {
                        $SO_item->price = 0;
                    }

                    /* if ($item->discount_percentage) {
                        $SO_item->discount_percentage = $item->discount_percentage;
                    } else {
                        $SO_item->discount_percentage = 0;
                    } */

                    $SO_item->discount_percentage = 0;
                    /* if ($item->discount_amount) {
                        $SO_item->discount_amount = $item->discount_amount;
                    } else {
                        $SO_item->discount_amount = 0;
                    } */
                    $SO_item->discount_amount = 0;

                    $SO_item->save();
                }
                    
                DB::commit();
                return response()->json(["status" => true]);
            } else {
                return response()->json(["status" => false]);
            }
            
        } catch (Exception $ex) {
            DB::rollback();
            return $ex;
        }

        
    }
}
