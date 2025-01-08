<?php

namespace Modules\Prc\Http\Controllers;

use App\Http\Controllers\IntenelNumberController;
use DateTime;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Md\Entities\supplier;
use Modules\Prc\Entities\delivery_type;
use Modules\Prc\Entities\item;
use Modules\Prc\Entities\purchase_order_note;
use Modules\Prc\Entities\purchase_order_note_draft;
use Modules\Prc\Entities\purchase_order_note_item;
use Modules\Prc\Entities\purchase_order_note_item_draft;
use Modules\Prc\Entities\purchase_request_item;

class PurchaseOrderController extends Controller
{
    public function addPurchaseOrder(Request $request, $id){
        try {
            
             if ($id != "null") {

             purchase_order_note_draft::find($id)->delete();
             purchase_order_note_item_draft::where("purchase_order_Id", "=", $id)->delete();
           
            } 

            $referencenumber = $request->input('LblexternalNumber');
            $bR_id = $request->input('cmbBranch');
           
            $data = DB::table('branches')->where('branch_id', $bR_id)->get();
            
            $EXPLODE_ID = explode("-",$referencenumber);
            $externalNumber  = '';
        if ($data->count() > 0) {
            $documentPrefix = $data[0]->prefix;
            $externalNumber  =$documentPrefix."-".$EXPLODE_ID[0]."-".$EXPLODE_ID[1];
        }
            $PreparedBy = Auth::user()->id;
            $collection = json_decode($request->input('collection'));
            $Purchase_order = new purchase_order_note();
            $Purchase_order->internal_number = IntenelNumberController::getNextID();
            $Purchase_order->external_number = $externalNumber; // need to change 
            $Purchase_order->purchase_order_date_time = $request->input('purchase_order_date_time');
            $Purchase_order->branch_id = $request->input('cmbBranch');
            $Purchase_order->location_id = $request->input('cmbLocation');
            $Purchase_order->supplier_id = $request->input('txtSupplier');
            $Purchase_order->supplier_name = $request->input('lblSupplierName');
            $Purchase_order->payment_mode_id = $request->input('cmbPaymentType');
            /* $Purchase_order->Approval_status = $request->input('Approval_status'); */
            $Purchase_order->deliver_type_id = $request->input('cmbDeliveryType');
            $Purchase_order->discount_percentage = $request->input('txtDiscountPrecentage');
            $Purchase_order->discount_amount = $request->input('txtDiscountAmount');
            $Purchase_order->remarks = $request->input('txtRemarks');
            $Purchase_order->deliver_date_time = $request->input('deliveryDate');
            $Purchase_order->delivery_instruction = $request->input('txtDeliveryInst');
            $Purchase_order->prepaired_by = $PreparedBy;
            $Purchase_order->document_number = 110;
            $Purchase_order->your_reference_number = $request->input('txtYourReference');


            if ($Purchase_order->save()) {

                //looping ifrst array
                foreach ($collection as $i) {
                   /*  $date = date('Y-m-d H:i:s'); */
                    $item = json_decode($i);
                    foreach ($item as $key => $value) {
                        if (is_string($value) && empty($value)) {
                            $item->$key = null;
                        }
                    }
                    $product_ = item::find($item->item_id);
                    $PO_item = new purchase_order_note_item();
                    $PO_item->purchase_order_Id = $Purchase_order->purchase_order_Id;
                    $PO_item->internal_number = $Purchase_order->internal_number;
                    $PO_item->external_number = $Purchase_order->external_number; // need to change
                    $PO_item->item_id = $item->item_id;
                    $PO_item->item_name = $item->item_name;
                    
                    if ($item->qty) {
                        $PO_item->quantity = $item->qty;
                    }else{
                        $PO_item->quantity = 0;
                    }  

                     if ($item->free_quantity) {
                        $PO_item->free_quantity = $item->free_quantity;
                    }else{
                        $PO_item->free_quantity = 0;
                    } 

                    if ($item->addQty) {
                        $PO_item->additional_bonus = $item->addQty;
                    }else{
                        $PO_item->additional_bonus = 0;
                    } 
                  
                    if ($item->uom) {
                        $PO_item->unit_of_measure = $item->uom;
                    }else{
                        $PO_item->unit_of_measure = 0;
                    } 

                    if ($item->PackUnit) {
                        $PO_item->package_unit = $item->PackUnit;
                    }else{
                        $PO_item->package_unit = 0;
                    }

                    if ($item->PackSize) {
                        $PO_item->package_size = $item->PackSize;
                    }else{
                        $PO_item->package_size = 0;
                    }
                   
                    if ($item->price) {
                        $PO_item->price = $item->price;
                    }else{
                        $PO_item->price = 0;
                    }

                    if ($item->cost) {
                        $PO_item->cost_price = $item->cost;
                    }else{
                        $PO_item->cost_price = 0;
                    }


                    if ($item->discount_percentage) {
                        $PO_item->discount_percentage = $item->discount_percentage;
                    }else{
                        $PO_item->discount_percentage = 0;
                    }

                    
                    if ($item->discount_amount) {
                        $PO_item->discount_amount = $item->discount_amount;
                    }else{
                        $PO_item->discount_amount = 0;
                    }
                   
                    if($product_->previouse_purchase_price == $item->price){
                        $PO_item->is_new_price = 0;
                    }else{
                        $PO_item->is_new_price = 1;
                    }
                   
                    $PO_item->save();


                   
                }



                return response()->json(["status" => true,"primaryKey" => $Purchase_order->purchase_order_Id]);
            } else {
                return response()->json(["status" => false]);
            }
        } catch (Exception $ex) {
            return $ex;
        }

    }

    //add PO draft
    public function addPurchaseOrderDraft(Request $request){
        try {
            
             $PreparedBy = Auth::user()->id;
             $collection = json_decode($request->input('collection'));
             $Purchase_order = new purchase_order_note_draft();
             $Purchase_order->internal_number = 0000;
             $Purchase_order->external_number = $request->input('LblexternalNumber'); // need to change 
             $Purchase_order->purchase_order_date_time = $request->input('purchase_order_date_time');
             $Purchase_order->branch_id = $request->input('cmbBranch');
             $Purchase_order->location_id = $request->input('cmbLocation');
             $Purchase_order->supplier_id = $request->input('txtSupplier');
             $Purchase_order->supplier_name = $request->input('lblSupplierName');
             $Purchase_order->payment_mode_id = $request->input('cmbPaymentType');
             /* $Purchase_order->Approval_status = $request->input('Approval_status'); */
             $Purchase_order->deliver_type_id = $request->input('cmbDeliveryType');
             $Purchase_order->discount_percentage = $request->input('txtDiscountPrecentage');
             $Purchase_order->discount_amount = $request->input('txtDiscountAmount');
             $Purchase_order->remarks = $request->input('txtRemarks');
             $Purchase_order->deliver_date_time = $request->input('deliveryDate');
             $Purchase_order->delivery_instruction = $request->input('txtDeliveryInst');
             
             $Purchase_order->prepaired_by = $PreparedBy;
             $Purchase_order->document_number = 110;
             $Purchase_order->your_reference_number = $request->input('txtYourReference');

 
 
             if ($Purchase_order->save()) {
 
                
                 foreach ($collection as $i) { 
                     $item = json_decode($i);
                     foreach ($item as $key => $value) {
                        if (is_string($value) && empty($value)) {
                            $item->$key = null;
                        }
                    }
                     $PO_item = new purchase_order_note_item_draft();
                     $PO_item->purchase_order_Id = $Purchase_order->purchase_order_Id;
                     $PO_item->internal_number = 0000;
                     $PO_item->external_number = $Purchase_order->external_number; // need to change
                     $PO_item->item_id = $item->item_id;
                     $PO_item->item_name = $item->item_name;
                     $PO_item->quantity = $item->qty;
                     $PO_item->free_quantity = $item->free_quantity;
                     $PO_item->unit_of_measure = $item->uom;
                     $PO_item->package_unit = $item->PackUnit;
                     $PO_item->package_size = $item->PackSize;
                     $PO_item->price = $item->price;
                     $PO_item->discount_percentage = $item->discount_percentage;
                     $PO_item->discount_amount = $item->discount_amount;
                     $PO_item->save();
                 }
 
 
 
                 return response()->json(["status" => true]);
             } else {
                 return response()->json(["status" => false]);
             }
         } catch (Exception $ex) {
             return $ex;
         }

    }

    //update PO 
    public function updatePO(Request $request,$id){
        try {
            
            
             $collection = json_decode($request->input('collection'));
             //dd($collection);
             $Purchase_order = purchase_order_note::find($id);
             $Purchase_order->internal_number = $request->input('internalNumber');
            $Purchase_order->external_number = $request->input('LblexternalNumber');  // need to change 
             $Purchase_order->purchase_order_date_time = $request->input('purchase_order_date_time');
             $Purchase_order->branch_id = $request->input('cmbBranch');
             $Purchase_order->location_id = $request->input('cmbLocation');
             $Purchase_order->supplier_id = $request->input('txtSupplier');
             $Purchase_order->supplier_name = $request->input('lblSupplierName');
             $Purchase_order->payment_mode_id = $request->input('cmbPaymentType');
             /* $Purchase_order->Approval_status = $request->input('Approval_status'); */
             $Purchase_order->deliver_type_id = $request->input('cmbDeliveryType');
             $Purchase_order->discount_percentage = $request->input('txtDiscountPrecentage');
             $Purchase_order->discount_amount = $request->input('txtDiscountAmount');
             $Purchase_order->remarks = $request->input('txtRemarks');
             $Purchase_order->deliver_date_time = $request->input('deliveryDate');
             $Purchase_order->delivery_instruction = $request->input('txtDeliveryInst');
             $Purchase_order->document_number = 110;
             $Purchase_order->your_reference_number = $request->input('txtYourReference');

             
 
 
             if ($Purchase_order->save()) {
                $deleteRequestItem = purchase_order_note_item::where("purchase_order_Id", "=", $id)->delete();
                 //looping ifrst array
                 foreach ($collection as $i) {
                     $item = json_decode($i);
                     foreach ($item as $key => $value) {
                        if (is_string($value) && empty($value)) {
                            $item->$key = null;
                        }
                    }
                     $product_ = item::find($item->item_id);
                     $PO_item = new purchase_order_note_item();
                     $PO_item->purchase_order_Id = $Purchase_order->purchase_order_Id;
                     $PO_item->internal_number = $Purchase_order->internal_number;
                     $PO_item->external_number = $Purchase_order->external_number; // need to change
                     $PO_item->item_id = $item->item_id;
                     $PO_item->item_name = $item->item_name;
                     $PO_item->quantity = $item->qty;
                     if ($item->qty) {
                        $PO_item->quantity = $item->qty;
                    }else{
                        $PO_item->quantity = 0;
                    }  

                     if ($item->free_quantity) {
                        $PO_item->free_quantity = $item->free_quantity;
                    }else{
                        $PO_item->free_quantity = 0;
                    } 

                    if ($item->addQty) {
                        $PO_item->additional_bonus = $item->addQty;
                    }else{
                        $PO_item->additional_bonus = 0;
                    } 
                  
                    if ($item->uom) {
                        $PO_item->unit_of_measure = $item->uom;
                    }else{
                        $PO_item->unit_of_measure = 0;
                    } 

                    if ($item->PackUnit) {
                        $PO_item->package_unit = $item->PackUnit;
                    }else{
                        $PO_item->package_unit = 0;
                    }

                    if ($item->PackSize) {
                        $PO_item->package_size = $item->PackSize;
                    }else{
                        $PO_item->package_size = 0;
                    }
                   
                    if ($item->price) {
                        $PO_item->price = $item->price;
                    }else{
                        $PO_item->price = 0;
                    }

                    if ($item->cost) {
                        $PO_item->cost_price = $item->cost;
                    }else{
                        $PO_item->cost_price = 0;
                    }


                    if ($item->discount_percentage) {
                        $PO_item->discount_percentage = $item->discount_percentage;
                    }else{
                        $PO_item->discount_percentage = 0;
                    }

                    
                    if ($item->discount_amount) {
                        $PO_item->discount_amount = $item->discount_amount;
                    }else{
                        $PO_item->discount_amount = 0;
                    }
                   
                    if($product_->previouse_purchase_price == $item->price){
                        $PO_item->is_new_price = 0;
                    }else{
                        $PO_item->is_new_price = 1;
                    }
                  // dd($PO_item);
                    
                     $PO_item->save();
                 }
 
 
 
                 return response()->json(["status" => true]);
             } else {
                 return response()->json(["status" => false]);
             }
         } catch (Exception $ex) {
             return $ex;
         }

    }

    //update PO draft
    public function updatePODraft(Request $request, $id){
        try {
            $collection = json_decode($request->input('collection'));
            $Purchase_order = purchase_order_note_draft::find($id);
            $Purchase_order->internal_number = 0000;
            $Purchase_order->external_number = $request->input('LblexternalNumber'); // need to change 
            $Purchase_order->purchase_order_date_time = $request->input('purchase_order_date_time');
            $Purchase_order->branch_id = $request->input('cmbBranch');
            $Purchase_order->location_id = $request->input('cmbLocation');
            $Purchase_order->supplier_id = $request->input('txtSupplier');
            $Purchase_order->supplier_name = $request->input('lblSupplierName');
            $Purchase_order->payment_mode_id = $request->input('cmbPaymentType');
            /* $Purchase_order->Approval_status = $request->input('Approval_status'); */
            $Purchase_order->deliver_type_id = $request->input('cmbDeliveryType');
            $Purchase_order->discount_percentage = $request->input('txtDiscountPrecentage');
            $Purchase_order->discount_amount = $request->input('txtDiscountAmount');
            $Purchase_order->remarks = $request->input('txtRemarks');
            $Purchase_order->deliver_date_time = $request->input('deliveryDate');
            $Purchase_order->delivery_instruction = $request->input('txtDeliveryInst');
            $Purchase_order->document_number = 110;
            

            if ($Purchase_order->save()) {
                $deleteRequestItem = purchase_order_note_item_draft::where("purchase_order_Id", "=", $id)->delete();
               
                foreach ($collection as $i) { 
                    $item = json_decode($i);
                    foreach ($item as $key => $value) {
                        if (is_string($value) && empty($value)) {
                            $item->$key = null;
                        }
                    }
                    $PO_item = new purchase_order_note_item_draft();
                    $PO_item->purchase_order_Id = $Purchase_order->purchase_order_Id;
                    $PO_item->internal_number = 0000;
                    $PO_item->external_number = $Purchase_order->external_number; // need to change
                    $PO_item->item_id = $item->item_id;
                    $PO_item->item_name = $item->item_name;
                    $PO_item->quantity = $item->qty;
                    $PO_item->free_quantity = $item->free_quantity;
                    $PO_item->unit_of_measure = $item->uom;
                    $PO_item->package_unit = $item->PackUnit;
                    $PO_item->package_size = $item->PackSize;
                    $PO_item->price = $item->price;
                    $PO_item->discount_percentage = $item->discount_percentage;
                    $PO_item->discount_amount = $item->discount_amount;
                    $PO_item->save();
                }



                return response()->json(["status" => true]);
            } else {
                return response()->json(["status" => false]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //get delivery types
    public function getDeliveryTypes(){
        try {
            $delivery_type = delivery_type::all();
            if ($delivery_type) {
                return response()->json($delivery_type);
            } else {
                return response()->json(['status' => false]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function getPOData(){
        try {
           /*  $query = "SELECT *
            FROM (
                SELECT purchase_order_Id, external_number, supplier_name, purchase_order_date_time, approval_status, deliver_date_time, 'Draft' AS status,SUM(purchase_order_note_item_drafts.price) AS total_sum
                FROM purchase_order_note_drafts INNER JOIN purchase_order_note_item_drafts ON purchase_order_note_drafts.purchase_order_Id = purchase_order_note_item_drafts.purchase_order_Id
                UNION
                SELECT purchase_order_Id, external_number, supplier_name, purchase_order_date_time, approval_status, deliver_date_time, 'Original' AS status
                FROM purchase_order_notes INNER JOIN purchase_order_note_items ON purchase_order_notes.	purchase_order_Id = purchase_order_note_items.purchase_order_Id,SUM(purchase_order_note_items.price) AS total_sum
            ) AS subquery
            ORDER BY (CASE WHEN subquery.status = 'Draft' THEN 0 ELSE 1 END), subquery.external_number DESC;
            
            "; */

            $query = "SELECT 
            purchase_order_notes.purchase_order_Id, 
            purchase_order_notes.external_number, 
            supplier_name, 
            purchase_order_date_time, 
            approval_status, 
            deliver_date_time, 
            purchase_order_notes.discount_percentage,
            'Original' AS status,
            FORMAT(
                SUM(
                    (IFNULL(purchase_order_note_items.cost_price, 0) * IFNULL(purchase_order_note_items.quantity, 0))
                    - IFNULL(purchase_order_note_items.discount_amount, 0)
                ),
                2
            ) AS total_sum
        FROM 
            purchase_order_notes 
        INNER JOIN 
            purchase_order_note_items 
            ON purchase_order_notes.purchase_order_Id = purchase_order_note_items.purchase_order_Id
        GROUP BY 
            purchase_order_notes.purchase_order_Id, 
            purchase_order_notes.external_number, 
            supplier_name, 
            purchase_order_date_time, 
            approval_status, 
            deliver_date_time";

            $result = DB::select($query);
            if ($result) {
                return response()->json((['success' => 'Data loaded', 'data' => $result]));
            } else {
                return response()->json((['error' => 'Data is not loaded','data' => []]));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //delete PO
    public function deletePo($id,$status){
        try {
            if ($status == "Original") {
                $PO = purchase_order_note::find($id);
                if ($PO->delete()) {
                    $PO_item = purchase_order_note_item::where('purchase_order_Id', '=', $id)->delete();;

                    return response()->json(["message" => "Deleted"]);
                } else {
                    return response()->json(["message" => "Not Deleted"]);
                }
            } else {
                $PO_draft = purchase_order_note_draft::find($id);
                if ($PO_draft->delete()) {
                    $PO_item_draft = purchase_order_note_item_draft::where('purchase_order_Id', '=', $id)->delete();

                    return response()->json(["message" => "Deleted"]);
                } else {
                    return response()->json(["message" => "Not Deleted"]);
                }
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function getEachPO($id,$status){
        try{
            if($status == "Original"){
                $query = 'SELECT purchase_order_notes.*,suppliers.primary_address,suppliers.supplier_code FROM purchase_order_notes INNER JOIN suppliers ON purchase_order_notes.supplier_id = suppliers.supplier_id WHERE purchase_order_Id = "' . $id . '"';
                $result = DB::select($query);
                if($query){
                    return response()->json((['success' => 'Data loaded', 'data' => $result]));
                }else{
                    return response()->json((['success' => 'Data loaded', 'data' => []]));
                }
            }else{
                $query = 'SELECT purchase_order_note_drafts.*,suppliers.primary_address FROM purchase_order_note_drafts INNER JOIN suppliers ON purchase_order_note_drafts.supplier_id = suppliers.supplier_id WHERE purchase_order_Id = "'.$id.'"';
                $result = DB::select($query);
                if($query){
                    return response()->json((['success' => 'Data loaded', 'data' => $result]));
                }else{
                    return response()->json((['success' => 'Data loaded', 'data' => []]));
                }
            }
            
        }catch(Exception $ex){
            return $ex;
        }
    }

    public function getEachproductofPO($id, $status)
{
    try {
        // Fetch purchase order details
        $po = purchase_order_note::find($id);
        if (!$po) {
            return response()->json(['error' => 'Purchase Order not found', 'data' => []]);
        }

        $currentDate = new DateTime();
        $to = $currentDate->format('Y-m-d');

        $threeMonthsAgo = new DateTime();
        $threeMonthsAgo->modify('-3 months');
        $from = $threeMonthsAgo->format('Y-m-d');

        // Check the status and build the appropriate query
        if ($status === "Original") {
            $query = "
                SELECT 
                    purchase_order_note_items.*, 
                    items.Item_code, 
                    (
                        SELECT 
                            COALESCE(SUM(quantity - setoff_quantity), 0)
                        FROM 
                            item_history_set_offs 
                        INNER JOIN 
                            locations L ON item_history_set_offs.location_id = L.location_id 
                        INNER JOIN 
                            location_types LT ON L.location_type_id = LT.location_type_id
                        WHERE 
                            item_history_set_offs.item_id = purchase_order_note_items.item_id 
                            AND item_history_set_offs.branch_id = ? 
                            AND quantity > 0 
                            AND price_status = 0 
                            AND LT.location_type_id = 3
                    ) AS from_balance,
                    (
                        SELECT 
                            COALESCE(average_sales(purchase_order_note_items.item_id, ?, ?, ?), 0) * -1
                    ) AS avg_sales 
                FROM 
                    purchase_order_note_items
                INNER JOIN 
                    items ON purchase_order_note_items.item_id = items.item_id
                WHERE 
                    purchase_order_note_items.purchase_order_Id = ?";
            
            // Execute the query with prepared statements
            $item = DB::select($query, [$po->branch_id, $po->branch_id, $from, $to, $id]);
        } else {
            $query = "
                SELECT 
                    purchase_order_note_item_drafts.*, 
                    items.Item_code 
                FROM 
                    purchase_order_note_item_drafts
                INNER JOIN 
                    items ON purchase_order_note_item_drafts.item_id = items.item_id
                WHERE 
                    purchase_order_note_item_drafts.purchase_order_Id = ?";
            
            // Execute the query with prepared statements
            $item = DB::select($query, [$id]);
        }

        // Return the result
        if ($item) {
            return response()->json($item);
        } else {
            return response()->json(['error' => 'Data not loaded', 'data' => []]);
        }
    } catch (Exception $ex) {
        return response()->json(['error' => $ex->getMessage()]);
    }
}


    //get approval list
    public function getPendingapprovalsPurchaseOrder(){
        try{
            $query = 'SELECT purchase_order_Id, external_number, purchase_order_date_time, supplier_name, approval_status, deliver_date_time, "Original" AS status, branches.branch_name
            FROM purchase_order_notes
            INNER JOIN branches ON purchase_order_notes.branch_id = branches.branch_id
            WHERE approval_status = "Pending"
            ORDER BY external_number DESC;
            ';
            $result = DB::select($query);
            if($result){
                return response()->json((['success' => 'Data loaded', 'data' => $result]));
            }else{
                return response()->json((['success' => 'Data not loaded', 'data' => []]));
            }

        }catch(Exception $ex){
            return $ex;
        }
    }


    //apprve
    public function approveRequestPO(Request $request,$id){
        try{
            $collection = json_decode($request->input('collection'));
            //dd($collection);
            $PO_data = purchase_order_note::find($id);
            $query = "SELECT approval_status,internal_number,external_number FROM purchase_order_notes WHERE purchase_order_Id = ".$id;

            $result = DB::select($query);
            if($result){
                $cur_status = $result[0]->approval_status;
                if($cur_status != "Pending"){
                    return response()->json((['status' => false,'msg' => 'no']));
                }else{
                    DB::beginTransaction();
                    $deleteRequestItem = purchase_order_note_item::where("purchase_order_Id", "=", $id)->delete();
                    foreach ($collection as $i) {
                        /*  $date = date('Y-m-d H:i:s'); */
                         $item = json_decode($i);
                         foreach ($item as $key => $value) {
                             if (is_string($value) && empty($value)) {
                                 $item->$key = null;
                             }
                         }
                         $product_ = item::find($item->item_id);
                         $PO_item = new purchase_order_note_item();
                         $PO_item->purchase_order_Id = $PO_data->purchase_order_Id;
                         $PO_item->internal_number = $PO_data->internal_number;
                         $PO_item->external_number = $PO_data->external_number; 
                         $PO_item->item_id = $item->item_id;
                         $PO_item->item_name = $item->item_name;
                         
                         if ($item->qty) {
                             $PO_item->quantity = $item->qty;
                         }else{
                             $PO_item->quantity = 0;
                         }  
     
                          if ($item->free_quantity) {
                             $PO_item->free_quantity = $item->free_quantity;
                         }else{
                             $PO_item->free_quantity = 0;
                         } 
     
                         if ($item->addQty) {
                             $PO_item->additional_bonus = $item->addQty;
                         }else{
                             $PO_item->additional_bonus = 0;
                         } 
                       
                         if ($item->uom) {
                             $PO_item->unit_of_measure = $item->uom;
                         }else{
                             $PO_item->unit_of_measure = 0;
                         } 
     
                         if ($item->PackUnit) {
                             $PO_item->package_unit = $item->PackUnit;
                         }else{
                             $PO_item->package_unit = 0;
                         }
     
                         if ($item->PackSize) {
                             $PO_item->package_size = $item->PackSize;
                         }else{
                             $PO_item->package_size = 0;
                         }
                        
                         if ($item->price) {
                             $PO_item->price = $item->price;
                         }else{
                             $PO_item->price = 0;
                         }
     
                         if ($item->cost) {
                             $PO_item->cost_price = $item->cost;
                         }else{
                             $PO_item->cost_price = 0;
                         }
     
     
                         if ($item->discount_percentage) {
                             $PO_item->discount_percentage = $item->discount_percentage;
                         }else{
                             $PO_item->discount_percentage = 0;
                         }
     
                         
                         if ($item->discount_amount) {
                             $PO_item->discount_amount = $item->discount_amount;
                         }else{
                             $PO_item->discount_amount = 0;
                         }
                        
                         if($product_->previouse_purchase_price == $item->price){
                             $PO_item->is_new_price = 0;
                         }else{
                             $PO_item->is_new_price = 1;
                         }
                        
                         $PO_item->save();
     
     
                        
                     }
                    }


                    $approvedBy = Auth::user()->id;
                    
                    $PO_data->approval_status = "Approved";
                    $PO_data->approved_by = $approvedBy;
                    DB::commit();
                    if ($PO_data->update()) {
                        DB::rollback();
                        return response()->json((['status' => true]));
                    } else {
                        return response()->json((['status' => false]));
                    }

                }
            
            
        }catch(Exception $ex){
            return $ex;
        }
    }

    public function rejectRequestPO($id){
        try{

            $query = "SELECT approval_status FROM purchase_order_notes WHERE purchase_order_Id = ".$id;

            $result = DB::select($query);
            if($result){
                $cur_status = $result[0]->approval_status;
                if($cur_status != "Pending"){
                    return response()->json((['status' => false,'msg' => 'no']));
                }else{
                    $approvedBy = Auth::user()->id;
                    $PO_data = purchase_order_note::find($id);
                    $PO_data->approval_status = "Rejected";
                    $PO_data->approved_by = $approvedBy;
                    if ($PO_data->update()) {
                        return response()->json((['status' => true]));
                    } else {
                        return response()->json((['status' => false]));
                    }

                }
                
            }
        }catch(Exception $ex){
            return $ex;
        }
    }


    //load items to transaction table accrding to supplier group
    public function loadItems_purchaseOrder($sup_id)
    {
        $items = [];
        try {
            if($sup_id > 0){
                $supplier = supplier::find($sup_id);
                if($supplier->supply_group_id == 1){
                    $items = DB::select("SELECT it.item_id,it.Item_code,it.item_Name FROM items it");
                }else{
                    $items = DB::select("SELECT it.item_id, it.Item_code, it.item_Name,SG.supply_group FROM items it 
                    INNER JOIN supply_groups SG ON it.supply_group_id = SG.supply_group_id 
                    INNER JOIN suppliers SP ON SG.supply_group_id = SP.supply_group_id 
                    WHERE SP.supplier_id = " . $sup_id);
                }
                

            }else{
                $items = DB::select("SELECT it.item_id,it.Item_code,it.item_Name FROM items it");
            }
            
          
           
            $collection = [];
            foreach ($items as $item) {
                array_push($collection, ["hidden_id" => $item->item_id, "id" =>  $item->item_Name, "value" =>  $item->Item_code,"value2" => $item->supply_group, "collection" => [$item->item_id, $item->item_Name, $item->Item_code,$item->supply_group]]);
            }
            return response()->json(['success' => true, 'data' => $collection]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function getItemInfo_purchase_order($Item_id, $from_branch)
    {
        try {
            $currentDate = new DateTime();
            $to = $currentDate->format('Y-m-d');

            $threeMonthsAgo = new DateTime();
            $threeMonthsAgo->modify('-3 months');
            $from = $threeMonthsAgo->format('Y-m-d');

           // $from = DateTime::createFromFormat('d/m/Y', $from)->format('Y-m-d');
           // $to = DateTime::createFromFormat('d/m/Y', $to)->format('Y-m-d');
            $info = DB::select("SELECT it.item_id,it.Item_code,it.item_Name,it.item_description,it.package_unit,it.unit_of_measure,it.package_size,it.previouse_purchase_price,(
                SELECT IF(ISNULL(SUM(quantity - setoff_quantity)), 0, SUM(quantity - setoff_quantity))
                FROM item_history_set_offs INNER JOIN locations L ON item_history_set_offs.location_id = L.location_id INNER JOIN location_types LT ON L.location_type_id = LT.location_type_id
                WHERE item_id = '" . $Item_id . "' AND item_history_set_offs.branch_id = '" . $from_branch . "'  AND quantity > 0 AND price_status = 0 AND LT.location_type_id = 3
            ) AS from_balance,
            (SELECT IFNULL(average_sales('" . $Item_id . "'," . $from_branch . ",'" . $from . "','" . $to . "'), 0) * -1 AS Offerd_quantity) AS avg_sales
            FROM items it WHERE it.item_id = $Item_id");

            if ($info) {
                return $info;
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

}



