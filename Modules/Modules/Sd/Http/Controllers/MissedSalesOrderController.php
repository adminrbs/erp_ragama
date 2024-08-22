<?php

namespace Modules\Sd\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Sd\Entities\sales_oder_item;

class MissedSalesOrderController extends Controller
{
   //load missed order details
   public function get_missed_order_sales(){
    try{
        $result = DB::select("SELECT SO.order_date_time as ordered_date ,
        SO.external_number AS order_number , 
        SI.order_date_time as invoiced_date ,  
        SI.manual_number AS  invoice_number ,
        I.Item_code , 
        I.item_Name,
        I.package_unit AS  pack_size ,
        SOI.quantity AS  order_qty , 
         IFNULL(SII.quantity*-1,0) AS invoiced_qty ,
         SOI.quantity - IFNULL(SII.quantity*-1,0) AS missed_oreder_qty,
         SOI.sales_order_item_id
       FROM sales_orders SO 
      INNER JOIN sales_order_items SOI ON SO.sales_order_Id=SOI.sales_order_Id
      INNER JOIN items I ON I.item_id=SOI.item_id 
      INNER JOIN sales_invoices SI ON SI.sales_order_Id=SO.sales_order_Id
      LEFT JOIN  sales_invoice_items SII  ON SI.sales_invoice_Id=SII.sales_invoice_Id  AND SII.item_id=SOI.item_id  
      WHERE order_status_id=2  AND SOI.quantity > IFNULL(SII.quantity*-1,0) AND SOI.`status`= 0");

        if($result){
            return response()->json(['success' => 'Data loaded', 'data' => $result]);
        }else{
            return response()->json(['faied' => 'Data not loaded', 'data' => []]);
        }

    }catch(Exception $ex){
        return $ex;
    }

   }

   //update status
   public function update_missed_order_sales_status(Request $request){
    try{
        
        $id_array = json_decode($request->input('id_array'));
        foreach($id_array as $id){
            $SO_item = sales_oder_item::find($id);
            $SO_item->status = 1;
            $SO_item->update();
        }

        return response()->json(['status' => true]);
        
    }catch(Exception $ex){
        return $ex;
    }

   }
}
