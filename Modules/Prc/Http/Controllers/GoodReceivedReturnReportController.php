<?php

namespace Modules\Prc\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class GoodReceivedReturnReportController extends Controller
{
    public function printGoodResiveReportPdf($id){
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
                 'goodrecive' => $this->getDatagrnRqst($id),
                 'goodrecive_item' => $this->getDatagrnItem($id),
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
                 private function getDatagrnItem($id)
                 {
                     $qry = 'SELECT goods_received_note_items.*, 
                     items.*,
                     ROUND(goods_received_note_items.price * goods_received_note_items.quantity, 2) AS amount
              FROM goods_received_note_items
              INNER JOIN items ON goods_received_note_items.item_id = items.item_id
              WHERE goods_received_Id = "' . $id . '"';
                     return DB::select($qry);
                 }
}
