<?php

namespace Modules\Sc\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

use Modules\Tools\Entities\branch;
use Modules\Tools\Entities\item;
use Modules\Tools\Entities\ItemHistorySetOff;
use Modules\Tools\Entities\SalesInvoiceItemSetoff;
use Modules\Tools\Entities\supply_group;

class UpdateBatchPriceController extends Controller
{
    public function get_filter_data()
    {

        try {
            $supplyGroup = supply_group::all();
            $item = item::all();
            $branch = branch::all();
            return response()->json(["status" => true, "data" => ["supplyGroup" => $supplyGroup, "item" => $item, "branch" => $branch]]);
        } catch (Exception $ex) {
            return response()->json(["status" => false, "exception" => $ex]);
        }
    }


    public function getBatchData($filters)
    {


        try {
            $filters =  json_decode($filters);

           /*  $query = "SELECT DISTINCT
            ih.item_history_setoff_id,
            goods_received_notes.external_number,
            goods_received_notes.goods_received_date_time,
            items.item_Name,
            items.package_unit,
            ih.batch_number,
            ih.cost_price,
            ih.whole_sale_price,
            ih.retial_price,
            ih.quantity - ih.setoff_quantity as qty,
            B.branch_name,
            SG.supply_group
            FROM item_history_set_offs AS ih
            LEFT JOIN goods_received_notes ON ih.external_number = goods_received_notes.external_number
            LEFT JOIN goods_received_note_items ON goods_received_notes.goods_received_Id = goods_received_note_items.goods_received_Id
            LEFT JOIN items ON ih.item_id = items.item_id
            LEFT JOIN branches B ON ih.branch_id = B.branch_id
            LEFT JOIN supply_groups SG ON items.supply_group_id = SG.supply_group_id
            WHERE (ih.quantity - ih.setoff_quantity) > 0 AND "; */
            $query = "SELECT DISTINCT
	ih.item_history_setoff_id,
	ih.external_number,
	ih.transaction_date AS goods_received_date_time,
	items.item_Name,
	items.package_unit,
	ih.batch_number,
	ih.cost_price,
	ih.whole_sale_price,
	ih.retial_price,
	ih.quantity - ih.setoff_quantity AS qty,
	B.branch_name,
	SG.supply_group 
FROM
	item_history_set_offs AS ih
	LEFT JOIN items ON ih.item_id = items.item_id
	LEFT JOIN branches B ON ih.branch_id = B.branch_id
	LEFT JOIN supply_groups SG ON items.supply_group_id = SG.supply_group_id 
WHERE
	( ih.quantity - ih.setoff_quantity ) > 0 AND ";

            if ($filters->supply_group != "any") {
                $query .= "items.supply_group_id = '" . $filters->supply_group . "' AND ";
            }
            if ($filters->branch != "any") {
                $query .= "ih.branch_id = '" . $filters->branch . "' AND ";
            }
            if ($filters->item != "any") {
                $query .= "ih.item_id = '" . $filters->item . "' AND ";
            }
            $query = preg_replace('/\W\w+\s*(\W*)$/', '$1', $query);
            //dd($query);
            //return $query;
            $result = DB::select($query);
            return response()->json(["status" => true, "data" => $result]);
        } catch (Exception $ex) {
            return response()->json(["status" => false, "exception" => $ex]);
        }
    }


    public function updateBatchPrice(Request $request, $item_setoff_id)
    {
        try {
            $invoice_item_setoff = ItemHistorySetOff::find($item_setoff_id);
            $status = false;
            if ($invoice_item_setoff) {
                $invoice_item_setoff->whole_sale_price = $request->get('whole_sale_price');
                $invoice_item_setoff->retial_price = $request->get('retail_price');
                $status =  $invoice_item_setoff->update();
            }

            return response()->json(["status" => $status, "data" => []]);
        } catch (Exception $ex) {
            return response()->json(["status" => false, "exception" => $ex]);
        }
    }
}
