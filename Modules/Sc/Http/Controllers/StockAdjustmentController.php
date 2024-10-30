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
use Modules\Sc\Entities\item_history_setOff;
use Modules\Sc\Entities\stock_adjustment;
use Modules\Sc\Entities\stock_adjustment_item;
use Modules\Sc\Entities\stock_adjustment_item_set_off;

class StockAdjustmentController extends Controller
{
    public function addstockadjustment(Request $request)
    {
        try {
            DB::beginTransaction();
            $setOffArray = json_decode($request->input('setOffArray'));
            // dd($setOffArray);
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
            $st_adjustment = new stock_adjustment();
            $st_adjustment->internal_number = IntenelNumberController::getNextID();
            $st_adjustment->external_number = $externalNumber;
            $st_adjustment->date = $request->input('stock_adjustment_date_time');
            $st_adjustment->branch_id = $request->input('cmbBranch');
            $st_adjustment->location_id = $request->input('cmbLocation');

            $st_adjustment->your_reference_number = $request->input('txtYourReference');
            $st_adjustment->remarks = $request->input('txtRemarks');
            $st_adjustment->document_number = 1500;
            $st_adjustment->cretae_by = $PreparedBy;


            if ($st_adjustment->save()) {

                //looping ifrst array
                foreach ($collection as $i) {

                    $item = json_decode($i);

                    foreach ($item as $key => $value) {
                        if (is_string($value) && empty($value)) {
                            $item->$key = null;
                        }
                    }

                    $itemQty = $item->qty;


                    $formatted_qty = floatval(str_replace(',', '', $itemQty));

                    $st_adjustment_item = new stock_adjustment_item();
                    $st_adjustment_item->stock_adjusment_id = $st_adjustment->stock_adjustment_id;
                    $st_adjustment_item->internal_number = $st_adjustment->internal_number;
                    $st_adjustment_item->external_number = $st_adjustment->external_number; // need to change
                    $st_adjustment_item->item_id = $item->item_id;
                    $st_adjustment_item->item_name = $item->item_name;
                    $st_adjustment_item->quantity = $formatted_qty;
                    $st_adjustment_item->packsize = $item->PackSize;
                    $st_adjustment_item->cost_price = $item->cost_price;
                    $st_adjustment_item->whole_sale_price = $item->whole_sale_price;
                    $st_adjustment_item->retial_price = $item->retial_price;
                    $st_adjustment_item->batch_number = $item->batch_number;

                    $st_adjustment_item->save();
                    //$this->createAndSaveItemHistory($st_adjustment, $st_adjustment_item);
                    $this->createAndSaveItemHistorysetoffplus($st_adjustment, $st_adjustment_item);
                    $setOff = null;
                    if (count($setOffArray) > 0) {

                        foreach ($setOffArray as $j) {

                            $SetOff_item = json_decode($j);
                           // dd($SetOff_item);
                            if ($SetOff_item->item_id == $item->item_id) {
                                // dd($SetOff_item->item_id);
                                if ($formatted_qty < 0) {

                                    $setOff = new stock_adjustment_item_set_off();
                                    $setOff->internal_number = $st_adjustment->internal_number;
                                    $setOff->external_number = $st_adjustment->external_number;
                                    $setOff->stock_adjusment_item_id = $st_adjustment_item->stock_adjusment_item_id;
                                    $setOff->item_history_setoff_id = $SetOff_item->history_id;
                                    $setOff->item_id = $SetOff_item->item_id;
                                    $setOff->set_off_qty = $SetOff_item->setoff_quantity;
                                    $setOff->cost_price = $SetOff_item->cost_price;
                                    $setOff->whole_sale_price = $SetOff_item->wholesale_price;
                                    $setOff->retial_price = $SetOff_item->retail_price;
                                    $setOff->batch_number = $SetOff_item->batch_no;
                                    $setOff->save();
                                    // $setOff_obj = $setOff;
                                    $this->createAndSaveItemHistorysetoff($st_adjustment, $setOff, $st_adjustment_item);
                                }
                            }
                        }
                    }

                    $this->createAndSaveItemHistory($st_adjustment, $st_adjustment_item, $setOff);
                }
                DB::commit();
                return response()->json(["status" => true, "primaryKey" => $st_adjustment->goods_received_return_Id]);
            } else {
                DB::rollBack();
                return response()->json(["status" => false]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    private function createAndSaveItemHistory($st_adjustment, $st_adjustment_item, $setOff)
    {
        // dd($setOff);  

        if ($st_adjustment_item->quantity > 0) {
            $itemhistory = new item_history();
            $itemhistory->internal_number = $st_adjustment->internal_number;
            $itemhistory->external_number = $st_adjustment->external_number;
            $itemhistory->branch_id = $st_adjustment->branch_id;
            $itemhistory->location_id = $st_adjustment->location_id;
            $itemhistory->transaction_date = $st_adjustment->date;
            $itemhistory->document_number = $st_adjustment->document_number;
            $itemhistory->item_id = $st_adjustment_item->item_id;
            $itemhistory->quantity = $st_adjustment_item->quantity;
            $itemhistory->whole_sale_price = $st_adjustment_item->whole_sale_price;
            $itemhistory->retial_price = $st_adjustment_item->retial_price;
            $itemhistory->cost_price = $st_adjustment_item->cost_price;
            $itemhistory->batch_number = $st_adjustment_item->batch_number;
            $itemhistory->description = "Stock Adjustment";
            $itemhistory->save();
        } else {
            $itemhistory = new item_history();
            $itemhistory->internal_number = $st_adjustment->internal_number;
            $itemhistory->external_number = $st_adjustment->external_number;
            $itemhistory->branch_id = $st_adjustment->branch_id;
            $itemhistory->location_id = $st_adjustment->location_id;
            $itemhistory->transaction_date = $st_adjustment->date;
            $itemhistory->document_number = $st_adjustment->document_number;
            $itemhistory->item_id = $st_adjustment_item->item_id;
            $itemhistory->quantity = $st_adjustment_item->quantity;
            $itemhistory->whole_sale_price = $setOff->whole_sale_price;
            $itemhistory->retial_price = $setOff->retial_price;
            $itemhistory->cost_price = $setOff->cost_price;
            $itemhistory->batch_number = $st_adjustment_item->batch_number;
            $itemhistory->description = "Stock Adjustment";
            $itemhistory->save();
        }
    }
    private function createAndSaveItemHistorysetoffplus($st_adjustment, $st_adjustment_item)
    {

        if ($st_adjustment_item->quantity > 0) {



            $itemhistory_setOff = new item_history_setOff();
            $itemhistory_setOff->internal_number = $st_adjustment->internal_number;
            $itemhistory_setOff->external_number = $st_adjustment->external_number;
            $itemhistory_setOff->document_number = $st_adjustment->document_number;
            $itemhistory_setOff->batch_number = $st_adjustment_item->batch_number;
            $itemhistory_setOff->branch_id = $st_adjustment->branch_id;
            $itemhistory_setOff->location_id = $st_adjustment->location_id;

            $itemhistory_setOff->item_id = $st_adjustment_item->item_id;
            $itemhistory_setOff->quantity = $st_adjustment_item->quantity;
            $itemhistory_setOff->whole_sale_price = $st_adjustment_item->whole_sale_price;
            $itemhistory_setOff->retial_price = $st_adjustment_item->retial_price;
            $itemhistory_setOff->cost_price = $st_adjustment_item->cost_price;
            $itemhistory_setOff->save();
        }
    }

    private function createAndSaveItemHistorysetoff($st_adjustment, $setOff, $st_adjustment_item)
    {
        $ref_ih_id = $setOff->item_history_setoff_id;
        $reference_ih = item_history_setOff::find($ref_ih_id);

        $itemhistory_setOff = new item_history_setOff();
        $itemhistory_setOff->internal_number = $st_adjustment->internal_number;
        $itemhistory_setOff->external_number = $st_adjustment->external_number;
        $itemhistory_setOff->document_number = $st_adjustment->document_number;
        $itemhistory_setOff->batch_number = $setOff->batch_number;
        $itemhistory_setOff->branch_id = $st_adjustment->branch_id;
        $itemhistory_setOff->location_id = $st_adjustment->location_id;

        $itemhistory_setOff->item_id = $setOff->item_id;
        $itemhistory_setOff->quantity = -$setOff->set_off_qty;
        $itemhistory_setOff->setoff_quantity = -$setOff->set_off_qty;
        $itemhistory_setOff->whole_sale_price = $setOff->whole_sale_price;
        $itemhistory_setOff->retial_price = $setOff->retial_price;
        $itemhistory_setOff->cost_price = $setOff->cost_price;

        $itemhistory_setOff->reference_internal_number = $reference_ih->internal_number;
        $itemhistory_setOff->reference_external_number = $reference_ih->external_number;
        $itemhistory_setOff->reference_document_number = $reference_ih->document_number;
        $itemhistory_setOff->setoff_id = $ref_ih_id;
        $itemhistory_setOff->save();




        //updating set off line
        $reference_ih->setoff_quantity = floatval($reference_ih->setoff_quantity) + floatval($setOff->set_off_qty);
        $reference_ih->update();
    }


    public function getstock_adjustmentdata()
    {
        try {
            $result = stock_adjustment::all();
            if ($result) {
                return response()->json((['success' => 'Data loaded', 'data' => $result]));
            } else {
                return response()->json((['error' => 'Data is not loaded', 'data' => []]));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function getstock_adjustment($id)
    {
        try {
            $result = stock_adjustment::find($id);
            if ($result) {
                return response()->json((['success' => 'Data loaded', 'data' => $result]));
            } else {
                return response()->json((['error' => 'Data is not loaded', 'data' => []]));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }
    public function getstock_adjustmentitem($id)
    {
        try {
            $query = 'SELECT stock_adjustment_items.*,items.Item_code from stock_adjustment_items INNER JOIN items ON stock_adjustment_items.item_id = items.item_id WHERE stock_adjustment_items.stock_adjusment_id= "' . $id . '"';
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

    public function delete_stock_adjustment($id)
    {
        try {
            $stock_adjustmentitem = stock_adjustment::find($id);
            if ($stock_adjustmentitem->delete()) {
                $stock_adjustmentitem_item = stock_adjustment_item::where('stock_adjusment_id', '=', $id)->delete();;

                return response()->json(["message" => "Deleted"]);
            } else {
                return response()->json(["message" => "Not Deleted"]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //load data to view
    public function get_each_adjustment($id)
    {

        try {
            $adjusment = stock_adjustment::find($id);
            $adjustment_item = DB::select('SELECT DISTINCT
            SI.stock_adjusment_item_id,
            SI.packsize,
            SI.cost_price,
            SI.whole_sale_price,
            SI.retial_price,
            SI.quantity,
            I.item_Name,
            I.Item_code,
            SO.whole_sale_price AS set_wh,
            SO.retial_price AS set_rt,
            SO.cost_price AS set_co,
            I.item_id
        FROM
            stock_adjustment_items SI
        INNER JOIN
            items I ON SI.item_id = I.item_id
        LEFT JOIN
            stock_adjustment_item_set_offs SO ON SI.stock_adjusment_item_id = SO.stock_adjusment_item_id
         WHERE SI.stock_adjusment_id ='.$id);
            return response()->json(["adjusment" => $adjusment,"items"=>$adjustment_item]);
        } catch (Exception $ex) {
            return $ex;
        }
    }
}
