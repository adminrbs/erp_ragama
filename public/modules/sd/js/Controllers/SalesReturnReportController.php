<?php

namespace Modules\Sd\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class SalesReturnReportController extends Controller
{

    public function printsalesReturnPdf($id)
    {
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
                'salesReturnRequests' => $this->getDatagrnRqst($id),
                'salesReturnReqestItems' => $this->getDatagrnItem($id),
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
                     sales_returns.*,
                     branches.branch_name,
                     
                     branches.fixed_number,
                    customers.primary_address,
                    employees.employee_code,
                    employees.employee_id,
                    employees.employee_name,
                     customers.customer_name,
                     locations.location_name,
                     users.id,
                     users.name AS userName,
                     users.email
                 FROM
                     sales_returns
                 LEFT JOIN
                     locations ON sales_returns.location_id = locations.location_id
                 LEFT JOIN
                     users ON sales_returns.prepaired_by = users.id
                 LEFT JOIN
                     customers ON sales_returns.customer_id = customers.customer_id
                     LEFT JOIN 
                     employees ON sales_returns.employee_id = employees.employee_id
                     LEFT JOIN
                     branches ON sales_returns.branch_id=branches.branch_id
                     WHERE
                     sales_returns.sales_return_Id ="' . $id . '"';
        return DB::select($qry);
    }
    private function getDatagrnItem($id)
    {
        $qry = 'SELECT sales_return_items.*, 
                     items.*,
                     ROUND(sales_return_items.price * sales_return_items.quantity, 2) AS amount
              FROM sales_return_items
              INNER JOIN items ON sales_return_items.item_id = items.item_id
              WHERE sales_return_Id = "' . $id . '"';
        return DB::select($qry);

    }
}
