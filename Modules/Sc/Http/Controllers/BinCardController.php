<?php

namespace Modules\Sc\Http\Controllers;

use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class BinCardController extends Controller
{
    //get items
    public function getItems()
    {
        $qry = "SELECT items.item_id, CONCAT(items.item_Name, '-', items.Item_code) AS item_Name FROM items";
        $result = DB::select($qry);
        if ($qry) {
            return response()->json(["status" => true, "data" => $result]);
        } else {
            return response()->json(["status" => false, "data" => []]);
        }
    }

    //get item movement history data
    public function loadItemMovementHistoryData(Request $request)
    {
        try {
           
            $branch_id = $request->input('branch_id_');
            $loca_id = $request->input('location_id');
            $item_id = $request->input('item_id');
            $from_date = $request->input('from_date');
            $to_date = $request->input('to_date');
           
            //creating from date as yyy/mm/dd
            $from_date_parts = explode('/', $from_date);
            $new_from_date_ = $from_date_parts[2] . '/' . $from_date_parts[1] . '/' . $from_date_parts[0];

            $new_from_date = Carbon::createFromFormat('Y/m/d', $new_from_date_);
            $carbonDate = Carbon::createFromFormat('Y/m/d', $new_from_date_);
            $previousDay = $carbonDate->subDay();
            $new_previouse_day = $previousDay->toDateString();

            

            //creating to date as yyyy/mm/dd
            $todate_parts = explode('/', $to_date);
            $new_to_date_ = $todate_parts[2] . '/' . $todate_parts[1] . '/' . $todate_parts[0];
            $new_to_date = Carbon::createFromFormat('Y/m/d', $new_to_date_);
            DB::select("SET @running_total := 0");


            DB::select("SET @running_total := 0;");
            $qry = " 
                
               
        SELECT
        '" . $new_previouse_day . "' AS transaction_date,
        D.reference_number,
        D.whole_sale_price,
        D.retial_price,
        D.cost_price,
D.reference_external_number,
        'Opening Balance' AS description,
        IF(D.quantity > 0, D.quantity, 0) AS in_quantity,
        ABS(IF(D.quantity < 0, D.quantity, 0)) AS out_quantity,
        (@running_total := @running_total + IF(D.quantity > 0, D.quantity, 0) - ABS(IF(D.quantity < 0, D.quantity, 0))) AS running_total,
        '' AS user_name
    FROM (
        SELECT IFNULL(SUM(quantity), 0) AS quantity,
        IH.external_number AS reference_number,
        IH.whole_sale_price,
        IH.retial_price,
        IH.cost_price,
 IH.reference_external_number
        FROM item_historys IH
        LEFT JOIN items I ON I.item_id = IH.item_id
        WHERE IH.transaction_date < '" . $new_from_date->toDateString() . "' AND IH.item_id = '" . $item_id . "' AND IH.branch_id = '" . $branch_id . "' AND IH.location_id = '" . $loca_id . "'
    ) D
    UNION ALL
    SELECT
        IH.transaction_date,
        IH.external_number AS reference_number,
        IH.whole_sale_price,
        IH.retial_price,
        IH.cost_price,
 IH.reference_external_number,
        IH.description AS description,
        IF(IH.quantity > 0, IH.quantity, 0) AS in_quantity,
        ABS(IF(IH.quantity < 0, IH.quantity, 0)) AS out_quantity,
        (@running_total := @running_total + IF(IH.quantity > 0, IH.quantity, 0) - ABS(IF(IH.quantity < 0, IH.quantity, 0))) AS running_total,
        '' AS user_name
    FROM item_historys IH
    WHERE IH.item_id = '" . $item_id . "' AND IH.transaction_date BETWEEN '" . $new_from_date->toDateString() . "' AND '" . $new_to_date->toDateString() . "' AND IH.branch_id = '" . $branch_id . "' AND IH.location_id = '" . $loca_id . "'
    ";
            if ($branch_id != 0) {
                /*  $qry .= "WHERE IH.item_id = $item_id AND IH.transaction_date BETWEEN'" . $new_from_date . "' AND'" . $new_to_date . "'"; */
               
                $result = DB::select($qry);
                return response()->json(["status" => false, "data" => $result]);
            } else {
                $qry = "
                SELECT
                '" . $new_previouse_day . "' AS transaction_date,
                D.reference_number,
                D.whole_sale_price,
                D.retial_price,
                D.cost_price,
 D.reference_external_number,
                'Opening Balance' AS description,
                IF(D.quantity > 0, D.quantity, 0) AS in_quantity,
                ABS(IF(D.quantity < 0, D.quantity, 0)) AS out_quantity,
                (@running_total := @running_total + IF(D.quantity > 0, D.quantity, 0) - ABS(IF(D.quantity < 0, D.quantity, 0))) AS running_total,
                '' AS user_name
            FROM (
                SELECT IFNULL(SUM(quantity), 0) AS quantity,
                IFNULL(IH.manual_number, IH.external_number) AS reference_number,
                IH.whole_sale_price,
                IH.retial_price,
                IH.cost_price,
 IH.reference_external_number
                FROM item_historys IH
                LEFT JOIN items I ON I.item_id = IH.item_id
                WHERE IH.transaction_date < '" . $new_from_date->toDateString() . "' AND IH.item_id = '" . $item_id . "'
            ) D
            UNION ALL
            SELECT
                IH.transaction_date,
                IFNULL(IH.manual_number, IH.external_number) AS reference_number,
                IH.whole_sale_price,
                IH.retial_price,
                IH.cost_price,
 IH.reference_external_number,
                IH.description AS description,
                IF(IH.quantity > 0, IH.quantity, 0) AS in_quantity,
                ABS(IF(IH.quantity < 0, IH.quantity, 0)) AS out_quantity,
                (@running_total := @running_total + IF(IH.quantity > 0, IH.quantity, 0) - ABS(IF(IH.quantity < 0, IH.quantity, 0))) AS running_total,
                '' AS user_name
            FROM item_historys IH
            WHERE IH.item_id = $item_id AND IH.transaction_date BETWEEN '" . $new_from_date->toDateString() . "' AND '" . $new_to_date->toDateString() . "'
            
                ";
                
                $result = DB::select($qry);

                return response()->json(["status" => true, "data" => $result]);
            }
        } catch (Exception $ex) {
            return response()->json(["status" => false, "data" => $ex->getMessage()]);
        }
    }
}
