<?php

namespace Modules\Md\Http\Controllers;

use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Md\Entities\vehicle;
use Modules\Md\Entities\vehicle_type;

class VehicleController extends Controller
{
    public function vehicaltypename(){
        $data = vehicle_type::all();
        return response()->json($data);
    }



    public function vehicaleAlldata()
{
    try {
            $query = "SELECT vehicles.*,branches.branch_name,vehicle_types.vehicle_type
            FROM vehicles
            INNER JOIN vehicle_types ON vehicles.vehicle_type_id = vehicle_types.vehicle_type_id
            LEFT JOIN branches ON branches.branch_id = vehicles.branch_id
            ORDER BY vehicles.licence_expire_date ASC";
            $vehicle = DB::select($query);

//dd($vehicle);
        return response()->json(['success' => 'Data loaded', 'data' => $vehicle]);
    } catch (Exception $ex) {
        if ($ex instanceof ValidationException) {
            return response()->json([

            ]);
        }
    }


}


    public function savevehicle(Request $request){

        try {


            $vehicle= new vehicle();
            $vehicle->vehicle_no= $request->get('txtvehicleNo');
            $vehicle->vehicle_name= $request->get('txtVehicleName');
            $vehicle->description = $request->get('txtDescription');
            $vehicle->vehicle_type_id = $request->get('cmbVehicleType');
            $vehicle->licence_expire_date = $request->get('txtLicenceExpire');
            $vehicle->insurance_expire_date = $request->get('txtInsuranceExpire');
            $vehicle->remarks = $request->get('txtRemarks');
            $vehicle->branch_id = $request->get('cmbbranch');
            

         

           if ($vehicle->save()) {

                return response()->json(['status' => true]);
            } else {
                Log::error('Error saving common setting: ' . print_r($vehicle->getErrors(), true));
                return response()->json(['status' => false]);
            }

        } catch (Exception $ex) {
            return response()->json(['status' => false,'error' => $ex]);
        }
    }


public function vehicaleEdit($id){
    $data = vehicle::find($id);
    return response()->json($data);
}

public function vehicaleUpdate(Request $request,$id){
    $customer = vehicle::findOrFail($id);
    $customer->vehicle_no = $request->input('txtvehicleNo');
    $customer->vehicle_name = $request->input('txtVehicleName');
    $customer->description = $request->input('txtDescription');
    $customer->vehicle_type_id = $request->input('cmbVehicleType');
    $customer->licence_expire_date = $request->input('txtLicenceExpire');
    $customer->insurance_expire_date = $request->input('txtInsuranceExpire');
    $customer->remarks = $request->input('txtRemarks');
    $customer->branch_id = $request->get('cmbbranch');




        $customer->update();
        return response()->json($customer);
}



    public function vehicleStatus(Request $request,$id){
        $vehicle = vehicle::findOrFail($id);
        $vehicle->status_id = $request->status;
        $vehicle->save();

        return response()->json(' status updated successfully');
    }

    public function deleteVehicale($id){
        $vehicle = vehicle::find($id);
            $vehicle->delete();
        return response()->json(['success'=>'Record has been Delete']);
    }



}
