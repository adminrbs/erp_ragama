<?php

namespace Modules\Sd\Http\Controllers;


use Exception;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Sd\Entities\category_level_1;
use Modules\Sd\Entities\item;
use Modules\Sd\Entities\item_altenative_name;
use Modules\Sd\Entities\supply_group;

class ItemController extends Controller
{
    public function addItem(Request $request)
    {
        try {
            $request->validate([
    
                'txtName' => 'required'
                
            ]);

            $item = new item();
            $item->Item_code = $request->input('txtItemCode');
            $item->item_Name = $request->input('txtName');

            $item->item_description = $request->input('txtDescription'); // 1.4
            $item->item_altenative_name_id = $request->input('cmbInn');

            $item->sku = $request->input('txtSKU');
            $item->barcode = $request->input('txtBarcode');
            $item->unit_of_measure = $request->input('txtUnitOfMeasure');

            $item->whole_sale_price = $request->input('txtWholeSalePrice');//1.4
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
            

            if ($item->save()) {
                $primaryKey = $item->item_id; 
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
        try{
            $ctLevel_1 = category_level_1::all();
            return response()->json($ctLevel_1);

        }catch(Exception $ex){
            return $ex;
        }
    }

    //geting category 02
    public function getCategoryLevelTwo($Cat_lvl_1_id){
        try{
            $ctLevel_2 = DB::select("SELECT * FROM item_category_level_2s WHERE item_category_level_2s.Item_category_level_1_id = '".$Cat_lvl_1_id."' or item_category_level_2s.Item_category_level_1_id = 1 order by item_category_level_2s.Item_category_level_2_id ASC");
            return response()->json($ctLevel_2);

        }catch(Exception $ex){
            return $ex;
        }

    }

    //geting category 03
    public function getCategoryLevelThree($cat_lvl_2_id){
        try{
            $ctLevel_3 = DB::select("SELECT * FROM item_category_level_3s WHERE item_category_level_3s.Item_category_level_2_id = '".$cat_lvl_2_id."' or item_category_level_3s.Item_category_level_2_id = 1 order by item_category_level_3s.Item_category_level_3_id ASC");
            return response()->json($ctLevel_3);
        }catch(Exception $ex){
            return $ex;
        }

    }

    //geting item details
     public function getItemDetails(){
        try{
            $itemDetails = item::all();
            if ($itemDetails) {
                return response()->json((['success' => 'Data loaded', 'data' => $itemDetails]));
            } else {
                return response()->json((['error' => 'Data is not loaded']));
            }
        }catch(Exception $ex){
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
    public function deleteItem($id){
        $deletingItem = item::find($id);
        if ($deletingItem->delete()) {
            return response()->json((['message' => 'Deleted']));
        } else {
            return response()->json((['message' => 'Error']));
        }

    }

    //get each item to edit or view
    public function geteachItem($id){
        try{
            $searchedItem = item::find($id);
            if ($searchedItem) {
                return response()->json((['success' => 'Data loaded', 'data' => $searchedItem]));

            } else {
                return response()->json((['error' => 'Data is not loaded']));
            }

        }catch(Exception $ex){
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

            $item->whole_sale_price = $request->input('txtWholeSalePrice');//1.4
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
            

            if ($item->update()) {

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
    public function searchItemNames(Request $request){
        try{
            $data = $request->input('data');
            $itemData = item::where('item_Name','LIKE','%'.$data.'%')->take(1)->get();
            return response()->json($itemData);

        }catch(Exception $ex){
           
        }
    }



    //get INN to cmb
    public function getInn(){
        try{
           
             $Inn = item_altenative_name::all(); 
            return response()->json($Inn);

        }catch(Exception $ex){
            return $ex;
        }
    }


    
}


