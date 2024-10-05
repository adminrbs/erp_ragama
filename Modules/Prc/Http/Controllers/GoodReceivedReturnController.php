<?php

namespace Modules\Prc\Http\Controllers;

use App\Http\Controllers\IntenelNumberController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Md\Entities\supplier;
use Modules\Prc\Entities\creditors_ledger;
use Modules\Prc\Entities\creditors_ledger_setoff;
use Modules\Prc\Entities\goodreceivereturn;
use Modules\Prc\Entities\goods_received_note;
use Modules\Prc\Entities\goods_received_note_item;
use Modules\Prc\Entities\GoodsReceivedNoteDraft;
use Modules\Prc\Entities\GoodsReceivedNoteItemDraft;

use Modules\Prc\Entities\goodreceivereturnItem;
use Modules\Prc\Entities\goods_return_setOff;
use Modules\Prc\Entities\item_history;
use Modules\Prc\Entities\item_history_setOff;
use Modules\Prc\Entities\location;

class GoodReceivedReturnController extends Controller
{
    //add GR return
    public function addGRReturn(Request $request, $id)
    {
        // dd($request->input('setOffArray'));
        DB::beginTransaction();
        try {

            $setOffArray = json_decode($request->input('setOffArray'));
            //  dd($setOffArray);
            $collection = json_decode($request->input('collection'));
           // dd($collection);
            $branch_id_ = $request->input('cmbBranch');
            //validate set off array to check avl qty
            foreach ($setOffArray as $i) {

                $item = $i;
               
                $itemID = $item->item_id;
                $data = $item->data;
                $qty = 0;
               
                foreach ($data as $dt) {
                    if($dt){
                        $wh_price = $dt->wh_price;
                        $setoff_qty = $dt->set_off_qty;
                        $query = "SELECT IF(ISNULL(SUM(quantity-setoff_quantity)), 0, SUM(quantity-setoff_quantity)) AS balance 
                    FROM item_history_set_offs 
                    WHERE whole_sale_price = REPLACE('" . $wh_price . "', ',', '') AND item_id = '" . $itemID . "' AND branch_id = '" . $branch_id_ . "'  AND quantity> 0";
                        //dd($query);
                        $balance = DB::select($query);
                        if ($balance) {
                            $stockBalance = $balance[0]->balance;
    
                            $formatted_stockBalance = floatval(str_replace(',', '', $stockBalance));
                            $formatted_qty = floatval($setoff_qty);
    
                            if ($formatted_stockBalance < $formatted_qty) {
                                $status = false;
                                return response()->json(["message" => "insuficent", "qty" => $formatted_stockBalance]);
                            }
                        }
                    }
                    
                }

            
             /*    $setOffqty = $item->setoff_quantity;
                $wholeSalePrice = $item->wholesale_price; */
            }


            //validate collection array
            foreach ($collection as $i) {
                $item = json_decode($i);
                $itemID = $item->item_id;
                $qty = $item->qty;
                $foc = $item->free_quantity;
                if (is_nan(floatval($qty)) || $qty == "" || $qty == 0) {
                    return response()->json(["message" => "qty_zero"]);
                }

                if (is_nan(floatval($foc)) || $foc == "") {
                    $foc = 0;
                }
                // $total_ = floatval($qty) + floatval($foc);
                /* $query = "SELECT IF(ISNULL(SUM(quantity)), 0, SUM(quantity)) AS Balance
                FROM item_historys
                WHERE item_id = '".$itemID."' AND branch_id = '".$branch_id_."'";
                
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
 */
            }

            $referencenumber = $request->input('LblexternalNumber');
            $bR_id = $request->input('cmbBranch');

            $data = DB::table('branches')->where('branch_id', $bR_id)->get();

            $EXPLODE_ID = explode("-", $referencenumber);
            $externalNumber  = '';
            if ($data->count() > 0) {
                $documentPrefix = $data[0]->prefix;
                $externalNumber  = $documentPrefix . "-" . $EXPLODE_ID[0] . "-" . $EXPLODE_ID[1];
            }
            $PreparedBy = Auth::user()->id;
            $collection = json_decode($request->input('collection'));
            $GRN = new goodreceivereturn();
            $GRN->internal_number = IntenelNumberController::getNextID();
            $GRN->external_number = $externalNumber;
            $GRN->goods_received_date_time = $request->input('goods_received_date_time');
            $GRN->branch_id = $request->input('cmbBranch');
            $GRN->location_id = $request->input('cmbLocation');
            $GRN->supplier_id = $request->input('txtSupplier');
            $GRN->supplier_name = $request->input('lblSupplierName');
            /* $GRN->purchase_order_id = 1; */ //need to change
            $GRN->supppier_invoice_number = $request->input('txtSupplierInvoiceNumber');
            $GRN->invoice_amount = $request->input('lblNetTotal');  // need to change
            /*   $GRN->payment_due_date = $request->input('dtPaymentDueDate'); */
            /*    $GRN->payment_mode_id = $request->input('cmbPaymentType'); */
            $GRN->discount_percentage = $request->input('txtDiscountPrecentage');
            $GRN->discount_amount = $request->input('txtDiscountAmount');
            $GRN->adjustment_amount = $request->input('txtAdjustmentAmount');
            $GRN->remarks = $request->input('txtRemarks');
            $GRN->document_number = 130;
            $GRN->prepaired_by = $PreparedBy;
            $GRN->total_amount = $request->input('lblNetTotal');
            $sup_obj = supplier::find($GRN->supplier_id);

            if ($GRN->save()) {

                
                //looping ifrst array
                foreach ($collection as $i) {

                    $item = json_decode($i);
                    foreach ($item as $key => $value) {
                        if (is_string($value) && empty($value)) {
                            $item->$key = null;
                        }
                    }

                    $itemQty = $item->qty;
                    $itemFoc = $item->free_quantity;

                    $formatted_qty = floatval(str_replace(',', '', $itemQty));
                    $formatted_foc = floatval(str_replace(',', '', $itemFoc));

                    $GRN_item = new goodreceivereturnItem();
                    $GRN_item->goods_received_return_Id = $GRN->goods_received_return_Id;
                    $GRN_item->internal_number = $GRN->internal_number;
                    $GRN_item->external_number = $GRN->external_number; // need to change
                    $GRN_item->item_id = $item->item_id;
                    $GRN_item->item_name = $item->item_name;
                    $GRN_item->quantity = -$formatted_qty;
                    $GRN_item->free_quantity = -$formatted_foc;
                    $GRN_item->unit_of_measure = $item->uom;
                    $GRN_item->package_unit = $item->PackUnit;
                    $GRN_item->package_size = $item->PackSize;
                    $GRN_item->price = $item->price;
                    $GRN_item->discount_percentage = $item->discount_percentage;
                    $GRN_item->discount_amount = $item->discount_amount;
                    $GRN_item->whole_sale_price = $item->whole_sale_price;
                    $GRN_item->retial_price = $item->retial_price;
                    $GRN_item->batch_number = $item->batch_number;
                    $GRN_item->expire_date = $item->expire_date;
                    $GRN_item->cost_price = $item->cost_price;
                    if ($GRN_item->save()) {

                        $item_history = new item_history();
                        $item_history->internal_number = $GRN_item->internal_number;
                        $item_history->external_number = $GRN_item->external_number;
                        $item_history->external_number = $GRN_item->external_number;
                        $item_history->branch_id = $GRN->branch_id;
                        $item_history->location_id = $GRN->location_id;
                        $item_history->document_number = $GRN->document_number;
                        $item_history->transaction_date =$GRN->goods_received_date_time;
                        $item_history->description = "Goods Returned to " . $sup_obj->supplier_name;
                        $item_history->item_id =  $GRN_item->item_id;
                        $item_history->quantity = (floatVal($GRN_item->quantity) + floatval($GRN_item->free_quantity));
                        $item_history->free_quantity = $GRN_item->free_quantity;
                        $item_history->whole_sale_price = $GRN_item->whole_sale_price;
                        $item_history->retial_price = $GRN_item->retial_price;
                        $item_history->cost_price = $GRN_item->cost_price;
                        $item_history->save();

                        foreach ($setOffArray as $i) {
                            $item = $i;
                            $itemID = $item->item_id;
                            $data = $item->data;
                            $qty = 0;
                            foreach ($data as $dt) {
                                if($dt){
                                    if ($GRN_item->item_id == $itemID && $GRN_item->price == floatval(str_replace(',', '', $dt->wh_price))) {

                                        $setOff = new goods_return_setOff();
                                        $setOff->internal_number = $GRN->internal_number;
                                        $setOff->external_number = $GRN->external_number;
                                        $setOff->goods_received_return_item_id = $GRN_item->goods_received_return_item_id;
                                        $setOff->item_history_setoff_id = $dt->set_of_id;
                                        $setOff->item_id = $itemID;
                                        $setOff->set_off_qty = $dt->set_off_qty;
                                        $setOff->cost_price = str_replace(',', '', $dt->cost_p);
                                        $setOff->whole_sale_price = str_replace(',', '', $dt->wh_price);
                                        $setOff->retail_price = str_replace(',', '', $dt->rt_price);
                                        $setOff->batch_number = $dt->batch_number;
                                        $ih = item_history_setOff::find($setOff->item_history_setoff_id);
                                        //dd($ih);
                                        if($setOff->save()){
                                            $item_history_setoff = new item_history_setOff();
                                            $item_history_setoff->internal_number = $setOff->internal_number;
                                            $item_history_setoff->external_number = $setOff->external_number;
                                            $item_history_setoff->document_number = $item_history->document_number;
                                            $item_history_setoff->branch_id = $GRN->branch_id;
                                            $item_history_setoff->location_id = $GRN->location_id;
                                           /*  $item_history_setoff->document_number = $GRN->document_number; */
                                            $item_history_setoff->transaction_date =$GRN->goods_received_date_time;
                                            //$item_history_setoff->description = "Goods Returned to " . $sup_obj->supplier_name;
                                            $item_history_setoff->whole_sale_price = $setOff->whole_sale_price;
                                            $item_history_setoff->retial_price = $setOff->retail_price;
                                            $item_history_setoff->cost_price = $setOff->cost_price;
                                            $item_history_setoff->item_id =  $GRN_item->item_id;
                                            $item_history_setoff->quantity = -$setOff->set_off_qty;
                                            $item_history_setoff->reference_internal_number = $ih->internal_number;
                                            $item_history_setoff->reference_external_number = $ih->external_number;
                                            $item_history_setoff->reference_document_number = $ih->document_number;
                                            $item_history_setoff->setoff_id = $setOff->item_history_setoff_id;
                                            
                                            if($item_history_setoff->save()){
                                                $ih->setoff_quantity = $ih->setoff_quantity + $setOff->set_off_qty;
                                                $ih->update();
                                            }
                                        }
                                    }
                                }
                               
                            }
                        }
                    }
  
                }
                DB::commit();
                return response()->json(["status" => true, "primaryKey" => $GRN->goods_received_return_Id]);
            } else {
                return response()->json(["status" => false]);
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return $ex;
        }
    }

    //add return draft 
    public function addGRReturnDraft(Request $request)
    {
        try {
            $PreparedBy = Auth::user()->id;
            $collection = json_decode($request->input('collection'));
            $GRN = new GoodsReceivedNoteDraft();
            $GRN->internal_number = 0000;
            $GRN->external_number = $request->input('LblexternalNumber'); // need to change 
            $GRN->goods_received_date_time = $request->input('goods_received_date_time');
            $GRN->branch_id = $request->input('cmbBranch');
            $GRN->location_id = $request->input('cmbLocation');
            $GRN->supplier_id = $request->input('txtSupplier');
            $GRN->supplier_name = $request->input('lblSupplierName');
            $GRN->purchase_order_id = 1; //need to change
            $GRN->supppier_invoice_number = $request->input('txtSupplierInvoiceNumber');
            $GRN->invoice_amount = 1; // need to change
            $GRN->payment_due_date = $request->input('dtPaymentDueDate');
            $GRN->payment_mode_id = $request->input('cmbPaymentType');
            $GRN->discount_percentage = $request->input('txtDiscountPrecentage');
            $GRN->discount_amount = $request->input('txtDiscountAmount');
            $GRN->adjustment_amount = $request->input('txtAdjustmentAmount');
            $GRN->remarks = $request->input('txtRemarks');
            $GRN->document_number = 130;
            $GRN->prepaired_by = $PreparedBy;


            if ($GRN->save()) {

                //looping first array
                foreach ($collection as $i) {


                    $item = json_decode($i);
                    foreach ($item as $key => $value) {
                        if (is_string($value) && empty($value)) {
                            $item->$key = null;
                        }
                    }
                    $expireDate = $item->expire_date;
                    //  $carbonDate = Carbon::createFromFormat('d-m-Y', $expireDate);
                    /*  $date = date('Y-m-d H:i:s'); */
                    $GRN_item = new GoodsReceivedNoteItemDraft();
                    $GRN_item->goods_received_Id = $GRN->goods_received_Id;
                    $GRN_item->internal_number = 0000;
                    $GRN_item->external_number = $GRN->external_number; // need to change
                    $GRN_item->item_id = $item->item_id;
                    $GRN_item->item_name = $item->item_name;
                    $GRN_item->quantity = $item->qty;
                    $GRN_item->free_quantity = $item->free_quantity;
                    $GRN_item->unit_of_measure = $item->uom;
                    $GRN_item->package_unit = $item->PackUnit;
                    $GRN_item->package_size = $item->PackSize;
                    $GRN_item->price = $item->price;
                    $GRN_item->discount_percentage = $item->discount_percentage;
                    $GRN_item->discount_amount = $item->discount_amount;
                    $GRN_item->whole_sale_price = $item->whole_sale_price;
                    $GRN_item->retial_price = $item->retial_price;
                    $GRN_item->batch_number = $item->batch_number;
                    $GRN_item->expire_date = $item->expire_date;
                    $GRN_item->cost_price = $item->cost_price;
                    $GRN_item->save();
                }



                return response()->json(["status" => true]);
            } else {
                return response()->json(["status" => false]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //get retrun data
    public function getGRRetrundata()
    {

        try {
            $query = 'SELECT goodreceivereturns.goods_received_return_Id,goodreceivereturns.goods_received_date_time,goodreceivereturns.goods_received_date_time,
            goodreceivereturns.external_number,goodreceivereturns.supplier_name,goodreceivereturns.supppier_invoice_number,
            goodreceivereturns.approval_status,"Original" AS status,invoice_amount,goods_received_date_time FROM goodreceivereturns WHERE goodreceivereturns.document_number = 130 ORDER BY external_number DESC';
            $result = DB::select($query);
            if ($result) {
                return response()->json((['success' => 'Data loaded', 'data' => $result]));
            } else {
                return response()->json((['error' => 'Data is not loaded', 'data' => []]));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //update return
    public function updateGRReturn(Request $request, $id)
    {
        try {


            $collection = json_decode($request->input('collection'));
            $GRN = goods_received_note::find($id);
            $GRN->internal_number = 0000;
            $GRN->external_number = $request->input('LblexternalNumber'); // need to change 
            $GRN->goods_received_date_time = $request->input('goods_received_date_time');
            $GRN->branch_id = $request->input('cmbBranch');
            $GRN->location_id = $request->input('cmbLocation');
            $GRN->supplier_id = $request->input('txtSupplier');
            $GRN->supplier_name = $request->input('lblSupplierName');
            $GRN->purchase_order_id = 1; //need to change
            $GRN->supppier_invoice_number = $request->input('txtSupplierInvoiceNumber');
            $GRN->invoice_amount = 1; // need to change
            $GRN->payment_due_date = $request->input('dtPaymentDueDate');
            $GRN->payment_mode_id = $request->input('cmbPaymentType');
            $GRN->discount_percentage = $request->input('txtDiscountPrecentage');
            $GRN->discount_amount = $request->input('txtDiscountAmount');
            $GRN->adjustment_amount = $request->input('txtAdjustmentAmount');
            $GRN->remarks = $request->input('txtRemarks');
            //$GRN->prepaired_by = $PreparedBy; 


            if ($GRN->update()) {

                $deleteRequestItem = goods_received_note_item::where("goods_received_Id", "=", $id)->delete();
                //looping ifrst array
                foreach ($collection as $i) {
                    /*   $date = date('Y-m-d H:i:s'); */
                    $item = json_decode($i);
                    foreach ($item as $key => $value) {
                        if (is_string($value) && empty($value)) {
                            $item->$key = null;
                        }
                    }
                    $GRN_item = new goods_received_note_item();
                    $GRN_item->goods_received_Id = $GRN->goods_received_Id;
                    $GRN_item->internal_number = 0000;
                    $GRN_item->external_number = $GRN->external_number; // need to change
                    $GRN_item->item_id = $item->item_id;
                    $GRN_item->item_name = $item->item_name;
                    $GRN_item->quantity = $item->qty;
                    $GRN_item->free_quantity = $item->free_quantity;
                    $GRN_item->unit_of_measure = $item->uom;
                    $GRN_item->package_unit = $item->PackUnit;
                    $GRN_item->package_size = $item->PackSize;
                    $GRN_item->price = $item->price;
                    $GRN_item->discount_percentage = $item->discount_percentage;
                    $GRN_item->discount_amount = $item->discount_amount;
                    $GRN_item->whole_sale_price = $item->whole_sale_price;
                    $GRN_item->retial_price = $item->retial_price;
                    $GRN_item->batch_number = $item->batch_number;
                    $GRN_item->expire_date = $item->expire_date;
                    $GRN_item->cost_price = $item->cost_price;
                    $GRN_item->save();
                }

                return response()->json(["status" => true]);
            } else {
                return response()->json(["status" => false]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //update draft
    public function updateGRReturnDraft(Request $request, $id)
    {
        try {

            $collection = json_decode($request->input('collection'));
            $GRN = GoodsReceivedNoteDraft::find($id);
            $GRN->internal_number = 0000;
            $GRN->external_number = $request->input('LblexternalNumber'); // need to change 
            $GRN->goods_received_date_time = $request->input('goods_received_date_time');
            $GRN->branch_id = $request->input('cmbBranch');
            $GRN->location_id = $request->input('cmbLocation');
            $GRN->supplier_id = $request->input('txtSupplier');
            $GRN->supplier_name = $request->input('lblSupplierName');
            $GRN->purchase_order_id = 1; //need to change
            $GRN->supppier_invoice_number = $request->input('txtSupplierInvoiceNumber');
            $GRN->invoice_amount = 1; // need to change
            $GRN->payment_due_date = $request->input('dtPaymentDueDate');
            $GRN->payment_mode_id = $request->input('cmbPaymentType');
            $GRN->discount_percentage = $request->input('txtDiscountPrecentage');
            $GRN->discount_amount = $request->input('txtDiscountAmount');
            /*  $GRN->adjustment_amount = $request->input('txtAdjustmentAmount'); */
            $GRN->remarks = $request->input('txtRemarks');



            if ($GRN->update()) {
                $deleteRequestItem = GoodsReceivedNoteItemDraft::where("goods_received_Id", "=", $id)->delete();
                //looping first array
                foreach ($collection as $i) {
                    $item = json_decode($i);
                    foreach ($item as $key => $value) {
                        if (is_string($value) && empty($value)) {
                            $item->$key = null;
                        }
                    }
                    $expireDate = $item->expire_date;
                    //  $carbonDate = Carbon::createFromFormat('d-m-Y', $expireDate);
                    /*    $date = date('Y-m-d H:i:s'); */
                    $GRN_item = new GoodsReceivedNoteItemDraft();
                    $GRN_item->goods_received_Id = $GRN->goods_received_Id;
                    $GRN_item->internal_number = 0000;
                    $GRN_item->external_number = $GRN->external_number; // need to change
                    $GRN_item->item_id = $item->item_id;
                    $GRN_item->item_name = $item->item_name;
                    $GRN_item->quantity = $item->qty;
                    $GRN_item->free_quantity = $item->free_quantity;
                    $GRN_item->unit_of_measure = $item->uom;
                    $GRN_item->package_unit = $item->PackUnit;
                    $GRN_item->package_size = $item->PackSize;
                    $GRN_item->price = $item->price;
                    $GRN_item->discount_percentage = $item->discount_percentage;
                    $GRN_item->discount_amount = $item->discount_amount;
                    $GRN_item->whole_sale_price = $item->whole_sale_price;
                    $GRN_item->retial_price = $item->retial_price;
                    $GRN_item->batch_number = $item->batch_number;
                    $GRN_item->expire_date = $item->expire_date;
                    $GRN_item->cost_price = $item->cost_price;
                    $GRN_item->save();
                }

                return response()->json(["status" => true]);
            } else {
                return response()->json(["status" => false]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //pending approvals 
    public function getPendingapprovalsGRReturn()
    {
        try {
            $query = 'SELECT goods_received_Id,external_number,goods_received_date_time,payment_due_date,approval_status,branches.branch_name FROM goods_received_notes INNER JOIN branches ON goods_received_notes.branch_id = branches.branch_id WHERE approval_status = "Pending" AND document_number = 2';
            /* $pendingApprovals = purchase_request::where("approval_status","=","Pending")->get(); */
            $pendingApprovals = DB::select($query);
            if ($pendingApprovals) {
                return response()->json((['success' => 'Data loaded', 'data' => $pendingApprovals]));
            } else {
                return response()->json((['error' => 'Data not loaded', 'data' => []]));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //approve
    public function approveRequestGRReturn($id)
    {
        $approvedBy = Auth::user()->id;
        try {
            $request = goods_received_note::find($id);
            $request->approval_status = "Approved";
            $request->approved_by = $approvedBy;
            if ($request->update()) {
                return response()->json((['status' => true]));
            } else {
                return response()->json((['status' => false]));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //reject
    public function rejectRequestGRReturn($id)
    {
        $approvedBy = Auth::user()->id;
        try {
            $request = goods_received_note::find($id);
            $request->approval_status = "Rejected";
            $request->approved_by = $approvedBy;
            if ($request->update()) {
                return response()->json((['status' => true]));
            } else {
                return response()->json((['status' => false]));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //load grn to return model
    public function loadGRN($BranchIDforGRN)
    {
        try {
            $query = "SELECT goods_received_notes.goods_received_Id,goods_received_notes.external_number,goods_received_notes.goods_received_date_time,
            goods_received_notes.supplier_name,users.name FROM goods_received_notes INNER JOIN users ON goods_received_notes.prepaired_by = users.id
             WHERE goods_received_notes.branch_id = '" . $BranchIDforGRN . "'";

            $result = DB::select($query);
            if ($result) {
                return response()->json((['success' => 'Data loaded', 'data' => $result]));
            } else {
                return response()->json((['error' => 'Data not loaded', 'data' => []]));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //load GRN items to model table
    public function getGRN_Items($id)
    {
        try {
            $query = "SELECT 
            goods_received_note_items.*,
            items.Item_code,
            FORMAT((goods_received_note_items.quantity * goods_received_note_items.price) - goods_received_note_items.discount_amount, 2) AS Value,
            goods_received_note_items.quantity,
            goods_received_note_items.free_quantity
        FROM
            goods_received_note_items
        INNER JOIN
            items ON goods_received_note_items.item_id = items.item_id
        WHERE
            goods_received_note_items.goods_received_Id = '" . $id . "'";
            $result = DB::select($query);
            if ($result) {
                return response()->json((['success' => 'Data loaded', 'data' => $result]));
            } else {
                return response()->json((['error' => 'Data not loaded', 'data' => []]));
            }
        } catch (Exception $ex) {
        }
    }


    //load selected grn items in model to gtn return item table
    public function loadItems(Request $request, $branch_id, $grn_id)
    {
        try {
            $resultsArray = [];
            $collection = json_decode($request->get('Item_ids'));
            $order_ID = $grn_id;

            // Prepare an array to store the item IDs
            $itemIDs = [];
            foreach ($collection as $i) {
                $id = json_decode($i);
                $itemIDs[] = $id;
            }

            // Create a comma-separated string of item IDs for the IN clause
            $itemIDsString = implode(',', $itemIDs);
            $query = "SELECT 
            GRI.item_name,
            GRI.item_id,
            GRI.quantity,
            GRI.free_quantity,
            GRI.unit_of_measure,
            GRI.package_unit,
            GRI.package_size,
            GRI.price,
            GRI.discount_percentage,
            GRI.discount_amount,
            GRI.purchase_order_item_id,
            IT.Item_code,
            IT.whole_sale_price,
            IT.retial_price,
            IT.manage_batch,
            IT.manage_expire_date
            
        FROM 
            goods_received_note_items GRI
            INNER JOIN items IT ON GRI.item_id = IT.item_id
            INNER JOIN goods_received_notes GR ON GR.goods_received_Id = GRI.goods_received_Id 
        WHERE 
            GR.goods_received_Id = '6'
            AND GRI.item_id IN ('86')";


            $result = DB::select($query);
            if ($result) {
                $resultsArray = $result;
            }

            return response()->json(['success' => 'Data loaded', 'data' => $resultsArray]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //load selected grn items in model to gtn return item table
    public function get_selectedItem_grnReturn(Request $request, $branch_id, $grn_id, $location_id_)
    {
        try {
            $resultsArray = [];
            $collection = json_decode($request->get('Item_ids'));
            $good_receive_id = $grn_id;

            // Prepare an array to store the item IDs
            $itemIDs = [];
            foreach ($collection as $i) {
                $id = json_decode($i);
                $itemIDs[] = $id;
            }

            // Create a comma-separated string of item IDs for the IN clause
            $itemIDsString = implode(',', $itemIDs);
            $query = "SELECT 
            GRI.item_name,
            GRI.item_id,
            GRI.quantity,
            GRI.free_quantity,
            GRI.unit_of_measure,
            GRI.package_unit,
            GRI.package_size,
            GRI.price,
            GRI.discount_percentage,
            GRI.discount_amount,
            GRI.purchase_order_item_id,
            IT.Item_code,
            IT.whole_sale_price,
            IT.retial_price,
            IT.manage_batch,
            IT.manage_expire_date,
            (
               SELECT IF(ISNULL(SUM(quantity)), 0, SUM(quantity)) AS balance
               FROM item_historys
               WHERE item_id = GRI.item_id AND branch_id = '" . $branch_id . "' AND location_id = '" . $location_id_ . "'
           ) AS Balance
            
        FROM 
            goods_received_note_items GRI
            INNER JOIN items IT ON GRI.item_id = IT.item_id
            INNER JOIN goods_received_notes GR ON GR.goods_received_Id = GRI.goods_received_Id 
        WHERE 
            GR.goods_received_Id = '" . $good_receive_id . "'
            AND GRI.item_id IN (" . $itemIDsString . ")";

            $result = DB::select($query);
            foreach ($result as $res) {
                $res->setOffData = $this->getItemHistorySetoffBatch01($branch_id, $res->item_id, $location_id_);
            }
            if ($result) {
                $resultsArray = $result;
            }

            return response()->json(['success' => 'Data loaded', 'data' => $resultsArray]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function getheaderDetailsReturn($id)
    {
        try {
            $query = "SELECT goods_received_notes.supplier_id,
            goods_received_notes.discount_percentage,
            goods_received_notes.branch_id,
            goods_received_notes.purchase_order_id,
            goods_received_notes.supppier_invoice_number,
            goods_received_notes.payment_due_date,
            goods_received_notes.payment_mode_id,
            goods_received_notes.invoice_amount,
            goods_received_notes.discount_amount,
            suppliers.supplier_name,
            suppliers.primary_address,
            suppliers.supplier_code,
            goods_received_notes.location_id
            
     FROM goods_received_notes
     INNER JOIN suppliers ON goods_received_notes.supplier_id = suppliers.supplier_id
     WHERE goods_received_notes.goods_received_Id = '" . $id . "'";
            $result = DB::select($query);
            if ($result) {
                return response()->json(['success' => 'Data loaded', 'data' => $result]);
            } else {
                return response()->json(['success' => 'Data loaded', 'data' => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }



    public function getItemHistorySetoffBatch($branchID, $item_id, $location_id)
    {
        try {
            $query = "SELECT
            item_history_setoff_id,
            batch_number,
            item_id,
            quantity - setoff_quantity AS AvlQty,
            cost_price,
            whole_sale_price,
            retial_price
        FROM
            item_history_set_offs
        WHERE
        branch_id = '" . $branchID . "'
            AND item_id = '" . $item_id . "'
            AND location_id = '" . $location_id . "'
            AND price_status = 0
            AND quantity - setoff_quantity > 0
            
        ";
            $result = DB::select($query);
            if ($result) {
                return response()->json(['success' => 'Data loaded', 'data' => $result]);
            } else {
                return response()->json(['Error' => 'Data Not loaded', 'data' => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function getItemHistorySetoffBatch01($branchID, $item_id, $location_id)
    {

        ini_set('max_execution_time', '0');
        $query = "SELECT
            item_history_setoff_id,
            batch_number,
            item_id,
            quantity - setoff_quantity AS AvlQty,
            cost_price,
            whole_sale_price,
            retial_price
        FROM
            item_history_set_offs
        WHERE
        branch_id = '" . $branchID . "'
            AND item_id = '" . $item_id . "'
            AND price_status = 0
            AND quantity - setoff_quantity > 0
            
        ";
        $result = DB::select($query);
        return $result;
    }



    public function getItemInfotogrnReturn($branch_id, $item_id, $location_id)
    {
        try {
            $query = "SELECT
            IT.Item_code,
            IT.unit_of_measure,
            IT.item_Name,
            IT.average_cost_price,
            IT.package_size,
            IT.package_unit,
            IT.previouse_purchase_price,
            IT.manage_batch,
            (SELECT IF(ISNULL(SUM(quantity-setoff_quantity)), 0, SUM(quantity-setoff_quantity)) FROM item_history_set_offs WHERE item_id='" . $item_id . "' AND branch_id='" . $branch_id . "' AND location_id='" . $location_id . "' AND quantity>0  AND price_status = 0) AS Balance
        FROM
            items IT
        WHERE
            IT.item_id = '" . $item_id . "';
        ";
            $result = DB::select($query);
            //  $info = item::find($Item_id);
            if ($result) {
                return response()->json($result);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //delete gr return

    public function deleteGR_RTN($id, $status)
    {
        try {
            if ($status == "Original") {
                $GRN = goodreceivereturn::find($id);
                if ($GRN->delete()) {
                    $GRN_item = goodreceivereturnItem::where('goods_received_return_Id', '=', $id)->delete();;

                    return response()->json(["message" => "Deleted"]);
                } else {
                    return response()->json(["message" => "Not Deleted"]);
                }
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //get each gr rtn
    public function getEachGR_return($id, $status)
    {
        try {
            if ($status == 'Original') {
                $query = 'SELECT goodreceivereturns.*,suppliers.primary_address,suppliers.supplier_code,suppliers.supplier_id,suppliers.supplier_name,suppliers.primary_address FROM goodreceivereturns LEFT JOIN suppliers ON goodreceivereturns.supplier_id = suppliers.supplier_id WHERE goodreceivereturns.goods_received_return_Id ="' . $id . '"';
                $result = DB::select($query);
                if ($result) {
                    return response()->json((['success' => 'Data loaded', 'data' => $result]));
                } else {
                    return response()->json((['error' => 'Data not loaded', 'data' => []]));
                }
            } else {
                $query = 'SELECT *,suppliers.primary_address FROM goods_received_note_drafts LEFT JOIN suppliers ON goods_received_note_drafts.supplier_id = suppliers.supplier_id WHERE goods_received_note_drafts.goods_received_Id ="' . $id . '"';
                $result = DB::select($query);
                if ($result) {
                    return response()->json((['success' => 'Data loaded', 'data' => $result]));
                } else {
                    return response()->json((['error' => 'Data not loaded', 'data' => []]));
                }
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //get each gr rtn item
    public function getEachproductofGR_rtn($id)
    {
        try {

            $query = 'SELECT goodreceivereturn_items.*,items.Item_code from goodreceivereturn_items INNER JOIN items ON goodreceivereturn_items.item_id = items.item_id WHERE goodreceivereturn_items.goods_received_return_Id = "' . $id . '"';
            $item = DB::select($query);
            if ($item) {
                return response()->json($item);
            } else {
                return response()->json((['error' => 'Data not loaded', 'data' => []]));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //get foc_qty,offer data to theshold foc calculation
    public function getItem_foc_threshold_For_goods_returns($item_id, $entered_qty, $date)
    {

        try {

            $query = "SELECT IFNULL(sd_free_offerd_quantity_goods_return('" . $item_id . "','" . $entered_qty . "','" . $date . "'), 0) AS Offerd_quantity";
            $result = DB::select($query);
            if ($result) {
                return response()->json($result);
            } else {
                return response()->json(['error' => 'Data not loaded', 'data' => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //get pr price
    public function get_Pr_price($id)
    {
        $qry = "SELECT previouse_purchase_price FROM items WHERE item_id= $id";
        $result = DB::select($qry);
        if ($result) {
            return response()->json($result);
        } else {
            return response()->json(['error' => 'Data not loaded', 'data' => []]);
        }
    }

    public function loadAllLocation($id)
    {
        try {
            $locations = location::where('branch_id', '=', $id)
                ->where('Status', '=', 1)
                ->get();
            if ($locations) {
                return response()->json($locations);
            } else {
                return response()->json(['status' => false]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //load item details for rtn on set off
    public function get_item_details_for_goods_rtn($id)
    {
        try {
            $item_data = "SELECT Item_code,item_Name,package_unit,unit_of_measure FROM items WHERE item_id = $id";
            $result = DB::select($item_data);
            if ($result) {
                return response()->json($result);
            } else {
                return response()->json(['status' => false]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }
}
