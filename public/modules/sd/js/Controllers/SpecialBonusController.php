<?php

namespace Modules\Sd\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Sd\Entities\special_bonus;

class SpecialBonusController extends Controller
{
    //load items for cmb in model
    public function load_items_for_special_bonus()
    {
        try {
            $qry = DB::select("SELECT item_id,Item_code,item_Name FROM items WHERE is_active = 1");
            return response()->json(["data" => $qry]);
        } catch (\Exception $ex) {
            return $ex;
        }
    }

    //load customers for cmb in model
    public function get_customer_special_bonus()
    {
        try {
            $qry = DB::select("SELECT customer_id,customer_code,customer_name FROM customers");
            return response()->json(["data" => $qry]);
        } catch (\Exception $ex) {
            return $ex;
        }
    }

    //add special bonus
    public function add_special_bonus(Request $request)
    {
        try {
            /*  $data = $request->all();
            $data['created_by'] = auth()->user()->id;
            $special_bonus = DB::table("special_bonuses")->insert($data);
            return response()->json(["status"=>true]); */
           $days = $request->input('valid_days');
           $item_id = $request->input('item_id');
           $cus_id = $request->input('customer_id');
            $validate_qry = "SELECT COUNT(*) as count 
            FROM special_bonuses 
            WHERE 
                special_bonuses.item_id = $item_id 
                AND special_bonuses.customer_id = $cus_id
                AND DATE(special_bonuses.created_at) BETWEEN DATE(NOW()) AND DATE(DATE_ADD(NOW(), INTERVAL $days DAY));
            
                ";
            $date_result = DB::select($validate_qry);
           
            if($date_result[0]->count > 1){
                return response()->json(["status" => false, "message" => "duplicated"]);
            }

            $special_bonus = new special_bonus();
            $special_bonus->item_id = $request->input('item_id');
            $special_bonus->quantity = $request->input('quantity');
            $special_bonus->bonus_quantity = $request->input('bonus_quantity');
            $special_bonus->updated_by = auth()->user()->id;
            $special_bonus->valid_days = $request->input('valid_days');
            $special_bonus->customer_id = $request->input('customer_id');
            $special_bonus->remark = $request->input('remark');
            if ($special_bonus->save()) {
                return response()->json(["status" => true]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //get all speacial bonus
   /*  public function getAllSpecialBonus($val)
    {
        try {
            $qry = "SELECT
            DATE_FORMAT(special_bonuses.created_at, '%Y-%m-%d') AS created_at,
            special_bonus_id,
            special_bonuses.reject_remark,
            quantity,
            bonus_quantity,
            valid_days,
            items.item_Name,
            special_bonuses.status,
            items.Item_code,
            items.package_unit,
            CONCAT(customers.customer_code, '-', customers.customer_name) AS customer_name,
            routes.route_name
        FROM
            special_bonuses
        INNER JOIN
            items ON special_bonuses.item_id = items.item_id
        INNER JOIN
            customers ON special_bonuses.customer_id = customers.customer_id
        INNER JOIN
            routes ON customers.route_id = routes.route_id
        ORDER BY
            special_bonus_id DESC;
";

            if ($val == 0) {
                $result = DB::select($qry);
                if ($result) {
                    return response()->json(["data" => $result]);
                } else {
                    return response()->json(["data" => []]);
                }
            } else if ($val == 1) {
                $qry = "SELECT
                 DATE_FORMAT(special_bonuses.created_at, '%Y-%m-%d') AS created_at,
                special_bonus_id,
                special_bonuses.reject_remark,
                quantity,
                bonus_quantity,
                valid_days,
                items.item_Name,
                special_bonuses.status,
                items.Item_code,
                items.package_unit,
                customers.customer_code,
                CONCAT(customers.customer_code, '-', customers.customer_name) AS customer_name,
                routes.route_name
            FROM
                special_bonuses
            INNER JOIN
                items ON special_bonuses.item_id = items.item_id
            INNER JOIN
                customers ON special_bonuses.customer_id = customers.customer_id
          
            INNER JOIN 
                routes ON customers.route_id = routes.route_id
            WHERE
              	special_bonuses.valid_days >= CURRENT_DATE - special_bonuses.created_at
            AND
            special_bonuses.status = 1
            ORDER BY
                special_bonus_id DESC;";

                $result = DB::select($qry);
                if ($result) {
                    return response()->json(["data" => $result]);
                } else {
                    return response()->json(["data" => []]);
                }
            } else if ($val == 2) {
                $qry = "SELECT
                 DATE_FORMAT(special_bonuses.created_at, '%Y-%m-%d') AS created_at,
                special_bonus_id,
                special_bonuses.reject_remark,
                quantity,
                bonus_quantity,
                valid_days,
                items.item_Name,
                special_bonuses.status,
                items.Item_code,
                items.package_unit,
                customers.customer_code,
                CONCAT(customers.customer_code, '-', customers.customer_name) AS customer_name,
                routes.route_name
            FROM
                special_bonuses
            INNER JOIN
                items ON special_bonuses.item_id = items.item_id
            INNER JOIN
                customers ON special_bonuses.customer_id = customers.customer_id
           
            INNER JOIN 
                routes ON customers.route_id = routes.route_id
            WHERE
              	special_bonuses.valid_days >= CURRENT_DATE - special_bonuses.created_at
            AND
                special_bonuses.status = 0
            ORDER BY
                special_bonus_id DESC;";

                $result = DB::select($qry);
                if ($result) {
                    return response()->json(["data" => $result]);
                } else {
                    return response()->json(["data" => []]);
                }
            }else if($val == 3){
                $qry = "SELECT
                 DATE_FORMAT(special_bonuses.created_at, '%Y-%m-%d') AS created_at,
                special_bonus_id,
                special_bonuses.reject_remark,
                quantity,
                bonus_quantity,
                valid_days,
                items.item_Name,
                special_bonuses.status,
                items.Item_code,
                items.package_unit,
                customers.customer_code,
                CONCAT(customers.customer_code, '-', customers.customer_name) AS customer_name,
                routes.route_name
            FROM
                special_bonuses
            INNER JOIN
                items ON special_bonuses.item_id = items.item_id
            INNER JOIN
                customers ON special_bonuses.customer_id = customers.customer_id
          
            INNER JOIN 
                routes ON customers.route_id = routes.route_id
            WHERE
              	special_bonuses.valid_days >= CURRENT_DATE - special_bonuses.created_at
            AND
                special_bonuses.status = 2
            ORDER BY
                special_bonus_id DESC;";

                $result = DB::select($qry);
                if ($result) {
                    return response()->json(["data" => $result]);
                } else {
                    return response()->json(["data" => []]);
                }

            }
        } catch (Exception $ex) {
            return $ex;
        }
    } */

    public function getAllSpecialBonus($val)
{
    try {
        // Base SQL query
        $qry = "SELECT
                    DATE_FORMAT(sb.created_at, '%Y-%m-%d') AS created_at,
                    sb.special_bonus_id,
                    sb.reject_remark,
                    sb.quantity,
                    sb.bonus_quantity,
                    sb.valid_days,
                    i.item_Name,
                    sb.status,
                    i.Item_code,
                    i.package_unit,
                    c.customer_code,
                    CONCAT(c.customer_code, '-', c.customer_name) AS customer_name,
                    r.route_name
                FROM
                    special_bonuses sb
                INNER JOIN
                    items i ON sb.item_id = i.item_id
                INNER JOIN
                    customers c ON sb.customer_id = c.customer_id
                LEFT JOIN
                    routes r ON c.route_id = r.route_id";

        // Add conditions based on $val
        if ($val == 1 || $val == 2 || $val == 3) {
            $qry .= " WHERE sb.valid_days >= CURRENT_DATE - sb.created_at";
        }
        if ($val == 1) {
            $qry .= " AND sb.status = 1";
        } elseif ($val == 2) {
            $qry .= " AND sb.status = 0";
        } elseif ($val == 3) {
            $qry .= " AND sb.status = 2";
        }

        $qry .= " ORDER BY sb.special_bonus_id DESC";

        // Execute the query
        $result = DB::select($qry);

        if ($result) {
            return response()->json(["data" => $result]);
        } else {
            return response()->json(["data" => []]);
        }
    } catch (Exception $ex) {
        return $ex;
    }
}

    //get each special bonus
    public function get_each_special_bonus_edit($id)
    {
        try {
            $special_bonus = special_bonus::find($id);
            if ($special_bonus) {
                return response()->json(["data" => $special_bonus]);
            } else {
                return response()->json(["data" => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //update bonus
    public function update_special_bonus(Request $request, $id)
    {
        try {

            $days = $request->input('valid_days');
           $item_id = $request->input('item_id');
           $cus_id = $request->input('customer_id');
            $validate_qry = "SELECT COUNT(*) as count 
            FROM special_bonuses 
            WHERE 
                special_bonuses.item_id = $item_id 
                AND special_bonuses.customer_id = $cus_id
                AND DATE(special_bonuses.created_at) BETWEEN DATE(NOW()) AND DATE(DATE_ADD(NOW(), INTERVAL $days DAY));
            
                ";
            $date_result = DB::select($validate_qry);
           
            if($date_result[0]->count == 1){
                $id_qry = DB::select("SELECT special_bonus_id
                FROM special_bonuses 
                WHERE 
                    special_bonuses.item_id = $item_id 
                    AND special_bonuses.customer_id = $cus_id
                    AND DATE(special_bonuses.created_at) BETWEEN DATE(NOW()) AND DATE(DATE_ADD(NOW(), INTERVAL $days DAY));
                
                    ");
                    if($id_qry[0]->special_bonus_id != $id){
                        return response()->json(["status" => false, "message" => "duplicated"]);
                    }
                
            }else if($date_result[0]->count > 1){
                return response()->json(["status" => false, "message" => "duplicated"]);
            }
            $special_bonus = special_bonus::find($id);
            $special_bonus->item_id = $request->input('item_id');
            $special_bonus->quantity = $request->input('quantity');
            $special_bonus->bonus_quantity = $request->input('bonus_quantity');
            $special_bonus->updated_by = auth()->user()->id;
            $special_bonus->valid_days = $request->input('valid_days');
            $special_bonus->customer_id = $request->input('customer_id');
            $special_bonus->remark = $request->input('remark');
            if ($special_bonus->update()) {
                return response()->json(["status" => true]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //delete bonus
    public function delete_bonus($id)
    {
        try {
            $special_bonus = special_bonus::find($id);
            if ($special_bonus->status != 0) {
                return response()->json(["status" => false]);
            } else {
                if ($special_bonus->delete()) {
                    return response()->json(["status" => true]);
                }
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //approve reject bonus
    public function approve_reject(Request $request,$id, $type)
    {
        try {
            $special_bonus = Special_bonus::find($id);
            if ($type == 1) {
                if ($special_bonus->status != 0) {
                    return response()->json(["message" => "failed"]);
                } else {
                    $special_bonus->status = 1;
                    if ($special_bonus->update()) {
                        return response()->json(["message" => "approved"]);
                    }
                }
            } else {
                if ($special_bonus->status != 0) {
                    return response()->json(["message" => "failed"]);
                } else {
                    $special_bonus->status = 2;
                    $special_bonus->reject_remark = $request->input('remark');
                    if ($special_bonus->update()) {
                        return response()->json(["message" => "rejected"]);
                    }
                }
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }
}
