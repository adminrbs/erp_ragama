<?php

namespace Modules\Prc\Http\Controllers;

use App\Http\Controllers\IntenelNumberController;
use App\Http\Controllers\price_status_controller;
use App\Models\global_setting;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Md\Entities\supplier;
use Modules\Prc\Entities\creditors_ledger;
use Modules\Prc\Entities\creditors_ledger_setoff;
use Modules\Prc\Entities\GoodsReceivedNoteDraft;
use Modules\Prc\Entities\GoodsReceivedNoteItemDraft;
use Modules\Prc\Entities\goods_received_note;
use Modules\Prc\Entities\goods_received_note_item;
use Modules\Prc\Entities\item;
use Modules\Prc\Entities\item_history;
use Modules\Prc\Entities\item_history_setOff;
use Modules\Prc\Entities\SupplierPaymentMethod;
use Modules\Prc\Entities\purchase_order_note;
use Modules\Prc\Entities\purchase_order_note_item;

class GoodReceivedController extends Controller
{


    //loadPamentType
    public function loadPamentType()
    {
        try {
            $paymentTypes = supplierPaymentMethod::all();
            if ($paymentTypes) {
                return response()->json($paymentTypes);
            } else {
                return response()->json(['status' => false]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //load supplier to chooser
    public function loadSupplierTochooser()
    {
        /* $suppliers = supplier::all(); */
        $qry = 'SELECT supplier_code as value,supplier_name as id,supplier_id as hidden_id FROM suppliers WHERE suppliers.supplier_status = 1';
        $result = DB::select($qry);
        if ($result) {
            return response()->json(['data' => $result]);
        } else {
            return response()->json(['status' => false]);
        }
    }

    public function loadSupplierOtherDetails($id)
    {
        try {
            $qry = 'SELECT primary_address,supplier_id FROM suppliers WHERE supplier_code = "' . $id . '"';
            $result = DB::select($qry);
            if ($result) {
                return response()->json(['data' => $result]);
            } else {
                return response()->json(['status' => false]);
            }
        } catch (Exception $ex) {

            return $ex;
        }
    }


    //add GRN
    public function addGRN(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $global_setting = global_setting::first();

            if ($global_setting->direct_grn == 1) {
                $margin_gaps = [];

                $collection = json_decode($request->input('collection'));
                //looping first array to check prices and margin
                foreach ($collection as $i) {

                    $item = json_decode($i);

                    if (is_null($item->whole_sale_price) || floatval($item->whole_sale_price) == 0) {
                        return response()->json(["message" => "null"]);
                    } /* else if (is_null($item->retial_price) || floatval($item->retial_price) == 0) {
                                return response()->json(["message" => "null retail"]);
                            } */ else if (is_null($item->cost_price) || floatval($item->cost_price) == 0) {
                        //return response()->json(["message" => "null cost"]);
                    } else if (is_null($item->price) || floatval($item->price) == 0) {
                        return response()->json(["message" => "null price"]);
                    }
                    $Itm_ID = $item->item_id;
                    $item_ = item::find($Itm_ID);
                    $minimum_margin_ =  $item_->minimum_margin;

                    if (floatval($minimum_margin_) > 0) {

                        $cost = floatval($item->price) - (((floatval($item->price)) / 100) * floatval($item->discount_percentage));
                        $margin = ((floatval($item->whole_sale_price) - $cost) / floatval($item->whole_sale_price)) * 100;

                        if (floatval($minimum_margin_) > floatval($margin)) {
                            $item_code_ = $item_->Item_code;
                            array_push($margin_gaps, $item_code_);
                        }
                    }
                }
                if (count($margin_gaps) > 0) {
                    return response()->json(["message" => "margin_gaps", "margin_gaps" => $margin_gaps]);
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

                $GRN = new goods_received_note();
                $GRN->internal_number = IntenelNumberController::getNextID();
                $GRN->external_number = $externalNumber; // need to change 
                $GRN->goods_received_date_time = $request->input('goods_received_date_time');
                $GRN->branch_id = $request->input('cmbBranch');
                $GRN->location_id = $request->input('cmbLocation');
                $GRN->supplier_id = $request->input('txtSupplier');
                $GRN->supplier_name = $request->input('lblSupplierName');
                $GRN->purchase_order_id = $request->input('txtPurchaseORder');
                $GRN->supppier_invoice_number = $request->input('txtSupplierInvoiceNumber');
                $GRN->invoice_amount = $request->input('txtInvoiceAmount');
                $GRN->payment_due_date = $request->input('dtPaymentDueDate');
                $GRN->payment_mode_id = $request->input('cmbPaymentType');
                $GRN->discount_percentage = $request->input('txtDiscountPrecentage');
                $GRN->discount_amount = $request->input('txtDiscountAmount');
                $GRN->adjustment_amount = $request->input('txtAdjustmentAmount');
                $GRN->remarks = $request->input('txtRemarks');
                $GRN->document_number = 120;
                $GRN->prepaired_by = $PreparedBy;
                $GRN->your_reference_number = $request->input('txtYourReference');
                $GRN->total_amount = $request->input('lblNetTotal');


                if ($GRN->save()) {



                    //looping first array
                    foreach ($collection as $i) {

                        $item = json_decode($i);
                        foreach ($item as $key => $value) {
                            if (is_string($value) && empty($value)) {
                                $item->$key = null;
                            }
                        }
                        $itm_id = $item->item_id;
                        $item_code = "";
                        $query = DB::select("SELECT Item_code FROM items WHERE item_id = '" . $itm_id . "'");
                        if ($query) {
                            $item_code = $query[0]->Item_code;
                        }

                        $GRN_item = new goods_received_note_item();
                        $GRN_item->goods_received_Id = $GRN->goods_received_Id;
                        $GRN_item->internal_number = $GRN->internal_number;
                        $GRN_item->external_number = $GRN->external_number; // need to change
                        $GRN_item->item_id = $item->item_id;
                        $GRN_item->item_name = $item->item_name;
                        // $GRN_item->quantity = $item->qty;
                        if ($item->qty) {
                            $GRN_item->quantity = $item->qty;
                        } else {
                            $GRN_item->quantity = 0;
                        }


                        if ($item->free_quantity) {
                            $GRN_item->free_quantity = $item->free_quantity;
                        } else {
                            $GRN_item->free_quantity = 0;
                        }

                        if ($item->addBonus) {
                            $GRN_item->additional_bonus = $item->addBonus;
                        } else {
                            $GRN_item->additional_bonus = 0;
                        }

                        if ($item->PackUnit) {
                            $GRN_item->package_unit = $item->PackUnit;
                        } else {
                            $GRN_item->package_unit = 0;
                        }

                        if ($item->PackSize) {
                            $GRN_item->package_size = $item->PackSize;
                        } else {
                            $GRN_item->package_size = 0;
                        }

                        if ($item->price) {
                            $GRN_item->price = $item->price;
                        } else {
                            $GRN_item->price = 0;
                        }

                        if ($item->discount_percentage) {
                            $GRN_item->discount_percentage = $item->discount_percentage;
                        } else {
                            $GRN_item->discount_percentage = 0;
                        }

                        if ($item->discount_amount) {
                            $GRN_item->discount_amount = $item->discount_amount;
                        } else {
                            $GRN_item->discount_amount = 0;
                        }


                        $GRN_item->whole_sale_price = $item->whole_sale_price;
                        $GRN_item->retial_price = $item->retial_price;

                        if ($item->previouse_whole_sale_price) {
                            $GRN_item->previouse_whole_sale_price = $item->previouse_whole_sale_price;
                        } else {
                            $GRN_item->previouse_whole_sale_price = 0;
                        }

                        if ($item->previouse_retial_price) {
                            $GRN_item->previouse_retail_price = $item->previouse_retial_price;
                        } else {
                            $GRN_item->previouse_retail_price = 0;
                        }

                        if ($item->batch_number) {
                            $GRN_item->batch_number = $item->batch_number;
                        } else {
                            $GRN_item->batch_number = $item_code;
                        }

                        if ($item->expire_date) {
                            $GRN_item->expire_date = $item->expire_date;
                        } else {
                            $GRN_item->expire_date = null;
                        }

                        if ($item->cost_price) {
                            $GRN_item->cost_price = $item->cost_price;
                        } else {
                            $GRN_item->cost_price = 0;
                        }

                        $GRN_item->purchase_order_item_id = $item->purchase_order_item_id;
                        $GRN_item->is_new_price = $item->is_new_price;
                        $GRN_item->save();
                    }


                    DB::commit();
                    return response()->json(["status" => true, "primaryKey" => $GRN->goods_received_Id, "PO_id" => $GRN->purchase_order_id]);
                } else {
                    DB::rollBack();
                    return response()->json(["status" => false]);
                }
            } else {
                $margin_gaps = [];
                $PO_id_ = $request->input('txtPurchaseORder');
                $query = "SELECT status FROM purchase_order_notes WHERE purchase_order_Id = " . $PO_id_;
                $result = DB::select($query);
                if ($result) {
                    $PO_status = $result[0]->status;
                    if ($PO_status != 0) {
                        return response()->json(["status" => false, "message" => "completed"]);
                    } else {

                        $collection = json_decode($request->input('collection'));
                        //looping first array to check prices and margin
                        foreach ($collection as $i) {

                            $item = json_decode($i);

                            if (is_null($item->whole_sale_price) || floatval($item->whole_sale_price) == 0) {
                                return response()->json(["message" => "null"]);
                            } /* else if (is_null($item->retial_price) || floatval($item->retial_price) == 0) {
                                return response()->json(["message" => "null retail"]);
                            } */ else if (is_null($item->cost_price) || floatval($item->cost_price) == 0) {
                                //return response()->json(["message" => "null cost"]);
                            } else if (is_null($item->price) || floatval($item->price) == 0) {
                                return response()->json(["message" => "null price"]);
                            }
                            $Itm_ID = $item->item_id;
                            $item_ = item::find($Itm_ID);
                            $minimum_margin_ =  $item_->minimum_margin;

                            if (floatval($minimum_margin_) > 0) {

                                $cost = floatval($item->price) - (((floatval($item->price)) / 100) * floatval($item->discount_percentage));
                                $margin = ((floatval($item->whole_sale_price) - $cost) / floatval($item->whole_sale_price)) * 100;

                                if (floatval($minimum_margin_) > floatval($margin)) {
                                    $item_code_ = $item_->Item_code;
                                    array_push($margin_gaps, $item_code_);
                                }
                            }
                        }
                        if (count($margin_gaps) > 0) {
                            return response()->json(["message" => "margin_gaps", "margin_gaps" => $margin_gaps]);
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

                        $GRN = new goods_received_note();
                        $GRN->internal_number = IntenelNumberController::getNextID();
                        $GRN->external_number = $externalNumber; // need to change 
                        $GRN->goods_received_date_time = $request->input('goods_received_date_time');
                        $GRN->branch_id = $request->input('cmbBranch');
                        $GRN->location_id = $request->input('cmbLocation');
                        $GRN->supplier_id = $request->input('txtSupplier');
                        $GRN->supplier_name = $request->input('lblSupplierName');
                        $GRN->purchase_order_id = $request->input('txtPurchaseORder');
                        $GRN->supppier_invoice_number = $request->input('txtSupplierInvoiceNumber');
                        $GRN->invoice_amount = $request->input('txtInvoiceAmount');
                        $GRN->payment_due_date = $request->input('dtPaymentDueDate');
                        $GRN->payment_mode_id = $request->input('cmbPaymentType');
                        $GRN->discount_percentage = $request->input('txtDiscountPrecentage');
                        $GRN->discount_amount = $request->input('txtDiscountAmount');
                        $GRN->adjustment_amount = $request->input('txtAdjustmentAmount');
                        $GRN->remarks = $request->input('txtRemarks');
                        $GRN->document_number = 120;
                        $GRN->prepaired_by = $PreparedBy;
                        $GRN->your_reference_number = $request->input('txtYourReference');
                        $GRN->total_amount = $request->input('lblNetTotal');


                        if ($GRN->save()) {



                            //looping first array
                            foreach ($collection as $i) {

                                $item = json_decode($i);
                                foreach ($item as $key => $value) {
                                    if (is_string($value) && empty($value)) {
                                        $item->$key = null;
                                    }
                                }
                                $itm_id = $item->item_id;
                                $item_code = "";
                                $query = DB::select("SELECT Item_code FROM items WHERE item_id = '" . $itm_id . "'");
                                if ($query) {
                                    $item_code = $query[0]->Item_code;
                                }

                                $GRN_item = new goods_received_note_item();
                                $GRN_item->goods_received_Id = $GRN->goods_received_Id;
                                $GRN_item->internal_number = $GRN->internal_number;
                                $GRN_item->external_number = $GRN->external_number; // need to change
                                $GRN_item->item_id = $item->item_id;
                                $GRN_item->item_name = $item->item_name;
                                // $GRN_item->quantity = $item->qty;
                                if ($item->qty) {
                                    $GRN_item->quantity = $item->qty;
                                } else {
                                    $GRN_item->quantity = 0;
                                }


                                if ($item->free_quantity) {
                                    $GRN_item->free_quantity = $item->free_quantity;
                                } else {
                                    $GRN_item->free_quantity = 0;
                                }

                                if ($item->addBonus) {
                                    $GRN_item->additional_bonus = $item->addBonus;
                                } else {
                                    $GRN_item->additional_bonus = 0;
                                }

                                if ($item->PackUnit) {
                                    $GRN_item->package_unit = $item->PackUnit;
                                } else {
                                    $GRN_item->package_unit = 0;
                                }

                                if ($item->PackSize) {
                                    $GRN_item->package_size = $item->PackSize;
                                } else {
                                    $GRN_item->package_size = 0;
                                }

                                if ($item->price) {
                                    $GRN_item->price = $item->price;
                                } else {
                                    $GRN_item->price = 0;
                                }

                                if ($item->discount_percentage) {
                                    $GRN_item->discount_percentage = $item->discount_percentage;
                                } else {
                                    $GRN_item->discount_percentage = 0;
                                }

                                if ($item->discount_amount) {
                                    $GRN_item->discount_amount = $item->discount_amount;
                                } else {
                                    $GRN_item->discount_amount = 0;
                                }


                                $GRN_item->whole_sale_price = $item->whole_sale_price;
                                $GRN_item->retial_price = $item->retial_price;

                                if ($item->previouse_whole_sale_price) {
                                    $GRN_item->previouse_whole_sale_price = $item->previouse_whole_sale_price;
                                } else {
                                    $GRN_item->previouse_whole_sale_price = 0;
                                }

                                if ($item->previouse_retial_price) {
                                    $GRN_item->previouse_retail_price = $item->previouse_retial_price;
                                } else {
                                    $GRN_item->previouse_retail_price = 0;
                                }

                                if ($item->batch_number) {
                                    $GRN_item->batch_number = $item->batch_number;
                                } else {
                                    $GRN_item->batch_number = $item_code;
                                }

                                if ($item->expire_date) {
                                    $GRN_item->expire_date = $item->expire_date;
                                } else {
                                    $GRN_item->expire_date = null;
                                }

                                if ($item->cost_price) {
                                    $GRN_item->cost_price = $item->cost_price;
                                } else {
                                    $GRN_item->cost_price = 0;
                                }

                                $GRN_item->purchase_order_item_id = $item->purchase_order_item_id;
                                $GRN_item->is_new_price = $item->is_new_price;
                                $GRN_item->save();
                            }
                            $setting = global_setting::first();
                            if ($setting->approved == 0) {
                                if ($PO_id_ > 0) {
                                    $PO = purchase_order_note::find($PO_id_);
                                    $PO->approval_status = "Approved";
                                    $PO->update();
                                }
                            }

                            DB::commit();
                            return response()->json(["status" => true, "primaryKey" => $GRN->goods_received_Id, "PO_id" => $GRN->purchase_order_id]);
                        } else {
                            DB::rollBack();
                            return response()->json(["status" => false]);
                        }
                    }
                }
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //add draft
    public function addGRNDraft(Request $request)
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
            $GRN->document_number = 120;
            $GRN->prepaired_by = $PreparedBy;
            $GRN->your_reference_number = $request->input('txtYourReference');
            $GRN->total_amount = $request->input('lblNetTotal');


            if ($GRN->save()) {

                //looping first array
                foreach ($collection as $i) {


                    $item = json_decode($i);
                    foreach ($item as $key => $value) {
                        if (is_string($value) && empty($value)) {
                            $item->$key = null;
                        }
                    }
                    /*  $expireDate = $item->expire_date; */
                    //  $carbonDate = Carbon::createFromFormat('d-m-Y', $expireDate);
                    /*  $date = date('Y-m-d H:i:s'); */
                    $GRN_item = new GoodsReceivedNoteItemDraft();
                    $GRN_item->goods_received_Id = $GRN->goods_received_Id;
                    $GRN_item->internal_number = $GRN->internal_number;
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
                    $GRN_item->purchase_order_item_id = $item->purchase_order_item_id;
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

    //get GRN data
    public function getGRNdata()
    {
        try {
            $query = 'SELECT goods_received_Id, goods_received_date_time, goods_received_date_time,invoice_amount,
            external_number, supplier_name, supppier_invoice_number, approval_status, "Draft" AS status
     FROM goods_received_note_drafts
     WHERE document_number = 120
     
     UNION
     
     SELECT goods_received_Id, goods_received_date_time, goods_received_date_time,invoice_amount,
            external_number, supplier_name, supppier_invoice_number, approval_status, "Original" AS status
     FROM goods_received_notes
     WHERE document_number = 120
     
     ORDER BY external_number DESC;
     ';
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


    //delete GRN
    public function deleteGRN($id, $status)
    {
        try {
            if ($status == "Original") {
                $GRN = goods_received_note::find($id);
                if ($GRN->delete()) {
                    $GRN_item = goods_received_note_item::where('goods_received_Id', '=', $id)->delete();;

                    return response()->json(["message" => "Deleted"]);
                } else {
                    return response()->json(["message" => "Not Deleted"]);
                }
            } else {
                $GRN_draft = GoodsReceivedNoteDraft::find($id);
                if ($GRN_draft->delete()) {
                    $rqst_item_draft = GoodsReceivedNoteItemDraft::where('goods_received_Id', '=', $id)->delete();

                    return response()->json(["message" => "Deleted"]);
                } else {
                    return response()->json(["message" => "Not Deleted"]);
                }
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //get each GRN
    public function getEachGRN($id, $status)
    {
        try {
            if ($status == 'Original') {
                $query = 'SELECT goods_received_notes.*,purchase_order_notes.external_number AS p_external_number,suppliers.primary_address,suppliers.supplier_code,suppliers.supplier_id FROM goods_received_notes LEFT JOIN purchase_order_notes ON goods_received_notes.purchase_order_id = purchase_order_notes.purchase_order_Id LEFT JOIN suppliers ON goods_received_notes.supplier_id = suppliers.supplier_id WHERE goods_received_notes.goods_received_Id ="' . $id . '"';
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

    //get each item
    public function getEachproductofGRN($id, $status)
    {
        try {
            if ($status == "Original") {
                $query = 'SELECT goods_received_note_items.*,items.Item_code from goods_received_note_items INNER JOIN items ON goods_received_note_items.item_id = items.item_id WHERE goods_received_note_items.goods_received_Id = "' . $id . '"';
                $item = DB::select($query);
                if ($item) {
                    return response()->json($item);
                } else {
                    return response()->json((['error' => 'Data not loaded', 'data' => []]));
                }
            } else {
                $query = 'SELECT goods_received_note_item_drafts.*,items.Item_code from goods_received_note_item_drafts INNER JOIN items ON goods_received_note_item_drafts.item_id = items.item_id WHERE goods_received_note_item_drafts.goods_received_Id = "' . $id . '"';
                $item = DB::select($query);
                if ($item) {
                    return response()->json($item);
                } else {
                    return response()->json((['error' => 'Data not loaded', 'data' => []]));
                }
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //update GRN
    public function updateGRN(Request $request, $id)
    {
        try {

            $PreparedBy = Auth::user()->id;
            $collection = json_decode($request->input('collection'));
            $GRN = goods_received_note::find($id);
            /*  $GRN->internal_number = 0000; */
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
            $GRN->your_reference_number = $request->input('txtYourReference');
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
                    $GRN_item->internal_number = IntenelNumberController::getNextID();
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

    //update GRN drafts
    public function updateGRNDraft(Request $request, $id)
    {
        try {
            $PreparedBy = Auth::user()->id;
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
            $GRN->adjustment_amount = $request->input('txtAdjustmentAmount');
            $GRN->remarks = $request->input('txtRemarks');
            // $GRN->prepaired_by = $PreparedBy;


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
                    $GRN_item->internal_number = $GRN->internal_number;
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



    public function getPendingapprovalsGRN()
    {
        try {
            $query = 'SELECT goods_received_Id,total_amount,external_number,goods_received_date_time,payment_due_date,approval_status,branches.branch_name,"Original" AS Status FROM goods_received_notes INNER JOIN branches ON goods_received_notes.branch_id = branches.branch_id WHERE approval_status = "Pending" AND document_number = 120 ORDER BY external_number DESC';
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


    //approve GRN
    public function approveRequestGRN($id)
    {
        $approvedBy = Auth::user()->id;
        DB::beginTransaction();
        try {
            $request = goods_received_note::find($id);
            $request->approval_status = "Approved";
            $request->approved_by = $approvedBy;
            $grn_items = goods_received_note_item::where("goods_received_Id", "=", $id)->get();
            $query = Db::select("SELECT supplier_name,branch_id,location_id,goods_received_date_time,document_number,purchase_order_id FROM goods_received_notes WHERE goods_received_notes.goods_received_Id = $id");
            $branch_id_ = $query[0]->branch_id;
            $lcation_id_ = $query[0]->location_id;
            $goods_received_date_time_ = $query[0]->goods_received_date_time;
            $document_number = $query[0]->document_number;
            $purchase_order_id = $query[0]->purchase_order_id;
            $sup = $query[0]->supplier_name;
            $desc = "Goods Received FROM" . " " . $sup;
            $sup_obj = supplier::find($request->supplier_id);

            if ($request->update()) {

                //save creditors ledger
                $creditors_ledger = new creditors_ledger();
                $creditors_ledger->internal_number = $request->internal_number;
                $creditors_ledger->external_number = $request->external_number;
                $creditors_ledger->document_number = $request->document_number;
                $creditors_ledger->trans_date = $request->goods_received_date_time;
                $creditors_ledger->description = "Goods Received From " . $request->supplier_name;
                $creditors_ledger->branch_id = $request->branch_id;
                $creditors_ledger->supplier_id = $request->supplier_id;
                $creditors_ledger->supplier_code = $sup_obj->supplier_code;
                $creditors_ledger->amount = -$request->total_amount;
                $creditors_ledger->save();

                //save creditors ledger set off
                $creditors_ledger_setoff = new creditors_ledger_setoff();
                $creditors_ledger_setoff->internal_number = $creditors_ledger->internal_number;
                $creditors_ledger_setoff->external_number = $creditors_ledger->external_number;
                $creditors_ledger_setoff->document_number = $creditors_ledger->document_number;
                $creditors_ledger_setoff->trans_date = $creditors_ledger->trans_date;
                $creditors_ledger_setoff->description = $creditors_ledger->description;
                $creditors_ledger_setoff->branch_id = $creditors_ledger->branch_id;
                $creditors_ledger_setoff->supplier_id = $creditors_ledger->supplier_id;
                $creditors_ledger_setoff->supplier_code = $creditors_ledger->supplier_code;
                $creditors_ledger_setoff->amount = $creditors_ledger->amount;
                $creditors_ledger_setoff->save();

                //item historys
                foreach ($grn_items as $item) {
                    $total_qty = floatval($item->quantity) + floatval($item->free_quantity);
                    $item_history = new item_history();
                    $item_history->internal_number = $item->internal_number;
                    $item_history->external_number = $item->external_number;
                    $item_history->branch_id = $branch_id_;
                    $item_history->location_id = $lcation_id_;
                    $item_history->document_number = $document_number;
                    $item_history->transaction_date = $goods_received_date_time_;
                    $item_history->description = $desc;
                    $item_history->item_id = $item->item_id;
                    $item_history->quantity = $total_qty;
                    $item_history->free_quantity = $item->free_quantity;
                    $item_history->batch_number = $item->batch_number;
                    $item_history->whole_sale_price = $item->whole_sale_price;
                    $item_history->retial_price = $item->retial_price;
                    $item_history->expire_date = $item->expire_date;
                    $item_history->cost_price = $item->cost_price;
                    $item_history->save();
                }

                //item history set off
                foreach ($grn_items as $item) {
                    $total_qty = floatval($item->quantity) + floatval($item->free_quantity);
                    $item_history = new item_history_setOff();
                    $item_history->internal_number = $item->internal_number;
                    $item_history->external_number = $item->external_number;
                    $item_history->document_number = $document_number;
                    $item_history->batch_number = $item->batch_number;
                    $item_history->expire_date = $item->expire_date;
                    $item_history->branch_id = $branch_id_;
                    $item_history->location_id = $lcation_id_;
                    $item_history->transaction_date = $goods_received_date_time_;
                    $item_history->item_id = $item->item_id;
                    $item_history->whole_sale_price = $item->whole_sale_price;
                    $item_history->retial_price = $item->retial_price;
                    $item_history->cost_price = $item->cost_price;
                    $item_history->quantity = $total_qty;
                    $item_history->reference_internal_number = $item->internal_number;
                    $item_history->reference_external_number = $item->external_number;
                    $item_history->reference_document_number = $item->external_number;
                    $item_history->price_status = 0; //price_status_controller::validte_whole_sale_price($branch_id_,$lcation_id_,$item->item_id,$item->whole_sale_price); changed on 04/06 as per instruction of sachin
                    $item_history->save();
                }

                //purchase order items recived qty update
                foreach ($grn_items as $item) {

                    $po_item = purchase_order_note_item::find($item->purchase_order_item_id);
                    if ($po_item) {
                        $po_item->quantity_received = floatval($po_item->quantity_received) + floatval($item->quantity);
                        $po_item->free_received =  floatval($po_item->free_received) + floatval($item->free_quantity);
                        $po_item->update();
                        $this->completeOrderstatus_auto_new($po_item->purchase_order_Id);
                    }
                }

                //update previouse purchase price
                foreach ($grn_items as $item) {
                    $items = item::find($item->item_id);
                    $items->previouse_purchase_price = $item->price;
                    $items->update();
                }

                DB::commit();
                return response()->json((['status' => true, 'PO_id' => $purchase_order_id]));
            } else {
                return response()->json((['status' => false]));
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return $ex;
        }
    }


    //new function
    public function completeOrderstatus_auto_new($id)
    {
        try {


            $query = "SELECT COUNT(*) AS TotalCount
                FROM purchase_order_note_items
                WHERE (purchase_order_note_items.quantity - purchase_order_note_items.quantity_received > 0)
                AND purchase_order_note_items.purchase_order_Id = '" . $id . "'";

            $result = DB::select($query);
            $totalCount = $result[0]->TotalCount;

            if ($totalCount > 0) {
                // return response()->json(['status' => false, 'message' => 'Yes']);
            } else if ($totalCount == 0) {
                $PO = purchase_order_note::find($id);
                $PO->status = 1;
                if ($PO->update()) {
                    //   return response()->json(['status' => true]);
                } else {
                    //  return response()->json(['status' => false]);
                }
            } else {
                //  return response()->json(['status' => $totalCount]);
            }
        } catch (Exception $ex) {
            //  return $ex;
        }
    }

    public function rejectRequestGRN($id)
    {
        $approvedBy = Auth::user()->id;
        try {
            $request = goods_received_note::find($id);
            $request->approval_status = "Rejected";
            $request->approved_by = $approvedBy;
            $grn_items = goods_received_note_item::where("goods_received_Id", "=", $id)->get();
            $query = Db::select("SELECT branch_id,location_id,goods_received_date_time,document_number,purchase_order_id FROM goods_received_notes WHERE goods_received_notes.goods_received_Id = $id");
            $purchase_order_id = $query[0]->purchase_order_id;
            if ($request->update()) {

                //purchase order items recived qty update
                foreach ($grn_items as $item) {
                    $po_item = purchase_order_note_item::find($item->purchase_order_item_id);
                    $po_item->quantity_received = floatval($po_item->quantity_received) - floatval($item->quantity);
                    $po_item->free_received =  floatval($po_item->free_received) - floatval($item->free_quantity);
                    $po_item->update();

                    if (floatval($po_item->quantity) - floatval($po_item->quantity_received) > 0) {
                        $po = purchase_order_note::find($purchase_order_id);
                        $po->status = 0;
                        $po->update();
                    }
                }



                return response()->json((['status' => true]));
            } else {
                return response()->json((['status' => false]));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function getServerTime()
    {
        try {
            $serverDate = Carbon::now()->format('d/m/Y');
            return response()->json(['date' => $serverDate]);
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //get approved PO to model to create GRN
    public function getPendingPurchaseOrder($branch_id)
    {
        try {

            $settings = global_setting::first();
            //  dd($settings);
            /* FORMAT(SUM(I.quantity * I.price), 2) AS total_amount */
            $query = "";
            if ($settings->approved == 1) {
                $query = "SELECT 
                P.purchase_order_Id, 
                P.purchase_order_date_time, 
                P.external_number, 
                P.supplier_name, 
                P.deliver_date_time, 
                U.name AS prepaired_by,
               
               
               FORMAT(SUM(((I.quantity - I.quantity_received) * I.cost_price - (((I.quantity - I.quantity_received) * I.cost_price) * I.discount_percentage) / 100 )), 2) AS total_amount
            FROM 
                purchase_order_notes P
            INNER JOIN 
                users U ON P.prepaired_by = U.id 
            LEFT JOIN 
                purchase_order_note_items I ON P.purchase_order_Id = I.purchase_order_Id
            WHERE 
                P.approval_status = 'Approved'
            AND
                P.status = 0
            AND
                P.branch_id =   '" . $branch_id . "'
            GROUP BY 
                P.purchase_order_Id, 
                P.purchase_order_date_time, 
                P.external_number, 
                P.supplier_name, 
                P.deliver_date_time, 
                U.name
            ORDER BY
                external_number DESC";
            } else {
                $query = "SELECT 
            P.purchase_order_Id, 
            P.purchase_order_date_time, 
            P.external_number, 
            P.supplier_name, 
            P.deliver_date_time, 
            U.name AS prepaired_by,
           
           
           FORMAT(SUM(((I.quantity - I.quantity_received) * I.cost_price - (((I.quantity - I.quantity_received) * I.cost_price) * I.discount_percentage) / 100 )), 2) AS total_amount
        FROM 
            purchase_order_notes P
        INNER JOIN 
            users U ON P.prepaired_by = U.id 
        LEFT JOIN 
            purchase_order_note_items I ON P.purchase_order_Id = I.purchase_order_Id
        WHERE 
            P.approval_status = 'Pending'
        AND
            P.status = 0
        AND
            P.branch_id =   '" . $branch_id . "'
        GROUP BY 
            P.purchase_order_Id, 
            P.purchase_order_date_time, 
            P.external_number, 
            P.supplier_name, 
            P.deliver_date_time, 
            U.name
        ORDER BY
            external_number DESC";
            }

            $purchase_orders = DB::select($query);
            if ($purchase_orders) {
                return response()->json((['success' => 'Data loaded', 'data' => $purchase_orders]));
            } else {
                return response()->json((['error' => 'Data Not loaded', 'data' => []]));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //get PO items
    public function getorderItems($id)
    {
        try {
            $query = "SELECT 
            purchase_order_note_items.*,
            items.Item_code,
            purchase_order_note_items.quantity AS PO_quantity,
            purchase_order_note_items.free_quantity AS PO_Foc,
            FORMAT(((purchase_order_note_items.quantity - purchase_order_note_items.quantity_received) * purchase_order_note_items.cost_price) - purchase_order_note_items.discount_amount, 2) AS Value,
            FORMAT((purchase_order_note_items.quantity - purchase_order_note_items.quantity_received),2) AS quantity,
            FORMAT((purchase_order_note_items.free_quantity - purchase_order_note_items.free_received),2) AS free_quantity
        FROM
            purchase_order_note_items
        INNER JOIN
            items ON purchase_order_note_items.item_id = items.item_id
        WHERE
            purchase_order_note_items.purchase_order_Id = '" . $id . "' AND ((purchase_order_note_items.quantity - purchase_order_note_items.quantity_received > 0) OR (purchase_order_note_items.free_quantity - purchase_order_note_items.free_received > 0))";
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


    //get selected items
    public function getSelectedItems(Request $request, $branch_id, $orderID)
    {
        try {
            $resultsArray = [];
            $collection = json_decode($request->get('Item_ids'));
            $order_ID = $orderID;

            // Prepare an array to store the item IDs
            $itemIDs = [];
            foreach ($collection as $i) {
                $id = json_decode($i);
                $itemIDs[] = $id;
            }

            // Create a comma-separated string of item IDs for the IN clause
            $itemIDsString = implode(',', $itemIDs);
            $query = "SELECT 
            POI.item_name,
            POI.item_id,
            FORMAT((POI.quantity - POI.quantity_received),2) AS quantity,
            FORMAT((POI.free_quantity - POI.free_received),2) AS free_quantity,
            POI.additional_bonus,
            POI.unit_of_measure,
            POI.package_unit,
            POI.package_size,
            POI.price,
            POI.discount_percentage,
            POI.discount_amount,
            POI.purchase_order_item_id,
            POI.is_new_price,
            POI.cost_price,
            IT.Item_code,
            IT.whole_sale_price,
            IT.retial_price,
            IT.manage_batch,
            IT.manage_expire_date
            
        FROM 
            purchase_order_note_items POI
            INNER JOIN items IT ON POI.item_id = IT.item_id
            INNER JOIN purchase_order_notes PO ON PO.purchase_order_Id = POI.purchase_order_Id 
        WHERE 
            PO.purchase_order_Id = '" . $order_ID . "'
            AND POI.item_id IN (" . $itemIDsString . ")";


            $result = DB::select($query);
            if ($result) {
                $resultsArray = $result;
            }

            return response()->json(['success' => 'Data loaded', 'data' => $resultsArray]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //get PO header details
    public function getheaderDetails($id)
    {
        try {
            $query = "SELECT
                purchase_order_Id, 
                location_id,
                external_number,
                discount_percentage,
                discount_amount,
                payment_mode_id,
                purchase_order_notes.supplier_id,
                suppliers.supplier_code,
                suppliers.primary_address,
                suppliers.supplier_name
            FROM 
                purchase_order_notes
            INNER JOIN 
                suppliers ON purchase_order_notes.supplier_id = suppliers.supplier_id 
            WHERE 
                purchase_order_notes.purchase_order_Id = '" . $id . "'";

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


    //complete order
    public function completeOrderstatus($id)
    {
        try {


            $query = "SELECT COUNT(*) AS TotalCount
                FROM purchase_order_note_items
                WHERE (purchase_order_note_items.quantity - purchase_order_note_items.quantity_received > 0)
                AND purchase_order_note_items.purchase_order_Id = '" . $id . "'";

            $result = DB::select($query);
            $totalCount = isset($result[0]->TotalCount) ? (int)$result[0]->TotalCount : 0;

            if ($totalCount > 0) {
                $PO = purchase_order_note::find($id);
                $PO->status = 1;
                if ($PO->update()) {
                    return response()->json(['status' => true]);
                } else {
                    return response()->json(['status' => false]);
                }
            } else if ($totalCount == 0) {
                $PO = purchase_order_note::find($id);
                $PO->status = 1;
                if ($PO->update()) {
                    return response()->json(['status' => true]);
                } else {
                    return response()->json(['status' => false]);
                }
            } else {
                return response()->json(['status' => $totalCount]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function completeOrderstatus_auto($id)
    {
        try {


            $query = "SELECT COUNT(*) AS TotalCount
                FROM purchase_order_note_items
                WHERE (purchase_order_note_items.quantity - purchase_order_note_items.quantity_received > 0)
                AND purchase_order_note_items.purchase_order_Id = '" . $id . "'";

            $result = DB::select($query);
            $totalCount = $result[0]->TotalCount;

            if ($totalCount > 0) {
                return response()->json(['status' => false, 'message' => 'Yes']);
            } else if ($totalCount == 0) {
                $PO = purchase_order_note::find($id);
                $PO->status = 1;
                if ($PO->update()) {
                    return response()->json(['status' => true]);
                } else {
                    return response()->json(['status' => false]);
                }
            } else {
                return response()->json(['status' => $totalCount]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }
}
