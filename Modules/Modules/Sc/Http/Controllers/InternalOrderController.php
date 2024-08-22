<?php

namespace Modules\Sc\Http\Controllers;

use App\Http\Controllers\IntenelNumberController;
use DateTime;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Sc\Entities\internal_order;
use Modules\Sc\Entities\internal_order_items;

class InternalOrderController extends Controller
{
    //load item
    public function getItemInfo_internal_order(Request $request, $Item_id, $from_branch, $to_branch)
    {
        try {
            $from = $request->input('from_date');
            $to = $request->input('to_date');

            $from = DateTime::createFromFormat('d/m/Y', $from)->format('Y-m-d');
            $to = DateTime::createFromFormat('d/m/Y', $to)->format('Y-m-d');
            $info = DB::select("SELECT it.item_id,it.Item_code,it.item_Name,it.item_description,it.package_unit,it.reorder_level,(
                SELECT IF(ISNULL(SUM(quantity - setoff_quantity)), 0, SUM(quantity - setoff_quantity))
                FROM item_history_set_offs INNER JOIN locations L ON item_history_set_offs.location_id = L.location_id INNER JOIN location_types LT ON L.location_type_id = LT.location_type_id
                WHERE item_id = '" . $Item_id . "' AND item_history_set_offs.branch_id = '" . $from_branch . "'  AND quantity > 0 AND price_status = 0 AND LT.location_type_id = 3
            ) AS from_balance, (
                SELECT IF(ISNULL(SUM(quantity - setoff_quantity)), 0, SUM(quantity - setoff_quantity))
                FROM item_history_set_offs INNER JOIN locations L ON item_history_set_offs.location_id = L.location_id INNER JOIN location_types LT ON L.location_type_id = LT.location_type_id
                WHERE item_id = '" . $Item_id . "' AND item_history_set_offs.branch_id = '" . $to_branch . "' AND quantity > 0 AND price_status = 0 AND LT.location_type_id = 3
            ) AS to_balance,
            (SELECT IFNULL(average_sales('" . $Item_id . "'," . $from_branch . ",'" . $from . "','" . $to . "'), 0) * -1 AS Offerd_quantity) AS avg_sales
            FROM items it WHERE it.item_id = $Item_id");

            if ($info) {
                return $info;
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //add internal orders
    public function addInternalOrders(Request $request)
    {
        try {
            DB::beginTransaction();

            $from = $request->input('from_date');
            $to = $request->input('to_date');

            $from = DateTime::createFromFormat('d/m/Y', $from)->format('Y-m-d');
            $to = DateTime::createFromFormat('d/m/Y', $to)->format('Y-m-d');

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
            $internal_order = new internal_order();
            $internal_order->internal_number = IntenelNumberController::getNextID();
            $internal_order->external_number = $externalNumber;
            $internal_order->order_date_time = $request->input('order_date_time');
            $internal_order->status = 0;
            $internal_order->remarks = $request->input('txtRemarks');
            $internal_order->prepaired_by = $PreparedBy;
            $internal_order->document_number = 2400;
            $internal_order->from_branch_id = $request->input('cmbBranch');
            $internal_order->to_branch_id = $request->input('ToBranch');
            $internal_order->from_date = $from;
            $internal_order->to_date = $to;
            if ($internal_order->save()) {
              
                foreach ($collection as $i) {
                    
                    $item = json_decode($i);
                    $io_item = new internal_order_items();
                    $io_item->internal_orders_id = $internal_order->internal_orders_id;
                    $io_item->external_number = $internal_order->external_number; 
                    $io_item->internal_number = $internal_order->internal_number; 
                    $io_item->item_id = $item->item_id;
                    $io_item->item_name = $item->item_name;
                    $io_item->quantity = $item->qty;
                    if ($item->PackSize) {
                        $io_item->package_unit = $item->PackSize;
                    } else {
                        $io_item->package_unit = 0;
                    }

                    if ($item->from_branch_stock) {
                        $io_item->from_branch_stock = $item->from_branch_stock;
                    } else {
                        $io_item->from_branch_stock = 0;
                    }

                    if ($item->to_branch_stock) {
                        $io_item->to_branch_stock = $item->to_branch_stock;
                    } else {
                        $io_item->to_branch_stock = 0;
                    }

                    if ($item->avg_sales) {
                        $io_item->avg_sales = $item->avg_sales;
                    } else {
                        $io_item->avg_sales = 0;
                    }

                    $io_item->save();
                }
            }
            DB::commit();
            return response()->json(["status" => true]);
        } catch (Exception $ex) {
            DB::rollBack();
            return $ex;
        }
    }


    //load orders to the list
    public function get_internal_orders(Request $request){
        try{

            $pageNumber = ($request->start / $request->length) + 1;
            $pageLength = $request->length;
            $skip = ($pageNumber - 1) * $pageLength;
            $searchValue = request('search.value');
            $query = DB::table('internal_orders')
            ->select(
                'internal_orders.internal_orders_id',
                'internal_orders.order_date_time',
                'internal_orders.external_number',
                
                'from_branch.branch_name as from_branch_name',
                'to_branch.branch_name as to_branch_name',
                'users.name',
              
                DB::raw('
                IF(internal_orders.status = 0, "Pending",
                    IF(internal_orders.status = 1, "Approved",
                        IF(internal_orders.status = 2, "Rejected",
                            IF(internal_orders.status = 3, "Completed", "")
                        )
                    )
                ) AS status'
            ),
            
              
               
            )
            
            ->join('branches as from_branch', 'internal_orders.from_branch_id', '=', 'from_branch.branch_id')
            ->join('branches as to_branch', 'internal_orders.to_branch_id', '=', 'to_branch.branch_id')
            ->leftJoin('users', 'internal_orders.prepaired_by', '=', 'users.id')
            ->orderBy('internal_orders.external_number', 'DESC');


            if (!empty($searchValue)) {
  
                $query->where(function ($query) use ($searchValue) {
                   
                    $query->where('external_number', 'like', '%' . $searchValue . '%')
                        ->orWhere('order_date_time', 'like', '%' . $searchValue . '%');
                       
                      
                      
                });
            }

            $results = $query->take($pageLength)->skip($skip)->get();
            

            $results->transform(function ($item) {
                $status = "Original";
              //  $disabled = "disabled";
              
                $buttons = '<button class="btn btn-success btn-sm" onclick="view(' . $item->internal_orders_id . ')"><i class="fa fa-eye" aria-hidden="true"></i></button>&#160';
               

                $item->buttons = $buttons;


                $statusLabel = '<label class="badge badge-pill bg-success">' . $status . '</label>';

                $item->statusLabel = $statusLabel;

                return $item;
            });

            return response()->json([
                'data' => $results,
                'draw' => request('draw'),
            ]);
        }catch(Exception $ex){
            return $ex;
        }
    }


    //get each internal order
    public function getEachInternalOrder($id){
        try{
            $order = internal_order::find($id);
            $items = DB::select('SELECT IOI.*,I.reorder_level,I.Item_code FROM internal_order_items IOI INNER JOIN items I ON IOI.item_id = I.item_id WHERE IOI.internal_orders_id = '.$id);
            return response()->json(["status" => true,"data"=>$order,"items"=>$items]);
        }catch(Exception $ex){
            return $ex;
        }
    }


    //load supply group
    public function load_supply_group(){
        try{
            $supply = DB::select("SELECT supply_groups.supply_group_id,supply_groups.supply_group FROM supply_groups");
            return response()->json(["status" => true,"data"=>$supply]);
        }catch(Exception $ex){
            return $ex;
        }
    }

    //load supply group items
    public function load_supply_group_item(Request $request,$id, $from_branch, $to_branch)
    {
        try {
            $from = $request->input('from_date');
            $to = $request->input('to_date');

            $from = DateTime::createFromFormat('d/m/Y', $from)->format('Y-m-d');
            $to = DateTime::createFromFormat('d/m/Y', $to)->format('Y-m-d');
            $info = DB::select("SELECT it.item_id,it.Item_code,it.item_Name,it.item_description,it.package_unit,it.reorder_level,SG.supply_group,(
                SELECT IF(ISNULL(SUM(quantity - setoff_quantity)), 0, SUM(quantity - setoff_quantity))
                FROM item_history_set_offs INNER JOIN locations L ON item_history_set_offs.location_id = L.location_id INNER JOIN location_types LT ON L.location_type_id = LT.location_type_id
                
                WHERE item_id = it.item_id AND item_history_set_offs.branch_id = '" . $from_branch . "'  AND quantity > 0 AND price_status = 0 AND LT.location_type_id = 3
            ) AS from_balance, (
                SELECT IF(ISNULL(SUM(quantity - setoff_quantity)), 0, SUM(quantity - setoff_quantity))
                FROM item_history_set_offs INNER JOIN locations L ON item_history_set_offs.location_id = L.location_id INNER JOIN location_types LT ON L.location_type_id = LT.location_type_id
                WHERE item_id = it.item_id AND item_history_set_offs.branch_id = '" . $to_branch . "' AND quantity > 0 AND price_status = 0 AND LT.location_type_id = 3
            ) AS to_balance,
            (SELECT IFNULL(average_sales(it.item_id," . $from_branch . ",'" . $from . "','" . $to . "'), 0) * -1 AS Offerd_quantity) AS avg_sales
            FROM items it INNER JOIN supply_groups SG ON it.supply_group_id = SG.supply_group_id WHERE it.supply_group_id = $id");

            if ($info) {
                return $info;
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //load selected items to transaction table
    public function loadSelectedItems(Request $request,$from_branch,$to_branch){
        try{
            $from = $request->input('from_date');
            $to = $request->input('to_date');
            $itemArray = $request->input('itemArray');
            $from = DateTime::createFromFormat('d/m/Y', $from)->format('Y-m-d');
            $to = DateTime::createFromFormat('d/m/Y', $to)->format('Y-m-d');
            $item_data = [];
            
            foreach($itemArray as $id){
                $info = DB::select("SELECT it.item_id,it.Item_code,it.item_Name,it.item_description,it.package_unit,it.reorder_level,(
                    SELECT IF(ISNULL(SUM(quantity - setoff_quantity)), 0, SUM(quantity - setoff_quantity))
                    FROM item_history_set_offs INNER JOIN locations L ON item_history_set_offs.location_id = L.location_id INNER JOIN location_types LT ON L.location_type_id = LT.location_type_id
                    WHERE item_id = '" . $id . "' AND item_history_set_offs.branch_id = '" . $from_branch . "'  AND quantity > 0 AND price_status = 0 AND LT.location_type_id = 3
                ) AS from_balance, (
                    SELECT IF(ISNULL(SUM(quantity - setoff_quantity)), 0, SUM(quantity - setoff_quantity))
                    FROM item_history_set_offs INNER JOIN locations L ON item_history_set_offs.location_id = L.location_id INNER JOIN location_types LT ON L.location_type_id = LT.location_type_id
                    WHERE item_id = '" . $id . "' AND item_history_set_offs.branch_id = '" . $to_branch . "' AND quantity > 0 AND price_status = 0 AND LT.location_type_id = 3
                ) AS to_balance,
                (SELECT IFNULL(average_sales('" . $id . "'," . $from_branch . ",'" . $from . "','" . $to . "'), 0) * -1 AS Offerd_quantity) AS avg_sales
                FROM items it WHERE it.item_id = $id");


                /* (SELECT IFNULL(average_sales('" . $Item_id . "'," . $from_branch . ",'" . $from . "','" . $to . "'), 0) * -1 AS Offerd_quantity) AS avg_sales */

                if($info){
                    $item_data[] = $info;
                }
            }



        return response()->json(["status" => true,"data"=>$item_data]);
        }catch(Exception $ex){
            return $ex;
        }
    }
}
