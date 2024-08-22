<?php

namespace Modules\Md\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\Hash;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Modules\Md\Entities\category_level_1;
use Modules\Md\Entities\category_level_2;
use Modules\Md\Entities\category_level_3;
use Modules\Md\Entities\delivery_type;
use Modules\Md\Entities\employee_designation;
use Modules\Md\Entities\employee_Status;
use Modules\Md\Entities\SalesReturnReson;
use Modules\Md\Entities\vehicle_type;

class CategoryLevelController extends Controller
{
    //......loard data ...
    public function categoryLevel1Data()
    {

        try {
            $customerDteails = category_level_1::all();
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

    //...........save data.....
    public function saveCategoryLevel1(Request $request)
    {

        $validatedData = $request->validate([
            'txtCategorylevel1' => 'required',
        ]);

        try {

            $categoryLevel1 = new category_level_1();
            $categoryLevel1->category_level_1 = $request->get('txtCategorylevel1');


            if ($categoryLevel1->save()) {

                return response()->json(['status' => true]);
            } else {
                Log::error('Error saving common setting: ' . print_r($categoryLevel1->getErrors(), true));
                return response()->json(['status' => false]);
            }
        } catch (Exception $ex) {
            return response()->json(['error' => $ex]);
        }
    }

    //.....lavel1 Edite.......

    public function categorylevel1Edite($id)
    {
        $level1 = category_level_1::find($id);
        return response()->json($level1);
    }

    //...........leval1 update...
    public function txtCategorylevel1Update(Request $request, $id)
    {
        $lavel1 = category_level_1::findOrFail($id)->update([
            'category_level_1' => $request->txtCategorylevel1,
        ]);
        return response()->json($lavel1);
    }


    // category Level 1 Status update

    public function catLevel1tStatus(Request $request, $id)
    {
        $level1 = category_level_1::findOrFail($id);
        $level1->is_active = $request->status;
        $level1->save();

        return response()->json(' status updated successfully');
    }


    public function deletelevel1($id)
    {

        
        try {
            $level3 = category_level_1::find($id);
            if (is_null($level3->system) || $level3->system == 1) {
                return response()->json(['error' => 'Record has been Not Delete']);
            } else {
                $level3->delete();
                return response()->json(['success' => 'Record has been Delete']);
            }
        } catch (Exception $ex) {
            return response()->json($ex);
        }


    }


    //#################   Level 2 ##########
    public function loadCategory2()
    {
        $data = category_level_1::orderBy('item_category_level_1_id', 'ASC')->get();
        return response()->json($data);
    }
    public function categoryLevel2Data()
    {
        try {

            $query = "SELECT item_category_level_2s.*,item_category_level_1s.category_level_1 FROM item_category_level_2s
        LEFT JOIN item_category_level_1s ON item_category_level_2s.Item_category_level_1_id = item_category_level_1s.item_category_level_1_id";

            $customerDetails = DB::select($query);

            return response()->json(['success' => 'Data loaded', 'data' => $customerDetails]);
        } catch (\Exception $ex) {
            // Log the error for debugging purposes
            Log::error($ex);

            return response()->json(['error' => 'An error occurred while loading data']);
        }
    }


    //...........save data.....
    public function saveCategoryLevel2(Request $request)
    {

        $validatedData = $request->validate([
            'cmbLeve1' => 'required',
            'txtCategorylevel2' => 'required',
        ]);
        try {

            $categoryLevel2 = new category_level_2();
            $categoryLevel2->Item_category_level_1_id = $request->get('cmbLeve1');
            $categoryLevel2->category_level_2 = $request->get('txtCategorylevel2');

            if ($categoryLevel2->save()) {

                return response()->json(['status' => true]);
            } else {
                Log::error('Error saving common setting: ' . print_r($categoryLevel2->getErrors(), true));
                return response()->json(['status' => false]);
            }
        } catch (Exception $ex) {
            return response()->json(['error' => $ex]);
        }
    }

    //.....lavel1 Edite.......

    public function categorylevel2Edite($id)
    {
        $level1 = category_level_2::find($id);
        return response()->json($level1);
    }

    //...........leval1 update...
    public function txtCategorylevel2Update(Request $request, $id)
    {
        $lavel1 = category_level_2::findOrFail($id)->update([
            'Item_category_level_1_id' => $request->cmbLeve1,
            'category_level_2' => $request->txtCategorylevel2,
        ]);
        return response()->json($lavel1);
    }

    // category Level 2 Status update


    public function deletelevel2($id)
    {
        
        try {
            $level3 = category_level_2::find($id);
            if (is_null($level3->system) || $level3->system == 1) {
                return response()->json(['error' => 'Record has been Not Delete']);
            } else {
                $level3->delete();
                return response()->json(['success' => 'Record has been Delete']);
            }
        } catch (Exception $ex) {
            return response()->json($ex);
        }

    }
    public function catLevel2tStatus(Request $request, $id)
    {
        $level1 = category_level_2::findOrFail($id);
        $level1->is_active = $request->status;
        $level1->save();

        return response()->json(' status updated successfully');
    }





    //#################   Level 3 ##########
    public function loadCaegory3()
    {
        $data = category_level_2::orderBy('Item_category_level_2_id', 'ASC')->get();
        return response()->json($data);
    }
    public function categoryLevel3Data()
    {
        try {


            $query = "SELECT item_category_level_3s.*,item_category_level_2s.category_level_2 FROM item_category_level_3s
        LEFT JOIN item_category_level_2s ON item_category_level_3s.Item_category_level_2_id = item_category_level_2s.item_category_level_2_id
";

            $customerDetails = DB::select($query);


            /* $customerDetails = DB::table('item_category_level_3s')
        ->join('item_category_level_2s', 'item_category_level_3s.Item_category_level_2_id', '=', 'item_category_level_2s.Item_category_level_2_id')
        ->select('item_category_level_3s.*', 'item_category_level_2s.category_level_2')
        ->orderBy('item_category_level_3s.Item_category_level_3_id', 'DESC')
        ->distinct()
        ->get();*/

            $customerDetails = DB::select($query);

            return response()->json(['success' => 'Data loaded', 'data' => $customerDetails]);
        } catch (\Exception $ex) {
            // Log the error for debugging purposes
            Log::error($ex);

            return response()->json(['error' => 'An error occurred while loading data']);
        }
    }

    //...........save data.....
    public function saveCategoryLevel3(Request $request)
    {
        $validatedData = $request->validate([
            'txtCategorylevel3' => 'required',
            'cmbLeve2' => 'required',
        ]);
        try {

            $categoryLevel3 = new category_level_3();
            $categoryLevel3->category_level_3 = $request->get('txtCategorylevel3');
            $categoryLevel3->Item_category_level_2_id = $request->get('cmbLeve2');




            if ($categoryLevel3->save()) {

                return response()->json(['status' => true]);
            } else {
                Log::error('Error saving common setting: ' . print_r($categoryLevel3->getErrors(), true));
                return response()->json(['status' => false]);
            }
        } catch (Exception $ex) {
            return response()->json(['error' => $ex]);
        }
    }

    //.....lavel1 Edite.......

    public function categorylevel3Edite($id)
    {

        $data = category_level_3::find($id);
        return response()->json($data);
    }

    //...........leval1 update...
    public function Categorylevel3Update(Request $request, $id)
    {
        $lavel3 = category_level_3::findOrFail($id)->update([
            'Item_category_level_2_id' => $request->cmbLeve2,
            'category_level_3' => $request->txtCategorylevel3,
        ]);
        return response()->json($lavel3);
    }


    // category Level 3 Status update

    public function catLevel3tStatus(Request $request, $id)
    {
        $level1 = category_level_3::findOrFail($id);
        $level1->is_active = $request->status;
        $level1->save();

        return response()->json(' status updated successfully');
    }




    public function deletelevel3($id)
    {

        try {
            $level3 = category_level_3::find($id);
            if (is_null($level3->system) || $level3->system == 1) {
                return response()->json(['error' => 'Record has been Not Delete']);
            } else {
                $level3->delete();
                return response()->json(['success' => 'Record has been Delete']);
            }
        } catch (Exception $ex) {
            return response()->json($ex);
        }
    }



    ///.........Desgination..................................

    //..all disginstion.

    public function disginationData()
    {

        try {
            $customerDteails = employee_designation::all();
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



    //..add disginstion.

    public function saveDesgination(Request $request)
    {
        $validatedData = $request->validate([
            'txtDesgination' => 'required',

        ]);

        try {

            $designation = new employee_designation();
            $designation->employee_designation = $request->get('txtDesgination');


            if ($designation->save()) {

                return response()->json(['status' => true]);
            } else {
                Log::error('Error saving common setting: ' . print_r($designation->getErrors(), true));
                return response()->json(['status' => false]);
            }
        } catch (Exception $ex) {
            return response()->json(['error' => $ex]);
        }
    }

    //edite desgination

    public function desginationEdite($id)
    {
        $level1 = employee_designation::find($id);
        return response()->json($level1);
    }


    public function desginationtUpdate(Request $request, $id)
    {

        $lavel1 = employee_designation::findOrFail($id)->update([
            'employee_designation' => $request->txtDesgination,
        ]);
        return response()->json($lavel1);
    }

    //update desgination status

    public function updateDesginationStatus(Request $request, $id)
    {

        $level1 = employee_designation::findOrFail($id);
        $level1->is_active = $request->status;
        $level1->save();

        return response()->json(' status updated successfully');
    }


    // desgination Delete

    public function deletedesgination($id)
    {

        try {
            $level3 = employee_designation::find($id);
            if (is_null($level3->system) || $level3->system == 1) {
                return response()->json(['error' => 'Record has been Not Delete']);
            } else {
                $level3->delete();
                return response()->json(['success' => 'Record has been Delete']);
            }
        } catch (Exception $ex) {
            return response()->json($ex);
        }


    }


    ///###########.............Status ....................###############endregion



    ///.........Desgination..................................

    //..all disginstion.

    public function empStatusData()
    {
        try {
            $customerDteails = employee_Status::all();
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



    //..add disginstion.

    public function empSaveStatus(Request $request)
    {
        $validatedData = $request->validate([
            'txtStatus' => 'required',

        ]);
        try {

            $designation = new employee_Status();
            $designation->employee_status = $request->get('txtStatus');


            if ($designation->save()) {

                return response()->json(['status' => true]);
            } else {
                Log::error('Error saving common setting: ' . print_r($designation->getErrors(), true));
                return response()->json(['status' => false]);
            }
        } catch (Exception $ex) {
            return response()->json(['error' => $ex]);
        }
    }

    //edite desgination

    public function empStatusEdite($id)
    {
        $level1 = employee_Status::find($id);
        return response()->json($level1);
    }


    public function empStatusUpdate(Request $request, $id)
    {

        $lavel1 = employee_Status::findOrFail($id)->update([
            'employee_status' => $request->txtStatus,
        ]);
        return response()->json($lavel1);
    }

    //update desgination status

    public function updateempStatus(Request $request, $id)
    {

        $level1 = employee_Status::findOrFail($id);
        $level1->is_active = $request->status;
        $level1->save();

        return response()->json(' status updated successfully');
    }

    // desgination serch


    // employee status Delete

    public function deleteempStatus($id)
    {

        try {
            $level3 = employee_Status::find($id);
            if (is_null($level3->system) || $level3->system == 1) {
                return response()->json(['error' => 'Record has been Not Delete']);
            } else {
                $level3->delete();
                return response()->json(['success' => 'Record has been Delete']);
            }
        } catch (Exception $ex) {
            return response()->json($ex);
        }

        
    }

    //........vehicale Type........

    public function getVehicletype()
    {

        try {
            $customerDteails = vehicle_type::all();
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

    //...........save data.....
    public function saveVehicleType(Request $request)
    {



        try {

            $vehicletype = new vehicle_type();
            $vehicletype->vehicle_type = $request->get('txtVehicletype');


            if ($vehicletype->save()) {

                return response()->json(['status' => true]);
            } else {
                Log::error('Error saving common setting: ' . print_r($vehicletype->getErrors(), true));
                return response()->json(['status' => false]);
            }
        } catch (Exception $ex) {
            return response()->json(['error' => $ex]);
        }
    }

    //.... Edite.......

    public function vehicletypeEdite($id)
    {
        $level1 = vehicle_type::find($id);
        return response()->json($level1);
    }

    //.......... update...
    public function vehicleTypeUpdate(Request $request, $id)
    {
        $vehicle = vehicle_type::findOrFail($id)->update([
            'vehicle_type' => $request->txtVehicletype,
        ]);
        return response()->json($vehicle);
    }


    // .............Status update

    public function vehicletypeStatus(Request $request, $id)
    {
        $vehiclet = vehicle_type::findOrFail($id);
        $vehiclet->is_active = $request->status;
        $vehiclet->save();

        return response()->json(' status updated successfully');
    }

    public function deleteVehicle($id)
    {
 
        try {
            $level3 = vehicle_type::find($id);
            if (is_null($level3->system) || $level3->system == 1) {
                return response()->json(['error' => 'Record has been Not Delete']);
            } else {
                $level3->delete();
                return response()->json(['success' => 'Record has been Delete']);
            }
        } catch (Exception $ex) {
            return response()->json($ex);
        }

    }



    //....End Vehicle Type......

    public function category2()
    {
        $data = category_level_1::orderBy('item_category_level_1_id', 'ASC')->get();
        return response()->json($data);
    }


    public function category3()
    {
        $data = category_level_2::orderBy('Item_category_level_2_id', 'ASC')->get();
        return response()->json($data);
    }


    //............Delivery Type.........

    public function getdeliveryType()
    {

        try {
            $customerDteails = delivery_type::all();
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

    //...........save data.....
    public function addDeliveryType(Request $request)
    {
        try {
            $deliveryType = new delivery_type();
            $deliveryType->delivery_type_name = $request->input('txtDeliveryType');
            if ($deliveryType->save()) {
                return response()->json(['status' => true]);
            } else {
                return response()->json(['status' => false]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }
    //.... Edite.......

    public function deliveryTypeEdite($id)
    {
        $level1 = delivery_type::find($id);
        return response()->json($level1);
    }

    //.......... update...
    public function deliveryTypeUpdate(Request $request, $id)
    {


        $DeliveryType = delivery_type::find($id);
        $DeliveryType->delivery_type_name = $request->input('txtDeliveryType');
        $DeliveryType->update();
        return response()->json($DeliveryType);
    }


    // .............Status update

    public function deliveryypeStatus(Request $request, $id)
    {
        $vehiclet = delivery_type::findOrFail($id);
        $vehiclet->is_active = $request->status;
        $vehiclet->save();

        return response()->json(' status updated successfully');
    }

    public function deleteDeliveryType($id)
    {

        try {
            $level3 = delivery_type::find($id);
            if (is_null($level3->system) || $level3->system == 1) {
                return response()->json(['error' => 'Record has been Not Delete']);
            } else {
                $level3->delete();
                return response()->json(['success' => 'Record has been Delete']);
            }
        } catch (Exception $ex) {
            return response()->json($ex);
        }

       
    }





    //............Delivery Type.........

    public function gesalesRetornreson()
    {

        try {
            $salesRetornre = SalesReturnReson::all();
            if ($salesRetornre) {
                return response()->json((['success' => 'Data loaded', 'data' => $salesRetornre]));
            } else {
                return response()->json((['error' => 'Data is not loaded']));
            }
        } catch (Exception $ex) {
            if ($ex instanceof ValidationException) {
                return response()->json(["ValidationException" => ["id" => collect($ex->errors())->keys()[0], "message" => $ex->errors()[collect($ex->errors())->keys()[0]]]]);
            }
        }
    }

    //...........save data.....
    public function addsalesRetornreson(Request $request)
    {
        try {
            $salesRetornre = new SalesReturnReson();
            $salesRetornre->sales_return_resons = $request->input('txtsalesReturnNme');
            if ($salesRetornre->save()) {
                return response()->json(['status' => true]);
            } else {
                return response()->json(['status' => false]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }
    //.... Edite.......

    public function salesRetornResonEdite($id)
    {
        $level1 = SalesReturnReson::find($id);
        return response()->json($level1);
    }

    //.......... update...
    public function salesRetornResonUpdate(Request $request, $id)
    {


        $salesRetornre = SalesReturnReson::find($id);
        $salesRetornre->sales_return_resons = $request->input('txtsalesReturnNme');
        $salesRetornre->update();
        return response()->json($salesRetornre);
    }


    // .............Status update

    public function cbxSalesRetornStatus(Request $request, $id)
    {
        $salesRetornre = SalesReturnReson::findOrFail($id);
        $salesRetornre->is_active = $request->status;
        $salesRetornre->save();

        return response()->json(' status updated successfully');
    }

    public function deletesalesretornReson($id)
    {

        try {
            $level3 = SalesReturnReson::find($id);
            if (is_null($level3->system) || $level3->system == 1) {
                return response()->json(['error' => 'Record has been Not Delete']);
            } else {
                $level3->delete();
                return response()->json(['success' => 'Record has been Delete']);
            }
        } catch (Exception $ex) {
            return response()->json($ex);
        }


    }
}
