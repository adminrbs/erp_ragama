<?php

namespace Modules\Sc\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Sc\Entities\branch;
use Modules\Sc\Entities\location;
use Modules\Sc\Entities\supply_group;

class ViewStockBlanceController extends Controller
{
    public function getStockBlance(Request $request)
    {

        try {

            $Branch = $request->input('cmbBranch');
            $DateofTo = $request->input('txtDateofTo');
            $cmbAny = $request->input('cmbAny');
            $supplyGroup = $request->input('cmbSupplyGroup');

            $cmbproduct = $request->input('cmbproduct');
            $category1 = $request->input('cmbcategory1');

            $category2 = $request->input('cmbcategory2');
            $category3 = $request->input('cmbcategory3');
            $loction = $request->input('cmbLocation');

            $query= '';
            if($loction != 0){
                $query = 'SELECT items.Item_code, items.item_Name,items.reorder_level, SUM(item_historys.quantity) AS quantity, items.unit_of_measure
                FROM item_historys
                LEFT JOIN branches ON item_historys.branch_id  = branches.branch_id 
                LEFT JOIN items ON item_historys.item_id = items.item_id WHERE item_historys.location_id = '.$loction.' AND';
            }else{
                $query = 'SELECT items.Item_code, items.item_Name,items.reorder_level, SUM(item_historys.quantity) AS quantity, items.unit_of_measure
                FROM item_historys
                LEFT JOIN branches ON item_historys.branch_id  = branches.branch_id 
                LEFT JOIN items ON item_historys.item_id = items.item_id WHERE';
            }
           

            
         
            if ($cmbAny == 1) {
               
                $conditions = array();
                if (!empty($Branch) && $Branch !== 'null') {
                    $conditions[] = "item_historys.branch_id = '" . $Branch . "'";
                }
               
                if (!empty($supplyGroup) && $supplyGroup !== 'null') {
                    $conditions[] = "items.supply_group_id = '" . $supplyGroup . "'";
                }

                if (!empty($cmbproduct) && $cmbproduct !== 'null') {
                    $conditions[] = "item_historys.item_id = '" . $cmbproduct . "'";
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

                    $query .= ' ' . implode(' AND ', $conditions);
                }
            }else{
                $conditions = array();
                if (!empty($Branch) && $Branch !== 'null') {
                    $conditions[] = "item_historys.branch_id = '" . $Branch . "'";
                }
                if (!empty($DateofTo && $DateofTo !== 'null')) {
                    $conditions[] = "item_historys.transaction_date <= '" . $DateofTo . "'";
                }
                if (!empty($supplyGroup) && $supplyGroup !== 'null') {
                    $conditions[] = "items.supply_group_id = '" . $supplyGroup . "'";
                }

                if (!empty($cmbproduct) && $cmbproduct !== 'null') {
                    $conditions[] = "item_historys.item_id = '" . $cmbproduct . "'";
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

                    $query .= ' ' . implode(' AND ', $conditions);
                }
            }
            
            $query .= ' GROUP BY items.Item_code';
            //$query = $query . ' GROUP BY items.Item_code';
           // dd($query);
            $result = DB::select($query);
            return response()->json((['success' => 'Data loaded', 'data' => $result]));
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function getSupllyGroup()
    {
        try {

            $data = supply_group::all();
            return response()->json($data);
        } catch (Exception $ex) {
            return $ex;
        }
    }
    public function getbranch()
    {
        try {

            $data = branch::all();
            return response()->json($data);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //get locations
    public function getLocation_stock_balance($id){
        try{
            $locations = location::where("branch_id","=",$id)->get();
            return response()->json($locations);
        }catch(Exception $ex){
            return $ex;
        }
    }

    public function getlocationForBranch(Request $request){
        $branch_ids = $request->get('branch_ids');
        $query = "SELECT L.location_id, CONCAT(L.location_name,'-',branch_name) AS location_name 
                          FROM locations L
                          INNER JOIN branches ON L.branch_id = branches.branch_id
                          WHERE L.branch_id IN (" . implode(", ", $branch_ids) . ")";
        $result = DB::Select($query);
        return response()->json($result);
    }
}
