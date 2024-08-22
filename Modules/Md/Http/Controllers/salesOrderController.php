<?php

namespace Modules\Md\Http\Controllers;
use Illuminate\Routing\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class salesOrderController extends Controller
{
    public function getSalesOrderDetails(){
        try{
            $query = 'SELECT so.*, c.customer_name, e.employee_name
            FROM sales_orders so
            JOIN customers c ON so.customer_id = c.customer_id
            JOIN employees e ON so.employee_id = e.employee_id';
            $result =  DB::select($query);
            return $result;

        }catch(Exception $ex){
            return $ex;
        }
    }
    
}
