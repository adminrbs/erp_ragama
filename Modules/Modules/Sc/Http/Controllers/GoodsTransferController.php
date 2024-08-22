<?php

namespace Modules\Sc\Http\Controllers;

use App\Http\Controllers\IntenelNumberController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Md\Entities\location;
use Modules\Prc\Entities\goods_return_setOff;
use Modules\Prc\Entities\item_history;
use Modules\Prc\Entities\item_history_setOff;
use Modules\Sc\Entities\goods_transfer;
use Modules\Sc\Entities\goods_transfer_items;
use Modules\Sc\Entities\goods_transfer_set_off;

class GoodsTransferController extends Controller
{
    public function getItemInfotogrnReturn($branch_id, $item_id, $location_id)
    {
        try {
            $query = "SELECT
            IT.unit_of_measure,
            IT.item_Name,
            IT.average_cost_price,
            IT.package_size,
            IT.package_unit,
            IT.previouse_purchase_price,
            (SELECT IF(ISNULL(SUM(quantity-setoff_quantity)), 0, SUM(quantity-setoff_quantity)) FROM item_history_set_offs WHERE item_id='" . $item_id . "' AND branch_id='" . $branch_id . "' AND location_id='" . $location_id . "' AND quantity>0  AND price_status = 0) AS Balance
        FROM
            items IT
        WHERE
            IT.item_id = '" . $item_id . "';
        ";
            $result = DB::select($query);

            if ($result) {
                return response()->json($result);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //load sellable location
    public function loadAllLocation($id)
    {
        try {
            $locations = location::where('branch_id', '=', $id)
               /*  ->where('location_type_id', '=', 3)  changed on 06/06 - sachin*/
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

    //add goods transfer
    public function add_goods_transfer(Request $request)
    {
        DB::beginTransaction();
        try {
            $setOffArray = json_decode($request->input('setOffArray'));
            // dd($setOffArray);
            $collection = json_decode($request->input('collection'));
            // dd($setOffArray);
            $branch_id_ = $request->input('cmbBranch');
            $location_id = $request->input('cmbLocation');

            //validate set off array to check avl qty
            foreach ($setOffArray as $i) {
                $item = json_decode($i);
                $itemID = $item->item_id;
                $setOffqty = $item->setoff_quantity;
                $wholeSalePrice = $item->wholesale_price;
                $query = "SELECT IF(ISNULL(SUM(quantity)), 0, SUM(quantity)) AS balance 
                FROM item_historys 
                WHERE whole_sale_price = '" . $wholeSalePrice . "' AND item_id = '" . $itemID . "'AND branch_id = '" . $branch_id_ . "' AND location_id = " . $location_id;
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
            $GTR = new goods_transfer();
            $GTR->internal_number = IntenelNumberController::getNextID();
            $GTR->external_number = $externalNumber;
            $GTR->document_number = 1200;
            $GTR->goods_transfer_date = $request->input('goods_transfer_date');
            $GTR->from_branch_id = $request->input('cmbBranch');
            $GTR->from_location_id = $request->input('cmbLocation');
            $GTR->to_branch_id = $request->input('cmb_to_Branch');
            $GTR->to_location_id = $request->input('cmb_to_Location');
            $GTR->remarks = $request->input('txtRemarks');
            $GTR->your_reference_number = $request->input('your_reference_number');
            $GTR->prepaired_by = $PreparedBy;
            $GTR->total_amount = $request->input('lblNetTotal');

            $locations_to = location::find($GTR->to_location_id);
            $locations_from = location::find($GTR->from_location_id);

            if ($GTR->save()) {
                foreach ($collection as $i) {
                    $item = json_decode($i);
                    $gtr_item = new goods_transfer_items();
                    $gtr_item->goods_transfer_id = $GTR->goods_transfer_id;
                    $gtr_item->internal_number =  $GTR->internal_number;
                    $gtr_item->external_number =  $GTR->external_number;
                    $gtr_item->item_id =  $item->item_id;
                    $gtr_item->quantity = $item->qty;
                    $gtr_item->package_size =  $item->PackUnit;
                    $gtr_item->price =  $item->price;
                    $gtr_item->whole_sale_price =  $item->whole_sale_price;
                    $gtr_item->retial_price =  $item->retial_price;
                    $gtr_item->cost_price =  $item->cost_price;
                    $gtr_item->batch_number =  $item->batch_number;
                    if ($gtr_item->save()) {
                        //item history plus
                        $item_history = new item_history();
                        $item_history->internal_number = $gtr_item->internal_number;
                        $item_history->external_number = $gtr_item->external_number;
                        $item_history->external_number = $gtr_item->external_number;
                        $item_history->branch_id =  $GTR->to_branch_id;
                        $item_history->location_id = $GTR->to_location_id;
                        $item_history->document_number = $GTR->document_number;
                        $item_history->transaction_date = $GTR->goods_transfer_date;
                        $item_history->description = "Goods transfer from " . $locations_from->location_name;
                        $item_history->item_id =  $gtr_item->item_id;
                        $item_history->quantity = floatVal($gtr_item->quantity);
                        // $item_history->free_quantity = $gtr_item->free_quantity;
                        $item_history->whole_sale_price = $gtr_item->whole_sale_price;
                        $item_history->retial_price = $gtr_item->retial_price;
                        $item_history->cost_price = $gtr_item->cost_price;
                        $item_history->save();

                        //item history minus
                        $item_history_minus = new item_history();
                        $item_history_minus->internal_number = $gtr_item->internal_number;
                        $item_history_minus->external_number = $gtr_item->external_number;
                        $item_history_minus->external_number = $gtr_item->external_number;
                        $item_history_minus->branch_id =  $GTR->from_branch_id;
                        $item_history_minus->location_id = $GTR->from_location_id;
                        $item_history_minus->document_number = $GTR->document_number;
                        $item_history_minus->transaction_date = $GTR->goods_transfer_date;
                        $item_history_minus->description = "Goods transfer to " . $locations_to->location_name;
                        $item_history_minus->item_id =  $gtr_item->item_id;
                        $item_history_minus->quantity = -floatVal($gtr_item->quantity);
                        // $item_history->free_quantity = $gtr_item->free_quantity;
                        $item_history_minus->whole_sale_price = $gtr_item->whole_sale_price;
                        $item_history_minus->retial_price = $gtr_item->retial_price;
                        $item_history_minus->cost_price = $gtr_item->cost_price;
                        $item_history_minus->save();
                    }

                    foreach ($setOffArray as $j) {

                        $SetOff_item = json_decode($j);
                        if ($SetOff_item->item_id == $item->item_id) {


                            $setOff = new goods_transfer_set_off();
                            $setOff->internal_number = $GTR->internal_number;
                            $setOff->external_number = $GTR->external_number;
                            $setOff->goods_transfer_items_id = $gtr_item->goods_transfer_items_id;
                            $setOff->item_history_setoff_id = $SetOff_item->history_id;
                            $setOff->item_id = $SetOff_item->item_id;
                            $setOff->set_off_qty = $SetOff_item->setoff_quantity;
                            $setOff->cost_price = $SetOff_item->cost_price;
                            $setOff->whole_sale_price = $SetOff_item->wholesale_price;
                            $setOff->retail_price = $SetOff_item->retail_price;
                            $setOff->batch_number = $SetOff_item->batch_no;
                            if ($setOff->save()) {

                                $reference_item_history = item_history_setOff::find($SetOff_item->history_id); // plus

                                $item_history_set_off = new item_history_setOff();
                                $item_history_set_off->internal_number = $setOff->internal_number;
                                $item_history_set_off->external_number = $setOff->external_number;
                                $item_history_set_off->document_number = $GTR->document_number;
                                $item_history_set_off->batch_number = $setOff->batch_number;
                                $item_history_set_off->branch_id = $GTR->to_branch_id;
                                $item_history_set_off->location_id = $GTR->to_location_id;
                                $item_history_set_off->transaction_date = $GTR->goods_transfer_date;
                                $item_history_set_off->item_id =  $setOff->item_id;
                                $item_history_set_off->whole_sale_price = $setOff->whole_sale_price;
                                $item_history_set_off->retial_price = $setOff->retail_price;
                                $item_history_set_off->cost_price = $setOff->cost_price;
                                $item_history_set_off->quantity = $setOff->set_off_qty;
                               // $item_history_set_off->setoff_quantity = $setOff->set_off_qty;
                                $item_history_set_off->reference_internal_number = $reference_item_history->internal_number;
                                $item_history_set_off->reference_external_number = $reference_item_history->external_number;
                                $item_history_set_off->reference_document_number = $reference_item_history->document_number;
                               // $item_history_set_off->setoff_id =  $item_history_set_off->item_history_setoff_id;
                                if ($item_history_set_off->save()) {
                                    $reference_item_history->setoff_quantity = $reference_item_history->setoff_quantity + $setOff->set_off_qty;
                                    $reference_item_history->update();
                                }



                                //  $reference_item_history = item_history_setOff::find($SetOff_item->history_id); // plus

                                $item_history_set_off_minus = new item_history_setOff();
                                $item_history_set_off_minus->internal_number = $setOff->internal_number;
                                $item_history_set_off_minus->external_number = $setOff->external_number;
                                $item_history_set_off_minus->document_number = $GTR->document_number;
                                $item_history_set_off_minus->batch_number = $setOff->batch_number;
                                $item_history_set_off_minus->branch_id = $GTR->from_branch_id;
                                $item_history_set_off_minus->location_id = $GTR->from_location_id;
                                $item_history_set_off_minus->transaction_date = $GTR->goods_transfer_date;
                                $item_history_set_off_minus->item_id =  $setOff->item_id;
                                $item_history_set_off_minus->whole_sale_price = $setOff->whole_sale_price;
                                $item_history_set_off_minus->retial_price = $setOff->retail_price;
                                $item_history_set_off_minus->cost_price = $setOff->cost_price;
                                $item_history_set_off_minus->quantity = -$setOff->set_off_qty;
                                $item_history_set_off_minus->setoff_quantity = -$setOff->set_off_qty;
                                $item_history_set_off_minus->reference_internal_number = $GTR->internal_number;
                                $item_history_set_off_minus->reference_external_number = $GTR->external_number;
                                $item_history_set_off_minus->reference_document_number = $GTR->document_number;
                                /* $item_history_set_off_minus->setoff_id = $item_history_set_off->item_history_setoff_id; */
                                $item_history_set_off_minus->save();
                            }
                        }
                    }
                }
            }
            DB::commit();
            return response()->json(["status" => true, "message" => "success"]);
        } catch (Exception $ex) {
            DB::rollBack();
            return $ex;
        }
    }

    //get goods transfers details
    public function get_goods_transfer_details_approval()
    {
        try {
            $GTR = DB::select('SELECT GT.goods_transfer_id, GT.goods_transfer_date,GT.external_number,BR_FROM.branch_name as from_branch, BR_TO.branch_name as to_branch,LOC_FROM.location_name as from_location,LOC_TO.location_name as to_location FROM goods_transfers GT INNER JOIN branches BR_FROM ON GT.from_branch_id = BR_FROM.branch_id INNER JOIN branches BR_TO ON GT.to_branch_id = BR_TO.branch_id INNER JOIN locations LOC_FROM ON GT.from_location_id = LOC_FROM.location_id INNER JOIN locations LOC_TO ON GT.to_location_id = LOC_TO.location_id WHERE GT.status = 0 ORDER BY GT.external_number DESC');
            if ($GTR) {
                return response()->json(["status" => true, "data" => $GTR]);
            } else {
                return response()->json(["status" => false, "data" => []]);
            }
        } catch (Exception $ex) {
        }
    }

    //get each trasnfers
    public function get_each_transfer($id)
    {
        try {
            $GTR = goods_transfer::find($id);
            $GTR_item = DB::select("SELECT goods_transfer_items.*,items.item_Name,items.Item_code,items.package_unit FROM goods_transfer_items INNER JOIN items ON goods_transfer_items.item_id = items.item_id WHERE goods_transfer_items.goods_transfer_id = $id");
            return response()->json(["status" => true, "gtr" => $GTR, "gtr_item" => $GTR_item]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //get pendings
    public function get_goods_transfer_details()
    {
        try {
            $GTR = DB::select('SELECT GT.goods_transfer_id, GT.goods_transfer_date,GT.external_number,BR_FROM.branch_name as from_branch, BR_TO.branch_name as to_branch,LOC_FROM.location_name as from_location,LOC_TO.location_name as to_location FROM goods_transfers GT INNER JOIN branches BR_FROM ON GT.from_branch_id = BR_FROM.branch_id INNER JOIN branches BR_TO ON GT.to_branch_id = BR_TO.branch_id INNER JOIN locations LOC_FROM ON GT.from_location_id = LOC_FROM.location_id INNER JOIN locations LOC_TO ON GT.to_location_id = LOC_TO.location_id');
            if ($GTR) {
                return response()->json(["status" => true, "data" => $GTR]);
            } else {
                return response()->json(["status" => false, "data" => []]);
            }
        } catch (Exception $ex) {
        }
    }

    //approve
    public function approve_goods_transfer($id)
    {
        try {
            $goods_transfer = goods_transfer::find($id);
            if ($goods_transfer->status != 0) {
                return response()->json(["status" => false, "message" => "used"]);
            } else {
                $goods_transfer->status = 1;
                if ($goods_transfer->update()) {
                    $transfer_item = goods_transfer_items::where('goods_transfer_id','=',$id)->get();
                    $locations_from = location::find($goods_transfer->from_location_id);
                    foreach ($transfer_item as $gtr_item) {
                        $item_history = new item_history();
                        $item_history->internal_number = $gtr_item->internal_number;
                        $item_history->external_number = $gtr_item->external_number;
                        $item_history->external_number = $gtr_item->external_number;
                        $item_history->branch_id =  $goods_transfer->to_branch_id;
                        $item_history->location_id = $goods_transfer->to_location_id;
                        $item_history->document_number = $goods_transfer->document_number;
                        $item_history->transaction_date = $goods_transfer->goods_transfer_date;
                        $item_history->description = "Goods transfer from " . $locations_from->location_name;
                        $item_history->item_id =  $gtr_item->item_id;
                        $item_history->quantity = floatVal($gtr_item->quantity);
                        /*  $item_history->free_quantity = $gtr_item->free_quantity; */
                        $item_history->whole_sale_price = $gtr_item->whole_sale_price;
                        $item_history->retial_price = $gtr_item->retial_price;
                        $item_history->cost_price = $gtr_item->cost_price;
                        if($item_history->save()){
                            $setOff = goods_transfer_set_off::where("goods_transfer_items_id","=",$gtr_item->goods_transfer_items_id)->get();
                            foreach ($setOff as $set_off_data) {
                                $reference_item_history = item_history_setOff::find($set_off_data->item_history_setoff_id); // plus

                                $item_history_set_off = new item_history_setOff();
                                $item_history_set_off->internal_number = $set_off_data->internal_number;
                                $item_history_set_off->external_number = $set_off_data->external_number;
                                $item_history_set_off->document_number = $goods_transfer->document_number;
                                $item_history_set_off->batch_number = $set_off_data->batch_number;
                                $item_history_set_off->branch_id = $goods_transfer->to_branch_id;
                                $item_history_set_off->location_id = $goods_transfer->to_location_id;
                                $item_history_set_off->transaction_date = $goods_transfer->goods_transfer_date;
                                $item_history_set_off->item_id =  $set_off_data->item_id;
                                $item_history_set_off->whole_sale_price = $set_off_data->whole_sale_price;
                                $item_history_set_off->retial_price = $set_off_data->retail_price;
                                $item_history_set_off->cost_price = $set_off_data->cost_price;
                                $item_history_set_off->quantity = $set_off_data->set_off_qty;
                                /* $item_history_set_off->setoff_quantity = $setOff->set_off_qty; */
                                $item_history_set_off->reference_internal_number = $reference_item_history->internal_number;
                                $item_history_set_off->reference_external_number = $reference_item_history->external_number;
                                $item_history_set_off->reference_document_number = $reference_item_history->document_number;
                                /* $item_history_set_off->setoff_id =  $item_history_set_off->item_history_setoff_id; */
                                if ($item_history_set_off->save()) {
                                    $reference_item_history->setoff_quantity = $reference_item_history->setoff_quantity + $set_off_data->set_off_qty;
                                    $reference_item_history->update();
                                }

                            }
                        }
                    }
                    
                }

                return response()->json(["status" => true, "message" => "success"]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }



     //eject
     public function reject_goods_transfer($id)
     {
         try {
             $goods_transfer = goods_transfer::find($id);
             if ($goods_transfer->status != 0) {
                 return response()->json(["status" => false, "message" => "used"]);
             } else {
                 $goods_transfer->status = 2;
                 if ($goods_transfer->update()) {
                     $item_history = item_history::where("internal_number","=",$goods_transfer->internal_number)->delete();
                     $item_history_setoff = item_history_setOff::where("internal_number","=",$goods_transfer->internal_number)->delete();
                     
                 }
 
                 return response()->json(["status" => true, "message" => "success"]);
             }
         } catch (Exception $ex) {
             return $ex;
         }
     }
}
