<?php

namespace Modules\Prc\Http\Controllers;

use App\Http\Controllers\IntenelNumberController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Md\Entities\supplier;
use Modules\Prc\Entities\bonus_claim;
use Modules\Prc\Entities\bonus_claim_item;
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

class BonusClaimController extends Controller
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
        $qry = 'SELECT supplier_code as value,supplier_name as id FROM suppliers';
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


    //add bonus_claim
    public function addbonusClaim(Request $request, $id)
    {
        try {
            //dd($request);
            $margin_gaps = [];
            // $PO_id_ = $request->input('txtPurchaseORder');
            // $query = "SELECT status FROM purchase_order_notes WHERE purchase_order_Id = " . $PO_id_;
            // $result = DB::select($query);
            // if ($result) {
            //     $PO_status = $result[0]->status;
            //     if ($PO_status != 0) {
            //         return response()->json(["status" => false, "message" => "completed"]);
            //     } else {

            $collection = json_decode($request->input('collection'));
            //looping first array to check prices and margin
            foreach ($collection as $i) {

                $item = json_decode($i);

                if (is_null($item->whole_sale_price) || floatval($item->whole_sale_price) == 0) {
                    return response()->json(["message" => "null"]);
                } else if (is_null($item->retial_price) || floatval($item->retial_price) == 0) {
                    return response()->json(["message" => "null retail"]);
                } else if (is_null($item->cost_price) || floatval($item->cost_price) == 0) {
                    return response()->json(["message" => "null cost"]);
                } else if (is_null($item->price) || floatval($item->price) == 0) {
                    return response()->json(["message" => "null price"]);
                }
                $Itm_ID = $item->item_id;
                $item_ = item::find($Itm_ID);
                $minimum_margin_ =  $item_->minimum_margin;

                if (floatval($minimum_margin_) > 0) {

                    $cost = floatval($item->price) - (((floatval($item->price)) / 100) * floatval($item->discount_percentage));
                    $margin = ((floatval($item->whole_sale_price) - $cost) / floatval($item->whole_sale_price)) * 100;
                    // dd($margin);
                    if (floatval($minimum_margin_) > floatval($margin)) {
                        $item_code_ = $item_->Item_code;
                        array_push($margin_gaps, $item_code_);
                    }
                }
            }
            if (count($margin_gaps) > 0) {
                return response()->json(["message" => "margin_gaps", "margin_gaps" => $margin_gaps]);
            }

            /* if ($id != "null") {
 
                         $bonus_claimDraft = GoodsReceivedNoteDraft::find($id)->delete();
                         $itemDraft =  GoodsReceivedNoteItemDraft::where("goods_received_Id", "=", $id)->delete();
                     }
  */

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

            $bonus_claim = new bonus_claim();
            $bonus_claim->internal_number = IntenelNumberController::getNextID();
            $bonus_claim->external_number = $externalNumber; // need to change 
            $bonus_claim->bonus_claim_date_time = $request->input('bonus_claim_date_time');
            $bonus_claim->branch_id = $request->input('cmbBranch');
            $bonus_claim->location_id = $request->input('cmbLocation');
            //$bonus_claim->supplier_id = 1; //$request->input('txtSupplier');
            // $bonus_claim->supplier_name = $request->input('lblSupplierName');
            $bonus_claim->purchase_order_id = $request->input('txtPurchaseORder');
            $bonus_claim->supppier_invoice_number = $request->input('txtSupplierInvoiceNumber');
            $bonus_claim->invoice_amount = $request->input('txtInvoiceAmount');
            $bonus_claim->payment_due_date = $request->input('dtPaymentDueDate');
            $bonus_claim->payment_mode_id = $request->input('cmbPaymentType');
            $bonus_claim->discount_percentage = $request->input('txtDiscountPrecentage');
            $bonus_claim->discount_amount = $request->input('txtDiscountAmount');
            $bonus_claim->adjustment_amount = $request->input('txtAdjustmentAmount');
            $bonus_claim->remarks = $request->input('txtRemarks');
            $bonus_claim->document_number = 2700;
            $bonus_claim->prepaired_by = $PreparedBy;
            $bonus_claim->your_reference_number = $request->input('txtYourReference');
            $bonus_claim->total_amount = $request->input('lblNetTotal');


            if ($bonus_claim->save()) {

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

                    $bonus_claim_item = new bonus_claim_item();
                    $bonus_claim_item->bonus_claim_Id = $bonus_claim->bonus_claim_Id;
                    $bonus_claim_item->internal_number = $bonus_claim->internal_number;
                    $bonus_claim_item->external_number = $bonus_claim->external_number; // need to change
                    $bonus_claim_item->item_id = $item->item_id;
                    $bonus_claim_item->item_name = $item->item_name;
                    $bonus_claim_item->quantity = $item->qty;
                    if ($item->free_quantity) {
                        $bonus_claim_item->free_quantity = $item->free_quantity;
                    } else {
                        $bonus_claim_item->free_quantity = 0;
                    }

                    if ($item->addBonus) {
                        $bonus_claim_item->additional_bonus = $item->addBonus;
                    } else {
                        $bonus_claim_item->additional_bonus = 0;
                    }

                    if ($item->PackUnit) {
                        $bonus_claim_item->package_unit = $item->PackUnit;
                    } else {
                        $bonus_claim_item->package_unit = 0;
                    }

                    if ($item->PackSize) {
                        $bonus_claim_item->package_size = $item->PackSize;
                    } else {
                        $bonus_claim_item->package_size = 0;
                    }

                    if ($item->price) {
                        $bonus_claim_item->price = $item->price;
                    } else {
                        $bonus_claim_item->price = 0;
                    }

                    if ($item->discount_percentage) {
                        $bonus_claim_item->discount_percentage = $item->discount_percentage;
                    } else {
                        $bonus_claim_item->discount_percentage = 0;
                    }

                    if ($item->discount_amount) {
                        $bonus_claim_item->discount_amount = $item->discount_amount;
                    } else {
                        $bonus_claim_item->discount_amount = 0;
                    }


                    $bonus_claim_item->whole_sale_price = $item->whole_sale_price;
                    $bonus_claim_item->retial_price = $item->retial_price;

                    if ($item->previouse_whole_sale_price) {
                        $bonus_claim_item->previouse_whole_sale_price = $item->previouse_whole_sale_price;
                    } else {
                        $bonus_claim_item->previouse_whole_sale_price = 0;
                    }

                    if ($item->previouse_retial_price) {
                        $bonus_claim_item->previouse_retail_price = $item->previouse_retial_price;
                    } else {
                        $bonus_claim_item->previouse_retail_price = 0;
                    }



                    if ($item->batch_number) {
                        $bonus_claim_item->batch_number = $item->batch_number;
                    } else {
                        $bonus_claim_item->batch_number = $item_code;
                    }

                    if ($item->expire_date) {
                        $bonus_claim_item->expire_date = $item->expire_date;
                    } else {
                        $bonus_claim_item->expire_date = null;
                    }

                    $bonus_claim_item->cost_price = $item->cost_price;
                    $bonus_claim_item->purchase_order_item_id = $item->purchase_order_item_id;
                    $bonus_claim_item->save();
                    $this->thcreateAndSaveItem_Historysetoff($bonus_claim_item, $bonus_claim);
                }
                
                return response()->json(["status" => true, "primaryKey" => $bonus_claim->goods_received_Id, "PO_id" => $bonus_claim->purchase_order_id]);
            } else {
                return response()->json(["status" => false]);
            }
            //     }
            // }
        } catch (Exception $ex) {
            return $ex;
        }
    }
    private function thcreateAndSaveItem_Historysetoff($bonusclaim, $bonus_claim)
    {
        try {

         
            $itemhistory = new item_history();
            $itemhistory->internal_number = $bonusclaim->internal_number;
            $itemhistory->external_number = $bonusclaim->external_number;
            $itemhistory->branch_id = $bonus_claim->branch_id;
            $itemhistory->location_id = $bonus_claim->location_id;
            $itemhistory->transaction_date = $bonus_claim->bonus_claim_date_time;
            $itemhistory->document_number = $bonus_claim->document_number;
            $itemhistory->item_id = $bonusclaim->item_id;
            $itemhistory->quantity = $bonusclaim->quantity;
            $itemhistory->whole_sale_price = $bonusclaim->whole_sale_price;
            $itemhistory->retial_price = $bonusclaim->retial_price;
            $itemhistory->batch_number = $bonusclaim->batch_number;
            $itemhistory->cost_price = $bonusclaim->cost_price;
           
            $itemhistory->description = "Bonus Claim";
            if ($itemhistory->save()) {

                $itemhistory_setOff = new item_history_setOff();
                $itemhistory_setOff->internal_number = $bonusclaim->internal_number;
                $itemhistory_setOff->external_number = $bonusclaim->external_number;
                $itemhistory_setOff->document_number = $bonus_claim->document_number;
                $itemhistory_setOff->batch_number = $bonusclaim->batch_number;
                $itemhistory_setOff->branch_id = $bonus_claim->branch_id;
                $itemhistory_setOff->location_id = $bonus_claim->location_id;
                $itemhistory_setOff->transaction_date = $bonus_claim->bonus_claim_date_time;
                $itemhistory_setOff->item_id = $bonusclaim->item_id;
                $itemhistory_setOff->quantity = $bonusclaim->quantity;
                $itemhistory_setOff->setoff_quantity =0;
                $itemhistory_setOff->whole_sale_price = $bonusclaim->whole_sale_price;
                $itemhistory_setOff->retial_price = $bonusclaim->retial_price;
                $itemhistory_setOff->reference_external_number = $bonusclaim->external_number;
                $itemhistory_setOff->reference_document_number = $bonus_claim->document_number;
                $itemhistory_setOff->cost_price = $bonusclaim->cost_price;

                $itemhistory_setOff->save();
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

   
    


    //get each bonus_claim
    public function getEacchBonusclaim($id, $status)
    {
        try {

            $query = 'SELECT * FROM bonus_claims BC WHERE BC.bonus_claim_Id ="' . $id . '"';
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

    //get each item
    public function getEachbonusclaimitem($id, $status)
    {
        try {

            $query = 'SELECT bonus_claim_items.*,items.Item_code from bonus_claim_items INNER JOIN items ON bonus_claim_items.item_id = items.item_id WHERE bonus_claim_items.bonus_claim_Id = "' . $id . '"';
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



    public function getServerTime()
    {
        try {
            $serverDate = Carbon::now()->format('d/m/Y');
            return response()->json(['date' => $serverDate]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function getBonusdata($id)
    {
        try {

           

                $query = 'SELECT BS.bonus_claim_Id, BS.bonus_claim_date_time,
                BS.external_number,BS.supppier_invoice_number
         FROM bonus_claims BS
         WHERE document_number= 2700';
         if (isset($id) && is_numeric($id) && $id > 0) {
                    $query .= '  AND  BS.branch_id = ' . $id;
                }
                $query .= ' ORDER BY BS.external_number DESC';
       
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

}
