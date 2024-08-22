<?php

namespace Modules\Sd\Http\Controllers;

use Illuminate\Http\Request;

use Modules\Sd\Entities\customer_app_user;
use Modules\Sd\Entities\Customer;
use Illuminate\Support\Facades\Hash;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Modules\Sd\Entities\TownNonAdministrative;

class customerAppuseController extends Controller
{
    public function index()
    {
        $data = Customer::all();

        return view('customerAppuser',)->with('data', $data);
    }
    public function selectCustomer($id)
    {
        try {
            $result = Customer::find($id);
            return response()->json($result);
        } catch (Exception $ex) {
            return $ex;
        }
    }
public function townadmistrative($id){
    try {
        $result ="SELECT t.townName,t.town_id
        FROM customers 
        INNER JOIN town_non_administratives AS t ON t.town_id = customers.town
        WHERE customers.customer_id =$id";
$town=DB::select($result);
        return response()->json($town);
    } catch (Exception $ex) {
        return $ex;
    }
}




    /*  public function searchCustomers(Request $request)
    {
        $query = $request->get('query');

        $customers = Customer::where('customer_name', 'like', '%'.$query.'%')->get();

        $data = [];

        foreach ($customers as $customer) {
            $data[] = [
                'id' => $customer->id,
                'name' => $customer->name,

            ];
        }

        return response()->json($data);
    }
*/
    public function autoComplete(Request $request)
    {
        $query = $request->input('query');

        $customers = Customer::where('customer_name', 'LIKE', '%' . $query . '%')->get();


        return response()->json($customers);
    }





    public function customeruserApp()
    {
        try {
            /*$customerDetails = DB::table('customer_app_users')
            ->join('customers', 'customer_app_users.customer_id', '=', 'customers.customer_id')
            ->select('customer_app_users.customer_app_user_id', 'customers.customer_name', 'customers.primary_address', 'customer_app_users.email', 'customer_app_users.mobile', 'customer_app_users.password', 'customer_app_users.status_id')
            ->orderBy('customers.customer_id', 'DESC')
            ->distinct()
            ->get();*/

            $query = "SELECT customer_app_users.*,customers.*,B.branch_name FROM customer_app_users
            INNER JOIN customers ON customer_app_users.customer_id = customers.customer_id
            LEFT JOIN branches B ON customer_app_users.branch_id = B.branch_id ORDER BY customer_app_users.customer_app_user_id DESC";

            $customerDetails = DB::select($query);

            /* if ($customerDetails->isNotEmpty()) {
            return response()->json(['success' => 'Data loaded', 'data' => $customerDetails]);
        } else {
            return response()->json(['error' => 'Data is not loaded']);
        }*/
            return response()->json(['success' => 'Data loaded', 'data' => $customerDetails]);
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
    }


    public function savecustomerUserApp(Request $request)
    {

        try {
            $random_number = Hash::make(uniqid());

            $customerUserApp = new customer_app_user();
            $customerUserApp->customer_id = $request->get('cmbcustomerApp');
            $customerUserApp->customer_code = $request->get('cmbcustomercode');
            $customerUserApp->address = $request->get('cmbaddress');
            $customerUserApp->town_id = $request->get('cmbadTown');
            $customerUserApp->email = $request->get('txtEmailcustomer');
            $customerUserApp->mobile = $request->get('txtMobilphonecustomer');
            $customerUserApp->password = Hash::make($request->get('txtPasswordcustomer'));
            $customerUserApp->session_key = $random_number;
            $customerUserApp->branch_id = $request->get('branch_id');

            if ($customerUserApp->save()) {

                return response()->json(['status' => true]);
            } else {
                Log::error('Error saving common setting: ' . print_r($customerUserApp->getErrors(), true));
                return response()->json(['status' => false]);
            }
        } catch (Exception $ex) {
            return response()->json(['status' => false, 'error' => $ex]);
        }
    }


    public function customerEdit($id)
    {
        $data = customer_app_user::find($id);
        return response()->json($data);
    }

    public function customerAppUpdate(Request $request, $id)
    {
        $customer = customer_app_user::findOrFail($id);
        $session_key = Hash::make(uniqid());
        $customer->customer_id = $request->input('cmbcustomerApp');
        $customer->customer_code = $request->get('cmbcustomercode');
        $customer->address = $request->get('cmbaddress');
        $customer->town_id = $request->get('cmbadTown');
        $customer->email = $request->input('txtEmailcustomer');
        $customer->mobile = $request->input('txtMobilphonecustomer');
        $customer->session_key = $session_key;
        if ($request->input('txtPasswordcustomer')) {
            $customer->password = Hash::make($request->input('txtPasswordcustomer'));
        }
        $customer->branch_id = $request->get('branch_id');


        $customer->update();

        return response()->json($customer);
    }


    public function deletecustomerApp($id)
    {
        $level3 = customer_app_user::find($id);
        $level3->delete();
        return response()->json(['success' => 'Record has been Delete']);
    }


    public function customerAppsearch(Request $request)
    {
        $data = $users = DB::table('customer_app_users')
            ->join('customers', 'customer_app_users.customer_id', '=', 'customers.customer_id')
            ->select('customer_app_users.customer_app_user_id', 'customers.customer_name', 'customers.primary_address', 'customer_app_users.email', 'customer_app_users.mobile', 'customer_app_users.password', 'customer_app_users.status_id')
            ->orderBy('customers.customer_id', 'DESC')
            ->distinct()
            ->get();

        $output = "";
        $rowIndex = 0; // Initialize rowIndex to start from 0

        $customerApp = DB::table('customer_app_users')
            ->join('customers', 'customer_app_users.customer_id', '=', 'customers.customer_id')
            ->select('customer_app_users.customer_app_user_id', 'customers.customer_name', 'customers.primary_address', 'customer_app_users.email', 'customer_app_users.mobile', 'customer_app_users.password', 'customer_app_users.status_id')
            ->where('customer_app_users.customer_app_user_id', 'LIKE', '%' . $request->search . '%')
            ->orWhere('customer_app_users.email', 'LIKE', '%' . $request->search . '%')
            ->orWhere('customer_app_users.mobile', 'LIKE', '%' . $request->search . '%')
            ->orWhere('customers.customer_name', 'LIKE', '%' . $request->search . '%')
            ->orderBy('customers.customer_id', 'DESC')
            ->distinct()
            ->get();

        foreach ($customerApp as $customerApp) {
            $category2 = $data->where('customer_app_user_id', $customerApp->customer_app_user_id)->first();

            $status = $customerApp->status_id == 1 ? 'checked' : '';
            $rowClass = $rowIndex % 2 === 0 ? 'even-row' : 'odd-row';

            $output .= '
            <tr class="' . $rowClass . '">
                <td>' . $customerApp->customer_app_user_id . '</td>
                <td>' . $category2->customer_name . '</td>
                <td>' . $customerApp->email . '</td>
                <td>' . $customerApp->mobile . '</td>
                <td>
                    <a href="" type="button" class="btn btn-primary customerEdit" id="' . $customerApp->customer_app_user_id . '" data-bs-toggle="modal" data-bs-target="#modalCustomerApp">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                    </a>
                </td>
                <td>
                    <input type="button" class="btn btn-danger" name="switch_single" id="btncustomerApp" value="Delete" onclick="btnCustommerAppDelete(' . $customerApp->customer_app_user_id . ')">
                </td>
                <td>
                    <label class="form-check form-switch">
                        <input type="checkbox" class="form-check-input" name="switch_single" id="cbxCustomerApp" value="1" onclick="cbxCustomerappStatus(' . $customerApp->customer_app_user_id . ')" required ' . $status . '>
                    </label>
                </td>
            </tr>';

            $rowIndex++; // Increment rowIndex for each iteration
        }

        return response($output);
    }


    public function customerAppStatus(Request $request, $id)
    {
        $item_altenative = customer_app_user::findOrFail($id);
        $item_altenative->status_id = $request->status;
        $item_altenative->save();

        return response()->json(' status updated successfully');
    }

    public function customername()
    {
        $data = Customer::all();
        return response()->json($data);
    }


    //load branches
    public function loadBranches_cus_app(){
       $data = DB::select('SELECT branches.branch_id,branches.branch_name FROM branches');
       return response()->json($data);
    }
}
