<?php

namespace Modules\Sd\Http\Controllers;

use App\Http\Controllers\IntenelNumberController;
use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Sd\Entities\delivery_type;
use Modules\Sd\Entities\item;
use Modules\Sd\Entities\location;
use Modules\Sd\Entities\paymentTerm;
use Modules\Sd\Entities\sales_oder_item;
use Modules\Sd\Entities\sales_order;
use Modules\Sd\Entities\sales_order_draft;
use Modules\Sd\Entities\sales_order_item_draft;

class salesOrderController extends Controller
{
    public function getSalesOrderDetails(Request $request)
    {
        try {
            $pageNumber = ($request->start / $request->length) + 1;
            $pageLength = $request->length;
            $skip = ($pageNumber - 1) * $pageLength;
            $searchValue = request('search.value');
            $query = DB::table('sales_orders')
    ->select(
        'sales_orders.sales_order_Id',
        'sales_orders.order_date_time',
        'sales_orders.external_number',
        'sales_orders.expected_date_time',
        'branches.branch_name',
        DB::raw("FORMAT(total_amount, 2) as total_amount"),
        DB::raw('IF(sales_orders.order_type = 0,"ERP", IF(sales_orders.order_type = 1,"Customer App", IF(sales_orders.order_type = 2,"Customer App", IF(sales_orders.order_type = 3,"SFA", IF(sales_orders.order_type = 4,"SFA Web App", IF(sales_orders.order_type = 5,"API","Unknown")))))) AS Sales_order_type'),
        DB::raw("SUBSTRING(customers.customer_name, 1, 15) as customer_name"), 
        DB::raw("SUBSTRING(employees.employee_name, 1, 10) as employee_name"),
        'delivery_types.delivery_type_name',
        'sales_orders.order_status_id'
    )
    ->join('customers', 'sales_orders.customer_id', '=', 'customers.customer_id')
    ->join('employees', 'sales_orders.employee_id', '=', 'employees.employee_id')
    ->join('branches', 'sales_orders.branch_id', '=', 'branches.branch_id')
    ->leftJoin('delivery_types', 'sales_orders.deliver_type_id', '=', 'delivery_types.delivery_type_id')
    ->orderBy('sales_orders.external_number', 'DESC');

    if (!empty($searchValue)) {
  
        $query->where(function ($query) use ($searchValue) {
            $search_amount = str_replace(',', '', $searchValue);
            $query->where('external_number', 'like', '%' . $searchValue . '%')
                ->orWhere('order_date_time', 'like', '%' . $searchValue . '%')
                ->orWhere('total_amount', 'like', '%' . $search_amount . '%')
                ->orWhere('employees.employee_name', 'like', '%' . $searchValue . '%')
                ->orWhere('expected_date_time', 'like', '%' . $searchValue . '%')
                ->orWhere('customers.customer_name', 'like', '%' . $searchValue . '%');
              
              
        });
    }

            $results = $query->take($pageLength)->skip($skip)->get();
            

            $results->transform(function ($item) {
                $status = "Original";
              //  $disabled = "disabled";
                $buttons = '<button class="btn btn-primary btn-sm" id="btnEdit_' . $item->sales_order_Id . '" onclick="edit(' . $item->sales_order_Id . ', \'' . $status . '\')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>&#160';
                $buttons .= '<button class="btn btn-success btn-sm" onclick="view(' . $item->sales_order_Id . ', \'' . $status . '\')"><i class="fa fa-eye" aria-hidden="true"></i></button>&#160';
                $buttons .= '<button class="btn btn-secondary btn-sm" onclick="salesorderReport(' . $item->sales_order_Id . ')"><i class="fa fa-print" aria-hidden="true"></i></button>';

                $item->buttons = $buttons;


                $statusLabel = '<label class="badge badge-pill bg-success">' . $status . '</label>';

                $item->statusLabel = $statusLabel;

                return $item;
            });

            return response()->json([
                'data' => $results,
                'draw' => request('draw'),
            ]);

        } catch (Exception $ex) {
            return $ex;
        }
    }


    //load location
    public function getLocation($id)
    {
        try {
            $locations = location::where('branch_id', '=', $id)
            ->where('location_type_id', '=', 3)
            ->get();
            if ($locations) {
                return response()->json($locations);
            } else {
                return response()->json(['status' => false]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //load deliver types
    public function getDeliveryTypes()
    {
        try {
            $types = delivery_type::all();
            if ($types) {
                return response()->json($types);
            } else {
                return response()->json(['status' => false]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //get server time
    public function getServerTime()
    {
        try {
            $serverDate = Carbon::now()->format('d/m/Y');
            $firstDate = Carbon::now()->startOfMonth()->format('d/m/Y');
            $lastDate = Carbon::now()->endOfMonth()->format('d/m/Y');
            return response()->json(['date' => $serverDate, 'first' => $firstDate, 'last' => $lastDate]);
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function loadItems()
    {
        $val = 1;
        try {
            /* $items = item::all(); */
            $items = DB::table('items')
                ->select('item_id', 'item_Name', 'Item_code')
                ->where('is_active', '=', $val)
                ->get();
            $collection = [];
            foreach ($items as $item) {
                array_push($collection, ["hidden_id" => $item->item_id, "id" =>  $item->item_Name, "value" =>  $item->Item_code, "collection" => [$item->item_id, $item->item_Name, $item->Item_code]]);
            }
            return response()->json(['success' => true, 'data' => $collection]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //add sales order
    public function addSalesOrder(Request $request, $id)
    {
        try {
            if ($id != "null") {

                sales_order_draft::find($id)->delete();
                sales_order_item_draft::where("sales_order_Id", "=", $id)->delete();
            }
            $referencenumber = $request->input('LblexternalNumber');
            $bR_id = $request->input('cmbBranch');

            $data = DB::table('branches')->where('branch_id', $bR_id)->get();

            $EXPLODE_ID = explode("-", $referencenumber);
            $externalNumber  = '';
            if ($data->count() > 0) {
                $documentPrefix = $data[0]->prefix;
                $externalNumber  = $documentPrefix . "-" . $EXPLODE_ID[0] . "-" . $EXPLODE_ID[1];
            }
            $PreparedBy = Auth::user()->id;
            $collection = json_decode($request->input('collection'));
            $Sales_order = new sales_order();
            $Sales_order->internal_number = IntenelNumberController::getNextID();
            $Sales_order->external_number = $externalNumber;
            $Sales_order->order_date_time = $request->input('order_date_time');
            /*$Sales_order->order_type = $request->input('cmbOrderType'); */
            $Sales_order->location_id = $request->input('cmbLocation');
            $Sales_order->employee_id = $request->input('cmbEmp');
            $Sales_order->customer_id = $request->input('customerID');
            $Sales_order->order_status_id = 1;
            $Sales_order->total_amount = $request->input('grandTotal');
            /* $Purchase_order->Approval_status = $request->input('Approval_status'); */
            $Sales_order->discount_percentage = $request->input('txtDiscountPrecentage');
            $Sales_order->discount_amount = $request->input('txtDiscountAmount');
            $Sales_order->payment_term_id = $request->input('cmbPaymentTerm');
            $Sales_order->deliver_type_id = $request->input('cmbDeliverType');
            $Sales_order->remarks = $request->input('txtRemarks');
            $Sales_order->delivery_instruction = $request->input('txtDeliveryInst');
            $Sales_order->expected_date_time = $request->input('delivery_date_time');
            $Sales_order->prepaired_by = $PreparedBy;
            $Sales_order->document_number = 200;
            $Sales_order->your_reference_number = $request->input('txtYourReference');
            $Sales_order->branch_id = $request->input('cmbBranch');
           
            if ($Sales_order->save()) {

                //looping ifrst array
                foreach ($collection as $i) {
                    /*  $date = date('Y-m-d H:i:s'); */
                    $item = json_decode($i);
                    $SO_item = new sales_oder_item();
                    $SO_item->sales_order_Id = $Sales_order->sales_order_Id;
                    $SO_item->external_number = $Sales_order->external_number; // need to change
                    $SO_item->item_id = $item->item_id;
                    $SO_item->item_name = $item->item_name;
                    $SO_item->quantity = $item->qty;

                    if ($item->free_quantity) {
                        $SO_item->free_quantity = $item->free_quantity;
                    } else {
                        $SO_item->free_quantity = 0;
                    }

                    if ($item->uom) {
                        $SO_item->unit_of_measure = $item->uom;
                    } else {
                        $SO_item->unit_of_measure = 0;
                    }

                    if ($item->PackUnit) {
                        $SO_item->package_unit = $item->PackUnit;
                    } else {
                        $SO_item->package_unit = 0;
                    }

                    if ($item->PackSize) {
                        $SO_item->package_size = $item->PackSize;
                    } else {
                        $SO_item->package_size = 0;
                    }

                    if ($item->price) {
                        $SO_item->price = $item->price;
                    } else {
                        $SO_item->price = 0;
                    }

                    if ($item->discount_percentage) {
                        $SO_item->discount_percentage = $item->discount_percentage;
                    } else {
                        $SO_item->discount_percentage = 0;
                    }

                    if ($item->discount_amount) {
                        $SO_item->discount_amount = $item->discount_amount;
                    } else {
                        $SO_item->discount_amount = 0;
                    }

                    $SO_item->save();
                }


                return response()->json(["status" => true,"primaryKey" =>$Sales_order->sales_order_Id]);
            } else {
                return response()->json(["status" => false]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //update sales order
    public function updateSalesOrder(Request $request, $id)
    {
        try {
            $collection = json_decode($request->input('collection'));
            $Sales_order = sales_order::find($id);
            if ($Sales_order) {
                if ($Sales_order->order_status_id != 1) {
                    return response()->json(["status" => true , "message" => "invoiced"]);
                } else {
                    /* $Sales_order->internal_number = 0000; */
                    //$Sales_order->external_number = $request->input('LblexternalNumber');  // need to change 
                    $Sales_order->order_date_time = $request->input('order_date_time');
                    /*$Sales_order->order_type = $request->input('cmbOrderType'); */
                    $Sales_order->location_id = $request->input('cmbLocation');
                    $Sales_order->employee_id = $request->input('cmbEmp');
                    $Sales_order->customer_id = $request->input('customerID');
                    $Sales_order->total_amount = $request->input('grandTotal');
                    /* $Purchase_order->Approval_status = $request->input('Approval_status'); */
                    $Sales_order->discount_percentage = $request->input('txtDiscountPrecentage');
                    $Sales_order->discount_amount = $request->input('txtDiscountAmount');
                    $Sales_order->payment_term_id = $request->input('cmbPaymentTerm');
                    $Sales_order->deliver_type_id = $request->input('cmbDeliverType');
                    $Sales_order->remarks = $request->input('txtRemarks');
                    $Sales_order->delivery_instruction = $request->input('txtDeliveryInst');
                    $Sales_order->expected_date_time = $request->input('delivery_date_time');

                    $Sales_order->document_number = 200;
                    $Sales_order->your_reference_number = $request->input('txtYourReference');
                    $Sales_order->branch_id = $request->input('cmbBranch');



                    if ($Sales_order->update()) {
                        $deleteOrderItem = sales_oder_item::where("sales_order_Id", "=", $id)->delete();
                        //looping ifrst array
                        foreach ($collection as $i) {
                            /*  $date = date('Y-m-d H:i:s'); */
                            $item = json_decode($i);
                            $SO_item = new sales_oder_item();
                            $SO_item->sales_order_Id = $Sales_order->sales_order_Id;
                            $SO_item->internal_number = $Sales_order->internal_number;
                            $SO_item->external_number = $Sales_order->external_number; // need to change
                            $SO_item->item_id = $item->item_id;
                            $SO_item->item_name = $item->item_name;
                            $SO_item->quantity = $item->qty;

                            if ($item->free_quantity) {
                                $SO_item->free_quantity = $item->free_quantity;
                            } else {
                                $SO_item->free_quantity = 0;
                            }

                            if ($item->uom) {
                                $SO_item->unit_of_measure = $item->uom;
                            } else {
                                $SO_item->unit_of_measure = 0;
                            }

                            if ($item->PackUnit) {
                                $SO_item->package_unit = $item->PackUnit;
                            } else {
                                $SO_item->package_unit = 0;
                            }

                            if ($item->PackSize) {
                                $SO_item->package_size = $item->PackSize;
                            } else {
                                $SO_item->package_size = 0;
                            }

                            if ($item->price) {
                                $SO_item->price = $item->price;
                            } else {
                                $SO_item->price = 0;
                            }

                            if ($item->discount_percentage) {
                                $SO_item->discount_percentage = $item->discount_percentage;
                            } else {
                                $SO_item->discount_percentage = 0;
                            }

                            if ($item->discount_amount) {
                                $SO_item->discount_amount = $item->discount_amount;
                            } else {
                                $SO_item->discount_amount = 0;
                            }

                            $SO_item->save();
                        }
                    }
                }



                return response()->json(["status" => true]);
            } else {
                return response()->json(["status" => false]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //add sales order draft
    public function addSalesOderDraft(Request $request)
    {
        try {

            $PreparedBy = Auth::user()->id;
            $collection = json_decode($request->input('collection'));
            $Sales_order = new sales_order_draft();
            $Sales_order->internal_number = 0000;
            $Sales_order->external_number = $request->input('LblexternalNumber');  // need to change 
            $Sales_order->order_date_time = $request->input('order_date_time');
            /*    $Sales_order->order_type = $request->input('cmbOrderType'); */
            $Sales_order->location_id = $request->input('cmbLocation');
            $Sales_order->employee_id = $request->input('cmbEmp');
            $Sales_order->customer_id = $request->input('customerID');
            $Sales_order->total_amount = $request->input('grandTotal');
            /* $Purchase_order->Approval_status = $request->input('Approval_status'); */
            $Sales_order->discount_percentage = $request->input('txtDiscountPrecentage');
            $Sales_order->discount_amount = $request->input('txtDiscountAmount');
            $Sales_order->payment_term_id = $request->input('cmbPaymentTerm');
            $Sales_order->deliver_type_id = $request->input('cmbDeliverType');
            $Sales_order->remarks = $request->input('txtRemarks');
            $Sales_order->delivery_instruction = $request->input('txtDeliveryInst');
            $Sales_order->expected_date_time = $request->input('delivery_date_time');
            $Sales_order->prepaired_by = $PreparedBy;
            $Sales_order->document_number = 200;

            if ($Sales_order->save()) {

                //looping ifrst array
                foreach ($collection as $i) {
                    /*  $date = date('Y-m-d H:i:s'); */
                    $item = json_decode($i);
                    $SO_item = new sales_order_item_draft();
                    $SO_item->sales_order_Id = $Sales_order->sales_order_Id;
                    $SO_item->external_number = $Sales_order->external_number; // need to change
                    $SO_item->item_id = $item->item_id;
                    $SO_item->item_name = $item->item_name;
                    $SO_item->quantity = $item->qty;
                    $SO_item->free_quantity = $item->free_quantity;
                    $SO_item->unit_of_measure = $item->uom;
                    $SO_item->package_unit = $item->PackUnit;
                    $SO_item->package_size = $item->PackSize;
                    $SO_item->price = $item->price;
                    $SO_item->discount_percentage = $item->discount_percentage;
                    $SO_item->discount_amount = $item->discount_amount;

                    $SO_item->save();
                }


                return response()->json(["status" => true]);
            } else {
                return response()->json(["status" => false]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //update sales order draft
    public function updateSalesOrderDraft(Request $request, $id)
    {
        try {
            $collection = json_decode($request->input('collection'));
            $Sales_order = sales_order_draft::find($id);
            $Sales_order->internal_number = 0000;
            $Sales_order->external_number = $request->input('LblexternalNumber');  // need to change 
            $Sales_order->order_date_time = $request->input('order_date_time');
            /*    $Sales_order->order_type = $request->input('cmbOrderType'); */
            $Sales_order->location_id = $request->input('cmbLocation');
            $Sales_order->employee_id = $request->input('cmbEmp');
            $Sales_order->customer_id = $request->input('customerID');
            $Sales_order->total_amount = $request->input('grandTotal');
            /* $Purchase_order->Approval_status = $request->input('Approval_status'); */
            $Sales_order->discount_percentage = $request->input('txtDiscountPrecentage');
            $Sales_order->discount_amount = $request->input('txtDiscountAmount');
            $Sales_order->payment_term_id = $request->input('cmbPaymentTerm');
            $Sales_order->deliver_type_id = $request->input('cmbDeliverType');
            $Sales_order->remarks = $request->input('txtRemarks');
            $Sales_order->delivery_instruction = $request->input('txtDeliveryInst');
            $Sales_order->expected_date_time = $request->input('delivery_date_time');


            if ($Sales_order->update()) {
                $deleteOrderItem = sales_order_item_draft::where("sales_order_Id", "=", $id)->delete();
                //looping ifrst array
                foreach ($collection as $i) {
                    /*  $date = date('Y-m-d H:i:s'); */
                    $item = json_decode($i);
                    $SO_item = new sales_order_item_draft();
                    $SO_item->sales_order_Id = $Sales_order->sales_order_Id;
                    $SO_item->external_number = $Sales_order->external_number; // need to change
                    $SO_item->item_id = $item->item_id;
                    $SO_item->item_name = $item->item_name;
                    $SO_item->quantity = $item->qty;
                    $SO_item->free_quantity = $item->free_quantity;
                    $SO_item->unit_of_measure = $item->uom;
                    $SO_item->package_unit = $item->PackUnit;
                    $SO_item->package_size = $item->PackSize;
                    $SO_item->price = $item->price;
                    $SO_item->discount_percentage = $item->discount_percentage;
                    $SO_item->discount_amount = $item->discount_amount;

                    $SO_item->save();
                }


                return response()->json(["status" => true]);
            } else {
                return response()->json(["status" => false]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //get payment term
    public function getPaymentTerm()
    {
        try {
            $P_term = paymentTerm::all();
            if ($P_term) {
                return response()->json(['success' => true, 'data' => $P_term]);
            } else {
                return response()->json(['success' => true, 'data' => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //delete sales order
    public function deleteSO($id, $status)
    {
        try {
            if ($status == "Original") {
                $Sales_order = sales_order::find($id);
                if ($Sales_order->delete()) {
                    $Sales_order_item = sales_oder_item::where('sales_order_Id', '=', $id)->delete();;

                    return response()->json(["message" => "Deleted"]);
                } else {
                    return response()->json(["message" => "Not Deleted"]);
                }
            } else {
                $Sales_order = sales_order_draft::find($id);
                if ($Sales_order->delete()) {
                    $Sales_order_item = sales_order_item_draft::where('sales_order_Id', '=', $id)->delete();

                    return response()->json(["message" => "Deleted"]);
                } else {
                    return response()->json(["message" => "Not Deleted"]);
                }
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //get each sales order
    public function getEachSalesOrder($id, $status)
    {
        try {
            if ($status == "Original") {
                $query = 'SELECT sales_orders.*,customers.customer_code,customers.customer_name,customers.primary_address FROM sales_orders INNER JOIN customers ON sales_orders.customer_id = customers.customer_id WHERE sales_order_Id = "' . $id . '"';
                $result = DB::select($query);
                if ($result) {
                    return response()->json(["message" => "Deleted", "data" => $result]);
                } else {
                    return response()->json(["message" => "Not Deleted", "data" => []]);
                }
            } else {
                $query = 'SELECT sales_order_drafts.*,customers.customer_code,customers.customer_name,customers.primary_address FROM sales_order_drafts INNER JOIN customers ON sales_order_drafts.customer_id = customers.customer_id WHERE sales_order_Id = "' . $id . '"';
                $result = DB::select($query);
                if ($result) {
                    return response()->json(["message" => "Deleted", "data" => $result]);
                } else {
                    return response()->json(["message" => "Not Deleted", "data" => []]);
                }
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //get each product
    public function getEachproductofSalesOder($id, $status)
    {
        try {
            if ($status == "Draft") {
                $query = 'SELECT sales_order_item_drafts.*,items.Item_code FROM sales_order_item_drafts INNER JOIN items ON sales_order_item_drafts.item_id = items.item_id WHERE sales_order_item_drafts.sales_order_Id = "' . $id . '"';
                $result = DB::select($query);
                if ($query) {
                    return response()->json($result);
                } else {
                    return response()->json((['success' => 'Data loaded', 'data' => []]));
                }
            } else {
                $SO = sales_order::find($id);
                $cus = $SO->customer_id;
                $date = date('Y-m-d'); 
                $query = "SELECT sales_order_items.*, items.Item_code, 
    (SELECT IFNULL(sd_free_offerd_quantity('$cus', sales_order_items.item_id, sales_order_items.quantity, '$date'), 0) AS Offerd_quantity) AS free_offered_quantity 
    FROM sales_order_items 
    INNER JOIN items ON sales_order_items.item_id = items.item_id 
    WHERE sales_order_items.sales_order_Id = '$id'";

                $result = DB::select($query);
                if ($query) {
                    return response()->json($result);
                } else {
                    return response()->json((['success' => 'Data loaded', 'data' => []]));
                }
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //get approvals
    public function getSalesOrderPendingApprovals()
    {
        try {
            $query = 'SELECT
            sales_orders.sales_order_Id,
            sales_orders.order_date_time,
            sales_orders.external_number,
            sales_orders.expected_date_time,
            sales_orders.approval_status,
            customers.customer_name,
            employees.employee_name,
            delivery_types.delivery_type_name
        FROM
            sales_orders
            INNER JOIN customers ON sales_orders.customer_id = customers.customer_id
            INNER JOIN employees ON sales_orders.employee_id = employees.employee_id
            INNER JOIN delivery_types ON sales_orders.deliver_type_id = delivery_types.delivery_type_id
        WHERE
            sales_orders.approval_status = "Pending"';

            $result = DB::select($query);

            if ($result) {
                return response()->json(['success' => 'Data loaded', 'data' => $result]);
            } else {
                return response()->json(['success' => 'Data loaded', 'data' => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //approve
    public function approveSalesOrder($id)
    {
        try {
            $approvedBy = Auth::user()->id;
            $salesOrder = sales_order::find($id);
            $salesOrder->approval_status = "Approved";
            $salesOrder->approved_by = $approvedBy;
            if ($salesOrder->update()) {
                return response()->json((['status' => true]));
            } else {
                return response()->json((['status' => false]));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //reject
    public function rejectSalesOrder($id)
    {
        try {
            $approvedBy = Auth::user()->id;
            $salesOrder = sales_order::find($id);
            $salesOrder->approval_status = "Rejected";
            $salesOrder->approved_by = $approvedBy;
            if ($salesOrder->update()) {
                return response()->json((['status' => true]));
            } else {
                return response()->json((['status' => false]));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function getItemInfo($Item_id)
    {
        try {
            $info = item::find($Item_id);

            if ($info) {
                return response()->json([$info]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //for sales order only
    public function getItemInfo_sales_order($Item_id)
    {
        try {
            /* $info = item::find($Item_id); */
            $info = DB::select("SELECT it.item_id,it.Item_code,it.item_Name,it.item_description,it.package_unit,it.unit_of_measure,it.whole_sale_price,it.average_cost_price,it.retial_price,IFNULL((SELECT whole_sale_price  FROM   item_history_set_offs 
            WHERE  (quantity-setoff_quantity) > 0  AND item_id=it.item_id
            ORDER BY item_history_setoff_id DESC LIMIT 1),0) AS item_price FROM items it WHERE it.item_id = $Item_id");

            if ($info) {
                return $info;
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //reject sales order for invoice
    public function rejectSalesOrderForInvocie($id)
    {
        try {
            $qry = "SELECT order_status_id FROM sales_orders WHERE sales_orders.sales_order_Id = $id";
            $result = DB::select($qry);
            if($result){
                if($result[0]->order_status_id > 1){
                    return response()->json((['status' => false,'message' => 'no']));
                }
            }
            $approvedBy = Auth::user()->id;
            $salesOrder = sales_order::find($id);
            $salesOrder->approval_status = "Rejected";
            $salesOrder->order_status_id = 3;
            $salesOrder->approved_by = $approvedBy;
            if ($salesOrder->update()) {
                return response()->json((['status' => true]));
            } else {
                return response()->json((['status' => false]));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //updatestatus when invoice is created
    public function updateStatusOfOrder($id)
    {
        try {
            $salesOrder = sales_order::find($id);
            $salesOrder->order_status_id = 2;
            if ($salesOrder->update()) {
                return response()->json((['status' => true]));
            } else {
                return response()->json((['status' => false]));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //check order type
    public function checkSalesOrderType($val){
        try{
            $salesOrder = sales_order::find($val);
            $order_type = $salesOrder->order_type;
            return response()->json((['status' => true, 'data'=>$order_type]));

        }catch(Exception $ex){
            return $ex;
        }
    }

    //check is approved
    public function isAPproved($val){
        try{
            $salesOrder = sales_order::find($val);
            $order_Status_id = $salesOrder->order_status_id;
            return response()->json((['status' => true, 'data'=>$order_Status_id]));

        }catch(Exception $ex){
            return $ex;
        }
    }
}
