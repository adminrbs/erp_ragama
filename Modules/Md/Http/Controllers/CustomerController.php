<?php

namespace Modules\Md\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Models\delivery_points;
use Illuminate\Support\Facades\Storage;
use Modules\Md\Entities\CustommerAttachment;
use Modules\Md\Entities\Customer_group;
use Modules\Md\Entities\Customer_grade;
use Modules\Md\Entities\customer_contact;
use Modules\Md\Entities\District;
use Modules\Md\Entities\Town;
use Modules\Md\Entities\Customer;
use Modules\Md\Entities\customer_delivery_points;
use Exception;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Modules\Md\Entities\paymentTerm;

class CustomerController extends Controller
{
    //
    public function saveCustomer(Request $request)
    {

        try {
            $request->validate([
                /* 'txtCustomerCode' => 'required', */
                'txtName' => 'required'


            ]);

            $customer = new Customer();
            $customer->customer_code = $request->input('txtCustomerCode');
            $customer->customer_name = $request->input('txtName');
            $customer->primary_address = $request->input('txtAddress');
            $customer->primary_mobile_number = $request->input('txtMobile');
            $customer->primary_fixed_number = $request->input('txtFixed');
            $customer->primary_email = $request->input('txtEMail');


            $customer->disctrict_id = $request->input('cmbDistrict');
            $customer->town_id = $request->input('cmbTown');

            $customer->google_map_link = $request->input('txtGooglemaplink');


            $customer->customer_group_id = $request->input('cmbCustomergroup');
            $customer->customer_grade_id = $request->input('cmbCustomergrade');

            $customer->deliver_primary_address = $request->input('chkDelivertoprimaryaddess');
            $customer->credit_amount_alert_limit = $request->input('txtAlertcreaditamountlimit');
            $customer->credit_amount_hold_limit = $request->input('txtHoldcreditamountlimit');
            $customer->credit_period_alert_limit = $request->input('txtAlertcreditperiodlimit');
            $customer->credit_period_hold_limit = $request->input('txtHoldcreditperiodlimit');
            $customer->pd_cheque_limit = $request->input('txtPDchequeamountlimit');
            $customer->pd_cheque_max_period = $request->input('txtMaximumPdchequeperiod');
            $customer->credit_control_type = $request->input('cmbCreditcontrolbytype');

            $customer->free_offer_allowed = $request->input('chkFreeofferAllowed');
            $customer->promotion_allowed = $request->input('chkPromotionAllowed');

            $customer->sms_notification = $request->input('chkSMSnotification');
            $customer->whatapp_notification = $request->input('chkWhatsAppnofification');
            $customer->email_notification = $request->input('chkEmailnotification');

            $customer->license_no = $request->input('txtLicense');
            $customer->customer_status = $request->input('cmbCustomerStatus');
            $customer->credit_allowed = $request->input('chkCreditAllowed');
            $customer->pd_cheque_allowed = $request->input('chkPDchequeAllowed');
            $customer->note = $request->input('txtnote');
            $customer->town = $request->input('cmbTown_onAdmin');
            $customer->route_id = $request->input('cmbDeliveryRoutes');
            $customer->payment_term_id = $request->input('cmbPaymentTerm');
            $customer->marketing_route_id = $request->input('cmbMarketingRoutes');
            $customer->created_by = Auth::user()->id;
            $status = $customer->save();
            $primaryKey = $customer->customer_id;
            return response()->json(["status" => $status, "primaryKey" => $primaryKey]); // return the primary key in the response






        } catch (Exception $ex) {

            if ($ex instanceof ValidationException) {
                return response()->json(["ValidationException" => ["id" => collect($ex->errors())->keys()[0], "message" => $ex->errors()[collect($ex->errors())->keys()[0]]]]);
            }
            return response()->json(["error" => $ex]);
        }
    }


    public function addContactDetails(Request $request, $id)
    {

        try {
            $lastCustomerID = $id;
            $data = $request->get('contact');
            if (count($data) > 0) {
                foreach ($data as $contact) {
                    $c_data = json_decode($contact);
                    DB::table('customer_contacts')->insert([
                        'customer_id' => $lastCustomerID,
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

    public function addcustomerDeliveryPoints(Request $request, $id)
    {
        try {
            $lastCustomerID = $id;
            $data = $request->get('deliverPoints');
            foreach ($data as $deliveryPoint) {
                $decodedData = json_decode($deliveryPoint);
                DB::table('customer_delivery_points')->insert([
                    'customer_id' =>  $lastCustomerID,
                    'destination' => $decodedData->destination,
                    'address' => $decodedData->address,
                    'mobile' => $decodedData->mobile,
                    'fixed' => $decodedData->fixed,
                    'instruction' => $decodedData->instruction,
                    'google_map_link' => $decodedData->google_map_link

                ]);
            }
            return  response()->json("Success");
        } catch (Exception $ex) {
        }
    }


    //districts getting function
    public function getDistrict(Request $request)
    {
        try {

            $districts = District::all();
            return response()->json($districts);
        } catch (Exception $ex) {
            if ($ex instanceof ValidationException) {
                return response()->json(["ValidationException" => ["id" => collect($ex->errors())->keys()[0], "message" => $ex->errors()[collect($ex->errors())->keys()[0]]]]);
            }
        }
    }


    //towns getting function

    public function getTown($id)
    {
        try {
            $town = DB::select("SELECT *FROM towns WHERE towns.district_id = '" . $id . "' OR towns.town_id = '1'");
            return response()->json($town);
        } catch (Exception $ex) {
            if ($ex instanceof ValidationException) {
                return response()->json(["ValidationException" => ["id" => collect($ex->errors())->keys()[0], "message" => $ex->errors()[collect($ex->errors())->keys()[0]]]]);
            }
        }
    }

    //getting customer group
    public function getCustomerGroup(Request $request)
    {
        try {
            $customerGroup = Customer_group::select('customer_group_id', 'group')->get();
            return response()->json($customerGroup);
        } catch (Exception $ex) {
            if ($ex instanceof ValidationException) {
                return response()->json(["ValidationException" => ["id" => collect($ex->errors())->keys()[0], "message" => $ex->errors()[collect($ex->errors())->keys()[0]]]]);
            }
        }
    }

    //getting cutomer grade
    public function getCustomerGrade(Request $request)
    {
        try {
            $customerGrade = Customer_grade::select('customer_grade_id', 'grade')->get();
            return response()->json($customerGrade);
        } catch (Exception $ex) {
            if ($ex instanceof ValidationException) {
                return response()->json(["ValidationException" => ["id" => collect($ex->errors())->keys()[0], "message" => $ex->errors()[collect($ex->errors())->keys()[0]]]]);
            }
        }
    }

    //getting customer details to the table
    public function getCsutomerDetails()
    {
        try {
            $query = 'SELECT
            customer_id,
            customer_code,
            customer_name,
            primary_address,
            primary_mobile_number,
            IF(customers.customer_status = 1, "Active",
              IF(customers.customer_status = 2, "Suspend",
                IF(customers.customer_status = 3, "Black List", ""))) AS customer_status,
            customer_groups.group,
            town_non_administratives.townName,
            routes.route_name
          FROM
            customers
            LEFT JOIN customer_groups ON customers.customer_group_id = customer_groups.customer_group_id
            LEFT JOIN town_non_administratives ON customers.town = town_non_administratives.town_id
            LEFT JOIN routes ON customers.route_id = routes.route_id';
             $customerDteails = DB::select($query);
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


    public function updateCustomer(Request $request, $id)
    {
        try {
            $customer = Customer::find($id);
            $customer->customer_code = $request->input('txtCustomerCode');
            $customer->customer_name = $request->input('txtName');
            $customer->primary_address = $request->input('txtAddress');

            $customer->primary_mobile_number = $request->input('txtMobile');
            $customer->primary_fixed_number = $request->input('txtFixed');
            $customer->primary_email = $request->input('txtEMail');

            $customer->disctrict_id = $request->input('cmbDistrict');
            $customer->town_id = $request->input('cmbTown');
            $customer->google_map_link = $request->input('txtGooglemaplink');

            $customer->customer_group_id = $request->input('cmbCustomergroup');
            $customer->customer_grade_id = $request->input('cmbCustomergrade');
            $customer->deliver_primary_address = $request->input('chkDelivertoprimaryaddess');

            $customer->credit_amount_alert_limit = $request->input('txtAlertcreaditamountlimit');
            $customer->credit_amount_hold_limit = $request->input('txtHoldcreditamountlimit');
            $customer->credit_period_alert_limit = $request->input('txtAlertcreditperiodlimit');

            $customer->credit_period_hold_limit = $request->input('txtHoldcreditperiodlimit');
            $customer->pd_cheque_limit = $request->input('txtPDchequeamountlimit');
            $customer->pd_cheque_max_period = $request->input('txtMaximumPdchequeperiod');

            $customer->credit_control_type = $request->input('cmbCreditcontrolbytype');

            $customer->free_offer_allowed = $request->input('chkFreeofferAllowed');
            $customer->promotion_allowed = $request->input('chkPromotionAllowed');

            $customer->sms_notification = $request->input('chkSMSnotification');
            $customer->whatapp_notification = $request->input('chkWhatsAppnofification');

            $customer->email_notification = $request->input('chkEmailnotification');
            $customer->license_no = $request->input('txtLicense');
            $customer->customer_status = $request->input('cmbCustomerStatus');
            $customer->credit_allowed = $request->input('chkCreditAllowed');
            $customer->pd_cheque_allowed = $request->input('chkPDchequeAllowed');
            $customer->note = $request->input('txtnote');
            $customer->town = $request->input('cmbTown_onAdmin');
            $customer->route_id = $request->input('cmbDeliveryRoutes');
            $customer->payment_term_id = $request->input('cmbPaymentTerm');
            $customer->marketing_route_id = $request->input('cmbMarketingRoutes');
            $customer->updated_by = Auth::user()->id;
            if ($customer->Update()) {
                return response()->json(["status" => true]);
            }
            return response()->json(["status" => false]);
        } catch (Exception $ex) {
            if ($ex instanceof ValidationException) {
                return response()->json(["ValidationException" => ["id" => collect($ex->errors())->keys()[0], "message" => $ex->errors()[collect($ex->errors())->keys()[0]]]]);
            }
        }
    }




    public function getEachCustomer($id)
    {
        try {
            $customerData = Customer::find($id);


            /*  return response()->json((['success' => 'Data loaded', 'data' => $customerData])); */

            if ($customerData) {
                return response()->json((['success' => 'Data loaded', 'data' => $customerData]));
            } else {
                return response()->json((['error' => 'Data is not loaded']));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function getEachCustomerContact($id)
    {
        $contactDetails = customer_contact::where('customer_id', $id)->get();
        return response()->json($contactDetails);
    }

    public function getEachDeliveryPoint($id)
    {
        $deliveryPoints = customer_delivery_points::where('customer_id', $id)->get();
        return response()->json($deliveryPoints);
    }


    public function deleteCustomer($id)
    {
        $customer = Customer::find($id);
        if ($customer->delete()) {
            return response()->json((['message' => 'Deleted']));
        } else {
            return response()->json((['message' => 'Error']));
        }
    }
    public function loadNames() //auto suggestion
    {
        $data = DB::table('customers')->select('customer_name')->get();
        return response()->json($data);
    }

    public function deleteDeliveryPoint($id)
    {
        try {
            $deletedRows =  DB::table('customer_delivery_points')->where('customer_id', $id)->delete();
            if ($deletedRows > 0) {
                return response()->json(["status" => true, "message" => "Delivery point deleted successfully"]);
            } else {
                return response()->json(["status" => false, "message" => "No matching data found to delete"]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function deleteCustomerContact($id)
    {
        try {
            $deletedRows =  DB::table('customer_contacts')->where('customer_id', $id)->delete();
            if ($deletedRows > 0) {
                return response()->json(["status" => true, "message" => "Contact deleted successfully"]);
            } else {
                return response()->json(["status" => false, "message" => "No matching contact found to delete"]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function uploadFile($file, $id)
    {
        /*  $file = $request->file('file');
        $path = $file->store('public/uploads');
        return response()->json(['path' => Storage::url($path)]);

 */
        if ($file) {
            $file_name = $file->getClientOriginalName();
            $filename = url('/') . '/attachment/' . uniqid() . '_' . time() . '.' . str_replace(' ', '_', $file_name);
            $filename = str_replace(' ', '', str_replace('\'', '', $filename));
            $file->move(public_path('attachment'), $filename);

            $attachment = new CustommerAttachment();
            $attachment->customer_id = $id;
            $attachment->path = $filename;
            $attachment->save();
        }
    }


    //load payment terms
    public function loadPamentTerm()
    {
        try {
            $paymentTerms = paymentTerm::all();
            if ($paymentTerms) {
                return response()->json($paymentTerms);
            } else {
                return response()->json(['status' => false]);
            }
        } catch (Exception $ex) {
        }
    }
}
