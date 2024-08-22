<?php

namespace Modules\Prc\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use RepoEldo\ELD\ReportViewer;

class GoodsReturnReportController extends Controller
{
    public function printGoodReturnReport($id)
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
                 goodreceivereturns.*,
                 branches.branch_name,
                 
                 branches.fixed_number,
                suppliers.primary_address,
               
                 locations.location_name,
                 users.id,
                 users.name AS userName,
                 users.email
             FROM
                 goodreceivereturns
             LEFT JOIN
                 locations ON goodreceivereturns.location_id = locations.location_id
             LEFT JOIN
                 users ON goodreceivereturns.prepaired_by = users.id
             LEFT JOIN
                 suppliers ON goodreceivereturns.supplier_id = suppliers.supplier_id
                
                 LEFT JOIN
                 branches ON goodreceivereturns.branch_id=branches.branch_id
                 WHERE
                 goodreceivereturns.goods_received_return_Id ="' . $id . '"';
                 return DB::select($qry);
             }
             private function getDatagrnItem($id)
             {
                 $qry = 'SELECT goodreceivereturn_items.*, 
                 items.*,
                 ROUND(goodreceivereturn_items.price * goodreceivereturn_items.quantity) AS amount
          FROM goodreceivereturn_items
          INNER JOIN items ON goodreceivereturn_items.item_id = items.item_id
          WHERE goodreceivereturn_items.goods_received_return_Id = "' . $id . '"';
                 return DB::select($qry);
             
    }
}
