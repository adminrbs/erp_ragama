<?php

namespace Modules\Prc\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class PurchaseOrderReporController extends Controller
{
    public function printpurchaseOrderReportPdf($id){
       // dd(CompanyDetailsController::CompanyName());
        try {

            
               return response()->json(['success' => true, 'data' => [
                 'purchaseorder' => $this->getDatagrnRqst($id),
                 'purchaseorder_item' => $this->getDatagrnItem($id),
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
                     purchase_order_notes.*,
                     branches.branch_name,
                     
                     branches.fixed_number,
                    suppliers.primary_address,
                   
                     locations.location_name,
                     users.id,
                     users.name AS userName,
                     users.email
                 FROM
                     purchase_order_notes
                 LEFT JOIN
                     locations ON purchase_order_notes.location_id = locations.location_id
                 LEFT JOIN
                     users ON purchase_order_notes.prepaired_by = users.id
                 LEFT JOIN
                     suppliers ON purchase_order_notes.supplier_id = suppliers.supplier_id
                    
                     LEFT JOIN
                     branches ON purchase_order_notes.branch_id=branches.branch_id
                     WHERE
                     purchase_order_notes.purchase_order_Id ="' . $id . '"';
                     return DB::select($qry);
                 }
                 private function getDatagrnItem($id)
                 {
                     $qry = 'SELECT *
                     FROM purchase_order_note_items
                     INNER JOIN items ON purchase_order_note_items.item_id = items.item_id
                     WHERE purchase_order_Id  ="' . $id . '"';
                     return DB::select($qry);
                 }
}
