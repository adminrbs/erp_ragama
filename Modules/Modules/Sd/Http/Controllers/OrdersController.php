<?php

namespace Modules\Sd\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    public function getSalesOrderPendingDetails($id)
    {
        try {



           

                
                $query = 'SELECT
            sales_orders.sales_order_Id,
            sales_orders.order_date_time,
            sales_orders.external_number,
            sales_orders.expected_date_time,
            sales_orders.total_amount,
            IF(sales_orders.order_type = 0,"ERP",
               IF(sales_orders.order_type = 1,"Customer App",
                  IF(sales_orders.order_type = 2,"Customer App",
                     IF(sales_orders.order_type = 3,"SFA",
                        IF(sales_orders.order_type = 4,"SFA Web App",
                          IF(sales_orders.order_type = 5,"API","Unknown")
                          )
                       )
                    )
                 )
              ) AS Sales_order_type,
            customers.customer_name,
            employees.employee_name,
            delivery_types.delivery_type_name,
            sales_orders.order_status_id 
        FROM
            sales_orders
            INNER JOIN customers ON sales_orders.customer_id = customers.customer_id
            INNER JOIN employees ON sales_orders.employee_id = employees.employee_id
            LEFT JOIN delivery_types ON sales_orders.deliver_type_id = delivery_types.delivery_type_id WHERE sales_orders.order_status_id = 1 ';

                if (isset($id) && is_numeric($id) && $id > 0) {
                   // dd('kkk');
                    $query .= '   AND  sales_orders.branch_id = ' . $id;
                }
                $query .= '  ORDER BY sales_orders.sales_order_Id DESC';
               // dd($query);
                $result =  DB::select($query);
                if ($result) {
                    return response()->json(['success' => 'Data loaded', 'data' => $result]);
                } else {
                    return response()->json(['success' => 'Data loaded', 'data' => []]);
                }
            
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //late orders
    public function getLateOrdersDetails($id)
    {
        try {

            $age = DB::select('SELECT order_late_age FROM global_settings');
            //dd($age[0]->order_late_age);

           


                $query = 'SELECT
            sales_orders.sales_order_Id,
            sales_orders.order_date_time,
            sales_orders.external_number,
            sales_orders.expected_date_time,
            sales_orders.total_amount,
            DATEDIFF(CURRENT_DATE(), sales_orders.order_date_time) AS age,
            IF(sales_orders.order_type = 0,"ERP",
               IF(sales_orders.order_type = 1,"Customer App",
                  IF(sales_orders.order_type = 2,"Customer App",
                     IF(sales_orders.order_type = 3,"SFA",
                        IF(sales_orders.order_type = 4,"SFA Web App",
                          IF(sales_orders.order_type = 5,"API","Unknown")
                          )
                       )
                    )
                 )
              ) AS Sales_order_type,
            customers.customer_name,
            employees.employee_name,
            delivery_types.delivery_type_name,
            sales_orders.order_status_id 
        FROM
            sales_orders
            INNER JOIN customers ON sales_orders.customer_id = customers.customer_id
            INNER JOIN employees ON sales_orders.employee_id = employees.employee_id
            LEFT JOIN delivery_types ON sales_orders.deliver_type_id = delivery_types.delivery_type_id 
            WHERE sales_orders.order_status_id = 1 AND sales_orders.order_date_time < CURRENT_DATE() AND DATEDIFF(CURRENT_DATE, sales_orders.order_date_time) > '.$age[0]->order_late_age;
          

                if (isset($id) && is_numeric($id) && $id > 0) {
                   
                    $query .= ' AND sales_orders.branch_id = ' . $id;
                   
                    
                }
                $query .= ' ORDER BY sales_order_Id DESC';
               // dd($query);
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
}
