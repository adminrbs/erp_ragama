<?php

namespace Modules\Sd\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Sd\Entities\sales_order;

class PendingOrderController extends Controller
{
    public function load_blocked_orders()
    {
        try {
            $block_result = [];
            /* $results = DB::SELECT('SELECT COUNT(*) AS pending_order_count,SO.sales_order_Id,SO.branch_id,So.external_number,SO.order_date_time,IF(SO.order_type = 0,"ERP",IF(SO.order_type = 1,"Customer App",IF(SO.order_type = 2,"Customer App",IF(SO.order_type = 3,"SFA",IF(SO.order_type = 4,"SFA Web App",IF(SO.order_type = 5,"API","Unknown")))))) AS order_type,SO.expected_date_time,SO.total_amount,C.customer_name,E.employee_name,B.branch_name FROM sales_orders SO INNER JOIN customers C ON SO.customer_id = C.customer_id INNER JOIN employees E ON SO.employee_id = E.employee_id INNER JOIN branches B ON SO.branch_id = B.branch_id WHERE SO.order_status_id = 1 GROUP BY branch_id'); */
            //getting pending order ids
            $pending_order_ids = DB::select('SELECT SO.sales_order_Id, SO.employee_id,SO.customer_id FROM sales_orders SO WHERE SO.order_status_id = 1');
            //checking block status of selected pending orders
            if ($pending_order_ids) {


                foreach ($pending_order_ids as $row) {
                    $customerId = $row->customer_id;
                    $employeeId = $row->employee_id;
                    $order_id = $row->sales_order_Id;
                    $is_block = DB::select('CALL sd_customer_is_blocked(?, ?)', [$customerId, $employeeId]);

                    // Check if is_blocked is equal to 1
                    if ($is_block[0]->is_blocked == 1) {
                        $block_result[] = $order_id;
                    }
                }
                $order_ids_string = implode(',', $block_result);
                $sql = "
                SELECT
                SO.sales_order_Id,
                SO.branch_id,
                SO.external_number,
                SO.employee_id,
                SO.customer_id,
                SO.block_request_sent,
                SO.order_date_time,
                IF(SO.order_type = 0, 'ERP',
                    IF(SO.order_type = 1, 'Customer App',
                        IF(SO.order_type = 2, 'Customer App',
                            IF(SO.order_type = 3, 'SFA',
                                IF(SO.order_type = 4, 'SFA Web App',
                                    IF(SO.order_type = 5, 'API', 'Unknown')
                                )
                            )
                        )
                    )
                ) AS order_type,
                SO.expected_date_time,
                SO.total_amount,
                CONCAT(C.customer_code,'-',C.customer_name) AS customer_name,
                T.townName,
                LEFT(E.employee_name,7) AS employee_name,
                B.branch_name,
                R.route_name
                
            FROM
                sales_orders SO
            LEFT JOIN
                customers C ON SO.customer_id = C.customer_id
            LEFT JOIN
                employees E ON SO.employee_id = E.employee_id
            LEFT JOIN
                branches B ON SO.branch_id = B.branch_id
            LEFT JOIN
                town_non_administratives T ON C.town = T.town_id
            LEFT JOIN
                routes R ON C.route_id = R.route_id
            WHERE
                SO.sales_order_Id IN ($order_ids_string)
            GROUP BY
                SO.sales_order_Id, SO.branch_id;";

                $results = DB::select($sql);

                $count_qry = DB::select("SELECT COUNT(*) as count,SO.branch_id,B.branch_name FROM sales_orders SO INNER JOIN branches B ON SO.branch_id = B.branch_id
                WHERE SO.sales_order_Id IN ($order_ids_string)
                GROUP BY
                 SO.branch_id;");

                if ($results) {
                    return response()->json(['success' => 'Data loaded', 'data' => $results, 'count' => $count_qry]);
                } else {
                    return response()->json(['success' => 'Data not loaded', 'data' => []]);
                }
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //update order status
    public function update_order_block_status(Request $request, $id, $block_id)
    {
        try {
            $order_ids = json_decode($request->input('order_ids'));

            $SO = sales_order::find($id);
            if ($SO) {
                $SO->block_request_sent = 1;
                $SO->customer_block_id = $block_id;
                if ($SO->update()) {

                    foreach ($order_ids as $id_) {
                        $SO_ = sales_order::find($id_);
                        $SO_->block_request_sent = 1;
                        $SO_->customer_block_id = $block_id;
                        $SO_->update();
                    }

                    return response()->json(['success' => true]);
                } else {
                    return response()->json(['success' => false]);
                }
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }
}
