<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class dashBoardController extends Controller
{
    //load data to dashboard
    public function loadOrderData()
    {
        try {
            $user_id = auth()->id();

            $currentMonth = Carbon::now()->month;

           // $userrole = "SELECT users_roles.role_id FROM users_roles WHERE users_roles.user_id=$user_id";
           // $alluserrol = DB::select($userrole);
          

                $order_count = DB::select(
                                "
                SELECT COUNT(*) AS count,FORMAT(SUM(total_amount), 0) AS order_total_amount
                FROM sales_orders
                WHERE MONTH(order_date_time) = ? 
                AND YEAR(order_date_time) = YEAR(CURRENT_DATE())",
                                [$currentMonth]
                );



                /* $pending_to_deliver = DB::select("SELECT COUNT(*) AS count
                FROM sales_invoices
                WHERE sales_invoices.is_delivery_planned = '0'
                AND NOT EXISTS (
                    SELECT 1 
                    FROM sales_returns 
                    WHERE sales_returns.sales_invoice_id = sales_invoices.sales_invoice_Id
                );"); */

                $pending_to_deliver = DB::select(
                                    '
                    SELECT COUNT(*) AS count,FORMAT(SUM(total_amount), 0) AS pending_total_amount
                    FROM sales_orders
                    WHERE sales_orders.order_status_id = 1
                    AND MONTH(order_date_time) = ? 
                    AND YEAR(order_date_time) = YEAR(CURRENT_DATE())',
                    [$currentMonth]
                );


                $age = DB::select('SELECT order_late_age FROM global_settings');
                $late_orders = DB::select("SELECT COUNT(*) AS count,FORMAT(SUM(total_amount),0) AS late_total_amount
                FROM sales_orders
                WHERE sales_orders.order_status_id = 1 AND sales_orders.order_date_time < CURRENT_DATE() AND DATEDIFF(CURRENT_DATE, sales_orders.order_date_time) > '" . $age[0]->order_late_age . "'"); // need to add branch

                $missed_order = DB::Select(
                                        "
                    SELECT COUNT(*) as count
                    FROM sales_orders SO 
                    INNER JOIN sales_order_items SOI ON SO.sales_order_Id=SOI.sales_order_Id
                    INNER JOIN sales_invoices SI ON SI.sales_order_Id=SO.sales_order_Id
                    LEFT JOIN  sales_invoice_items SII ON SI.sales_invoice_Id=SII.sales_invoice_Id AND SII.item_id=SOI.item_id  
                    WHERE order_status_id=2  
                    AND SOI.quantity > IFNULL(SII.quantity*-1,0) 
                    AND SOI.`status`= 0 
                    AND MONTH(SO.order_date_time) = ? 
                    AND YEAR(SO.order_date_time) = YEAR(CURRENT_DATE())",
                    [$currentMonth]
                );

                return response()->json(['order_count' => $order_count, 'pending_to_deliver' => $pending_to_deliver, 'late_orders' => $late_orders, 'missed_order' => $missed_order]);
            
        } catch (Exception $ex) {
            return $ex;
        }
    }
}
