<?php

namespace Modules\Md\Http\Controllers;

use Modules\Md\Entities\Town;

use Illuminate\Routing\Controller;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Modules\Md\Entities\category_level_1;
use Modules\Md\Entities\category_level_2;
use Modules\Md\Entities\Customer_grade;
use Modules\Md\Entities\Customer_group;
use Modules\Md\Entities\CustomerPaymentMode;
use Modules\Md\Entities\delivery_type;
use Modules\Md\Entities\District;
use Modules\Md\Entities\PaymentTerm;
use Modules\Md\Entities\SupplierGroup;
use Modules\Md\Entities\supplierPaymentMethod;
use Modules\Sd\Entities\paymentTerm as EntitiesPaymentTerm;

class CommonsettingController extends Controller
{

    public function index(){

        $townDistrict = District::orderBy('district_id','asc')->get();
        $level1= category_level_1::all();
        $level2 = category_level_2::all();


        return view('common_setting',)->with('townDistrict',$townDistrict)->with('level2',$level2)->with('level1',$level1);
    }

    public function districtData(){

        try {
            $customerDteails = District::all();
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


    public function saveDistrict(Request $request) {

        $validatedData = $request->validate([
            'txtDistrict' => 'required',

        ]);
        try {

            $commonSetting = new District();
            $commonSetting->district_name = $request->get('txtDistrict');


            if ($commonSetting->save()) {

                return response()->json(['status' => true]);
            } else {
                Log::error('Error saving common setting: ' . print_r($commonSetting->getErrors(), true));
                return response()->json(['status' => false]);
            }
        } catch (Exception $ex) {
            return response()->json(['error' => $ex]);
        }
    }

    //......update distric......

    public function districtEdite($id){
        //$id = $request->id;
		$district = District::find($id);
		return response()->json($district);



    }

    public function districtUpdate(Request $request,$id){
        $district = District::findOrFail($id)->update([
        'district_name' => $request->txtDistrict,

    ]);
    return response()->json($district);
    }

        public function districtStatus(Request $request,$id){
            $district = District::findOrFail($id);
            $district->is_active = $request->status;
            $district->save();

            return response()->json(' status updated successfully');
        }



    public function save_town_status(Request $request)
    {


    }
    public function deleteDistrict($id){

        
    try {
        $level3 = District::find($id);
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
///#####################....Town......################################

public function loadDistrict(){
    $data = District::orderBy('district_id','ASC' )->get();
return response()->json( $data );
}

public function towndistrict(){
    $data = District::orderBy('district_id','ASC' )->get();
    return response()->json( $data );

}



public function twonAlldata(){


    try {
        /*$customerDetails = DB::table('towns')
        ->join('districts', 'towns.district_id', '=', 'districts.district_id')
        ->select('towns.town_id  as town_id', 'towns.town_name as town_name', 'districts.district_name as district_name','towns.is_active')
        ->orderBy('districts.district_id', 'DESC')
        ->distinct()
        ->get();*/
        $query = "SELECT towns.*,districts.district_name FROM
        towns INNER JOIN districts ON towns.district_id = districts.district_id WHERE towns.town_id != '1'";
        $customerDetails = DB::select($query);

        if (count($customerDetails) > 0) {
            return response()->json(['success' => 'Data loaded', 'data' => $customerDetails]);
        } else {
            return response()->json(['error' => 'Data is not loaded']);
        }
    } catch (Exception $ex) {
        if ($ex instanceof ValidationException) {
            return response()->json([
                'ValidationException' => [
                    'id' => collect($ex->errors())->keys()[0],
                    'message' => $ex->errors()[collect($ex->errors())->keys()[0]]
                ]
            ]);
        }
    }

    return response()->json($customerDetails);

}


    public function saveTown(Request $request) {

        $validatedData = $request->validate([
            'txtTown' => 'required',
            'cmbDistrict' => 'required',

        ]);
        try {

            $towndata = new Town();
            $towndata->town_name = $request->get('txtTown');
            $towndata->district_id = $request->get('cmbDistrict');


            if ($towndata->save()) {

                return response()->json(['status' => true]);
            } else {

                return response()->json(['status' => false]);
            }
        } catch (Exception $ex) {
            return response()->json(['error' => $ex]);
        }

    }



    public function townEdite($id){

		$town = Town::find($id);
		return response()->json($town);
    }

    public function townUpdate(Request $request,$id){
        $town = Town::findOrFail($id)->update([
            'district_id' => $request->cmbDistrict,
            'town_name' => $request->txtTown,

        ]);
        return response()->json($town);
    }



    /////////.....Status........




    public function townUpdateStatus(Request $request,$id){
        $town = Town::findOrFail($id);
        $town->is_active = $request->status;
        $town->save();

        return response()->json(' status updated successfully');


    }

    public function deleteTown($id){
     
    try {
        $level3 = Town::find($id);
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



///#####################....Group......################################


public function groupAlldata(){
    try {
        $customerDteails = Customer_group::all();
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


    public function saveGroup(Request $request) {
        $validatedData = $request->validate([
            'txtGroup' => 'required',


        ]);
        try {

            $towndata = new Customer_group();
            $towndata->group = $request->get('txtGroup');
            $towndata->credit_preriod = $request->get('txtPeriod');



            if ($towndata->save()) {

                return response()->json(['status' => true]);
            } else {

                return response()->json(['status' => false]);
            }
        } catch (Exception $ex) {
            return response()->json(['error' => $ex]);
        }

    }


    public function groupEdite($id){

		$group = Customer_group::find($id);
		return response()->json($group);
    }

    /* public function groupUpdate(Request $request,$id){
        $group = Customer_group::findOrFail($id)->update([
        'group' => $request->txtGroup,'credit_preriod'=>$request->credit_preriod
    ]);
    return response()->json($group);
    } */

    public function groupUpdate(Request $request, $id)
{
    // Find the Customer_group instance by ID
    $group = Customer_group::findOrFail($id);
    $group->group = $request->get('txtGroup');
    $group->credit_preriod = $request->get('credit_preriod');
    $group->update();
   
    // Return the updated model as JSON
    return response()->json($group);
}


 /////////.....Status........

    public function updateStatusGroup($customer_group_id)
        {

            $group = Customer_group::find($customer_group_id);
            if (!$group) {
                return response()->json(['status' => 'error', 'message' => 'District not found']);
            }

            $status = ($group->status == 1) ? true : false;
            return response()->json(['status' => $status]);
          }


    public function groupUpdateStatus(Request $request,$id){
        $group = Customer_group::findOrFail($id);
        $group->is_active = $request->status;
        $group->save();

        return response()->json('District status updated successfully');


    }

    public function deleteGroup($id){

        try {
            $level3 = Customer_group::find($id);
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



///#####################....Grade......################################


public function gradeAlldata(){

    try {
        $customerDteails = Customer_grade::all();
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


    public function savegrade(Request $request) {
        $validatedData = $request->validate([
            'txtgrade' => 'required',


        ]);
        try {

            $towndata = new Customer_grade();
            $towndata->grade = $request->get('txtgrade');



            if ($towndata->save()) {

                return response()->json(['status' => true]);
            } else {

                return response()->json(['status' => false]);
            }
        } catch (Exception $ex) {
            return response()->json(['error' => $ex]);
        }

    }



    public function gradeEdite($id){
		$grade = Customer_grade::find($id);
		return response()->json($grade);
    }


    public function gradeUpdate(Request $request,$id){
        $group = Customer_grade::findOrFail($id)->update([
        'grade' => $request->txtgrade,
    ]);
    return response()->json($group);
    }

 

    public function updateStatusGrade($customer_grade_id)
    {

        $grade = Customer_grade::find($customer_grade_id);
        if (!$grade) {
            return response()->json(['status' => 'error', 'message' => 'District not found']);
        }

        $status = ($grade->status == 1) ? true : false;
        return response()->json(['status' => $status]);
      }


public function gradeUpdateStatus(Request $request,$id){
    $grade = Customer_grade::findOrFail($id);
    $grade->is_active = $request->status;
    $grade->save();

    return response()->json(' status updated successfully');


}
public function deleteGrade($id){

    try {
        $level3 = Customer_grade::find($id);
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


//add supplier group
public function addSupplierGroup(Request $request){
    try{
        $supplier_group = new supplierGroup();
        $supplier_group->supplier_group_name = $request->get('suppliergroup');
        if($supplier_group->save()){
            return response()->json(['status' => true]);
        }else{
            return response()->json(['status' => false]);
        }

    }catch(Exception $ex){
        return $ex;
    }
}

//getting supplier group data to table
public function getSupplierGroupDetails(){
    try{
        $supplierGroups = supplierGroup::all();
        return response()->json($supplierGroups);
    }catch(Exception $ex){
        return $ex;
    }
}
public function supplierGroupEdite(Request $request,$id){
    $supplygroup = supplierGroup::find($id);
    return response()->json($supplygroup);
}
public function supplierGroupUpdate(Request $request,$id){

   
    try {
        $supplier_group = SupplierGroup::findOrFail($id);
        $supplier_group->supplier_group_name = $request->get('txtSupplierGroup');
    
        if ($supplier_group->update()) {
            return response()->json($supplier_group);
        } else {
            return response()->json($supplier_group);
        }
    } catch (Exception $ex) {
        return $ex; // This might not be suitable for a production environment
    }
    
    

}
//delete supplier grouop
public function supplierGroupStatus(Request $request,$id){
    $supplygroup = SupplierGroup::findOrFail($id);
    $supplygroup->is_active = $request->status;
    $supplygroup->save();

    return response()->json(' status updated successfully');
}

public function deleteSupplierGroup($id){
    

    try {
        $level3 = SupplierGroup::find($id);
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

//all data

public function getPaymentMethod(){

    try {
        $customerDteails =supplierPaymentMethod::all();
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
public function saveSupplierPayment(Request $request){

    $validatedData = $request->validate([
        'txtSupplierPaymentMethodNme' => 'required',
    ]);

    try {

        $supplierpayment= new supplierPaymentMethod();
        $supplierpayment->supplier_payment_method = $request->get('txtSupplierPaymentMethodNme');


        if ($supplierpayment->save()) {

            return response()->json(['status' => true]);
        } else {
            Log::error('Error saving common setting: ' . print_r($supplierpayment->getErrors(), true));
            return response()->json(['status' => false]);
        }
    } catch (Exception $ex) {
        return response()->json(['error' => $ex]);
    }
}

//edit

public function suplypaymentMethordEdite(Request $request,$id){
    $supplierpayment = supplierPaymentMethod::find($id);
    return response()->json($supplierpayment);
}


//........... update...
public function updateSuplyPementMethord(Request $request,$id){

    $supplierpayment = supplierPaymentMethod::findOrFail($id);
    $supplierpayment->supplier_payment_method = $request->input('txtSupplierPaymentMethodNme');
    

        $supplierpayment->update();
        return response()->json($supplierpayment);
 

}


//Delete

public function deletepayementMode($id){

    try {
        $level3 = supplierPaymentMethod::find($id);
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

//Status Save

public function cbxPaymentMethordStatus(Request $request,$id){
    $supplierpayment = supplierPaymentMethod::findOrFail($id);
    $supplierpayment->is_active = $request->status;
    $supplierpayment->save();

    return response()->json(' status updated successfully');
}


//////////////////////////////////////////////////////////



//all data

public function getCustomerPaymentMethod(){

    try {
        $customerDteails =CustomerPaymentMode::all();
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
public function saveCustomerPayment(Request $request){

    $validatedData = $request->validate([
        'txtcustomerPaymentMethodNme' => 'required',
    ]);

    try {

        $customerpayment= new CustomerPaymentMode();
        $customerpayment->customer_payment_method = $request->get('txtcustomerPaymentMethodNme');


        if ($customerpayment->save()) {

            return response()->json(['status' => true]);
        } else {
            Log::error('Error saving common setting: ' . print_r($customerpayment->getErrors(), true));
            return response()->json(['status' => false]);
        }
    } catch (Exception $ex) {
        return response()->json(['error' => $ex]);
    }
}

//edit

public function customerpaymentMethordEdite(Request $request,$id){
    $customerpayment = CustomerPaymentMode::find($id);
    return response()->json($customerpayment);
}


//........... update...
public function updateCustomerPementMethord(Request $request,$id){

    $customerpayment = CustomerPaymentMode::findOrFail($id);
    $customerpayment->customer_payment_method = $request->input('txtcustomerPaymentMethodNme');
    

        $customerpayment->update();
        return response()->json($customerpayment);
 

}


//Delete

public function deletecustomerpayementMode($id){

    
    try {
        $level3 = CustomerPaymentMode::find($id);
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

//Status Save

public function cbxCustomerPaymentMethordStatus(Request $request,$id){
    $customerpayment = CustomerPaymentMode::findOrFail($id);
    $customerpayment->is_active = $request->status;
    $customerpayment->save();

    return response()->json(' status updated successfully');
}





/////////////////////// payment Term//////////////////////////////////////


public function getPaymentTerm(){

    try {
        $customerDteails =paymentTerm::all();

        //dd($customerDteails);
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
public function savePaymentTerm(Request $request){

    $validatedData = $request->validate([
        'txtPaymentTerm' => 'required',
    ]);

    try {

        $supplierpayment= new paymentTerm();
        $supplierpayment->payment_term_name = $request->get('txtPaymentTerm');


        if ($supplierpayment->save()) {

            return response()->json(['status' => true]);
        } else {
            Log::error('Error saving common setting: ' . print_r($supplierpayment->getErrors(), true));
            return response()->json(['status' => false]);
        }
    } catch (Exception $ex) {
        return response()->json(['error' => $ex]);
    }
}

//edit

public function suplyPaymentTermEdite(Request $request,$id){
    $supplierpayment = paymentTerm::find($id);
    return response()->json($supplierpayment);
}


//........... update...
public function updatePaymentTerm(Request $request,$id){

    
    $updateterm = paymentTerm::find($id);
    $updateterm->payment_term_name = $request->input('txtPaymentTermName');
    
    $updateterm->update();
    return response()->json($updateterm);
}


//Delete

public function deletepayementterm($id){

    try {
        $level3 = paymentTerm::find($id);
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

//Status Save

public function cbxPaymentTermStatus(Request $request,$id){
    $term  = paymentTerm::findOrFail($id);
    $term ->is_active = $request->status;
    $term ->save();

    return response()->json(' status updated successfully');
}
   



}
