<?php

namespace Modules\Md\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Md\Entities\branch;
use Modules\Md\Entities\Customer;
use Modules\Md\Entities\CustomerSupplierCode;

class SupplierCustomerCodeController extends Controller
{

    public function loadBranches()
    {
        try {
            $branches = branch::all();
            return response()->json(["status" => true, "data" => $branches]);
        } catch (Exception $ex) {
            return response()->json(["status" => false, "error" => $ex]);
        }
    }


    public function loadCustomers()
    {
        try {
            $customers = Customer::all();
            return response()->json(["status" => true, "data" => $customers]);
        } catch (Exception $ex) {
            return response()->json(["status" => false, "error" => $ex]);
        }
    }


    public function isExistingRecord($customer_id, $branch_id)
    {
        try {
            $data = CustomerSupplierCode::where([['customer_id', '=', $customer_id], ['branch_id', '=', $branch_id]])->first();
            $code = "";
            if ($data) {
                $code = $data->supplier_customer_code;
            }
            return response()->json(["status" => true, "data" => $code]);
        } catch (Exception $ex) {
            return response()->json(["status" => false, "error" => $ex]);
        }
    }


    public function save(Request $request)
    {
        try {
            $customer_id = $request->get('customer_id');
            $customer_code = $request->get('customer_code');
            $branch_id = $request->get('branch_id');

            $customer_supplier_code = new CustomerSupplierCode();
            $customer_supplier_code->customer_id = $customer_id;
            $customer_supplier_code->supplier_customer_code = $customer_code;
            $customer_supplier_code->branch_id = $branch_id;
            $status = $customer_supplier_code->save();

            return response()->json(["status" => $status]);
        } catch (Exception $ex) {
            return response()->json(["status" => false, "error" => $ex]);
        }
    }



    public function viewAllData()
    {
        try {
            $data = DB::select("SELECT customers.customer_id,
            branches.branch_id, 
            customers.customer_name,
            customer_supplier_code.supplier_customer_code,
            branches.branch_name
            FROM customer_supplier_code 
            INNER JOIN customers ON customer_supplier_code.customer_id = customers.customer_id
            INNER JOIN branches ON customer_supplier_code.branch_id = branches.branch_id");
            return response()->json(["status" => true, "data" => $data]);
        } catch (Exception $ex) {
            return response()->json(["status" => false, "error" => $ex]);
        }
    }



    public function getSupplierCustomerData($customer_id, $branch_id)
    {
        try {
            $data = CustomerSupplierCode::where([['customer_id', '=', $customer_id], ['branch_id', '=', $branch_id]])->first();
            $data->branch_id = "";
            $data->branch_name = "";
            $branch = branch::find($branch_id);
            if ($branch) {
                $data->branch_id = $branch->branch_id;
                $data->branch_name = $branch->branch_name;
            }
            return response()->json(["status" => true, "data" => $data]);
        } catch (Exception $ex) {
            return response()->json(["status" => false, "error" => $ex]);
        }
    }



    public function update(Request $request)
    {
        try {
            $customer_id = $request->get('customer_id');
            $customer_code = $request->get('customer_code');
            $branch_id = $request->get('branch_id');

            $customer_supplier_code = CustomerSupplierCode::find($customer_id);
            if ($customer_supplier_code) {
                $customer_supplier_code->supplier_customer_code = $customer_code;
                $customer_supplier_code->branch_id = $branch_id;
                $status = $customer_supplier_code->update();
            }

            return response()->json(["status" => $status]);
        } catch (Exception $ex) {
            return response()->json(["status" => false, "error" => $ex]);
        }
    }

    public function deleteSupplierCustomerCode($customer_id, $branch_id)
    {
        try {

            $customer_supplier_code = CustomerSupplierCode::find($customer_id);
            if ($customer_supplier_code) {
                $status = $customer_supplier_code->delete();
            }

            return response()->json(["status" => $status]);
        } catch (Exception $ex) {
            return response()->json(["status" => false, "error" => $ex]);
        }
    }
}
