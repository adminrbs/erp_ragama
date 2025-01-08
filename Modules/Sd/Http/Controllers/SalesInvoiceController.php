<?php

namespace Modules\Sd\Http\Controllers;

use App\Http\Controllers\ChequeReferenceNumberController;
use App\Http\Controllers\IntenelNumberController;
use App\Http\Controllers\ReferenceIdController;

use App\Models\DebtorsLedgerSetoff;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Md\Entities\CustomerPaymentMode;
use Modules\Sd\Entities\SalesReturnReson;
use Modules\Sd\Entities\item;
use Modules\Sd\Entities\item_history;
use Modules\Sd\Entities\item_history_setOff;
use Modules\Sd\Entities\PaymentTerm;
use Modules\Sd\Entities\reprint_request;
use Modules\Sd\Entities\sales_invoice;
use Modules\Sd\Entities\sales_Invoice_draft;
use Modules\Sd\Entities\sales_Invoice_draft_item;
use Modules\Sd\Entities\sales_Invoice_item;
use Modules\Sd\Entities\sales_Invoice_item_draft;
use Modules\Sd\Entities\sales_order;
use Modules\Sd\Entities\SalesInvoiceItemSetoff;
use Modules\Sd\Entities\supplierPaymentMethod;
use Illuminate\Support\Facades\Session;
use Modules\Cb\Entities\Customer;
use Modules\Cb\Entities\CustomerReceipt;
use Modules\Cb\Entities\CustomerReceiptBankSlip;
use Modules\Cb\Entities\CustomerReceiptCheque;
use Modules\Cb\Entities\CustomerReceiptSetoffData;
use Modules\Cb\Entities\DebtorsLedger;
use Modules\Dl\Entities\customer_transaction_alocation;
use Modules\Md\Entities\bank;
use Modules\Md\Entities\bank_branch;
use Modules\Sd\Entities\sales_invoice_return_request;
use Modules\Sd\Entities\sales_return;
use Modules\Sd\Entities\sales_return_debtor_setoff;
use Modules\Sd\Entities\SalesInvoiceBankTransfer;
use Modules\Sd\Entities\SalesInvoiceCardPayment;
use Modules\Sd\Entities\SalesInvoiceChequePayment;
use Modules\Sd\Entities\SalesInvoicePayments;
use Modules\Sd\Entities\sfa_return_request_item;

class SalesInvoiceController extends Controller
{
    //load customers to data chooser
    public function loadCustomerTOchooser()
    {
        /*  $qry = 'SELECT customers.customer_code as value, CONCAT(customers.customer_name," - ",IF(ISNULL(towns.town_name),"",towns.town_name)) as id,"tt" as value2 FROM customers LEFT JOIN towns ON towns.town_id = customers.town_id'; */
        $qry = 'SELECT customers.customer_code as value,customers.customer_name as id,town_non_administratives.townName as value2,routes.route_name as value3 FROM customers LEFT JOIN town_non_administratives ON town_non_administratives.town_id = customers.town LEFT JOIN routes ON customers.route_id = routes.route_id WHERE customers.customer_status = 1';

        $result = DB::select($qry);
        if ($result) {
            return response()->json(['data' => $result]);
        } else {
            return response()->json(['status' => false]);
        }
    }

    //load other data to data chooser
    public function loadCustomerOtherDetails($id)
    {

        /* $query = DB::table('customers')
            ->select('customers.customer_id', 'customers.primary_address')
            ->where('customers.customer_code', '=', $id)
            ->get(); */
        $special_bonus_count = 0;
        $query = "SELECT customers.customer_id,customers.payment_term_id, customers.primary_address, town_non_administratives.townName FROM customers
             LEFT JOIN town_non_administratives ON customers.town = town_non_administratives.town_id
             WHERE customers.customer_code ='" . $id . "'";
        $result = DB::select($query);
        $cus_id = $result[0]->customer_id;

        $special_bonus = DB::select('SELECT COUNT(*) as count FROM special_bonuses WHERE customer_id = "' . $cus_id . '"');
        if ($special_bonus) {
            $special_bonus_count = $special_bonus[0]->count;
        }
        if ($result) {
            return response()->json(['data' => $result, 'bonus_count' => $special_bonus_count]);
        } else {
            return response()->json(['status' => false]);
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

    public function loademployees()
    {
        try {
            $emp =  $query = DB::table('employees')
                ->select('employees.employee_id', 'employees.employee_name')
                ->where('employees.desgination_id', '=', 7)
                ->get();
            if ($emp) {
                return response()->json($emp);
            } else {
                return response()->json(['status' => false]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function loademployeesAccordingToBranch($id)
    {
        try {
            $emp =  DB::table('employees')
                ->select('employees.employee_id', 'employees.employee_name')
                ->join('employee_branches', 'employees.employee_id', '=', 'employee_branches.employee_id')
                ->where('employees.desgination_id', '=', 7)
                ->where('employee_branches.branch_id', '=', $id)
                ->get();
            if ($emp) {
                return response()->json($emp);
            } else {
                return response()->json(['status' => false]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }
    //add Invoice

    public function addSalesInvoice(Request $request, $id)
    {
        $status = true;
        try {



            DB::beginTransaction();
            //check order status
            $SO_id_ = $request->input('order_id');
            if ($SO_id_) {
                $qry_check_status = "SELECT order_status_id FROM sales_orders WHERE sales_orders.sales_order_Id = $SO_id_";
                $result_ = DB::select($qry_check_status);
                if ($result_) {
                    if ($result_[0]->order_status_id != 1) {
                        return response()->json(["message" => "no order"]);
                    }
                }
            }

            $collection = json_decode($request->input('collection'));
            $setOffArray = json_decode($request->input('setOffArray'));
            $return_request_collection = json_decode($request->input('return_request_collection'));
            $branch_id_ = $request->input('cmbBranch');
            $location_id = $request->input('cmbLocation');
            $wholeSalePrice = 0;
            $retail_price = 0;
            $cost_price = 0;
            $sales_order_id = 0;

            //validate set off array

            foreach ($setOffArray as $i) {
                $item = json_decode($i);
                $itemID = $item->item_id;
                $setOffqty = $item->setoff_quantity;
                $wholeSalePrice = $item->wholesale_price;
                $retail_price = $item->retail_price;
                $cost_price = $item->cost_price;
                $query = "SELECT IF(ISNULL(SUM(quantity-setoff_quantity)), 0, SUM(quantity-setoff_quantity)) AS balance 
                FROM item_history_set_offs 
                WHERE whole_sale_price = '" . $wholeSalePrice . "' 
                  AND item_id = '" . $itemID . "' 
                  AND branch_id = '" . $branch_id_ . "' 
                  AND location_id = " . $location_id . "
                  AND quantity> 0";




                $balance = DB::select($query);
                if ($balance) {
                    $stockBalance = $balance[0]->balance;

                    $formatted_stockBalance = floatval(str_replace(',', '', $stockBalance));
                    $formatted_qty = floatval(str_replace(',', '', $setOffqty));

                    if ($formatted_stockBalance < $formatted_qty) {
                        $status = false;
                        return response()->json(["message" => "insuficent"]);
                    }
                }
                //dd("dd");
            }

            //validate collection array
            foreach ($collection as $i) {
                $item = json_decode($i);
                $itemID = $item->item_id;
                $qty = $item->qty;
                $foc = $item->free_quantity;
                if ($qty == "" || is_nan($qty) || $qty == 0) {
                    return response()->json(["message" => "qty_zero"]);
                }
                if ($foc == "" || is_nan($foc)) {
                    $foc = 0;
                }
                $total_ = floatval($qty) + floatval($foc);
                $query = "SELECT IF(ISNULL(SUM(quantity-setoff_quantity)), 0, SUM(quantity-setoff_quantity)) AS Balance
                FROM item_history_set_offs
                WHERE item_id = '" . $itemID . "' AND branch_id = '" . $branch_id_ . "' AND location_id ='" . $location_id . "' AND price_status = 0 AND quantity>0";

                $balance_ = DB::select($query);
                if ($balance_) {
                    $stockBalance = $balance_[0]->Balance;

                    $formatted_stockBalance = floatval(str_replace(',', '', $stockBalance));
                    $formatted_qty = floatval(str_replace(',', '', $setOffqty));

                    if (floatval($formatted_stockBalance) < floatval($total_)) {
                        $status = false;
                        return response()->json(["message" => "insuficent"]);
                    }
                }
            }

            //saving invoice
            if ($status) {
                if ($id != "null") {

                    sales_Invoice_draft::find($id)->delete();
                    sales_Invoice_item_draft::where("sales_invoice_Id", "=", $id)->delete();
                }

                $referencenumber = $request->input('LblexternalNumber');
                // dd($referencenumber);
                $bR_id = $request->input('cmbBranch');

                $data = DB::table('branches')->where('branch_id', $bR_id)->get();

                $EXPLODE_ID = explode("-", $referencenumber);

                $externalNumber  = '';
                if ($data->count() > 0) {
                    $documentPrefix = $data[0]->prefix;
                    $externalNumber  = $documentPrefix . "-" . $EXPLODE_ID[0] . "-" . $EXPLODE_ID[1];
                }

                $payment_method = $request->input('cmbPaymentMethod');
                $code = $request->input('code');
                $branch_code = $request->input('branch_code');
                $result = DB::select('CALL sd_next_sales_invoice(?, ?, ?)', [$branch_code, $code, $payment_method,]);
                $manual_number = 0;
                if ($result) {
                    $manual_number = $result[0]->next_manual_number;
                }


                $PreparedBy = Auth::user()->id;
                $collection = json_decode($request->input('collection'));
                $Sales_invoice = new sales_Invoice();
                $Sales_invoice->internal_number = IntenelNumberController::getNextID();
                $Sales_invoice->external_number = $externalNumber; // need to change 
                $Sales_invoice->order_date_time = $request->input('invoice_date_time');
                $Sales_invoice->branch_id = $request->input('cmbBranch');
                $Sales_invoice->location_id = $request->input('cmbLocation');
                $Sales_invoice->employee_id = $request->input('cmbEmp');
                $Sales_invoice->customer_id = $request->input('customerID');
                $Sales_invoice->total_amount = $request->input('grandTotal');
                /* $Purchase_order->Approval_status = $request->input('Approval_status'); */
                $Sales_invoice->discount_percentage = $request->input('txtDiscountPrecentage');
                $Sales_invoice->discount_amount = $request->input('txtDiscountAmount');
                $Sales_invoice->payment_term_id = $request->input('cmbPaymentTerm');
                $Sales_invoice->document_number = 210;
                $Sales_invoice->remarks = $request->input('txtRemarks');
                $Sales_invoice->delivery_instruction = $request->input('txtDeliveryInst');
                $Sales_invoice->payment_method_id = $request->input('cmbPaymentMethod');
                $Sales_invoice->sales_analyst_id = $request->input('sales_analyst_id');

                //validating your reference number
                /*  if($request->input('txtYourReference') != null){
                    $lowercaseRef = strtolower(str_replace(' ', '', $request->input('txtYourReference')));
                    $query = "SELECT COUNT(*) FROM sales_invoices WHERE LOWER(REPLACE(your_reference_number, ' ', '')) = :$lowercaseRef";


                }else{
                    $Sales_invoice->your_reference_number = $request->input('txtYourReference');
                } */

                $Sales_invoice->your_reference_number = $request->input('txtYourReference');

                $Sales_invoice->manual_number = $manual_number;

                $Sales_invoice->prepaired_by = $PreparedBy;
                if (is_nan(floatval($request->input('SO_number')))) {
                    $sales_order_id = null;
                } else {

                    $sales_order_id = $request->input('SO_number');
                    $S_order = sales_order::find($sales_order_id); // create for status update after saving SI
                }
                $Sales_invoice->Sales_order_id = $sales_order_id;
                if ($Sales_invoice->save()) {
                    $cus_name = DB::select("SELECT customers.customer_name FROM sales_invoices INNER JOIN customers ON sales_invoices.customer_id = customers.customer_id WHERE sales_invoices.sales_invoice_Id = '" . $Sales_invoice->sales_invoice_Id . "'");


                    //looping ifrst array
                    foreach ($collection as $i) {
                        $item = json_decode($i);
                        $location_id = $request->input('locationID');
                        $item_id = $item->item_id;
                        $itemQty = $item->qty;
                        $itemFoc = $item->free_quantity;

                        $formatted_qty = floatval(str_replace(',', '', $itemQty));
                        $formatted_foc = floatval(str_replace(',', '', $itemFoc));

                        $SI_item = new sales_Invoice_item();
                        $SI_item->sales_invoice_Id = $Sales_invoice->sales_invoice_Id;
                        $SI_item->internal_number = $Sales_invoice->internal_number;
                        $SI_item->external_number = $Sales_invoice->external_number; // need to change
                        $SI_item->item_id = $item->item_id;
                        $SI_item->item_name = $item->item_name;

                        $SI_item->quantity = -$formatted_qty;
                        if ($formatted_foc) {
                            $SI_item->free_quantity = -$formatted_foc;
                        } else {
                            $SI_item->free_quantity = 0;
                        }

                        $SI_item->unit_of_measure = $item->uom;
                        $SI_item->package_unit = $item->PackUnit;
                        $SI_item->package_size = $item->PackSize;
                        $SI_item->price = $item->price;
                        if ($item->discount_percentage) {
                            $SI_item->discount_percentage = $item->discount_percentage;
                        } else {
                            $SI_item->discount_percentage = 0;
                        }
                        if ($item->discount_amount) {
                            $SI_item->discount_amount = $item->discount_amount;
                        } else {
                            $SI_item->discount_amount = 0;
                        }



                        foreach ($setOffArray as $i) {
                            $item = json_decode($i);
                            $itemID = $item->item_id;
                            $wholeSalePrice = $item->wholesale_price;
                            $retail_price = $item->retail_price;
                            $cost_price = $item->cost_price;

                            if ($SI_item->item_id == $itemID) {
                                $SI_item->whole_sale_price = $wholeSalePrice;
                                $SI_item->retial_price = $retail_price;
                                $SI_item->cost_price = $cost_price;
                            }
                        }


                        if ($SI_item->save()) {
                            $item_history = new item_history();
                            $item_history->internal_number = $SI_item->internal_number;
                            $item_history->external_number = $SI_item->external_number;
                            $item_history->external_number = $SI_item->external_number;
                            $item_history->branch_id = $Sales_invoice->branch_id;
                            $item_history->location_id = $Sales_invoice->location_id;
                            $item_history->document_number = $Sales_invoice->document_number;
                            $item_history->transaction_date = $Sales_invoice->order_date_time;
                            $item_history->description = "Sales Invoice to " . $cus_name[0]->customer_name;
                            $item_history->item_id =  $SI_item->item_id;
                            $item_history->quantity = floatVal($SI_item->quantity) + floatval($SI_item->free_quantity);
                            $item_history->free_quantity = $SI_item->free_quantity;
                            $item_history->whole_sale_price = $SI_item->whole_sale_price;
                            $item_history->retial_price = $SI_item->retial_price;
                            $item_history->cost_price = $SI_item->cost_price;
                            $item_history->manual_number = $Sales_invoice->manual_number;
                            $item_history->save();
                        }

                        foreach ($setOffArray as $j) {

                            $SetOff_item = json_decode($j);
                            if ($SetOff_item->item_id == $item_id) {

                                $setOff = new SalesInvoiceItemSetoff();
                                $setOff->internal_number = $Sales_invoice->internal_number;
                                $setOff->external_number = $Sales_invoice->external_number;
                                $setOff->sales_invoice_item_id = $SI_item->sales_invoice_item_id;
                                $setOff->item_history_setoff_id = $SetOff_item->history_id;
                                $setOff->item_id = $SetOff_item->item_id;
                                $setOff->set_off_qty = $SetOff_item->setoff_quantity;
                                $setOff->cost_price = $SetOff_item->cost_price;
                                $setOff->whole_sale_price = $SetOff_item->wholesale_price;
                                $setOff->retail_price = $SetOff_item->retail_price;
                                $setOff->batch_number = $SetOff_item->batch_no;
                                // dd("afte");
                                $setOff->save();

                                // break;
                            }
                        }
                    }

                    // if  (is_array($return_request_collection) && count($return_request_collection) > 0) {
                    foreach ($return_request_collection as $j) {
                        $return = new sales_invoice_return_request();
                        $return->internal_number = $Sales_invoice->internal_number;
                        $return->external_number = $Sales_invoice->external_number;
                        $return->sales_invoice_Id = $Sales_invoice->sales_invoice_Id;
                        $return->sfa_return_request_items_id = $j->sfa_return_request_items_id;
                        $return->employee_id = $j->rep_id;
                        $return->item_id = $j->item_id;
                        $return->quantity = $j->qty;
                        if ($return->save()) {
                            $r_item = sfa_return_request_item::find($j->sfa_return_request_items_id);
                            $r_item->invoiced = 1;
                            $r_item->sales_invoice_Id = $Sales_invoice->sales_invoice_Id;
                            $r_item->update();
                        }
                    }
                    // }

                    //save payment
                    $cardData = json_decode($request->input('cardData'));
                    $chequeData = json_decode($request->input('chequeData'));

                    $bankTransferData = json_decode($request->input('bankTransferData'));

                    $returns = json_decode($request->input('returnData'), true);
                    // $count = count($returns);
                    //save returns
                    if (is_array(value: $returns) && count($returns) > 0) {
                        $this->save_return_setoff($returns, $Sales_invoice);
                    }

                    //dd($chequeData);
                    $Payment = new SalesInvoicePayments();
                    //dd($cardData->cardAmount);
                    $Payment->sales_invoice_id = $Sales_invoice->sales_invoice_id ?: null;
                    $Payment->internal_number = $Sales_invoice->internal_number ?: null;
                    $Payment->external_number = $Sales_invoice->external_number ?: null;
                    $Payment->sales_invoice_id = $Sales_invoice->sales_invoice_Id;

                    $Payment->card_amount = $cardData->cardAmount ?: null;
                    $Payment->card_no = $cardData->cardNo ?: null;
                    $Payment->card_bank_id = $cardData->cardIssueBank ?: null;
                    $Payment->cardType = $cardData->type ?: null;

                    // dd($chequeData);
                    $Payment->cheque_amount = $chequeData->chequeAmount ?: null;
                    $Payment->cheque_no = $chequeData->chequeNo ?: null;
                    $Payment->cheque_date = $chequeData->chqDate ?: null;
                    $Payment->cheque_Bank_id = $chequeData->bankId ?: null;
                    $Payment->cheque_bank_branch_id = $chequeData->bankbranchId ?: null;

                    $Payment->bank_transfer_amount = $bankTransferData->bankAMount ?: null;
                    $Payment->bank_transfer_date = $bankTransferData->bankTransferDate ?: null;
                    $Payment->bank_transfer_reference = $bankTransferData->bankReference ?: null;

                    $Payment->cash_amount = $request->input('cash') ?: null;
                    $Payment->credit_amount = $request->input('credit') ?: null;

                    if ($Payment->save()) {
                        if ($Payment->cash_amount > 0) {
                            $this->saveCustomerReceipt($Sales_invoice, 1, $Payment);
                        }

                        if ($Payment->card_amount > 0) {
                            $this->saveCustomerReceipt($Sales_invoice, 8, $Payment);
                        }

                        if ($Payment->bank_transfer_amount > 0) {
                            $this->saveCustomerReceipt($Sales_invoice, 7, $Payment);
                        }

                        if ($Payment->cheque_amount > 0) {
                            $this->saveCustomerReceipt($Sales_invoice, 2, $Payment);
                        }
                    }



                    if ($S_order) {
                        $S_order->order_status_id = 2;
                        $S_order->update();
                    }

                    DB::commit();
                    return response()->json(["status" => true, "primaryKey" => $Sales_invoice->sales_invoice_Id]);
                } else {
                    DB::rollBack();
                    return response()->json(["status" => false]);
                }
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //update sales invoice
    public function updateSalesInvoice(Request $request, $id)
    {
        try {
            $collection = json_decode($request->input('collection'));
            $Sales_invoice = sales_Invoice::find($id);
            /*  $Sales_invoice->internal_number = 0000; */
            /*   $Sales_invoice->external_number = $request->input('LblexternalNumber'); */
            $Sales_invoice->order_date_time = $request->input('invoice_date_time');
            $Sales_invoice->branch_id = $request->input('cmbBranch');
            $Sales_invoice->location_id = $request->input('cmbLocation');
            $Sales_invoice->employee_id = $request->input('cmbEmp');
            $Sales_invoice->customer_id = $request->input('customerID');
            $Sales_invoice->total_amount = $request->input('grandTotal');
            /* $Purchase_order->Approval_status = $request->input('Approval_status'); */
            $Sales_invoice->discount_percentage = $request->input('txtDiscountPrecentage');
            $Sales_invoice->discount_amount = $request->input('txtDiscountAmount');
            $Sales_invoice->payment_term_id = $request->input('cmbPaymentTerm');

            $Sales_invoice->remarks = $request->input('txtRemarks');
            $Sales_invoice->delivery_instruction = $request->input('txtDeliveryInst');
            $Sales_invoice->payment_method_id = $request->input('cmbPaymentMethod');
            $Sales_invoice->your_reference_number = $request->input('txtYourReference');



            if ($Sales_invoice->update()) {
                $deleteRequestItem = sales_Invoice_item::where("sales_invoice_Id", "=", $id)->delete();
                //looping ifrst array
                foreach ($collection as $i) {
                    /*  $date = date('Y-m-d H:i:s'); */
                    $item = json_decode($i);
                    $SI_item = new sales_Invoice_item();
                    $SI_item->sales_invoice_Id = $Sales_invoice->sales_invoice_Id;
                    $SI_item->internal_number = IntenelNumberController::getNextID();
                    $SI_item->external_number = $Sales_invoice->external_number; // need to change
                    $SI_item->item_id = $item->item_id;
                    $SI_item->item_name = $item->item_name;
                    $SI_item->quantity = -$item->qty;
                    $SI_item->free_quantity = -$item->free_quantity;
                    $SI_item->unit_of_measure = $item->uom;
                    $SI_item->package_unit = $item->PackUnit;
                    $SI_item->package_size = $item->PackSize;
                    $SI_item->price = $item->price;
                    $SI_item->discount_percentage = $item->discount_percentage;
                    $SI_item->discount_amount = $item->discount_amount;

                    $SI_item->save();
                }


                return response()->json(["status" => true]);
            } else {
                return response()->json(["status" => false]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //get invoice data
    /* public function getSalesInvoiceData(Request $request)
    {

        $branch_id_array = Session::get('branch_id_array');

        try {

            $pageNumber = ($request->start / $request->length) + 1;
            $pageLength = $request->length;
            $skip = ($pageNumber - 1) * $pageLength;
            $searchValue = request('search.value');
          //  $query = DB::table('sales_orders');
            
            $query = DB::table('sales_invoices')
    ->select(
        'external_number',
        'manual_number',
        'sales_invoice_Id',
        'order_date_time',
        DB::raw("FORMAT(total_amount, 2) as total_amount"),
        'approval_status',
        DB::raw("SUBSTRING(employees.employee_name, 1, 10) as employee_name"), 
        DB::raw("SUBSTRING(customers.customer_name, 1, 20) as customer_name"), 
        DB::raw("'Original' AS status"),
        DB::raw("SUBSTRING(routes.route_name, 1, 10) as route_name"),
        'is_reprint_allowed'
    )
    ->leftJoin('employees', 'sales_invoices.employee_id', '=', 'employees.employee_id')
    ->leftJoin('customers', 'sales_invoices.customer_id', '=', 'customers.customer_id')
    ->leftJoin('routes', 'customers.route_id', '=', 'routes.route_id')
    ->where('sales_invoices.document_number', '=', '210');

// Add search conditions if a search value is provided
if (!empty($searchValue)) {
  
    $query->where(function ($query) use ($searchValue) {
        $search_amount = str_replace(',', '', $searchValue);
        $query->where('external_number', 'like', '%' . $searchValue . '%')
            ->orWhere('manual_number', 'like', '%' . $searchValue . '%')
            ->orWhere('order_date_time', 'like', '%' . $searchValue . '%')
            ->orWhere('total_amount', 'like', '%' . $search_amount . '%')
            ->orWhere('employees.employee_name', 'like', '%' . $searchValue . '%')
            ->orWhere('customers.customer_name', 'like', '%' . $searchValue . '%')
            ->orWhere('routes.route_name', 'like', '%' . $searchValue . '%');
          
    });
}




$query->orderBy('external_number', 'desc');
           

$results = $query->take($pageLength)->skip($skip)->get();


$results->transform(function ($item) {
    $status = "Original";
  
  
    $buttons = '<button class="btn btn-success btn-sm" onclick="view(' . $item->sales_invoice_Id . ', \'' . $status . '\')"><i class="fa fa-eye" aria-hidden="true"></i></button>&#160';
    $buttons .= '<button class="btn btn-secondary btn-sm" onclick="salesinvoiceReportpage(' . $item->sales_invoice_Id . ')"><i class="fa fa-print" aria-hidden="true"></i></button>';

    $item->buttons = $buttons;
    $statusLabel = '<label class="badge badge-pill bg-success">' . $status . '</label>';
    $item->statusLabel = $statusLabel;

    
    $encodedManualNumber = $this->base64Encode($item->external_number);
  
    $info = '<a href="../sd/invoice_nfo?manual_number=' .$encodedManualNumber. '&action=inquery" onclick="updateTotal()" target="_blank">' . $item->external_number .'&nbsp;&nbsp;<i class="fa fa-info-circle text-info fa-lg" aria-hidden="true"></i></a>';
    $item->info = $info;
    return $item;
});


return response()->json([
    'success' => 'Data loaded',
    'data' => $results,
    
]);
           
        } catch (Exception $ex) {
            return $ex;
        }
    } */

    public function getSalesInvoiceData(Request $request)
    {
        $branch_id_array = Session::get('branch_id_array');
        //dd($branch_id_array);
        $branch_ids = [];
        if (!empty($branch_id_array)) {
            foreach ($branch_id_array as $branch) {
                $branch_ids[] = $branch->branch_id;
            }
        }
        try {
            $pageNumber = ($request->start / $request->length) + 1;
            $pageLength = $request->length;
            $skip = ($pageNumber - 1) * $pageLength;
            $searchValue = request('search.value');

            $query = DB::table('sales_invoices')
                ->select(
                    'sales_invoices.external_number',
                    'sales_invoices.internal_number',
                    'manual_number',
                    'sales_invoice_Id',
                    'sales_invoices.order_date_time',
                    DB::raw("FORMAT(sales_invoices.total_amount, 2) as total_amount"),
                    'sales_orders.external_number AS sales_order_ref',
                    'sales_invoices.approval_status',
                    DB::raw("SUBSTRING(employees.employee_name, 1, 10) as employee_name"),
                    DB::raw("SUBSTRING(customers.customer_name, 1, 20) as customer_name"),
                    DB::raw("'Original' AS status"),
                    DB::raw("SUBSTRING(routes.route_name, 1, 10) as route_name"),
                    'is_reprint_allowed',
                    'is_printed'
                )
                ->leftJoin('employees', 'sales_invoices.employee_id', '=', 'employees.employee_id')
                ->leftJoin('customers', 'sales_invoices.customer_id', '=', 'customers.customer_id')
                ->leftJoin('routes', 'customers.route_id', '=', 'routes.route_id')
                ->leftJoin('sales_orders', function ($join) {
                    $join->on('sales_invoices.sales_order_Id', '=', 'sales_orders.sales_order_Id')
                        ->where('sales_invoices.sales_order_Id', '>', 0);
                })
                ->where('sales_invoices.document_number', '=', '210');



            // Add the whereIn clause only if the branch_id_array is not empty
            if (!empty($branch_ids)) {
                $query->whereIn('sales_invoices.branch_id', $branch_ids);
            }

            // Add search conditions if a search value is provided
            if (!empty($searchValue)) {
                $query->where(function ($query) use ($searchValue) {
                    $search_amount = str_replace(',', '', $searchValue);
                    $query->where('sales_invoices.external_number', 'like', '%' . $searchValue . '%')
                        ->orWhere('sales_invoices.manual_number', 'like', '%' . $searchValue . '%')
                        ->orWhere('sales_invoices.order_date_time', 'like', '%' . $searchValue . '%')
                        ->orWhere('sales_invoices.total_amount', 'like', '%' . $search_amount . '%')
                        ->orWhere('employees.employee_name', 'like', '%' . $searchValue . '%')
                        ->orWhere('customers.customer_name', 'like', '%' . $searchValue . '%')
                        ->orWhere('routes.route_name', 'like', '%' . $searchValue . '%');
                });
            }

            // Order the results
            $query->orderBy('internal_number', 'desc');

            // Paginate the results
            $results = $query->take($pageLength)->skip($skip)->get();

            $results->transform(function ($item) {
                $status = "Original";
                $buttons = '<button class="btn btn-success btn-sm" onclick="view(' . $item->sales_invoice_Id . ', \'' . $status . '\')"><i class="fa fa-eye" aria-hidden="true"></i></button>&#160';
                $buttons .= '<button class="btn btn-secondary btn-sm" onclick="salesinvoiceReportpage(' . $item->sales_invoice_Id . ')"><i class="fa fa-print" aria-hidden="true"></i></button>';

                $item->buttons = $buttons;
                $statusLabel = '<label class="badge badge-pill bg-success">' . $status . '</label>';
                $item->statusLabel = $statusLabel;

                $encodedManualNumber = $this->base64Encode($item->external_number);
                $info = '<a href="../sd/invoice_nfo?manual_number=' . $encodedManualNumber . '&action=inquery" onclick="updateTotal()" target="_blank">' . $item->external_number . '&nbsp;&nbsp;<i class="fa fa-info-circle text-info fa-lg" aria-hidden="true"></i></a>';

                $printed = '<input type="checkbox" class="form-check-input" disabled>';
                if ($item->is_printed == 1) {
                    $printed = '<input type="checkbox" class="form-check-input" checked disabled>';
                }
                $item->info = $info;
                $item->printed = $printed;
                return $item;
            });

            return response()->json([
                'success' => 'Data loaded',
                'data' => $results,
            ]);
        } catch (Exception $ex) {
            return $ex;
        }
    }



    //delete Sales Invoice
    public function deleteSI($id, $status)
    {
        try {
            if ($status == "Original") {
                $Sales_inv = sales_Invoice::find($id);
                if ($Sales_inv->delete()) {
                    $Sales_inv_item = sales_Invoice_item::where('sales_invoice_Id', '=', $id)->delete();;

                    return response()->json(["message" => "Deleted"]);
                } else {
                    return response()->json(["message" => "Not Deleted"]);
                }
            } else {
                $Sales_inv = sales_Invoice_draft::find($id);
                if ($Sales_inv->delete()) {
                    $Sales_inv_item = sales_Invoice_item_draft::where('sales_invoice_Id', '=', $id)->delete();

                    return response()->json(["message" => "Deleted"]);
                } else {
                    return response()->json(["message" => "Not Deleted"]);
                }
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function getEachSalesInvoice($id, $status)
    {
        try {
            if ($status == "Original") {
                $query = 'SELECT sales_invoices.*, customers.customer_name, customers.primary_address,customers.customer_code,towns.town_name
                          FROM sales_invoices
                          INNER JOIN customers ON sales_invoices.customer_id = customers.customer_id
                          LEFT JOIN towns ON customers.town = towns.town_id
                          WHERE sales_invoice_Id = :id';

                $result = DB::select($query, ['id' => $id]);

                if ($result) {
                    return response()->json(['success' => 'Data loaded', 'data' => $result]);
                } else {
                    return response()->json(['success' => 'Data loaded', 'data' => []]);
                }
            } else {
                $query = 'SELECT sales_invoice_drafts.*, customers.customer_name, customers.primary_address,customers.customer_code
                          FROM sales_invoice_drafts
                          INNER JOIN customers ON sales_invoice_drafts.customer_id = customers.customer_id
                          WHERE sales_invoice_Id = :id';

                $result = DB::select($query, ['id' => $id]);

                if ($result) {
                    return response()->json(['success' => 'Data loaded', 'data' => $result]);
                } else {
                    return response()->json(['success' => 'Data loaded', 'data' => []]);
                }
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //get each product
    public function getEachproductofSalesInv($id, $status)
    {
        try {
            if ($status == "Original") {
                $query = 'SELECT sales_invoice_items.*,items.Item_code FROM sales_invoice_items INNER JOIN items ON sales_invoice_items.item_id = items.item_id WHERE sales_invoice_items.sales_invoice_Id = "' . $id . '"';
                $result = DB::select($query);
                if ($query) {
                    return response()->json($result);
                } else {
                    return response()->json((['success' => 'Data loaded', 'data' => []]));
                }
            } else {
                $query = 'SELECT sales_invoice_items_drafts.*,items.Item_code FROM sales_invoice_items_drafts INNER JOIN items ON sales_invoice_items_drafts.item_id = items.item_id WHERE sales_invoice_items_drafts.sales_invoice_Id = "' . $id . '"';
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

    //get pending approvals
    public function getPendingapprovalsSalesInv()
    {
        try {
            $pendingAPprovals = 'SELECT
            sales_invoices.sales_invoice_Id,
            sales_invoices.external_number,
            sales_invoices.order_date_time,
            sales_invoices.total_amount,
            sales_invoices.approval_status,
            customers.customer_name,
            employees.employee_name
          FROM
            sales_invoices
          INNER JOIN
            customers
            ON sales_invoices.customer_id = customers.customer_id
          INNER JOIN
            employees
            ON sales_invoices.employee_id = employees.employee_id
          WHERE
            sales_invoices.approval_status = "Pending"';
            $result = DB::select($pendingAPprovals);
            if ($result) {
                return response()->json(['success' => 'Data loaded', 'data' => $result]);
            } else {
                return response()->json(['success' => 'Data loaded', 'data' => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //approve Sales invoice
    public function approveRequestSalesInv($id)
    {
        try {
            $approvedBy = Auth::user()->id;
            $sales_inv_data = sales_Invoice::find($id);
            $sales_inv_data->approval_status = "Approved";
            $sales_inv_data->approved_by = $approvedBy;
            if ($sales_inv_data->update()) {
                return response()->json((['status' => true]));
            } else {
                return response()->json((['status' => false]));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //reject Sales Invoice
    public function rejectRequestSalesInv($id)
    {
        try {
            $approvedBy = Auth::user()->id;
            $sales_inv_data = sales_Invoice::find($id);
            $sales_inv_data->approval_status = "Rejected";
            $sales_inv_data->approved_by = $approvedBy;
            if ($sales_inv_data->update()) {
                return response()->json((['status' => true]));
            } else {
                return response()->json((['status' => false]));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //get Sales Order Details For Invice
    public function getSalesOrderDetailsForInvice($branchID)
    {
        try {


            $query = "SELECT
            sales_orders.sales_order_Id,
            sales_orders.order_date_time,
            sales_orders.external_number,
            sales_orders.expected_date_time,
            sales_orders.employee_id,
            sales_orders.customer_id,
            customers.customer_name,
            employees.employee_name,
            SUM(sales_order_items.price * sales_order_items.quantity) AS amount
          FROM
            sales_orders
            INNER JOIN customers ON sales_orders.customer_id = customers.customer_id
            INNER JOIN employees ON sales_orders.employee_id = employees.employee_id
            INNER JOIN sales_order_items ON sales_orders.sales_order_id = sales_order_items.sales_order_id
          WHERE
            sales_orders.order_status_id = '1'  AND branch_id = '" . $branchID . "'
          GROUP BY
            sales_orders.sales_order_Id,
            sales_orders.order_date_time,
            sales_orders.external_number,
            sales_orders.expected_date_time,
            customers.customer_name
            ORDER BY external_number DESC";

            $block_result = [];
            $result = DB::select($query);
            foreach ($result as $row) {
                $customerId = $row->customer_id;
                $employeeId = $row->employee_id;
                $is_block = DB::select('CALL sd_customer_is_blocked(?, ?)', [$customerId, $employeeId]);
                $block_result[$customerId] = $is_block[0]->is_blocked;
            }

            // dd($block_result);
            if ($result) {
                return response()->json(['success' => 'Data loaded', 'data' => $result, 'block_data' => $block_result]);
            } else {
                return response()->json(['success' => 'Data Not loaded', 'data' => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //get order Items
    public function getorderItems($id, $date, $cus, $branch, $location)
    {
        try {
            $order = sales_order::find($id);
            $cus_id = $order->customer_id;
            $emp_id = $order->employee_id;
            $query = "SELECT sales_order_items.item_name,sales_order_items.item_id,sales_order_items.quantity,sales_order_items.free_quantity,
            sales_order_items.unit_of_measure,sales_order_items.package_unit,
            sales_order_items.package_size,sales_order_items.price,sales_order_items.discount_percentage,
            sales_order_items.discount_amount,items.Item_code,
            (
                SELECT IF(ISNULL(SUM(quantity-setoff_quantity)), 0, SUM(quantity-setoff_quantity)) AS balance
                   FROM item_history_set_offs
                   WHERE item_id = sales_order_items.item_id AND branch_id = $branch AND location_id = $location AND price_status = 0 AND quantity > 0
               ) AS Balance,
               (
                SELECT IF(ISNULL(SUM(quantity-setoff_quantity)), 0, SUM(quantity-setoff_quantity)) AS all_Balance
                   FROM item_history_set_offs
                   WHERE item_id = sales_order_items.item_id AND branch_id = $branch AND location_id = $location AND quantity > 0
               ) AS all_Balance,
               (SELECT IFNULL(sd_free_offerd_quantity('" . $cus . "',sales_order_items.item_id,sales_order_items.quantity,'" . $date . "'), 0) AS Offerd_quantity) AS system_free_quantity,
               items.supply_group_id
            FROM sales_order_items INNER JOIN items ON sales_order_items.item_id = items.item_id WHERE sales_order_items.sales_order_Id ='" . $id . "'";

            $result = DB::select($query);

            $count_qry = "SELECT COUNT(*) as order_count 
            FROM sales_orders SO
            WHERE order_status_id = 1 AND branch_id = $branch AND SO.order_status_id = 1 AND SO.employee_id = $emp_id AND SO.customer_id = $cus_id
            GROUP BY customer_id, employee_id
            HAVING COUNT(*) > 1";
            $count = DB::select($count_qry);
            $count_num = 0;
            if ($count) {
                $count_num = $count[0]->order_count;
            }
            if ($result) {
                return response()->json(['success' => 'Data loaded', 'data' => $result, 'count' => $count_num]);
            } else {
                return response()->json(['success' => 'Data loaded', 'data' => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //get header details
    public function getheaderDetails($id)
    {
        try {
            $query = "SELECT sales_orders.customer_id,sales_orders.branch_id,sales_orders.external_number,discount_percentage,discount_amount,customers.customer_name,employee_id, customers.primary_address,customers.customer_code,location_id,sales_orders.payment_term_id FROM sales_orders INNER JOIN customers ON sales_orders.customer_id = customers.customer_id  WHERE  sales_orders.sales_order_Id = '" . $id . "'";
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

    /* public function getItemInfo($Item_id)
{
    try {
        $info = item::find($Item_id);
        
        if ($info) {
            return response()->json([$info]);
        }
    } catch (Exception $ex) {
        return $ex;
    }
} */

    public function getItemInfoFoSI($Item_id, $item_branch_id, $item_location_id)
    {
        try {
            $query = "SELECT
            IT.unit_of_measure,
            IT.item_Name,
            IT.average_cost_price,
            IT.package_size,
            IT.package_unit,
            (SELECT IF(ISNULL(SUM(quantity-setoff_quantity)), 0, SUM(quantity-setoff_quantity)) FROM item_history_set_offs WHERE item_id='" . $Item_id . "' AND branch_id='" . $item_branch_id . "' AND location_id='" . $item_location_id . "' AND quantity>0) AS Balance
        FROM
            items IT
        WHERE
            IT.item_id = '" . $Item_id . "';
        ";
            $result = DB::select($query);
            //  $info = item::find($Item_id);
            if ($result) {
                return response()->json($result);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }



    public function getItemHistorySetoffBatch($branchID, $item_id, $location_id)
    {
        try {
            ini_set('max_execution_time', '0'); // for infinite time of execution
            $query = "SELECT
            item_history_setoff_id,
            CONCAT(batch_number,'/',external_number) AS batch_number,
            item_id,
            quantity - setoff_quantity AS AvlQty,
            cost_price,
            whole_sale_price,
            retial_price
        FROM
            item_history_set_offs
        WHERE
            branch_id = '" . $branchID . "'
            AND item_id = '" . $item_id . "'
            AND location_id = '" . $location_id . "'
            AND price_status = 0
            AND quantity - setoff_quantity > 0 AND quantity > 0 ORDER BY item_history_setoff_id ASC";
            $result = DB::select($query);
            if ($result) {
                return response()->json(['success' => 'Data loaded', 'data' => $result]);
            } else {
                return response()->json(['Error' => 'Data Not loaded', 'data' => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    /*  public function getItemHistorySetoff */
    public function getItemHistorySetoffBatch01($branchID, $item_id, $location_id)
    {

        ini_set('max_execution_time', '0'); // for infinite time of execution
        $query = "SELECT
            item_history_setoff_id,
            batch_number,
            item_id,
            quantity - setoff_quantity AS AvlQty,
            cost_price,
            whole_sale_price,
            retial_price
        FROM
            item_history_set_offs
        WHERE
            branch_id = '" . $branchID . "'
            AND item_id = '" . $item_id . "'
            AND location_id = '" . $location_id . "'
            AND price_status = 0
            AND quantity - setoff_quantity > 0 AND quantity > 0  ORDER BY item_history_setoff_id ASC";
        $result = DB::select($query);
        return $result;
    }



    public function getItemsForIncoiceTotable(Request $request, $branchID_, $orderID, $date, $location)
    {
        // dd('ddddd');
        try {
            $resultsArray = [];
            $collection = json_decode($request->get('Item_ids'));
            $order_ID = $orderID;

            // Prepare an array to store the item IDs
            // $gg = $collection[0]; 
            $itemIDs = [];
            $quantity_ = 0;

            foreach ($collection as $i) {
                $decode_collection = json_decode($i);
                $quantity_ = $decode_collection->qty;
                array_push($itemIDs, $decode_collection->id);
            }

            // Create a comma-separated string of item IDs for the IN clause
            $itemIDsString = implode(',', $itemIDs);

            ini_set('max_execution_time', '0'); // for infinite time of execution


            $query = "SELECT 
           SOI.item_name,
           SOI.item_id,
           SOI.quantity,
           SOI.unit_of_measure,
           SOI.package_unit,
           SOI.package_size,
           SOI.price,
           SOI.discount_percentage,
           SOI.discount_amount,
           IT.Item_code,
           SO.customer_id,
           SO.location_id,
           SO.branch_id,
           (SELECT IFNULL(sd_free_offerd_quantity(SO.customer_id,SOI.item_id,SOI.quantity,'" . $date . "'), 0) AS Offerd_quantity) AS free_quantity,
           (
            SELECT IF(ISNULL(SUM(quantity-setoff_quantity)), 0, SUM(quantity-setoff_quantity)) AS balance
               FROM item_history_set_offs
               WHERE item_id = SOI.item_id AND branch_id = '" . $branchID_ . "' AND location_id = '" . $location . "' AND price_status = 0 AND item_history_set_offs.quantity > 0 
           ) AS Balance
       FROM 
           sales_order_items SOI
           INNER JOIN items IT ON SOI.item_id = IT.item_id
           INNER JOIN sales_orders SO ON SO.sales_order_Id = SOI.sales_order_Id 
       WHERE 
           SO.sales_order_Id = '" . $order_ID . "'
           AND SOI.item_id IN (" . $itemIDsString . ");
       ";


            $result = DB::select($query);
            foreach ($result as $res) {
                $res->setOffData = $this->getItemHistorySetoffBatch01($branchID_, $res->item_id, $location);
                foreach ($collection as $i) {
                    $decode_collection_ = json_decode($i);
                    if ($res->item_id == $decode_collection_->id) {
                        $res->quantity = $decode_collection_->qty;
                        $res->free_quantity = $decode_collection_->foc;
                        break;
                    }
                }
            }
            if ($result) {
                $resultsArray = $result; // No need to loop through and append to $resultsArray
            }

            return response()->json(['success' => 'Data loaded', 'data' => $resultsArray]);
        } catch (Exception $ex) {

            return $ex;
        }
    }

    //get payment methods
    public function getPaymentMethods()
    {
        try {
            // $paymentMethod = supplierPaymentMethod::all();
            $paymentMethod = CustomerPaymentMode::all();
            if ($paymentMethod) {
                return response()->json($paymentMethod);
            } else {
                return response()->json(['status' => false]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //get foc_qty,offer data to theshold foc calculation
    public function getItem_foc_threshold_ForInvoice($cus_id, $item_id, $entered_qty, $date)
    {

        try {

            $query = "SELECT IFNULL(sd_free_offerd_quantity('" . $cus_id . "','" . $item_id . "','" . $entered_qty . "','" . $date . "'), 0) AS Offerd_quantity";
            $result = DB::select($query);
            if ($result) {
                return response()->json($result);
            } else {
                return response()->json(['error' => 'Data not loaded', 'data' => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //get rep code
    public function get_rep_code($id)
    {
        try {
            $query = "SELECT code FROM employees WHERE employee_id = $id";
            $result = DB::Select($query);
            if ($result) {
                return response()->json(['success' => 'Data loaded', 'data' => $result]);
            } else {
                return response()->json(['error' => 'Data not loaded', 'data' => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //get rep code
    public function get_branch_code($id)
    {
        try {
            $query = "SELECT code FROM branches WHERE branch_id = $id";
            $result = DB::Select($query);
            if ($result) {
                return response()->json(['success' => 'Data loaded', 'data' => $result]);
            } else {
                return response()->json(['error' => 'Data not loaded', 'data' => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //allow reprint
    public function allowReportin($id)
    {
        try {
            $invoice = sales_Invoice::find($id);
            if ($invoice) {
                if ($invoice->is_reprint_allowed == 0) {
                    $is_exist = reprint_request::where("sales_invoice_Id", "=", $invoice->sales_invoice_Id)
                        ->where("request_status", "!=", 2)
                        ->exists();
                    if (!$is_exist) {
                        $request_by = Auth::user()->id;
                        $rqst = new reprint_request();
                        $rqst->sales_invoice_Id = $invoice->sales_invoice_Id;
                        $rqst->customer_id = $invoice->customer_id;
                        $rqst->request_branch_id = 3; //need to edit
                        $rqst->requested_by = $request_by;
                        if ($rqst->save()) {
                            return response()->json(['status' => true, 'message' => 'granted']);
                        } else {
                            return response()->json(['status' => false]);
                        }
                    } else {
                        return response()->json(['status' => false, 'message' => 'exist']);
                    }
                } else {
                    return response()->json(['status' => false, 'message' => 'already_granted']);
                }
            }





            /* if ($invoice->is_reprint_allowed == 0) {
                $invoice->is_reprint_allowed = 2;
                if ($invoice->update()) {
                    return response()->json(['status' => true, 'message' => 'granted']);
                } else {
                    return response()->json(['status' => false]);
                }
            } else {
                if($invoice->is_reprint_allowed != 2){
                    $invoice->is_reprint_allowed = 0;
                    if ($invoice->update()) {
                        return response()->json(['status' => true, 'message' => 'revoked']);
                    } else {
                        return response()->json(['status' => false]);
                    }

                }
               
            } */
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //get invoice data to re-print

    public function load_invoice_details_reprint($number)
    {
        $inv_data_header_qry = 'SELECT SI.sales_invoice_Id,SI.external_number, SI.order_date_time,SI.is_reprint_allowed, B.branch_name, L.location_name, C.customer_name, E.employee_name,DL.amount,DL.paidamount, (DL.amount - DL.paidamount) as balance, SO.external_number as so_number,SO.order_date_time as s_order_date,DATEDIFF(SI.order_date_time,SO.order_date_time) AS date_gap
        FROM sales_invoices SI 
        LEFT JOIN branches B ON SI.branch_id = B.branch_id 
        LEFT JOIN locations L ON SI.location_id = L.location_id 
        LEFT JOIN customers C ON SI.customer_id = C.customer_id  
        LEFT JOIN employees E ON SI.employee_id = E.employee_id
        LEFT JOIN debtors_ledgers DL ON SI.external_number = DL.external_number
        LEFT JOIN sales_orders SO ON SI.sales_order_Id = SO.sales_order_Id
        WHERE SI.manual_number =' . $number;

        $result = DB::select($inv_data_header_qry);




        return response()->json(["header" => $result]);
    }

    //get re-print request
    public function get_reprint_request()
    {

        $val = 0;
        $reprint_data = DB::select('SELECT R.reprint_requests_id,SI.manual_number,SI.order_date_time,SI.total_amount,
        CONCAT(C.customer_name,"-",C.customer_code) AS customer_name,RO.route_name,U.name,E.employee_name,
        BR.branch_name FROM reprint_requests R INNER JOIN sales_invoices SI ON 
        R.sales_invoice_Id = SI.sales_invoice_Id INNER JOIN customers C ON SI.customer_id = C.customer_id 
        INNER JOIN users U ON R.requested_by = U.id INNER JOIN employees E ON SI.employee_id = E.employee_id 
        LEFT JOIN branches BR ON R.request_branch_id = BR.branch_id LEFT JOIN routes RO ON C.route_id = RO.route_id 
        WHERE R.request_status = 0 ORDER BY reprint_requests_id DESC');
        if ($reprint_data) {
            return response()->json(["status" => true, "data" => $reprint_data]);
        } else {
            return response()->json(["status" => true, "data" => []]);
        }
    }


    //approve request
    public function approve_request($id)
    {
        try {
            $request = reprint_request::find($id);
            if ($request->request_status == 0) {
                $request->request_status = 1;
                if ($request->update()) {
                    $invoice = sales_Invoice::find($request->sales_invoice_Id);
                    if ($invoice->is_reprint_allowed == 0) {
                        $invoice->is_reprint_allowed = 1;
                        $invoice->update();
                        return response()->json(['status' => true, 'message' => 'granted']);
                    }
                }
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //reject request
    public function reject_request($id)
    {
        try {
            $request = reprint_request::find($id);
            if ($request->request_status == 0) {
                $request->request_status = 2;
                $request->update();
                return response()->json(['status' => true, 'message' => 'rejected']);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //encode
    private function base64Encode($str)
    {
        return base64_encode(rawurlencode($str));
    }

    //load items to invoice according to supply group 
    public function loadItemsforsalesinvoice(Request $request, $sup_id)
    {
        $val = 1;
        try {
            /* $items = item::all(); */
            $branch_ = $request->input('branch');
            $location_ = $request->input('location');

            $items = [];
            if ($sup_id > 0) {
                /*   $qry = "SELECT it.item_id, it.Item_code, it.item_Name,SG.supply_group,
                sd_item_stock_balance(it.item_id, ".$branch_.", ".$location_.") AS stock_balance FROM items it 
                     LEFT JOIN supply_groups SG ON it.supply_group_id = SG.supply_group_id 
                     WHERE it.is_active = 1 AND SG.supply_group_id = " . $sup_id; */
                // dd($qry);
                /*  $items = DB::select("SELECT it.item_id, it.Item_code, it.item_Name,SG.supply_group,
                sd_item_stock_balance(it.item_id, ".$branch_.", ".$location_.") AS stock_balance FROM items it 
                     LEFT JOIN supply_groups SG ON it.supply_group_id = SG.supply_group_id 
                     WHERE it.is_active = 1 AND SG.supply_group_id = " . $sup_id); */

                /* $items = DB::select("SELECT it.item_id,it.Item_code,it.item_Name FROM items it"); */
                /*   $items = DB::select("SELECT it.item_id, it.Item_code, it.item_Name, SG.supply_group,
                (SELECT COALESCE(quantity, 0) AS stock_balance FROM stock_balances WHERE branch_id = ? AND location_id = ? AND item_id = it.item_id) AS stock_balance
                FROM items it 
                LEFT JOIN supply_groups SG ON it.supply_group_id = SG.supply_group_id 
                WHERE it.is_active = 1 AND SG.supply_group_id = ?", [$branch_, $location_, $sup_id]); */

                $items = DB::select("SELECT it.item_id, it.Item_code, it.item_Name, SG.supply_group,
(SELECT 
    GREATEST(SUM(COALESCE(quantity, 0)), 0) AS stock_balance 
FROM 
    stock_balances 
WHERE 
    branch_id = ? 
    AND location_id = ? 
    AND item_id = it.item_id 
GROUP BY 
    branch_id, location_id, item_id
) AS stock_balance
FROM items it 
LEFT JOIN supply_groups SG ON it.supply_group_id = SG.supply_group_id 
WHERE it.is_active = 1 AND SG.supply_group_id = ?", [$branch_, $location_, $sup_id]);
            } else {
                //$items = DB::select("SELECT it.item_id,it.Item_code,it.item_Name,sd_item_stock_balance(it.item_id, ".$branch_.", ".$location_.") AS stock_balance FROM items it WHERE is_active = 1");
                $items = DB::select("SELECT it.item_id, it.Item_code, it.item_Name, SG.supply_group,
                (SELECT COALESCE(quantity, 0) AS stock_balance FROM stock_balances WHERE branch_id = ? AND location_id = ? AND item_id = it.item_id) AS stock_balance
                FROM items it 
                LEFT JOIN supply_groups SG ON it.supply_group_id = SG.supply_group_id 
                WHERE it.is_active = 1", [$branch_, $location_]);
            }

            // dd($items);
            $collection = [];
            foreach ($items as $item) {
                array_push($collection, ["hidden_id" => $item->item_id, "id" =>  $item->item_Name, "value" =>  $item->Item_code, "value2" => (int)$item->stock_balance, "collection" => [$item->item_id, $item->item_Name, $item->Item_code]]);
            }
            return response()->json(['success' => true, 'data' => $collection]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function loadReturnRequest($cus_id)
    {
        try {
            $qry = "SELECT SRR.request_date_time,SRR.employee_id,I.Item_code,I.item_Name,I.package_unit, E.employee_name,
            SRRI.quantity,SRRI.sfa_return_request_items_id,SRRI.item_id
             FROM sfa_return_request SRR INNER JOIN sfa_return_request_items SRRI ON SRR.sfa_return_request_Id = SRRI.sfa_return_request_Id 
             INNER JOIN items I ON SRRI.item_id = I.item_id INNER JOIN employees E ON SRR.employee_id = E.employee_id 
             WHERE SRR.customer_id = $cus_id AND SRRI.invoiced = 0";

            $result = DB::select($qry);
            // dd($result);
            if ($result) {
                return response()->json(["status" => true, "data" => $result]);
            } else {
                return response()->json(["status" => true, "data" => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function saveCustomerReceipt($invoice, $type, $additionalData)
    {
        try {
            //dd("dddd");
            $br_id = $invoice->branch_id;
            $branchdata = DB::table('branches')->where('branch_id', $br_id)->get();
            //dd($branchdata);
            $customer_receipt = new CustomerReceipt();
            $customer_receipt->internal_number = IntenelNumberController::getNextID();
            $customer_receipt->external_number = $branchdata[0]->prefix . "-" . $this->createExternal_number($invoice->branch_id);
            $customer_receipt->branch_id = $invoice->branch_id;
            $customer_receipt->customer_id = $invoice->customer_id;
           // $customer_receipt->collector_id = Auth::user()->id;
            $customer_receipt->cashier_id = $invoice->employee_id;
            $customer_receipt->receipt_date = date('Y-m-d');
            $customer_receipt->receipt_method_id = $type;
            $customer_receipt->gl_account_id = 0; // need to change
            //dd($type);
            if ($type === 1) {

                $customer_receipt->amount = $additionalData->cash_amount;
            } else if ($type === 2) {
                $customer_receipt->amount = $additionalData->cheque_amount;
            } else if ($type === 8) {
                $customer_receipt->amount = $additionalData->card_amount;
            } else if ($type === 7) {
                $customer_receipt->amount = $additionalData->bank_transfer_amount;
            }
            $customer_receipt->discount = 0;

            $customer_receipt->round_up = 0;
            $customer_receipt->advance = 0;
            $customer_receipt->document_number = 500;

            //dd( $customer_receipt);

            if ($customer_receipt->save()) {


                /* $dl = DebtorsLedger::find($data->debtors_ledger_id);
                    $dl_amount = $dl->amount; */
                //$bal_amount = floatval($data->dl_amount) - floatval($data->paidamount);
                /*    $bal_amount = floatval($dl_amount) - floatval($data->set_off_amount); */
                $dl_id = DebtorsLedger::where("external_number", "=", $invoice->external_number)->first();
                $customer_receipt_set_off_data = new CustomerReceiptSetoffData();
                $customer_receipt_set_off_data->customer_receipt_id = $customer_receipt->customer_receipt_id;
                $customer_receipt_set_off_data->internal_number = $customer_receipt->internal_number;
                $customer_receipt_set_off_data->external_number = $customer_receipt->external_number;
                $customer_receipt_set_off_data->reference_internal_number = $invoice->internal_number; //debtors leger details
                $customer_receipt_set_off_data->reference_external_number = $invoice->external_number;
                $customer_receipt_set_off_data->reference_document_number = $invoice->document_number;

                if ($type === 1) {
                    $customer_receipt_set_off_data->amount = $invoice->total_amount;
                    $customer_receipt_set_off_data->paid_amount = $dl_id->paidamount;
                    $customer_receipt_set_off_data->return_amount = $dl_id->return_amount;
                    $customer_receipt_set_off_data->set_off_amount = $additionalData->cash_amount;
                } else if ($type === 2) {
                    $customer_receipt_set_off_data->amount = $invoice->total_amount;
                    $customer_receipt_set_off_data->paid_amount = $dl_id->paidamount;
                    $customer_receipt_set_off_data->return_amount = $dl_id->return_amount;
                    $customer_receipt_set_off_data->set_off_amount = $additionalData->cheque_amount;
                } else if ($type === 8) {

                    $customer_receipt_set_off_data->amount = $invoice->total_amount;
                    $customer_receipt_set_off_data->paid_amount = $dl_id->paidamount;
                    $customer_receipt_set_off_data->return_amount = $dl_id->return_amount;
                    $customer_receipt_set_off_data->set_off_amount = $additionalData->card_amount;
                } else if ($type === 7) {

                    $customer_receipt_set_off_data->amount = $invoice->total_amount;
                    $customer_receipt_set_off_data->paid_amount = $dl_id->paidamount;
                    $customer_receipt_set_off_data->return_amount = $dl_id->return_amount;
                    $customer_receipt_set_off_data->set_off_amount = $additionalData->bank_transfer_amount;
                }





                /*  $customer_receipt_set_off_data->amount = $invoice->total_amount; */
                /*  $customer_receipt_set_off_data->paid_amount = $invoice->total_amount; */
                $customer_receipt_set_off_data->balance = $customer_receipt_set_off_data->amount - $customer_receipt_set_off_data->paid_amount;
                /*   $customer_receipt_set_off_data->set_off_amount = $invoice->total_amount; */
                $customer_receipt_set_off_data->debtors_ledger_id = $dl_id->debtors_ledger_id;
                $customer_receipt_set_off_data->date = date('Y-m-d');


                if ($customer_receipt_set_off_data->save()) {
                    $dl_id->paidamount = $dl_id->paidamount +  $customer_receipt_set_off_data->set_off_amount;
                    $dl_id->update();


                    //add new dl record
                    $customer = Customer::find($customer_receipt->customer_id);
                    $deb_ledger = new DebtorsLedger();
                    $deb_ledger->internal_number =  $customer_receipt->internal_number;
                    $deb_ledger->external_number = $customer_receipt->external_number;
                    $deb_ledger->document_number = $customer_receipt->document_number;
                    $deb_ledger->trans_date = $customer_receipt->receipt_date;
                    $deb_ledger->description = "Customer Receipt";
                    $deb_ledger->branch_id = $customer_receipt->branch_id;
                    $deb_ledger->customer_id = $customer_receipt->customer_id;
                    $deb_ledger->customer_code = $customer->customer_code;
                    /*  $deb_ledger->amount = -$cus_recpt->amount;
                                    $deb_ledger->paidamount = -$cus_recpt->amount; */
                    if ($deb_ledger->save()) {
                        $deb_ledger_setOff = new DebtorsLedgerSetoff();
                        $deb_ledger_setOff->internal_number =  $customer_receipt->internal_number;
                        $deb_ledger_setOff->external_number = $customer_receipt->external_number;
                        $deb_ledger_setOff->document_number = $customer_receipt->document_number;
                        $deb_ledger_setOff->reference_internal_number = $customer_receipt_set_off_data->reference_internal_number;
                        $deb_ledger_setOff->reference_external_number = $customer_receipt_set_off_data->reference_external_number;
                        $deb_ledger_setOff->reference_document_number = $customer_receipt_set_off_data->reference_document_number;
                        $deb_ledger_setOff->trans_date = $deb_ledger->trans_date;
                        $deb_ledger_setOff->description = $deb_ledger->description;
                        $deb_ledger_setOff->branch_id =  $deb_ledger->branch_id;
                        $deb_ledger_setOff->customer_id = $deb_ledger->customer_id;
                        $deb_ledger_setOff->customer_code = $deb_ledger->customer_code;
                        $deb_ledger_setOff->amount = -$customer_receipt_set_off_data->set_off_amount;
                        $deb_ledger_setOff->save();
                    }
                }

                if ($type == 7 && $additionalData != null) {
                    $this->CustomerReceiptsaveBankSLip($customer_receipt, $additionalData);
                }

                if ($type == 2 && $additionalData != null) {
                    $this->saveCustomerReceiptCheque($customer_receipt, $additionalData);
                }
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    private function CustomerReceiptsaveBankSLip($receipt, $paymentSlip)
    {
        try {
            //  DB::beginTransaction();



            //$slip_data = json_decode($data);
            //dd($$data);
            $slip = new CustomerReceiptBankSlip();
            $slip->customer_receipt_id = $receipt->customer_receipt_id;
            $slip->internal_number = $receipt->internal_number;
            $slip->external_number = $receipt->external_number;
            $slip->reference = $paymentSlip->bank_transfer_reference;
            //$slip->slip_time = $paymentSlip->slip_time;
            $slip->slip_date = $paymentSlip->bank_transfer_date;
            $slip->save();



            // DB::commit(); 
        } catch (Exception $ex) {
            //  DB::rollBack();
            return $ex;
        }
    }


    private function saveCustomerReceiptCheque($receipt, $receiptCheque)
    {
        /*  $Payment->cheque_amount = $chequeData->chequeAmount ?: null;
        $Payment->cheque_no = $chequeData->chequeNo ?: null;
        $Payment->cheque_date = $chequeData->chqDate ?: null;
        $Payment->cheque_Bank_id = $chequeData->bankId ?: null;
        $Payment->cheque_bank_branch_id = $chequeData->bankbranchId ?: null; */

        try {
            //   DB::beginTransaction();
            $bankObj = bank::find($receiptCheque->cheque_Bank_id);
            $cheque = new CustomerReceiptCheque();
            $cheque->customer_receipt_id = $receipt->customer_receipt_id;
            $cheque->internal_number = $receipt->internal_number;
            $cheque->external_number = $receipt->external_number;
            $cheque->bank_code = $bankObj->bank_code;
            $cheque->cheque_referenceNo = 0;
            $cheque->cheque_number = $receiptCheque->cheque_no;
            $cheque->customer_cheque_reference_number = ChequeReferenceNumberController::customerChequeReferenceGenerator();
            $cheque->banking_date = $receiptCheque->cheque_date;
            $cheque->amount = $receiptCheque->cheque_amount;
            $cheque->bank_id = $receiptCheque->cheque_Bank_id;
            $cheque->bank_branch_id = $receiptCheque->cheque_bank_branch_id;
            $cheque->cheque_status = 0;
            $cheque->save();
            //dd($cheque);




            //  DB::commit();
        } catch (Exception $ex) {
            //  DB::rollBack();
            //array_push($this->response_data, $ex);
            return $ex;
        }
    }

    public function createExternal_number($branch)
    {
        /* $exter_num = $this->reference_number->CustomerReceipt_referenceID('customer_receipts', 500); */
        $exter_num = ReferenceIdController::CustomerReceipt_referenceID_sales_invoice_cash('customer_receipts', 500, $branch);
        $id_ = $exter_num['id'];
        $prefix_ = $exter_num['prefix'];

        $pattern = [
            1 => "0000",
            2 => "000",
            3 => "00",
            4 => "0",

        ];
        $id_len = strlen($id_);
        return $prefix_ . $pattern[$id_len] . $id_;
    }

    public function loadSalesReturns($customerID)
    {
        try {
            $return_query = "SELECT
            DL.debtors_ledger_id,
	DL.external_number,
	DL.trans_date,
	DL.amount,
	DL.debtors_ledger_id,
	( DL.amount - DL.paidamount ) AS balance 
FROM
	debtors_ledgers DL
	
WHERE
	DL.amount <> DL.paidamount AND DL.document_number = 220
	AND DL.customer_id = $customerID";

            $result = DB::select($return_query);

            if ($result) {
                return response()->json([
                    "status" => true,
                    "data" => $result
                ]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function loadBankData($bankCode, $branchCode)
    {
        try {
            $bank = bank::where("bank_code", "=", $bankCode)->get();
            $branch = bank_branch::where("bank_branch_code", "=", $branchCode)->get();
            return response()->json([
                "status" => true,
                "bank" => $bank->bank_id,
                "branch" => $branch->bank_branch_id
            ]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    private function save_return_setoff($setoffData, $invoice)
    {
        try {
            foreach ($setoffData as $data) {
                $data_ = json_decode($data);
                $dl_id = $data_->debtors_ledger_id; // Use array syntax
                $set_off_amount = $data_->amount;; // Use array syntax

                // Updating DL amount
                $debtor_ledger = DebtorsLedger::find($dl_id);
                $debtor_ledger->paidamount = floatval($debtor_ledger->paidamount) - abs(floatval($set_off_amount));
                $debtor_ledger->update();

                // Updating reference DL paid amount
                $debtor_ledger_sales_invoice = DebtorsLedger::where('internal_number', $invoice->internal_number)->first();
                $debtor_ledger_sales_invoice->paidamount = floatval($debtor_ledger_sales_invoice->paidamount) + floatval($set_off_amount);
                $debtor_ledger_sales_invoice->return_amount = floatval($debtor_ledger_sales_invoice->return_amount) + floatval($set_off_amount);

                if ($debtor_ledger_sales_invoice->update()) {
                    $retun_obj = sales_return::where('external_number', $debtor_ledger->external_number)->first();
                    $sales_return_debtor_setoff_obj = new sales_return_debtor_setoff();
                    $sales_return_debtor_setoff_obj->debtors_ledger_id = $debtor_ledger_sales_invoice->debtors_ledger_id;
                    $sales_return_debtor_setoff_obj->internal_number = $invoice->internal_number;
                    $sales_return_debtor_setoff_obj->external_number = $invoice->external_number;
                    $sales_return_debtor_setoff_obj->sales_return_Id = $retun_obj->sales_return_Id;
                    $sales_return_debtor_setoff_obj->setoff_amount = $set_off_amount;

                    if ($sales_return_debtor_setoff_obj->save()) {
                        $cus = Customer::find($retun_obj->customer_id);
                        $dl_set_off = new DebtorsLedgerSetoff();
                        $dl_set_off->internal_number = $retun_obj->internal_number;
                        $dl_set_off->external_number = $retun_obj->external_number;
                        $dl_set_off->document_number = $retun_obj->document_number;
                        $dl_set_off->reference_internal_number = $debtor_ledger_sales_invoice->internal_number;
                        $dl_set_off->reference_external_number = $debtor_ledger_sales_invoice->external_number;
                        $dl_set_off->reference_document_number = $debtor_ledger_sales_invoice->document_number;
                        $dl_set_off->trans_date = $retun_obj->order_date;
                        $dl_set_off->description = "Sales returned from " . $cus->customer_name;
                        $dl_set_off->branch_id = $retun_obj->branch_id;
                        $dl_set_off->customer_id = $retun_obj->customer_id;
                        $dl_set_off->customer_code = $cus->customer_code;
                        $dl_set_off->amount = -$set_off_amount;
                        $dl_set_off->save();
                    }
                }
            }
        } catch (\Exception $e) {
            // Log or handle exception here
            throw $e;
        }
    }

    public function getInvoicePaymentDetails($invoiceID)
    {
        try {
            $Returnquery = "
SELECT 
    DATE(SRDS.created_at) AS created_at,
    SRDS.external_number,
    SR.total_amount,
    SRDS.setoff_amount
FROM
    sales_invoices SI
    LEFT JOIN sales_return_debtor_setoffs SRDS ON SI.internal_number = SRDS.internal_number
    LEFT JOIN debtors_ledgers DL ON SRDS.debtors_ledger_id = DL.debtors_ledger_id
    LEFT JOIN sales_returns SR ON SRDS.internal_number = SR.internal_number
WHERE 
    SI.sales_invoice_Id = :invoiceID";

            $Returnresult = DB::select($Returnquery, ['invoiceID' => $invoiceID]);

            $Paymentquery = "SELECT * FROM sales_invoice_payments WHERE sales_invoice_id = :invoiceID";
            $Paymentresult = DB::select($Paymentquery, ['invoiceID' => $invoiceID]);

            return response()->json([
                'payment' => $Paymentresult,
                'return' => $Returnresult
            ]);

          
        } catch (\Exception $e) {
            return $e;
        } 
    }
}
