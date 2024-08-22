<?php

namespace Modules\Prc\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class GoodReceivedReportController extends Controller
{
    public function printGoodResiveReportPdf($id){
        try {

               return response()->json(['success' => true, 'data' => [
                 'goodrecive' => $this->getDatagrnRqst($id),
                 'goodrecive_item' => $this->getDatagrnItem($id),
                 'company_name' => CompanyDetailsController::CompanyName(),
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
                     goods_received_notes.*,
                     branches.branch_name,
                     
                     branches.fixed_number,
                    suppliers.primary_address,
                   
                     locations.location_name,
                     users.id,
                     users.name AS userName,
                     users.email
                 FROM
                     goods_received_notes
                 LEFT JOIN
                     locations ON goods_received_notes.location_id = locations.location_id
                 LEFT JOIN
                     users ON goods_received_notes.prepaired_by = users.id
                 LEFT JOIN
                     suppliers ON goods_received_notes.supplier_id = suppliers.supplier_id
                    
                     LEFT JOIN
                     branches ON goods_received_notes.branch_id=branches.branch_id
                     WHERE
                     goods_received_notes.goods_received_Id ="' . $id . '"';
                     return DB::select($qry);
                 }
              /*    private function getDatagrnItem($id)
                 {
                     $qry = 'SELECT goods_received_note_items.*, 
                     items.*,
                     ROUND(goods_received_note_items.price * goods_received_note_items.quantity, 2) AS amount
              FROM goods_received_note_items
              INNER JOIN items ON goods_received_note_items.item_id = items.item_id
              WHERE goods_received_Id = "' . $id . '"';
                     return DB::select($qry);
                 } */

                 private function getDatagrnItem($id)
                 {
                     $qry = 'SELECT goods_received_note_items.*, 
                     items.Item_code,items.item_Name,items.package_unit,
                     ROUND(goods_received_note_items.price * goods_received_note_items.quantity, 2) AS amount
              FROM goods_received_note_items
              INNER JOIN items ON goods_received_note_items.item_id = items.item_id
              WHERE goods_received_Id = "' . $id . '"';
                     return DB::select($qry);
                 }
}
