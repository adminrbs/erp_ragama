<?php

namespace Modules\Md\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Md\Entities\supplier;
use Modules\Md\Entities\supplierContact;
use Modules\Md\Entities\supply_group;
use Modules\Md\Entities\SupplierGroup;
use WeakMap;

class SupplierController extends Controller
{
  

    //add suppliers
     public function addSupplier(Request $request)
     {
        try{
            $request->validate([
                'txtName' => 'required',
                    
            ]);
            $supplier = new supplier();
            $supplier->supplier_code = $request->input('txtSupplierCode');
            $supplier->supplier_name = $request->input('txtName');
            $supplier->primary_address = $request->input('txtAddress');
            $supplier->primary_mobile_number = $request->input('txtMobile');
            $supplier->primary_fixed_number = $request->input('txtFixed');
            $supplier->primary_email = $request->input('txtEMail');
            $supplier->license_no = $request->input('txtLicense');
            $supplier->google_map_link = $request->input('txtGooglemaplink');
            $supplier->supplier_group_id = $request->input('cmbSupplierGroup');
            $supplier->supply_group_id = $request->input('cmbSupplyGroup');
            $supplier->supplier_status = $request->input('cmbSupplierStatus');

            $supplier->supplier_product_code = $request->input('chkPObySuppliersCode');

            $supplier->credit_amount_alert_limit = $request->input('txtAlertcreditAmountLimit');
            $supplier->credit_amount_hold_limit = $request->input('txtHoldcreditamountlimit');
            $supplier->credit_period_alert_limit = $request->input('txtAlertcreditperiodlimit');
            $supplier->credit_period_hold_limit = $request->input('txtHoldcreditperiodlimit');
            $supplier->pd_cheque_limit = $request->input('txtPDchequeamountlimit');
            $supplier->pd_cheque_max_period = $request->input('txtMaximumPdchequeperiod');
            
            $supplier->sms_notification = $request->input('chkSMSnotification');
            $supplier->whatapp_notification = $request->input('chkWhatsAppnofification');
            $supplier->email_notification = $request->input('chkEmailnotification');

          
            $supplier->supplier_status = $request->input('cmbSupplierStatus');
            $supplier->credit_allowed = $request->input('chkCreditAllowed');
            $supplier->pd_cheque_allowed = $request->input('chkPDchequeAllowed');
            $supplier->note = $request->input('txtnote');

            
             $status = $supplier->save();
            $primaryKey = $supplier->supplier_id; 
                return response()->json(["status" => $status, "primaryKey" => $primaryKey]);


        }catch(Exception $ex){
            return $ex;
        }
 
       
     }
     //update customer
     public function updateSupplier(Request $request, $id)
     {
        try{
            $request->validate([
                'txtName' => 'required'    
            ]);
            $supplier = supplier::find($id);
            $supplier->supplier_code = $request->input('txtSupplierCode');
            $supplier->supplier_name = $request->input('txtName');
            $supplier->primary_address = $request->input('txtAddress');
            $supplier->primary_mobile_number = $request->input('txtMobile');
            $supplier->primary_fixed_number = $request->input('txtFixed');
            $supplier->primary_email = $request->input('txtEMail');
            $supplier->license_no = $request->input('txtLicense');
            $supplier->google_map_link = $request->input('txtGooglemaplink');
            $supplier->supplier_group_id = $request->input('cmbSupplierGroup');
            $supplier->supply_group_id = $request->input('cmbSupplyGroup');
            $supplier->supplier_status=$request->input('cmbSupplierStatus');

            $supplier->supplier_product_code = $request->input('chkPObySuppliersCode');

            $supplier->credit_amount_alert_limit = $request->input('txtAlertcreditAmountLimit');
            $supplier->credit_amount_hold_limit = $request->input('txtHoldcreditamountlimit');
            $supplier->credit_period_alert_limit = $request->input('txtAlertcreditperiodlimit');
            $supplier->credit_period_hold_limit = $request->input('txtHoldcreditperiodlimit');
            $supplier->pd_cheque_limit = $request->input('txtPDchequeamountlimit');
            $supplier->pd_cheque_max_period = $request->input('txtMaximumPdchequeperiod');
            
            $supplier->sms_notification = $request->input('chkSMSnotification');
            $supplier->whatapp_notification = $request->input('chkWhatsAppnofification');
            $supplier->email_notification = $request->input('chkEmailnotification');

          
            $supplier->supplier_status = $request->input('cmbSupplierStatus');
            $supplier->credit_allowed = $request->input('chkCreditAllowed');
            $supplier->pd_cheque_allowed = $request->input('chkPDchequeAllowed');
            $supplier->note = $request->input('txtnote');

            
             $status = $supplier->update();
            
            return response()->json(["status" => $status]);


        }catch(Exception $ex){
            return $ex;
        }
 
       
     }

     //add contact details
     public function addSupplierContact(Request $request, $id){
        
        try {
            $lastSupplierID = $id;
            $data = $request->get('contact');
            if(count($data)> 0 ){
             foreach ($data as $contact) {
                $c_data = json_decode($contact);
                DB::table('supplier_contacts')->insert([
                    'supplier_id' => $lastSupplierID,
                    'contact_person' => $c_data->name,
                    'designation' => $c_data->designation,
                    'mobile' => $c_data->mobile,
                    'fixed' => $c_data->fixed,
                    'email' => $c_data->email   

                ]);
            }
        } 

            return response()->json($data);
        } catch (Exception $ex) {
            /*if ($ex instanceof ValidationException) {
                return response()->json(["ValidationException" => ["id" => collect($ex->errors())->keys()[0], "message" => $ex->errors()[collect($ex->errors())->keys()[0]]]]);
            }*/
            return response()->json(["error" => $ex]);
        }
     }


     //get supply group to combo box
     public function getSupplyGroup(){
        try{
            $suplyGroupDetails = supply_group::all();
            return response()->json($suplyGroupDetails);
        }catch(Exception $ex){
            Return $ex;
        }
     }

     public function getSupplierGroup(){
        try{
            $suplierGroupDetails = supplierGroup::all();
            return response()->json($suplierGroupDetails);

        }catch(Exception $ex){
            return $ex;
        }
     }

     //get supplier details to the table
     public function getSupplierDetails(){
        try{
            $query = 'SELECT
            supplier_id,
            supplier_code,
            supplier_name,
            primary_address,
            primary_mobile_number,
            IF(suppliers.supplier_status = 1, "Active",
              IF(suppliers.supplier_status = 2, "Suspend",
                IF(suppliers.supplier_status = 3, "Black List", ""))) AS supplier_status,
            supplier_groups.supplier_group_name,
            supply_groups.supply_group
          FROM
            suppliers
          INNER JOIN supplier_groups ON suppliers.supplier_group_id = supplier_groups.supplier_group_id
          INNER JOIN supply_groups ON suppliers.supply_group_id = supply_groups.supply_group_id';

          $result = DB::select($query);
          if ($result) {
            return response()->json((['success' => 'Data loaded', 'data' => $result]));
        } else {
            return response()->json((['error' => 'Data is not loaded']));
        } 
        }catch(Exception $ex){

        }

     }

     //get supplier contact
     public function getEachSupplierContact($id){
        try{
            $contactDetails = supplierContact::where('supplier_id', $id)->get();
            return response()->json($contactDetails);

        }catch (Exception $ex){
            return $ex;
        }
     }
     
     
     //get each supplier to update
     public function getEachSupplier($id){
        try{
            $supplierData = supplier::find($id);
            if ($supplierData) {
                return response()->json((['success' => 'Data loaded', 'data' => $supplierData]));
            } else {
                return response()->json((['error' => 'Data is not loaded']));
            }

        }catch(Exception $ex){
            return $ex;
        }
     }

     
     public function deleteSupplierContact($id){
        try{
            $deletingContact = DB::table('supplier_contacts')->where('supplier_id', $id)->delete();
            if ($deletingContact > 0) {
                return response()->json(["status" => true, "message" => "Contact deleted successfully"]);
            } else {
                return response()->json(["status" => false, "message" => "No matching contact found to delete"]);
            }

        }catch(Exception $ex){
            return $ex;
        }
     }

     public function deleteSupplier($id){
        try{
            $deletingSupplier = supplier::find($id);
            if($deletingSupplier->delete()){
                
                $deletingContact = DB::table('supplier_contacts')->where('supplier_id', $id)->delete();
                
            return response()->json((['message' => 'Deleted']));
            }
        }catch(Exception $ex){
            return $ex;
        }
     }


     public function loadSupplierNames(Request $request){
        try{
            $data = $request->input('data');
            $itemData = supplier::where('supplier_name','LIKE','%'.$data.'%')->take(20)->get();
            return response()->json($itemData);

        }catch(Exception $ex){
            return $ex;
        }
     }

}
