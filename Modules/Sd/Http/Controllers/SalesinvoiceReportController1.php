<?php

namespace Modules\Sd\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Sd\Entities\sales_Invoice;

class SalesinvoiceReportController extends Controller
{

    public function printsalesinvoicePdf($id){
        try {

            /* $query = 'SELECT grns.*, branches.branch_name, locations.location_name
             FROM grns
             INNER JOIN branches ON grns.distributor_id = branches.distributor_id
             INNER JOIN locations ON grns.location_id = locations.location_id
             LEFT JOIN grn_items ON grns.goods_received_note_Id = grn_items.goods_received_note_Id
             WHERE grns.goods_received_note_Id =  "' . $id . '"';
                 $results = DB::select($query);

                 $collection = [];
                 foreach($results as $data){
                     array_push($collection,["goods_received_note_Id"=>$data->goods_received_note_Id,"distributor_id"=>$data->branch_name,
                     "external_number"=>$data->external_number,"grn_date_time"=>$data->grn_date_time,"distributor_id"=>$data->branch_name,
                 "location_id"=>$data->location_name,"total_amount"=>$data->total_amount,"discount_percentage"=>$data->discount_percentage,"remarks"=>$data->remarks,
             "discount_amount"=>$data->discount_amount,"remarks"=>$data->remarks]);
               }*/
               return response()->json(['success' => true, 'data' => [
                 'salesInvoiceRequests' => $this->getDatagrnRqst($id),
                 'salesInvoiceReqestItems' => $this->getDatagrnItem($id),
             ]]);


             } catch (\Exception $ex) {
             return response()->json(['status' => false, 'error' => $ex->getMessage()]);
             }

     }

                     private function getDatagrnRqst($id)
                 {
                     $qry = 'SELECT
                     sales_invoices.*,
                     branches.branch_name,
                     
                     branches.fixed_number,
                    customers.primary_address,
                    customers.route_id,
                    customers.primary_fixed_number,
                    routes.route_name,
                    employees.employee_code,
                    employees.employee_id,
                    employees.employee_name,
                    town_non_administratives.townName,
                     customers.customer_name,
                     locations.location_name,
                     users.id,
                     users.name AS userName,
                     users.email
                 FROM
                     sales_invoices
                 
                 LEFT JOIN
                     locations ON sales_invoices.location_id = locations.location_id
                 LEFT JOIN
                     users ON sales_invoices.prepaired_by = users.id
                 LEFT JOIN
                     customers ON sales_invoices.customer_id = customers.customer_id
                     LEFT JOIN 
                     employees ON sales_invoices.employee_id = employees.employee_id
                     LEFT JOIN
                     branches ON sales_invoices.branch_id=branches.branch_id
                     LEFT JOIN
                     town_non_administratives ON customers.town = town_non_administratives.town_id
                     LEFT JOIN
                     routes ON customers.route_id = routes.route_id
                     
                     WHERE
                     sales_invoices.sales_invoice_Id ="' . $id . '"';
                     return DB::select($qry);
                 }
                 private function getDatagrnItem($id)
                 {
                     $qry = 'SELECT *
                     FROM sales_invoice_items
                     INNER JOIN items ON sales_invoice_items.item_id = items.item_id
                     WHERE sales_invoice_Id  = "' . $id . '"';
                     return DB::select($qry);
                 }
}
