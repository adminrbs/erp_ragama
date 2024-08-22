<?php

namespace Modules\Md\Http\Controllers;

use Exception;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Md\Entities\item;
use Modules\Md\Entities\supply_group;
use Modules\Md\Entities\category_level_1;
use Modules\Md\Entities\category_level_2;
use Modules\Md\Entities\category_level_3;
use Illuminate\Support\Facades\DB;
use Modules\Md\Entities\item_altenative_name;

use Modules\Md\Entities\item_price;
use Modules\Md\Entities\ItemPaymentTerm;

class ItemController extends Controller
{
    public function addItem(Request $request)
    {
        //dd($request->input('PaymentTerms'));
        try {
            $request->validate([

                'txtName' => 'required',
                

            ]);

            $item = new item();
            $item->Item_code = $request->input('txtItemCode');
            $item->item_Name = $request->input('txtName');

            $item->item_description = $request->input('txtDescription'); // 1.4
            $item->item_altenative_name_id = $request->input('cmbInn');

            $item->sku = $request->input('txtSKU');
            $item->barcode = $request->input('txtBarcode');
            $item->unit_of_measure = $request->input('txtUnitOfMeasure');

            $item->whole_sale_price = $request->input('txtWholeSalePrice'); //1.4
            $item->retial_price = $request->input('txtRetailPrice');
            $item->average_cost_price = $request->input('txtAverageCostPrice');


            $item->package_size = $request->input('txtPackageSize');
            $item->package_unit = $request->input('txtPackageUnit');
            $item->storage_requirements = $request->input('txtStorageRequirements');
            $item->supply_group_id = $request->input('cmbSupplyGroup');
            $item->category_level_1_id = $request->input('cmbCategoryLevel1');
            $item->category_level_2_id = $request->input('cmbCategoryLevel2');
            $item->category_level_3_id = $request->input('cmbCategoryLevel3');
            $item->is_active  = $request->input('chkActive');
            $item->minimum_order_quantity = $request->input('txtMinimumOrderQquantity');
            $item->maximum_order_quantity = $request->input('txtMaximumOrderQuantity');
            $item->reorder_level = $request->input('txtReorderLevel');
            $item->reorder_quantity = $request->input('txtReorderQuantity');
            $item->manage_batch = $request->input('chkManageBatch');
            $item->manage_expire_date = $request->input('chkManageExpireDate');
            $item->allowed_free_quantity = $request->input('chkAllowedFreeQuantity');
            $item->allowed_discount = $request->input('chkAllowedDiscount');

            $item->allowed_promotion = $request->input('chlAllowedPromotion'); // 1.4

            $item->note = $request->input('txtnote');
            if(is_nan($request->input('txtMinimum_margin'))){
                
                $item->minimum_margin =  0;
            }else{
                $item->minimum_margin = $request->input('txtMinimum_margin');
            }
            $item->created_by = Auth::user()->id;

            if ($item->save()) {
                $primaryKey = $item->item_id;

                if($request->input('PaymentTerms')){
                    $term_ids = $request->input('PaymentTerms');
                    $paymentTermsArray = explode(',', $term_ids);
                    foreach($paymentTermsArray as $id){
                       $itemPaymentTerm = new ItemPaymentTerm();
                       $itemPaymentTerm->item_id = $item->item_id;
                       $itemPaymentTerm->payment_term_id = $id;
                       $itemPaymentTerm->save();  
                    }
                }
                return response()->json(["status" => true, "primaryKey" => $primaryKey]);
            } else {
                return response()->json(["status" => false]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    /*     public function addPrice(Request $request, $id){
        try{
            $lastItemID = $id;
            $itemPrice = new item_price();
            $itemPrice->item_id = $lastItemID;
            $itemPrice->description = $request->input('txtPriceDescription');
            $itemPrice->wholesale_price = $request->input('txtWholeSalePrice');
            $itemPrice->retail_price = $request->input('txtRetailPrice');

            if($itemPrice->save()){
                return response()->json(["status" => true]);
            }else{
                return response()->json(["status" => false]);
            }


        }catch(exception $ex){
            return $ex;
        }
    }
 */
    //getting supply group id
    public function getSupplyGroup()
    {
        try {
            $supplrID = supply_group::all();
            return response()->json($supplrID);
        } catch (Exception $ex) {
            return $ex;
        }
    }
    //geting category 01
    public function getCategoryLevelOne()
    {
        try {
            $ctLevel_1 = category_level_1::all();
            return response()->json($ctLevel_1);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //geting category 02
    public function getCategoryLevelTwo($Cat_lvl_1_id)
    {
        try {
            $ctLevel_2 = DB::select("SELECT * FROM item_category_level_2s WHERE item_category_level_2s.Item_category_level_1_id = '" . $Cat_lvl_1_id . "' or item_category_level_2s.Item_category_level_1_id = 1 order by item_category_level_2s.Item_category_level_2_id ASC");
            return response()->json($ctLevel_2);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //geting category 03
    public function getCategoryLevelThree($cat_lvl_2_id)
    {
        try {
            $ctLevel_3 = DB::select("SELECT * FROM item_category_level_3s WHERE item_category_level_3s.Item_category_level_2_id = '" . $cat_lvl_2_id . "' or item_category_level_3s.Item_category_level_2_id = 1 order by item_category_level_3s.Item_category_level_3_id ASC");
            return response()->json($ctLevel_3);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //geting item details
    public function getItemDetails(Request $request)
    {
        try {


            $supplyGroup = $request->input('cmbSupplyGroup');
            $cmbstatus = $request->input('cmbstatus');
            $category1 = $request->input('cmbcategory1');
            $category2 = $request->input('cmbcategory2');
            $category3 = $request->input('cmbcategory3');

            /*  $itemDetails = item::all(); */
            $query = 'SELECT item_id,Item_code,item_Name,package_unit,whole_sale_price,retial_price,IF(is_active = 1,"Yes","No") AS is_active,
           supply_groups.supply_group FROM items LEFT JOIN supply_groups ON items.supply_group_id = supply_groups.supply_group_id';


            $conditions = array();
           
            if (!empty($supplyGroup) && $supplyGroup !== 'null') {
                $conditions[] = "items.supply_group_id = '" . $supplyGroup . "'";
            }

            if (!empty($cmbstatus) && $cmbstatus !== 'null') {
                $conditions[] = "items.is_active = '" . $cmbstatus . "'";
            }

            if (!empty($category1) && $category1 !== 'null') {
                $conditions[] = "items.category_level_1_id = '" . $category1 . "'";
            }

            if (!empty($category2) && $category2 !== 'null') {
                $conditions[] = "items.category_level_2_id = '" . $category2 . "'";
            }

            if (!empty($category3) && $category3 !== 'null') {
                $conditions[] = "items.category_level_3_id = '" . $category3 . "'";
            }

            if (!empty($conditions)) {

                $query .= ' WHERE ' . implode(' AND ', $conditions);
            }


            //dd($query);

           

           
            $result = DB::select($query);
            return response()->json((['success' => 'Data loaded', 'data' => $result]));
        } catch (Exception $ex) {
            return $ex;
        }
    }

    /* public function getItemDetails(Request $request){
        try{

        }catch(Exception $ex){
            $data = $request->input('data');
            $itemData = item::where('item_Name','LIKE','%'.$data.'%')->take(1)->get();
            return response()->json($itemData);
        }
        
    } */

    //delete item
    public function deleteItem($id)
    {
        $deletingItem = item::find($id);
        if ($deletingItem->delete()) {
            return response()->json((['message' => 'Deleted']));
        } else {
            return response()->json((['message' => 'Error']));
        }
    }

    //get each item to edit or view
    public function geteachItem($id)
    {
        try {
            $searchedItem = item::find($id);
            //$paymentTerms = $searchedItem->ItemPaymentTerm;
            $paymentTerms = DB::select("SELECT PT.payment_term_id,PT.payment_term_name FROM item_payment_terms IPT LEFT JOIN payment_terms PT ON IPT.payment_term_id = PT.payment_term_id WHERE IPT.item_id = $id LIMIT 1");

           // $ip = ItemPaymentTerm::all(); // Get all ItemPaymentTerm records



            if ($searchedItem) {
                return response()->json((['success' => 'Data loaded', 'data' => $searchedItem,'terms'=>$paymentTerms]));
            } else {
                return response()->json((['error' => 'Data is not loaded']));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    /*    public function geteachItemPrice($id){
        try{
            
            $searchItemPrice = item_price::where('item_id',$id)->get();
              if ($searchItemPrice) {
                return response()->json((['success' => 'Data loaded', 'data' => $searchItemPrice]));

            } else {
                return response()->json((['error' => 'Data is not loaded']));
            }

        }catch(Exception $ex){
            return $ex;
        }

    } */

    public function updateItem(Request $request, $id)
    {
        try {
            $request->validate([
                'txtName' => 'required',
                

            ]);

            $item = item::find($id);
            $item->Item_code = $request->input('txtItemCode');
            $item->item_Name = $request->input('txtName');

            $item->item_description = $request->input('txtDescription'); // 1.4
            $item->item_altenative_name_id = $request->input('cmbInn');

            $item->sku = $request->input('txtSKU');
            $item->barcode = $request->input('txtBarcode');
            $item->unit_of_measure = $request->input('txtUnitOfMeasure');

            $item->whole_sale_price = $request->input('txtWholeSalePrice'); //1.4
            $item->retial_price = $request->input('txtRetailPrice');
            $item->average_cost_price = $request->input('txtAverageCostPrice');


            $item->package_size = $request->input('txtPackageSize');
            $item->package_unit = $request->input('txtPackageUnit');
            $item->storage_requirements = $request->input('txtStorageRequirements');
            $item->supply_group_id = $request->input('cmbSupplyGroup');
            $item->category_level_1_id = $request->input('cmbCategoryLevel1');
            $item->category_level_2_id = $request->input('cmbCategoryLevel2');
            $item->category_level_3_id = $request->input('cmbCategoryLevel3');
            $item->is_active  = $request->input('chkActive');
            $item->minimum_order_quantity = $request->input('txtMinimumOrderQquantity');
            $item->maximum_order_quantity = $request->input('txtMaximumOrderQuantity');
            $item->reorder_level = $request->input('txtReorderLevel');
            $item->reorder_quantity = $request->input('txtReorderQuantity');
            $item->manage_batch = $request->input('chkManageBatch');
            $item->manage_expire_date = $request->input('chkManageExpireDate');
            $item->allowed_free_quantity = $request->input('chkAllowedFreeQuantity');
            $item->allowed_discount = $request->input('chkAllowedDiscount');

            $item->allowed_promotion = $request->input('chlAllowedPromotion'); // 1.4

            $item->note = $request->input('txtnote');
           
            if(is_nan($request->input('txtMinimum_margin'))){
                
                $item->minimum_margin =  0;
            }else{
                $item->minimum_margin = $request->input('txtMinimum_margin');
            }
            $item->updated_by = Auth::user()->id;


            if ($item->update()) {
                $searchedItem = item::find($id);
                $searchedItem->ItemPaymentTerm()->delete();

                if($request->input('PaymentTerms')){
                    $term_ids = $request->input('PaymentTerms');
                    $paymentTermsArray = explode(',', $term_ids);

                    foreach($paymentTermsArray as $id){
                       $itemPaymentTerm = new ItemPaymentTerm();
                       $itemPaymentTerm->item_id = $item->item_id;
                       $itemPaymentTerm->payment_term_id = $id;
                       $itemPaymentTerm->save();  
                    }
                }


                return response()->json(["status" => true]);
            } else {
                return response()->json(["status" => false]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    /*  public function searchItemNames(){
        try{
            $itemNames = DB::table('items')->select('item_Name')->get();
            return response()->json($itemNames);

        }catch(Exception $ex){
            return $ex;
        }
    } */


    //auto suggest function
    public function searchItemNames(Request $request)
    {
        try {
            $data = $request->input('data');
            $itemData = item::where('item_Name', 'LIKE', '%' . $data . '%')->take(20)->get();
            return response()->json($itemData);
        } catch (Exception $ex) {
        }
    }



    //get INN to cmb
    public function getInn()
    {
        try {

            $Inn = item_altenative_name::all();
            return response()->json($Inn);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //get INN names
    public function searchINNNames()
    {
        try {

            $data = DB::table('item_altenative_names')->select('item_altenative_name')->get();
            return response()->json($data);
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function getItemCategory2()
    {
        try {

            $data = category_level_2::all();
            return response()->json($data);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function getItemCategory3()
    {
        try {

            $data = category_level_3::all();
            return response()->json($data);
        } catch (Exception $ex) {
            return $ex;
        }
    }
}
