<?php

namespace Modules\Md\Http\Controllers;

use Illuminate\Routing\Controller;
use Exception;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Modules\Md\Entities\item;
use Modules\Md\Entities\supplier;
use Modules\Md\Entities\supplier_item_code;

class supplierItemCodeController extends Controller
{
    public function suppliersname(){
        $data = supplier::all();
        return response()->json($data);
    }
    public function getitemdata(){

        try {
/*
            $query = "SELECT items.item_id, items.item_Name, items.Item_code, IFNULL(supplier_item_codes.supplier_item_code, '') AS supplier_item_code
            FROM items
            LEFT JOIN supplier_item_codes ON items.item_id = supplier_item_codes.item_id
            WHERE CAST(items.item_id AS CHAR) = CAST(items.item_Name AS CHAR)
            ";
            $customerDteails = DB::select($query);

            return response()->json((['success' => 'Data loaded', 'data' => $customerDteails]));
*/


            $customerDteails = item::all();
            if ($customerDteails) {
                return response()->json((['success' => 'Data loaded', 'data' => $customerDteails]));
            } else {
                return response()->json((['error' => 'Data is not loaded']));
            }
        } catch (Exception $ex) {
            if ($ex instanceof ValidationException) {
                return response()->json(["ValidationException" => ["id" => collect($ex->errors())->keys()[0], "message" => $ex->errors()[collect($ex->errors())->keys()[0]]]]);
            }
        }

    }


    public function savesavesuppliers(Request $request){


        try {

            $status = false;
            $sup_item_code = supplier_item_code::where([['supplier_id','=',$request->get('cmbSupplieritemCode')]])->first();
            if($sup_item_code){

                $sup_item = supplier_item_code::where([['item_id','=',$request->get('item_id')]])->first();
                if($sup_item){


                $supplier=  supplier_item_code::find($sup_item['supplier_item_code_id']);
                if($supplier){
                    $supplier->item_id= $request->get('item_id');
                    $supplier->supplier_id= $request->get('cmbSupplieritemCode');
                    $supplier->supplier_item_code = $request->get('supplier_item_code');
                    $status = $supplier->update();
                }
            }else{
                $supplier= new supplier_item_code();
                $supplier->item_id= $request->get('item_id');
                $supplier->supplier_id= $request->get('cmbSupplieritemCode');
                $supplier->supplier_item_code = $request->get('supplier_item_code');
                $status = $supplier->save();
            }
            }else{
                $supplier= new supplier_item_code();
                $supplier->item_id= $request->get('item_id');
                $supplier->supplier_id= $request->get('cmbSupplieritemCode');
                $supplier->supplier_item_code = $request->get('supplier_item_code');
                $status = $supplier->save();
            }


           if ($status) {

                return response()->json(['status' => true]);
            } else {
                Log::error('Error saving common setting: ' . print_r($supplier->getErrors(), true));
                return response()->json(['status' => false]);
            }

        } catch (Exception $ex) {
            return response()->json(['status' => false,'error' => $ex]);
        }
    }
}
