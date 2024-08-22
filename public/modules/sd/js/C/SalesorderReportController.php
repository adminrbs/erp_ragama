<?php

namespace Modules\Sd\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Mockery\Expectation;
use RepoEldo\ELD\ReportViewer;

class SalesorderReportController extends Controller
{
    public function printSalesOrderReport($id)
   {
  
     try {
 
         
            return response()->json(['success' => true, 'data' => [
              'goodrecive' => $this->getDatagrnRqst($id),
              'goodrecive_item' => $this->getDatagrnItem($id),
              'company_name' =>CompanyDetailsController::CompanyName(),
              'company_address' => CompanyDetailsController::CompanyAddress(),
              'contact_details' => CompanyDetailsController::CompanyNumber()
          ]]);
 
 
          } catch (\Exception $ex) {
          return response()->json(['status' => false, 'error' => $ex->getMessage()]);
          }
 
  }
 
                  private function getDatagrnRqst($id)
              {
                  $qry = 'SELECT
                  sales_orders.*,
                  branches.branch_name,
                  employees.employee_name,
                  employees.address,
                  branches.fixed_number,
                
                
                  locations.location_name,
                  users.id,
                  users.name AS userName,
                  users.email
              FROM
                  sales_orders
              LEFT JOIN
                  locations ON sales_orders.location_id = locations.location_id
                  
              LEFT JOIN
                  users ON sales_orders.prepaired_by = users.id
            LEFT JOIN
            employees ON sales_orders.employee_id  = employees.employee_id 
                 
                  LEFT JOIN
                  branches ON sales_orders.branch_id=branches.branch_id
                  WHERE
                  sales_orders.sales_order_Id ="' . $id . '"';
                  return DB::select($qry);
              }
              private function getDatagrnItem($id)
              {
                  $qry = 'SELECT sales_order_items.*, 
                  items.*,
                  ROUND(sales_order_items.price * sales_order_items.quantity) AS amount
           FROM sales_order_items
           INNER JOIN items ON sales_order_items.item_id = items.item_id
           WHERE sales_order_items.sales_order_Id= "' . $id . '"';
                  return DB::select($qry);
              
     }
    
}
