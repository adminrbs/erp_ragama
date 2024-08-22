<?php

namespace Modules\Md\Http\Controllers;

use Illuminate\Routing\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Modules\Md\Entities\supplierGroup;
use Modules\Md\Entities\supply_group;

class Suply_groupController extends Controller
{

    //all data

    public function suplyGroupAllData(){

        try {
            $customerDteails = supply_group::all();
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

    //........Save......
    public function saveSuplyGroup(Request $request){

        $validatedData = $request->validate([
            'txtSupplygroup' => 'required',
        ]);

        try {

            $supplygroup= new supply_group();
            $supplygroup->supply_group = $request->get('txtSupplygroup');
            $supplygroup->created_by = Auth::user()->id;


            if ($supplygroup->save()) {

                return response()->json(['status' => true]);
            } else {
                Log::error('Error saving common setting: ' . print_r($supplygroup->getErrors(), true));
                return response()->json(['status' => false]);
            }
        } catch (Exception $ex) {
            return response()->json(['error' => $ex]);
        }
    }

    //edit

    public function suplyGroupEdite(Request $request,$id){
        $supplygroup = supply_group::find($id);
        
        //dd($supplygroup);
		return response()->json($supplygroup);
    }


    //........... update...
    public function supltGroupUpdate(Request $request,$id){

        $supplygroup = supply_group::find($id);
        $supplygroup->supply_group = $request->input('txtSupplygroup');
        $supplygroup->updated_by = Auth::user()->id;
        $supplygroup->update();
        return response()->json($supplygroup);
      
        /*$supplygroup = supplierGroup::findOrFail($id)->update([

            'supplier_group_name' => $request->txtSupplygroup,
        ]);
        return response()->json($supplygroup);*/

    }


    //Delete

    public function deleteSuplygroup($id){

        try {
            $level3 = supply_group::find($id);
           
                $level3->delete();
                return response()->json(['success' => 'Record has been Delete']);
          
        } catch (Exception $ex) {
            return response()->json($ex);
        }

       

    }

    //Status Save

    public function suplyGroupStatus(Request $request,$id){
        $supplygroup = supply_group::findOrFail($id);
        $supplygroup->status_id = $request->status;
        $supplygroup->save();

        return response()->json(' status updated successfully');
    }

    
    public function close(Request $request){
        return response()->json(['status' => 'success', 'message' => 'Request processed successfully']);
    }
}
