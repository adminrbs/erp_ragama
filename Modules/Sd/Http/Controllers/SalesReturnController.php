<?php

namespace Modules\Sd\Http\Controllers;

use App\Http\Controllers\IntenelNumberController;
use App\Http\Controllers\price_status_controller;
use App\Http\Controllers\ReferenceIdController;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Md\Database\Seeders\customer;
use Modules\Sd\Entities\book;
use Modules\Sd\Entities\DebtorsLedger;
use Modules\Sd\Entities\DebtorsLedgerSetoff;
use Modules\Sd\Entities\item;
use Modules\Sd\Entities\item_history;
use Modules\Sd\Entities\item_history_setOff;
use Modules\Sd\Entities\ItemHistorySetOff;
use Modules\Sd\Entities\return_transfer;
use Modules\Sd\Entities\return_transfer_item;
use Modules\Sd\Entities\sales_Invoice;
use Modules\Sd\Entities\sales_Invoice_draft;
use Modules\Sd\Entities\sales_Invoice_item;
use Modules\Sd\Entities\sales_Invoice_item_draft;
use Modules\Sd\Entities\SalesReturnReson;
use Modules\Sd\Entities\sales_return;
use Modules\Sd\Entities\sales_return_debtor_setoff;
use Modules\Sd\Entities\sales_return_item;

class SalesReturnController extends Controller
{
    public function addSalesReturn(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $wholeSalePrice = 0;
            $retail_price = 0;
            $cost_price = 0;

            $referencenumber = $request->input('LblexternalNumber');
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
            $result = DB::select('CALL sd_next_sales_returns(?, ?, ?)', [$branch_code, $code, 2]);
            $manual_number = 0;
            if ($result) {
                $manual_number = $result[0]->next_manual_number;
            }

            $PreparedBy = Auth::user()->id;
            $collection = json_decode($request->input('collection'));
            $set_off_array = json_decode($request->input('setoff_array'));
            //dd($set_off_array);
            $Sales_invoice = new sales_return();
            $Sales_invoice->internal_number = IntenelNumberController::getNextID();
            $Sales_invoice->external_number = $externalNumber;
            $Sales_invoice->sales_invoice_id = $request->input('sales_invoice_id');
            $Sales_invoice->order_date = $request->input('invoice_date_time');
            $Sales_invoice->branch_id = $request->input('cmbBranch');
            $Sales_invoice->location_id = $request->input('cmbLocation');
            $Sales_invoice->employee_id = $request->input('cmbEmp');
            $Sales_invoice->customer_id = $request->input('customerID');
            $Sales_invoice->total_amount = $request->input('grandTotal');
            /* $Purchase_order->Approval_status = $request->input('Approval_status'); */
            $Sales_invoice->discount_percentage = $request->input('txtDiscountPrecentage');
            $Sales_invoice->discount_amount = $request->input('txtDiscountAmount');

            $Sales_invoice->document_number = 220;
            $Sales_invoice->remarks = $request->input('txtRemarks');

            $Sales_invoice->return_reason_id = $request->input('cmbReason');
            $Sales_invoice->manual_number = $manual_number;
            $Sales_invoice->prepaired_by = $PreparedBy;
            $Sales_invoice->book_id = $request->input('book_number');
            $Sales_invoice->page_number = $request->input('page_number');
            $Sales_invoice->your_reference_number = $request->input('txtyourreferencenumber');
            $Sales_invoice->sales_analyst_id = $request->input('sales_analyst_id');
            $CU_ID = $Sales_invoice->customer_id;
            $cus_name_ = DB::select("SELECT customer_name FROM customers WHERE customer_id = $CU_ID");

            if ($Sales_invoice->save()) {

                //looping ifrst array
                foreach ($collection as $i) {
                    $item = json_decode($i);

                    $SI_item = new sales_return_item();
                    $SI_item->sales_return_Id = $Sales_invoice->sales_return_Id;
                    $SI_item->internal_number = $Sales_invoice->internal_number;
                    $SI_item->external_number = $Sales_invoice->external_number; // need to change
                    $SI_item->item_id = $item->item_id;
                    $SI_item->item_name = $item->item_name;
                    $SI_item->quantity = $item->qty;

                    if ($item->free_quantity) {
                        $SI_item->free_quantity = $item->free_quantity;
                    } else {
                        $SI_item->free_quantity = 0;
                    }

                    if ($item->uom) {
                        $SI_item->unit_of_measure = $item->uom;
                    } else {
                        $SI_item->unit_of_measure = 0;
                    }

                    if ($item->PackUnit) {
                        $SI_item->package_unit = $item->PackUnit;
                    } else {
                        $SI_item->package_unit = 0;
                    }

                    if ($item->PackSize) {
                        $SI_item->package_size = $item->PackSize;
                    } else {
                        $SI_item->package_size = 0;
                    }

                    if ($item->price) {
                        $SI_item->price = $item->price;
                        $SI_item->whole_sale_price = $item->price;
                    } else {
                        $SI_item->price = 0;
                        $SI_item->whole_sale_price = 0;
                    }

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

                    /*  $SI_item->whole_sale_price = $wholeSalePrice;  */
                    //$SI_item->retial_price = $item->retail_price;
                    if ($item->retail_price) {
                        $SI_item->retial_price = $item->retail_price;
                    } else {
                        $SI_item->retial_price = 0;
                    }

                    if ($item->cost_price) {
                        $SI_item->cost_price = $item->cost_price;
                    } else {
                        $SI_item->cost_price = 0;
                    }
                    // $SI_item->cost_price = $cost_price;
                    $sales_return_id = $Sales_invoice->sales_return_Id;
                    if ($SI_item->save()) {
                        $I_id = $SI_item->item_id;
                        $inv_id = DB::select("SELECT sales_invoice_id FROM sales_returns WHERE sales_return_Id = $sales_return_id ");
                        $item_code = DB::select("SELECT Item_code FROM items WHERE item_id = $I_id");
                      
                        if ($inv_id[0]->sales_invoice_id != 0) {
                            $sales_items = sales_Invoice_item::where("sales_invoice_id", $inv_id[0]->sales_invoice_id)->where("item_id", $SI_item->item_id)->first();
                            if($sales_items){
                                $sales_items->returned_qty = $sales_items->returned_qty + $SI_item->quantity;
                                $sales_items->returned_foc = $sales_items->returned_foc + $SI_item->free_quantity;
                                $sales_items->update();
                            }
                           
                        }

                        //item history record for each sales return item record
                        $item_history_ = new item_history();
                        $item_history_->internal_number = $SI_item->internal_number;
                        $item_history_->external_number = $SI_item->external_number;
                        $item_history_->branch_id = $Sales_invoice->branch_id;
                        $item_history_->location_id = $Sales_invoice->location_id;
                        $item_history_->document_number = 220;
                        $item_history_->transaction_date = $Sales_invoice->order_date;
                        $item_history_->description = "Sales Returned to" . $cus_name_[0]->customer_name;
                        $item_history_->item_id = $SI_item->item_id;
                        $item_history_->quantity = $SI_item->quantity + $SI_item->free_quantity;
                        $item_history_->free_quantity = $SI_item->free_quantity;
                        $item_history_->batch_number = $item_code[0]->Item_code;
                        $item_history_->whole_sale_price = $SI_item->whole_sale_price;
                        $item_history_->retial_price = $SI_item->retial_price;
                        $item_history_->cost_price = $SI_item->cost_price;
                        $item_history_->manual_number = $Sales_invoice->manual_number;
                        $item_history_->save();

                        //item history set off
                        if (!$inv_id) {
                            $item_history_setOff_ = new item_history_setOff();
                            $item_history_setOff_->internal_number = $Sales_invoice->internal_number;
                            $item_history_setOff_->external_number = $Sales_invoice->external_number;
                            $item_history_setOff_->branch_id = $Sales_invoice->branch_id;
                            $item_history_setOff_->location_id = $Sales_invoice->location_id;
                            $item_history_setOff_->document_number = 220;
                            $item_history_setOff_->batch_number = $item_code[0]->Item_code;
                            $item_history_setOff_->transaction_date = $Sales_invoice->order_date;
                            $item_history_setOff_->item_id = $SI_item->item_id;
                            $item_history_setOff_->whole_sale_price = $SI_item->whole_sale_price;
                            $item_history_setOff_->retial_price = $SI_item->retial_price;
                            $item_history_setOff_->cost_price = $SI_item->cost_price;
                            $item_history_setOff_->quantity = $SI_item->quantity + $SI_item->free_quantity;
                            $item_history_setOff_->reference_internal_number = $Sales_invoice->internal_number;;
                            $item_history_setOff_->reference_external_number = $Sales_invoice->external_number;
                            $item_history_setOff_->reference_document_number = 220;
                            $item_history_setOff_->price_status = 0; //price_status_controller::validte_whole_sale_price($item_history_setOff_->branch_id,$item_history_setOff_->location_id,$item->item_id,$item->price);
                            $item_history_setOff_->manual_number = $Sales_invoice->manual_number;
                            $item_history_setOff_->save();
                        } else {
                            $id_ = $inv_id[0]->sales_invoice_id;
                            $ref_numbers = DB::select("SELECT internal_number,external_number,document_number FROM sales_invoices WHERE sales_invoices.sales_invoice_Id =$id_");
                            $item_history_setOff_ = new item_history_setOff();
                            $item_history_setOff_->internal_number = $SI_item->internal_number;
                            $item_history_setOff_->external_number = $SI_item->external_number;
                            $item_history_setOff_->branch_id = $Sales_invoice->branch_id;
                            $item_history_setOff_->location_id = $Sales_invoice->location_id;
                            $item_history_setOff_->document_number = 220;
                            $item_history_setOff_->batch_number = $item_code[0]->Item_code;
                            $item_history_setOff_->transaction_date = $Sales_invoice->order_date;
                            $item_history_setOff_->item_id = $SI_item->item_id;
                            $item_history_setOff_->whole_sale_price = $SI_item->whole_sale_price;
                            $item_history_setOff_->retial_price = $SI_item->retial_price;
                            $item_history_setOff_->cost_price = $SI_item->cost_price;
                            $item_history_setOff_->quantity = $SI_item->quantity + $SI_item->free_quantity;
                            $item_history_setOff_->reference_internal_number = $Sales_invoice->internal_number;;
                            $item_history_setOff_->reference_external_number = $Sales_invoice->external_number;
                            $item_history_setOff_->reference_document_number = 220;
                            $item_history_setOff_->price_status = 0; //price_status_controller::validte_whole_sale_price($item_history_setOff_->branch_id,$item_history_setOff_->location_id,$item->item_id,$item->price);
                            $item_history_setOff_->manual_number = $Sales_invoice->manual_number;
                            $item_history_setOff_->save();
                        }
                    }
                }

                //inserting debtors ledger
                $cus_id = $Sales_invoice->customer_id;
                $cus_code = "";
                $cus_name = "";
                $query = "SELECT customer_code,customer_name FROM customers WHERE customer_id =$cus_id";
                if ($result = DB::select($query)) {
                    $cus_code = $result[0]->customer_code;
                    $cus_name = $result[0]->customer_name;
                }
                $desc = "Sales Returned From " . "" . $cus_name;
                $deb_ledger = new DebtorsLedger();
                $deb_ledger->internal_number = $Sales_invoice->internal_number;
                $deb_ledger->external_number = $Sales_invoice->external_number;
                $deb_ledger->document_number =  $Sales_invoice->document_number;
                $deb_ledger->trans_date =  $Sales_invoice->order_date;
                $deb_ledger->description =  $desc;
                $deb_ledger->branch_id =  $Sales_invoice->branch_id;
                $deb_ledger->customer_id =  $Sales_invoice->customer_id;
                $deb_ledger->customer_code = $cus_code;
                $deb_ledger->amount = -$Sales_invoice->total_amount;
                $deb_ledger->paidamount = -$request->input('text_box_values'); //set off amount
                $deb_ledger->employee_id = $Sales_invoice->employee_id;
                $deb_ledger->manual_number = $Sales_invoice->manual_number;
                $deb_ledger->sales_analyst_id = $Sales_invoice->sales_analyst_id;
                $deb_ledger->save();

                //dd($deb_ledger);

               

                //set off array
                foreach ($set_off_array as $i) {
                    $id_parts = explode('|', $i);
                   
                    $id = $id_parts[0];
                    
                    $amount = $id_parts[1]; //set off amount
                   
                    // invoice dl
                    $debotrs_ledgers_ = DebtorsLedger::find($id); 
                    //dd($debotrs_ledgers_);
                    $debotrs_ledgers_->paidamount = $debotrs_ledgers_->paidamount + $amount;
                    $debotrs_ledgers_->return_amount = $debotrs_ledgers_->return_amount + $amount; // updaeting return column

                    //sales return set off record - set off invoice data with set off amount
                    $return_setoff = new sales_return_debtor_setoff();
                    $return_setoff->debtors_ledger_id = $debotrs_ledgers_->debtors_ledger_id;
                    $return_setoff->internal_number = $debotrs_ledgers_->internal_number;
                    $return_setoff->external_number = $debotrs_ledgers_->external_number;
                    $return_setoff->sales_return_Id = $Sales_invoice->sales_return_Id; //*
                    $return_setoff->setoff_amount = $amount;
                    $return_setoff->save();
                   
                    if($debotrs_ledgers_->update()){
                         //return paid amount -update - sales return dl
                         $return_dl = DebtorsLedger::find($deb_ledger->debtors_ledger_id);
                         $return_dl->update();

                        //set off
                         $dl_set_off = new DebtorsLedgerSetoff();
                         $dl_set_off->internal_number = $Sales_invoice->internal_number;
                         $dl_set_off->external_number = $Sales_invoice->external_number;
                         $dl_set_off->document_number =  $Sales_invoice->document_number;
                         $dl_set_off->reference_internal_number = $debotrs_ledgers_->internal_number;
                         $dl_set_off->reference_external_number = $debotrs_ledgers_->external_number;
                         $dl_set_off->reference_document_number = $debotrs_ledgers_->document_number;
                         $dl_set_off->trans_date = $Sales_invoice->order_date;
                         $dl_set_off->description = "Sales returned from ".$cus_name;
                         $dl_set_off->branch_id = $Sales_invoice->branch_id;
                         $dl_set_off->customer_id = $debotrs_ledgers_->customer_id;
                         $dl_set_off->customer_code = $debotrs_ledgers_->customer_code;
                         $dl_set_off->amount = $amount;
                         $dl_set_off->save();

                         
                    }

                    
                }
                DB::commit();
                return response()->json(["status" => true, "primaryKey" => $Sales_invoice->sales_return_Id]);
            } else {
                return response()->json(["status" => false]);
            }
        } catch (Exception $ex) {
            DB::rollback();
            return $ex;
        }
    }

    //add draft
    public function addSalesReturnDraft(Request $request)
    {
        try {

            $PreparedBy = Auth::user()->id;
            $collection = json_decode($request->input('collection'));
            $Sales_return = new sales_Invoice_draft();
            $Sales_return->internal_number = 0000;
            $Sales_return->external_number = $request->input('LblexternalNumber'); // need to change 
            $Sales_return->order_date_time = $request->input('invoice_date_time');
            $Sales_return->branch_id = $request->input('cmbBranch');
            $Sales_return->location_id = $request->input('cmbLocation');
            $Sales_return->employee_id = $request->input('cmbEmp');
            $Sales_return->customer_id = $request->input('customerID');
            $Sales_return->total_amount = $request->input('grandTotal');
            /* $Purchase_order->Approval_status = $request->input('Approval_status'); */
            $Sales_return->discount_percentage = $request->input('txtDiscountPrecentage');
            $Sales_return->discount_amount = $request->input('txtDiscountAmount');
            $Sales_return->payment_term_id = $request->input('cmbPaymentTerm');
            $Sales_return->document_number = 220;
            $Sales_return->remarks = $request->input('txtRemarks');
            $Sales_return->delivery_instruction = $request->input('txtDeliveryInst');
            $Sales_return->prepaired_by = $PreparedBy;


            if ($Sales_return->save()) {

                //looping array
                foreach ($collection as $i) {

                    $item = json_decode($i);
                    $SI_item = new sales_Invoice_item_draft();
                    $SI_item->sales_invoice_Id = $Sales_return->sales_invoice_Id;
                    $SI_item->internal_number = $Sales_return->internal_number;
                    $SI_item->external_number = $Sales_return->external_number; // need to change
                    $SI_item->item_id = $item->item_id;
                    $SI_item->item_name = $item->item_name;
                    $SI_item->quantity = $item->qty;
                    $SI_item->free_quantity = $item->free_quantity;
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


    //get returns to the list
    public function getSalesInvoiceReturnData(Request $request,$id)
    {
        try {
            $pageNumber = ($request->start / $request->length) + 1;
            $pageLength = $request->length;
            $skip = ($pageNumber - 1) * $pageLength;
            $searchValue = request('search.value');
            $query = DB::table('sales_returns')
        ->select(
            'sales_return_Id',
            'sales_returns.external_number',
            'sales_returns.manual_number',
            'sales_returns.sales_invoice_Id',
            'sales_returns.order_date',
            'sales_returns.your_reference_number',
            DB::raw("FORMAT(sales_returns.total_amount, 2) as total_amount"),
            DB::raw("SUBSTRING(employees.employee_name, 1, 10) as employee_name"), 
            DB::raw("SUBSTRING(customers.customer_name, 1, 20) as customer_name"),
            DB::raw('"Original" AS status'),
            'sales_invoices.manual_number AS si_manual_number'
        )
        ->join('employees', 'sales_returns.employee_id', '=', 'employees.employee_id')
        ->join('customers', 'sales_returns.customer_id', '=', 'customers.customer_id')
        ->leftJoin('sales_invoices', 'sales_returns.sales_invoice_id', '=', 'sales_invoices.sales_invoice_id')
        ->where('sales_returns.document_number', '=', 220)
        ->orderByDesc('sales_return_Id');

        if ($id > 0) {
            $query->where('sales_returns.branch_id', '=', $id);
        }
           
        if (!empty($searchValue)) {
  
            $query->where(function ($query) use ($searchValue) {
                $search_amount = str_replace(',', '', $searchValue);
                $query->where('sales_returns.external_number', 'like', '%' . $searchValue . '%')
                    ->orWhere('sales_returns.manual_number', 'like', '%' . $searchValue . '%')
                    ->orWhere('sales_returns.order_date', 'like', '%' . $searchValue . '%')
                    ->orWhere('sales_returns.total_amount', 'like', '%' . $search_amount . '%')
                    ->orWhere('employees.employee_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('customers.customer_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('sales_invoices.manual_number', 'like', '%' . $searchValue . '%');
                  
            });
        }
        $results = $query->take($pageLength)->skip($skip)->get();
        
        
        $results->transform(function ($item) {
            $status = "Original";
          //  $disabled = "disabled";
           // $buttons = '<button class="btn btn-primary btn-sm" id="btnEdit_' . $item->sales_invoice_Id . '" onclick="btnEdit_(' . $item->sales_order_Id . ', \'' . $status . '\')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>&#160';
            $buttons = '<button class="btn btn-success btn-sm" onclick="view(' . $item->sales_return_Id . ')"><i class="fa fa-eye" aria-hidden="true"></i></button>&#160';
            $buttons .= '<button class="btn btn-secondary btn-sm" onclick="generateSalesReturnReport('  . $item->sales_return_Id . ')"><i class="fa fa-print" aria-hidden="true"></i></button>';
        
            $item->buttons = $buttons;
            $statusLabel = '<label class="badge badge-pill bg-success">' . $status . '</label>';
            $item->statusLabel = $statusLabel;
        
            $encodedManualNumber = $this->base64Encode($item->si_manual_number);

            if($item->si_manual_number != null){
                $info = '<a href="../sd/invoice_nfo?manual_number=' .$encodedManualNumber. '&action=inquery" onclick="updateTotal()" target="_blank">' . $item->si_manual_number .'&nbsp;&nbsp;<i class="fa fa-info-circle text-info fa-lg" aria-hidden="true"></i></a>';
                $item->info = $info;
            }else{
                $info = '<a href="#" onclick="event.preventDefault(); viewInfo('  . $item->sales_return_Id . ');" target="_blank">View More Info&nbsp;&nbsp;<i class="fa fa-info-circle text-info fa-lg" aria-hidden="true"></i></a>';

                $item->info = $info;
            }
          
            
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

    //update return
    public function updateReturn(Request $request, $id)
    {
        try {
            $collection = json_decode($request->input('collection'));
            $Sales_return = sales_Invoice::find($id);
            /*   $Sales_return->internal_number = 0000; */
            $Sales_return->external_number = $request->input('LblexternalNumber'); // need to change 
            $Sales_return->order_date_time = $request->input('invoice_date_time');
            $Sales_return->branch_id = $request->input('cmbBranch');
            $Sales_return->location_id = $request->input('cmbLocation');
            $Sales_return->employee_id = $request->input('cmbEmp');
            $Sales_return->customer_id = $request->input('customerID');
            $Sales_return->total_amount = $request->input('grandTotal');
            /* $Purchase_order->Approval_status = $request->input('Approval_status'); */
            $Sales_return->discount_percentage = $request->input('txtDiscountPrecentage');
            $Sales_return->discount_amount = $request->input('txtDiscountAmount');
            $Sales_return->payment_term_id = $request->input('cmbPaymentTerm');

            $Sales_return->remarks = $request->input('txtRemarks');
            $Sales_return->delivery_instruction = $request->input('txtDeliveryInst');



            if ($Sales_return->update()) {
                $deleteRequestItem = sales_Invoice_item::where("sales_invoice_Id", "=", $id)->delete();
                //looping ifrst array
                foreach ($collection as $i) {
                    /*  $date = date('Y-m-d H:i:s'); */
                    $item = json_decode($i);
                    $SI_item = new sales_Invoice_item();
                    $SI_item->sales_invoice_Id = $Sales_return->sales_invoice_Id;
                    $SI_item->internal_number = 0000;
                    $SI_item->external_number = $Sales_return->external_number; // need to change
                    $SI_item->item_id = $item->item_id;
                    $SI_item->item_name = $item->item_name;
                    $SI_item->quantity = $item->qty;
                    $SI_item->free_quantity = $item->free_quantity;
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

    //update return draft
    public function updateSalesReturnDraft(Request $request, $id)
    {
        try {


            $collection = json_decode($request->input('collection'));
            $Sales_return = sales_Invoice_draft::find($id);
            $Sales_return->internal_number = 0000;
            $Sales_return->external_number = $request->input('LblexternalNumber'); // need to change 
            $Sales_return->order_date_time = $request->input('invoice_date_time');
            $Sales_return->branch_id = $request->input('cmbBranch');
            $Sales_return->location_id = $request->input('cmbLocation');
            $Sales_return->employee_id = $request->input('cmbEmp');
            $Sales_return->customer_id = $request->input('customerID');
            $Sales_return->total_amount = $request->input('grandTotal');
            /* $Purchase_order->Approval_status = $request->input('Approval_status'); */
            $Sales_return->discount_percentage = $request->input('txtDiscountPrecentage');
            $Sales_return->discount_amount = $request->input('txtDiscountAmount');
            $Sales_return->payment_term_id = $request->input('cmbPaymentTerm');
            $Sales_return->remarks = $request->input('txtRemarks');
            $Sales_return->delivery_instruction = $request->input('txtDeliveryInst');


            if ($Sales_return->update()) {
                $deleteRequestItem = sales_Invoice_item_draft::where("sales_invoice_Id", "=", $id)->delete();
                //looping array
                foreach ($collection as $i) {
                    /*  $date = date('Y-m-d H:i:s'); */
                    $item = json_decode($i);
                    $SI_item = new sales_Invoice_item_draft();
                    $SI_item->sales_invoice_Id = $Sales_return->sales_invoice_Id;
                    $SI_item->internal_number = 0000;
                    $SI_item->external_number = $Sales_return->external_number; // need to change
                    $SI_item->item_id = $item->item_id;
                    $SI_item->item_name = $item->item_name;
                    $SI_item->quantity = $item->qty;
                    $SI_item->free_quantity = $item->free_quantity;
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

    //approvlal list
    public function getPendingapprovalsSalesInvReturn()
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
            sales_invoices.approval_status = "Pending"
        AND 
            sales_invoices.document_number = 220';
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


    //approve
    public function approveRequestSalesInvReturn($id)
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

    //reject
    public function RejectRequestSalesInvReturn($id)
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

    //load return reasons
    public function loadReason()
    {
        try {
            $reasons = SalesReturnReson::all();
            if ($reasons) {
                return response()->json($reasons);
            } else {
                return response()->json(['status' => false]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //load customers to cmb
    public function loadCustomers()
    {
        try {
            $query = " SELECT 0 AS customer_id, 'Any' AS customer_name UNION
            SELECT customer_id, customer_name 
            FROM customers";
            $customers = DB::select($query);
            if ($customers) {
                return response()->json(['data' => $customers]);
            } else {
                return response()->json(['status' => false]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //get first date and last date of month
    public function getMonthDates()
    {
        try {

            $firstDate = Carbon::now()->startOfMonth()->format('d/m/Y');
            $lastDate = Carbon::now()->endOfMonth()->format('d/m/Y');
            return response()->json(['first' => $firstDate, 'last' => $lastDate]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //get emp to model cmb
    public function loademployeesInModel()
    {
        try {
            $query = "SELECT 0 AS employee_id, 'Any' AS employee_name

            UNION
            
            SELECT employee_id, employee_name
            FROM employees
            WHERE employee_id <> (SELECT MIN(employee_id) FROM employees)";
            $emp = DB::select($query);
            if ($emp) {
                return response()->json($emp);
            } else {
                return response()->json(['status' => false]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //load invoice to model table
    public function getInvoicesForReturn(Request $request, $id)
    {
        try {

            /*   $Sales_return->external_number = $request->input('LblexternalNumber'); */
            $from_date = $request->input('from_date');
            $to_date = $request->input('to_date');
            $cusID = $request->input('cmbCustomer');
            $repID = $request->input('cmbSalesRep');

            if ($cusID == 0 && $repID == 0) {
                $query = "SELECT DISTINCT sales_invoices.sales_invoice_Id,order_date_time,sales_invoices.external_number,sales_invoices.manual_number,total_amount,customers.customer_name,employees.employee_name
                FROM sales_invoices
                INNER JOIN customers on sales_invoices.customer_id = customers.customer_id
                INNER JOIN employees ON sales_invoices.employee_id = employees.employee_id
                INNER JOIN sales_invoice_items ON sales_invoices.sales_invoice_Id = sales_invoice_items.sales_invoice_Id
                WHERE order_date_time BETWEEN '" . $from_date . "' AND '" . $to_date . "'
                AND branch_id = '" . $id . "' AND (ABS(sales_invoice_items.quantity) - sales_invoice_items.returned_qty) > 0 ORDER BY
                sales_invoice_Id DESC";
                $invoices = DB::select($query);
                if ($invoices) {
                    return response()->json(['status' => true, "data" => $invoices]);
                } else {
                    return response()->json(['status' => false, "data" => []]);
                }
            } else if ($cusID == 0) {
                $query = "SELECT DISTINCT sales_invoices.sales_invoice_Id,order_date_time,sales_invoices.external_number,sales_invoices.manual_number,total_amount,customers.customer_name,employees.employee_name
                FROM sales_invoices
                INNER JOIN customers on sales_invoices.customer_id = customers.customer_id
                INNER JOIN employees ON sales_invoices.employee_id = employees.employee_id
                INNER JOIN sales_invoice_items ON sales_invoices.sales_invoice_Id = sales_invoice_items.sales_invoice_Id
                WHERE order_date_time BETWEEN '" . $from_date . "' AND '" . $to_date . "'
                AND sales_invoices.branch_id = '" . $id . "'
                AND sales_invoices.employee_id = '" . $repID . "'AND (ABS(sales_invoice_items.quantity) - sales_invoice_items.returned_qty) > 0
                ORDER BY
                sales_invoices.sales_invoice_Id DESC";
                $invoices = DB::select($query);
                if ($invoices) {
                    return response()->json(['status' => true, "data" => $invoices]);
                } else {
                    return response()->json(['status' => false, "data" => []]);
                }
            } else if ($repID == 0) {
                $query = "SELECT DISTINCT sales_invoices.sales_invoice_Id,order_date_time,sales_invoices.external_number,sales_invoices.manual_number,total_amount,customers.customer_name,employees.employee_name
                FROM sales_invoices
                INNER JOIN customers on sales_invoices.customer_id = customers.customer_id
                INNER JOIN employees ON sales_invoices.employee_id = employees.employee_id
                INNER JOIN sales_invoice_items ON sales_invoices.sales_invoice_Id = sales_invoice_items.sales_invoice_Id
                WHERE order_date_time BETWEEN '" . $from_date . "' AND '" . $to_date . "'
                AND sales_invoices.branch_id = '" . $id . "'
                AND sales_invoices.customer_id = '" . $cusID . "'AND (ABS(sales_invoice_items.quantity) - sales_invoice_items.returned_qty) > 0 ORDER BY
                sales_invoices.sales_invoice_Id DESC
                ";
                $invoices = DB::select($query);
                if ($invoices) {
                    return response()->json(['status' => true, "data" => $invoices]);
                } else {
                    return response()->json(['status' => false, "data" => []]);
                }
            } else {
                $query = "SELECT DISTINCT sales_invoices.sales_invoice_Id,order_date_time,sales_invoices.external_number,total_amount,customers.customer_name,employees.employee_name
                FROM sales_invoices
                INNER JOIN customers on sales_invoices.customer_id = customers.customer_id
                INNER JOIN employees ON sales_invoices.employee_id = employees.employee_id
                INNER JOIN sales_invoice_items ON sales_invoices.sales_invoice_Id = sales_invoice_items.sales_invoice_Id
                WHERE order_date_time BETWEEN '" . $from_date . "' AND '" . $to_date . "'
                AND branch_id = '" . $id . "'
                AND sales_invoices.customer_id = '" . $cusID . "'
                AND sales_invoices.employee_id = '" . $repID . "'AND (ABS(sales_invoice_items.quantity) - sales_invoice_items.returned_qty) > 0 ORDER BY
                sales_invoices.sales_invoice_Id DESC";
                $invoices = DB::select($query);
                if ($invoices) {
                    return response()->json(['status' => true, "data" => $invoices]);
                } else {
                    return response()->json(['status' => false, "data" => []]);
                }
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //load invoice items to table
    public function getInvoiceItems($id)
    {
        try {

            try {
                $query = "SELECT
                sales_invoice_items.item_name,
                sales_invoice_items.item_id,
                ABS(sales_invoice_items.quantity) - ABS(sales_invoice_items.returned_qty) AS quantity,
                ABS(sales_invoice_items.free_quantity) - ABS(sales_invoice_items.returned_foc) AS free_quantity,
                sales_invoice_items.unit_of_measure,
                sales_invoice_items.package_unit,
                sales_invoice_items.package_size,
                sales_invoice_items.price,
                sales_invoice_items.discount_percentage,
                sales_invoice_items.discount_amount,
                items.Item_code
            FROM
                sales_invoice_items
            INNER JOIN
                items
            ON
                sales_invoice_items.item_id = items.item_id
            WHERE
                sales_invoice_items.sales_invoice_Id = '" . $id . "'
                AND ABS(sales_invoice_items.quantity) - ABS(sales_invoice_items.returned_qty) > 0 ORDER BY
                sales_invoice_Id DESC";


                $result = DB::select($query);
                if ($result) {
                    return response()->json(['success' => 'Data loaded', 'data' => $result]);
                } else {
                    return response()->json(['success' => 'Data loaded', 'data' => []]);
                }
            } catch (Exception $ex) {
                return $ex;
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //load sales invoice items to sales invoice return item table
    public function getInvoiceItemsToreturnTable(Request $request, $branchID, $id, $path)
    {
        try {
            try {
                if ($path == "model") {
                    $resultsArray = [];
                    $collection = json_decode($request->get('Item_ids'));
                    $invoice_id = $id;

                    // Prepare an array to store the item IDs
                    $itemIDs = [];
                    foreach ($collection as $i) {
                        $id = json_decode($i);
                        $itemIDs[] = $id;
                    }

                    // Create a comma-separated string of item IDs for the IN clause
                    $itemIDsString = implode(',', $itemIDs);


                    $query = "SELECT 
               SII.item_name,
               SII.sales_invoice_id,
               SII.item_id,
               ABS(SII.quantity) - ABS(SII.returned_qty) AS quantity,
               ABS(SII.free_quantity) - ABS(SII.returned_foc) AS free_quantity,
               ABS(SII.returned_qty) AS rtn_qty,
               SII.unit_of_measure,
               SII.package_unit,
               SII.package_size,
               SII.price,
               SII.discount_percentage,
               SII.discount_amount,
               SII.retial_price,
               SII.whole_sale_price,
               SII.cost_price,
               IT.Item_code
           FROM 
               sales_invoice_items SII
               INNER JOIN items IT ON SII.item_id = IT.item_id
               INNER JOIN sales_invoices SI ON SI.sales_invoice_Id = SII.sales_invoice_Id 
           WHERE 
               SII.sales_invoice_Id = '" . $invoice_id . "'
               AND SII.item_id IN (" . $itemIDsString . ")";


                    $result = DB::select($query);
                    if ($result) {
                        $resultsArray = $result;
                    }

                    return response()->json(['success' => 'Data loaded', 'data' => $resultsArray]);
                } else {
                    $resultsArray = [];
                    $invoice_id = $id;
                    $query = "SELECT 
               SII.item_name,
               SII.sales_invoice_id,
               SII.item_id,
               ABS(SII.quantity) - ABS(SII.returned_qty) AS quantity,
               ABS(SII.free_quantity) - ABS(SII.returned_foc) AS free_quantity,
               ABS(SII.returned_qty) AS rtn_qty,
               SII.unit_of_measure,
               SII.package_unit,
               SII.package_size,
               SII.price,
               SII.discount_percentage,
               SII.discount_amount,
               SII.retial_price,
               SII.whole_sale_price,
               SII.cost_price,
               IT.Item_code
           FROM 
               sales_invoice_items SII
               INNER JOIN items IT ON SII.item_id = IT.item_id
               INNER JOIN sales_invoices SI ON SI.sales_invoice_Id = SII.sales_invoice_Id
           WHERE 
               SII.external_number = (SELECT external_number FROM sales_invoices WHERE sales_invoices.external_number = '$invoice_id')";



                    $result = DB::select($query);
                    if ($result) {
                        $resultsArray = $result;
                    }

                    return response()->json(['success' => 'Data loaded', 'data' => $resultsArray]);
                }
            } catch (Exception $ex) {

                return $ex;
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //load header details of sales invoice to sales return
    public function getHeaderDetailsForInvoiceReturn($id, $path)
    {
        try {
            if ($path == "model") {
                $query = "SELECT sales_invoices.sales_invoice_Id,sales_invoices.customer_id,sales_invoices.external_number,sales_invoices.manual_number,sales_invoices.employee_id,sales_invoices.discount_percentage,sales_invoices.discount_amount,
                customers.customer_name, customers.primary_address,customers.customer_code,sales_invoices.location_id,
                sales_invoices.payment_term_id FROM sales_invoices INNER JOIN customers ON sales_invoices.customer_id = customers.customer_id INNER JOIN sales_invoice_items ON sales_invoices.sales_invoice_Id = sales_invoice_items.sales_invoice_Id  WHERE  sales_invoices.sales_invoice_Id = '" . $id . "' AND (ABS(sales_invoice_items.quantity) - sales_invoice_items.returned_qty) > 0";
                $result = DB::select($query);
                if ($result) {
                    return response()->json(['success' => 'Data loaded', 'data' => $result]);
                } else {
                    return response()->json(['success' => 'Data loaded', 'data' => []]);
                }
            } else {
                $query = "SELECT sales_invoices.sales_invoice_Id,sales_invoices.customer_id,sales_invoices.external_number,sales_invoices.manual_number,sales_invoices.employee_id,sales_invoices.discount_percentage,sales_invoices.discount_amount,
                customers.customer_name, customers.primary_address,customers.customer_code,sales_invoices.location_id,sales_invoices.branch_id,
                sales_invoices.payment_term_id FROM sales_invoices INNER JOIN customers ON sales_invoices.customer_id = customers.customer_id INNER JOIN sales_invoice_items ON sales_invoices.sales_invoice_Id = sales_invoice_items.sales_invoice_Id  WHERE  sales_invoices.external_number = '" . $id . "' AND (ABS(sales_invoice_items.quantity) - sales_invoice_items.returned_qty) > 0";
                $result = DB::select($query);
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

    //get e ach sales return
    public function getEachSalesReturn($id, $status)
    {
        try {
            if ($status == "Original") {
                $query = 'SELECT 
                sales_returns.*, 
                customers.customer_name, 
                customers.primary_address,
                customers.customer_code,
                towns.town_name,
                sales_invoices.manual_number AS SI_ext

            FROM 
                sales_returns
            LEFT JOIN 
                customers ON sales_returns.customer_id = customers.customer_id
            LEFT JOIN 
                towns ON customers.town = towns.town_id
            LEFT JOIN 
                sales_invoices ON sales_invoices.sales_invoice_Id = sales_returns.sales_invoice_id
            WHERE 
                sales_returns.sales_return_Id = :id;
            ';

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


    public function getEachproductofSalesReturn($id, $status)
    {
        try {
            if ($status == "Original") {
                $query = 'SELECT sales_return_items.*,items.Item_code FROM sales_return_items INNER JOIN items ON sales_return_items.item_id = items.item_id WHERE sales_return_items.sales_return_Id = "' . $id . '"';
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

    //get whole sale price in item manual insertion
    public function setPrice($id, $price, $brn_id)
    {
        try {
            $itemId = $id;
            $rtl_price = floatval($price);
            $price_query = "SELECT whole_sale_price,cost_price 
            FROM item_history_set_offs 
            WHERE item_history_set_offs.retial_price = $rtl_price
            
              AND item_history_set_offs.item_id = $itemId 
              AND item_history_set_offs.branch_id = $brn_id
             ORDER BY item_history_set_offs.setoff_id DESC LIMIT 1";
            $result = DB::select($price_query);
            if ($result) {
                return response()->json($result);
            } else {
                return response()->json((['success' => 'Data loaded', 'data' => []]));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //get foc_qty,offer data to theshold foc calculation
    public function getItem_foc_threshold_For_sales_returns($item_id, $entered_qty, $date)
    {

        try {

            $query = "SELECT IFNULL(sd_free_offerd_quantity_for_sales_returns('" . $item_id . "','" . $entered_qty . "','" . $date . "'), 0) AS Offerd_quantity";
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

    public function checkReturnLocation($id)
    {
        try {
            $qry = "SELECT locations.location_id,locations.location_name FROM locations WHERE locations.location_type_id = 2 AND locations.branch_id = $id AND locations.Status = 1";
            $result = DB::select($qry);
            if (count($result) > 0) {
                return response()->json(['status' => true, 'data' => $result]);
            } else {
                return response()->json(['status' => false, 'data' => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //load books
    public function loadBookNumber()
    {
        try {
            $qry = "SELECT CONCAT(books.book_name,'-',books.book_number) AS book_name,books.book_id FROM books WHERE books.is_active = 1 AND book_type_id = 1";
            $books = DB::select($qry);

            if ($books) {
                return response()->json(['status' => true, 'data' => $books]);
            } else {
                return response()->json(['status' => false, 'data' => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //load sales return details (return details) model table
    public function get_sales_retrun_details($branch_id, $location_id)
    {
        try {
            /* $qry = "SELECT
            sales_returns.order_date,
            sales_returns.manual_number AS sr_manual,
            sales_invoices.manual_number AS si_manual,
            CONCAT(customers.customer_code, '-', customers.customer_name) AS customer_name,
            employees.employee_name AS rep_name,
            books.book_name,
            books.book_number,
            books.book_id,
            sales_returns.page_number,
            users.name AS rtn_user,
            branches.branch_name,
            locations.location_name,
            sales_return_resons.sales_return_resons,
            items.item_name,
            sales_return_items.sales_return_item_id,
            sales_return_items.package_unit,
            sales_return_items.quantity,
            sales_return_items.free_quantity,
            SUM(sales_return_items.quantity + sales_return_items.free_quantity) 
            OVER (PARTITION BY sales_return_items.sales_return_item_id) AS total_qty
        FROM sales_return_items
        LEFT JOIN sales_returns ON sales_return_items.sales_return_Id = sales_returns.sales_return_Id
        LEFT JOIN customers ON sales_returns.customer_id = customers.customer_id
        LEFT JOIN employees ON employees.employee_id = sales_returns.employee_id
        LEFT JOIN books ON sales_returns.book_id = books.book_id
        LEFT JOIN users ON sales_returns.prepaired_by = users.id
        LEFT JOIN branches ON sales_returns.branch_id = branches.branch_id
        LEFT JOIN sales_return_resons ON sales_returns.return_reason_id = sales_return_resons.sales_return_reson_id
        LEFT JOIN sales_invoices ON sales_returns.sales_invoice_id = sales_invoices.sales_invoice_Id
        LEFT JOIN items ON sales_return_items.item_id = items.item_id WHERE sales_return_items.sales_return_status = 0;
        "; */

            $qry = "SELECT
sales_returns.order_date,
sales_returns.manual_number AS sr_manual,

CONCAT(customers.customer_code, '-', customers.customer_name) AS customer_name,
employees.employee_name AS rep_name,
users.name AS rtn_user,
branches.branch_name,
locations.location_name,
sales_return_resons.sales_return_resons,
items.item_name,
items.Item_code,
sales_return_items.sales_return_item_id,
sales_return_items.package_unit,
sales_return_items.quantity,
sales_return_items.free_quantity,
sales_return_items.return_qty_transfer,
sales_invoices.manual_number AS si_manual,
SUM(sales_return_items.quantity + sales_return_items.free_quantity) 
OVER (PARTITION BY sales_return_items.sales_return_item_id) AS total_qty
FROM sales_return_items
LEFT JOIN sales_returns ON sales_return_items.sales_return_Id = sales_returns.sales_return_Id
LEFT JOIN customers ON sales_returns.customer_id = customers.customer_id
LEFT JOIN employees ON employees.employee_id = sales_returns.employee_id
LEFT JOIN books ON sales_returns.book_id = books.book_id
LEFT JOIN users ON sales_returns.prepaired_by = users.id
LEFT JOIN branches ON sales_returns.branch_id = branches.branch_id
LEFT JOIN locations ON sales_returns.location_id = locations.location_id
LEFT JOIN sales_return_resons ON sales_returns.return_reason_id = sales_return_resons.sales_return_reson_id
LEFT JOIN sales_invoices ON sales_returns.sales_invoice_id = sales_invoices.sales_invoice_Id
LEFT JOIN items ON sales_return_items.item_id = items.item_id WHERE sales_return_items.sales_return_status = 0 AND sales_returns.branch_id = $branch_id AND sales_returns.location_id = $location_id AND ((sales_return_items.quantity + sales_return_items.free_quantity)-sales_return_items.return_qty_transfer) > 0
ORDER BY sales_returns.order_date DESC;
";

            $result = DB::select($qry);
            if ($result) {
                return response()->json(['status' => true, 'data' => $result]);
            } else {
                return response()->json(['status' => true, 'data' => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function get_sales_retrun_details_info()
    {
        try {
            

            $qry = "SELECT
sales_returns.order_date,
sales_returns.manual_number AS sr_manual,

CONCAT(customers.customer_code, '-', customers.customer_name) AS customer_name,
employees.employee_name AS rep_name,
users.name AS rtn_user,
branches.branch_name,
locations.location_name,
sales_return_resons.sales_return_resons,
items.item_name,
items.Item_code,
sales_return_items.sales_return_item_id,
sales_return_items.package_unit,
sales_return_items.quantity,
sales_return_items.free_quantity,
sales_return_items.return_qty_transfer,
sales_invoices.manual_number AS si_manual,
SUM(sales_return_items.quantity + sales_return_items.free_quantity) 
OVER (PARTITION BY sales_return_items.sales_return_item_id) AS total_qty
FROM sales_return_items
LEFT JOIN sales_returns ON sales_return_items.sales_return_Id = sales_returns.sales_return_Id
LEFT JOIN customers ON sales_returns.customer_id = customers.customer_id
LEFT JOIN employees ON employees.employee_id = sales_returns.employee_id
LEFT JOIN books ON sales_returns.book_id = books.book_id
LEFT JOIN users ON sales_returns.prepaired_by = users.id
LEFT JOIN branches ON sales_returns.branch_id = branches.branch_id
LEFT JOIN locations ON sales_returns.location_id = locations.location_id
LEFT JOIN sales_return_resons ON sales_returns.return_reason_id = sales_return_resons.sales_return_reson_id
LEFT JOIN sales_invoices ON sales_returns.sales_invoice_id = sales_invoices.sales_invoice_Id
LEFT JOIN items ON sales_return_items.item_id = items.item_id WHERE sales_return_items.sales_return_status = 0 AND ((sales_return_items.quantity + sales_return_items.free_quantity)-sales_return_items.return_qty_transfer) > 0
ORDER BY sales_returns.order_date DESC;
";

            $result = DB::select($qry);
            if ($result) {
                return response()->json(['status' => true, 'data' => $result]);
            } else {
                return response()->json(['status' => true, 'data' => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //load items to table
    public function getReturnItems(Request $request, $type)
    {
        try {
            $item_array = [];
            $idArray = $request->input('id_array');
            if ($type == 'item') {
                foreach ($idArray as $id) {
                    $parts = explode('|', strval($id));
                    $primary_id = $parts[0];
                    $rtn_items = DB::select("SELECT
                    sales_returns.order_date,
                    sales_returns.manual_number AS sr_manual,
                    sales_returns.customer_id,
                    sales_returns.return_reason_id,
                    CONCAT(customers.customer_code, '-', customers.customer_name) AS customer_name,
                    employees.employee_name AS rep_name,
                    users.name AS rtn_user,
                    branches.branch_name,
                    locations.location_name,
                    sales_return_resons.sales_return_resons,
                    items.item_name,
                    sales_return_items.sales_return_item_id,
                    sales_return_items.item_id,
                    sales_return_items.package_unit,
                    sales_return_items.quantity,
                    sales_return_items.free_quantity,
                    sales_return_items.return_qty_transfer,
                    sales_invoices.manual_number AS si_manual,
                    SUM(sales_return_items.quantity + sales_return_items.free_quantity) 
                    OVER (PARTITION BY sales_return_items.sales_return_item_id) AS total_qty
                    FROM sales_return_items
                    LEFT JOIN sales_returns ON sales_return_items.sales_return_Id = sales_returns.sales_return_Id
                    LEFT JOIN customers ON sales_returns.customer_id = customers.customer_id
                    LEFT JOIN employees ON employees.employee_id = sales_returns.employee_id
                    LEFT JOIN books ON sales_returns.book_id = books.book_id
                    LEFT JOIN users ON sales_returns.prepaired_by = users.id
                    LEFT JOIN branches ON sales_returns.branch_id = branches.branch_id
                    LEFT JOIN locations ON sales_returns.location_id = locations.location_id
                    LEFT JOIN sales_return_resons ON sales_returns.return_reason_id = sales_return_resons.sales_return_reson_id
                    LEFT JOIN sales_invoices ON sales_returns.sales_invoice_id = sales_invoices.sales_invoice_Id
                    LEFT JOIN items ON sales_return_items.item_id = items.item_id WHERE sales_return_items.sales_return_status = 0 AND sales_return_items.sales_return_item_id = $primary_id
                    AND ((sales_return_items.quantity + sales_return_items.free_quantity)-sales_return_items.return_qty_transfer) > 0
                    ORDER BY sales_returns.order_date DESC;");
                    if ($rtn_items) {
                        array_push($item_array, $rtn_items);
                    }
                }
            } else {

                foreach ($idArray as $id) {
                    $parts = explode('|', strval($id));
                    $manual_no = $parts[1];

                    $rtn_items = DB::select("SELECT
                    sales_returns.order_date,
                    sales_returns.manual_number AS sr_manual,
                    sales_returns.customer_id,
                    sales_returns.return_reason_id,
                    CONCAT(customers.customer_code, '-', customers.customer_name) AS customer_name,
                    employees.employee_name AS rep_name,
                    users.name AS rtn_user,
                    branches.branch_name,
                    locations.location_name,
                    sales_return_resons.sales_return_resons,
                    items.item_name,
                    sales_return_items.sales_return_item_id,
                    sales_return_items.item_id,
                    sales_return_items.package_unit,
                    sales_return_items.quantity,
                    sales_return_items.free_quantity,
                    sales_return_items.return_qty_transfer,
                    sales_invoices.manual_number AS si_manual,
                    SUM(sales_return_items.quantity + sales_return_items.free_quantity) 
                    OVER (PARTITION BY sales_return_items.sales_return_item_id) AS total_qty
                    FROM sales_return_items
                    LEFT JOIN sales_returns ON sales_return_items.sales_return_Id = sales_returns.sales_return_Id
                    LEFT JOIN customers ON sales_returns.customer_id = customers.customer_id
                    LEFT JOIN employees ON employees.employee_id = sales_returns.employee_id
                    LEFT JOIN books ON sales_returns.book_id = books.book_id
                    LEFT JOIN users ON sales_returns.prepaired_by = users.id
                    LEFT JOIN branches ON sales_returns.branch_id = branches.branch_id
                    LEFT JOIN locations ON sales_returns.location_id = locations.location_id
                    LEFT JOIN sales_return_resons ON sales_returns.return_reason_id = sales_return_resons.sales_return_reson_id
                    LEFT JOIN sales_invoices ON sales_returns.sales_invoice_id = sales_invoices.sales_invoice_Id
                    LEFT JOIN items ON sales_return_items.item_id = items.item_id WHERE sales_return_items.sales_return_status = 0 AND sales_returns.manual_number = $manual_no
                    AND ((sales_return_items.quantity + sales_return_items.free_quantity)-sales_return_items.return_qty_transfer) > 0
                    ORDER BY sales_returns.order_date DESC;");
                    if ($rtn_items) {
                        array_push($item_array, $rtn_items);
                    }
                }
            }


            return response()->json(['status' => true, 'data' => $item_array]);
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //get locations
    public function getLocatiofor_return($id)
    {
        try {
            $location = DB::select('SELECT locations.location_id,locations.location_name FROM locations WHERE 
            locations.branch_id = ' . $id . ' AND locations.location_type_id <> 2');

            $return_location = DB::select('SELECT locations.location_id,locations.location_name FROM locations WHERE 
            locations.branch_id = ' . $id . ' AND locations.location_type_id = 2');


            return response()->json(['status' => true, 'location' => $location, "return_location" => $return_location]);
        } catch (Exception $ex) {
            return $ex;
        }
    }



    //update sales retrun item status
    public function update_sales_return_item_status(Request $request)
    {
        try {
            $ids_array = json_decode($request->get('retrun_item_IDs_array'));

            foreach ($ids_array as $i) {
                $id = json_decode($i);
                $sales_return_item = sales_return_item::find($id);
                $sales_return_item->sales_return_status = 1;
                $sales_return_item->update();
            }
            return response()->json(['status' => true, 'message' => 'success']);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //save retrun trnasfer
    public function addReturnTransfer(Request $request)
    {
        try {
            $collection = json_decode($request->input('collection'));
            foreach ($collection as $i) {
                $item_ = json_decode($i);
                $id = $item_->rtn_item_id;
                $trn_qty = $item_->trnsf_qty;
                /* $qry_ = DB::select('SELECT COUNT(*) as count
                FROM sales_return_items 
                WHERE sales_return_item_id = ' . $id . ' 
                  AND sales_return_items.quantity + sales_return_items.free_quantity - sales_return_items.return_qty_transfer >= ' . $trn_qty . ';
                '); */

              //  $qry_ = DB::Select('SELECT "true" AS status FROM sales_return_items WHERE sales_return_items.sales_return_item_id = ' . $id . ' AND ((sales_return_items.quantity + sales_return_items.free_quantity) - sales_return_items.return_qty_transfer) <= ' . $trn_qty);
              $qry_ = DB::select('SELECT SUM(sales_return_items.quantity + sales_return_items.free_quantity) - sales_return_items.return_qty_transfer AS balance FROM sales_return_items WHERE sales_return_items.sales_return_item_id = ' . $id);
                if ($qry_[0]->balance < floatval($trn_qty)) {
                    
                    return response()->json(['status' => false, 'message' => 'used']);
                }
            }

            $return_transfer = new return_transfer();
            $return_transfer->internal_number = IntenelNumberController::getNextID();
            $return_transfer->external_number = $this->createExternal_number_trnasfer();
            $return_transfer->transfer_date = date('Y-m-d');;
            $return_transfer->branch_id = $request->input('cmbBranch');
            $return_transfer->from_location_id = $request->input('cmbfromLocation');
            $return_transfer->to_location_id = $request->input('cmb_to_location');
            $return_transfer->document_number = 1100;
            $return_transfer->prepaired_by = Auth::user()->id;
            $from_location_name = DB::select("SELECT location_name FROM locations WHERE location_id =  $return_transfer->from_location_id");
            $to_location_name = DB::select("SELECT location_name FROM locations WHERE location_id =   $return_transfer->to_location_id");
            if ($return_transfer->save()) {
                foreach ($collection as $i) {
                    $item = json_decode($i);

                    //get wh_price,cost,retail from SR items
                    $RT_ITM_ID = $item->rtn_item_id;
                    $prices = DB::select('SELECT whole_sale_price,retial_price,cost_price FROM sales_return_items WHERE sales_return_items.sales_return_item_id = ' . $RT_ITM_ID);
                    $rtn_trans_item = new return_transfer_item();
                    $rtn_trans_item->return_transfer_id = $return_transfer->return_transfer_id;
                    $rtn_trans_item->internal_number = $return_transfer->internal_number;
                    $rtn_trans_item->external_number = $return_transfer->external_number;
                    $rtn_trans_item->item_id = $item->item_id;
                    $rtn_trans_item->customer_id = $item->cus_id;
                    $rtn_trans_item->package_unit = $item->package_unit;
                    $rtn_trans_item->total_qty = $item->total;
                    $rtn_trans_item->transfer_qty = $item->trnsf_qty;
                    $rtn_trans_item->sales_return_reson_id = $item->reson;
                    $rtn_trans_item->Remark = $item->remark;
                    $rtn_trans_item->sales_return_item_id = $item->rtn_item_id;
                    $rtn_trans_item->save();

                    //change sales return item status
                    $sales_return_item = sales_return_item::find($item->rtn_item_id);
                    $sales_return_item->return_qty_transfer = $sales_return_item->return_qty_transfer + $rtn_trans_item->transfer_qty;
                    $sales_return_item->update();

                    //item history minus
                    $item_history_minus = new item_history();
                    $item_history_minus->internal_number = $rtn_trans_item->internal_number;
                    $item_history_minus->external_number = $rtn_trans_item->external_number;
                    $item_history_minus->branch_id = $return_transfer->branch_id;
                    $item_history_minus->location_id = $return_transfer->from_location_id;
                    $item_history_minus->document_number = 1000;
                    $item_history_minus->transaction_date = $return_transfer->transfer_date;
                    $item_history_minus->description = "Transfer to " . $to_location_name[0]->location_name;
                    $item_history_minus->item_id = $rtn_trans_item->item_id;
                    $item_history_minus->quantity = -$rtn_trans_item->total_qty;
                    /* $item_history_minus->free_quantity = $rtn_trans_item->total_qty; need to check */
                    $item_history_minus->whole_sale_price = $prices[0]->whole_sale_price;
                    $item_history_minus->retial_price = $prices[0]->retial_price;
                    $item_history_minus->cost_price = $prices[0]->cost_price;
                    if ($item_history_minus->save()) {

                        $Item_code = DB::table('items')
                            ->select(['Item_code'])
                            ->where('item_id', $item_history_minus->item_id)
                            ->first();

                        //item history plus
                        $item_history_plus = new item_history();
                        $item_history_plus->internal_number = $rtn_trans_item->internal_number;
                        $item_history_plus->external_number = $rtn_trans_item->external_number;
                        $item_history_plus->branch_id = $return_transfer->branch_id;
                        $item_history_plus->location_id = $return_transfer->to_location_id;
                        $item_history_plus->document_number = 1000;
                        $item_history_plus->transaction_date = $return_transfer->transfer_date;
                        $item_history_plus->description = "Transfer from " . $from_location_name[0]->location_name;
                        $item_history_plus->item_id = $rtn_trans_item->item_id;
                        $item_history_plus->quantity = $rtn_trans_item->total_qty;
                        /* $item_history_minus->free_quantity = $rtn_trans_item->total_qty; need to check */
                        $item_history_plus->whole_sale_price = $prices[0]->whole_sale_price;
                        $item_history_plus->retial_price = $prices[0]->retial_price;
                        $item_history_plus->cost_price = $prices[0]->cost_price;
                        $item_history_plus->save();

                        //item history set off plus
                        $item_history_setOff_plus = new item_history_setOff();
                        $item_history_setOff_plus->internal_number = $item_history_plus->internal_number;
                        $item_history_setOff_plus->external_number = $item_history_plus->external_number;
                        $item_history_setOff_plus->document_number = $item_history_plus->document_number;
                        $item_history_setOff_plus->batch_number = $Item_code->Item_code; //use until decide a solution.same use in grn
                        $item_history_setOff_plus->branch_id = $item_history_plus->branch_id;
                        $item_history_setOff_plus->location_id = $return_transfer->to_location_id;
                        $item_history_setOff_plus->transaction_date = $item_history_plus->transaction_date;
                        $item_history_setOff_plus->item_id = $item_history_plus->item_id;
                        $item_history_setOff_plus->whole_sale_price = $item_history_plus->whole_sale_price;
                        $item_history_setOff_plus->retial_price = $item_history_plus->retial_price;
                        $item_history_setOff_plus->cost_price = $item_history_plus->cost_price;
                        $item_history_setOff_plus->quantity = $item_history_plus->quantity;
                        $item_history_setOff_plus->reference_internal_number = $item_history_plus->internal_number;
                        $item_history_setOff_plus->reference_external_number = $item_history_plus->external_number;
                        $item_history_setOff_plus->reference_document_number = $item_history_plus->document_number;
                        if ($item_history_setOff_plus->save()) {

                            $result = DB::table('sales_return_items')
                                ->select(['internal_number', 'item_id'])
                                ->where('sales_return_item_id', $RT_ITM_ID)
                                ->first();



                            if ($result) {
                                $internalNumber = $result->internal_number;
                                $itemId = $result->item_id;
                                $IH_id = DB::select("SELECT item_history_setoff_id FROM item_history_set_offs WHERE internal_number = $internalNumber AND item_id = $itemId");
                                $item_history_setoff_minus_update = item_history_setOff::find($IH_id[0]->item_history_setoff_id);
                                $item_history_setoff_minus_update->setoff_quantity = $item_history_setoff_minus_update->setoff_quantity + $item_history_setOff_plus->quantity;
                                $item_history_setoff_minus_update->update();
                            }

                            //item history set off minus
                            $item_history_setOff_minus = new item_history_setOff();
                            $item_history_setOff_minus->internal_number = $item_history_plus->internal_number;
                            $item_history_setOff_minus->external_number = $item_history_plus->external_number;
                            $item_history_setOff_minus->document_number = $item_history_plus->document_number;
                            $item_history_setOff_minus->batch_number = $Item_code->Item_code; //use until decide a solution.same use in grn
                            $item_history_setOff_minus->branch_id = $item_history_plus->branch_id;
                            $item_history_setOff_minus->location_id = $return_transfer->from_location_id;
                            $item_history_setOff_minus->transaction_date = $item_history_plus->transaction_date;
                            $item_history_setOff_minus->item_id = $item_history_plus->item_id;
                            $item_history_setOff_minus->whole_sale_price = $item_history_plus->whole_sale_price;
                            $item_history_setOff_minus->retial_price = $item_history_plus->retial_price;
                            $item_history_setOff_minus->cost_price = $item_history_plus->cost_price;
                            $item_history_setOff_minus->quantity = -$item_history_plus->quantity;
                            $item_history_setOff_minus->setoff_quantity = -$item_history_plus->quantity;
                            $item_history_setOff_minus->reference_internal_number = $sales_return_item->internal_number;
                            $item_history_setOff_minus->reference_external_number = $sales_return_item->external_number;
                            $item_history_setOff_minus->reference_document_number = 220;
                            $item_history_setOff_minus->save();
                        }
                    }
                }


                return response()->json(['status' => true, 'message' => 'success']);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function createExternal_number_trnasfer()
    {
        /* $exter_num = $this->reference_number->CustomerReceipt_referenceID('customer_receipts', 500); */
        $exter_num = ReferenceIdController::SR_trans_referenceId('return_transfers', 1100);

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

    //load return transfer details to list
    public function getReturnTransfer()
    {
        try {
            $qry = DB::select("SELECT 
            r.return_transfer_id,
            r.external_number,
            r.transfer_date,
            fl.location_name as from_location,
            tl.location_name as to_location,
            b.branch_name
            
        FROM 
            return_transfers r
        INNER JOIN 
            locations fl ON r.from_location_id = fl.location_id
        INNER JOIN 
            locations tl ON r.to_location_id = tl.location_id
        INNER JOIN
            branches b ON r.branch_id = b.branch_id
        ");
            if ($qry) {
                return response()->json(['status' => true, 'data' => $qry]);
            } else {
                return response()->json(['status' => false, 'data' => []]);
            }
        } catch (exception $ex) {
            return $ex;
        }
    }

    //get each return transfer
    public function getEachReturnTransfer($id){
        try{
            $return_ransfer = DB::select('SELECT * FROM return_transfers WHERE return_transfer_id = "'.$id.'"');
            $return_transfer_item = DB::select('
            SELECT 
            return_transfer_items.*,
            sales_returns.order_date,
            sales_returns.manual_number as sr_manual ,
            customers.customer_name,
            items.item_Name,
            SUM(sales_return_items.quantity + sales_return_items.free_quantity) 
            OVER (PARTITION BY sales_return_items.sales_return_item_id) AS total_qty,
            sales_return_items.return_qty_transfer
        FROM 
        return_transfer_items
        INNER JOIN
            customers ON return_transfer_items.customer_id = customers.customer_id 
        INNER JOIN
            items ON return_transfer_items.item_id = items.item_id
        LEFT JOIN 
            sales_return_items 
            ON return_transfer_items.sales_return_item_id = sales_return_items.sales_return_item_id
        LEFT JOIN 
            sales_returns 
            ON sales_return_items.sales_return_Id = sales_returns.sales_return_Id
       
        
        WHERE 
            return_transfer_items.return_transfer_id = "'.$id.'"');


            if($return_ransfer && $return_transfer_item){
                return response()->json(['status' => true, 'header' => $return_ransfer,'items' => $return_transfer_item]);
            }else{
                return response()->json(['status' => false, 'header' => [],'items' => []]);
            }
        }catch(exception $ex){
            return $ex;
        }
    }

    //load set off data (from dl - without specific invoice)
    public function load_setoff_data_($id){
        try{
            $qry = "SELECT dl.debtors_ledger_id, dl.trans_date,IFNULL(si.external_number,dl.external_number) AS manual_number, (CURRENT_DATE - dl.trans_date)
            AS age,(dl.amount - dl.paidamount) as balance,si.your_reference_number FROM debtors_ledgers dl 
            LEFT JOIN sales_invoices si ON dl.internal_number = si.internal_number WHERE (dl.amount - dl.paidamount) > 0 AND dl.customer_id = $id";
             $result = DB::select($qry);
             if($result){
                return response()->json(['status' => true, 'data' => $result]);
             }else{
                return response()->json(['status' => false, 'data' => []]);
             }


        }catch(Exception $ex){
            return $ex;
        }
    }

     //load set off data (from dl - with invoice)
     //changed on 21-12- get selected invoice's customers' data
     public function  load_setoff_data_invoice($id){
        try{
            $cus_id = DB::select('SELECT customer_id FROM sales_invoices SI WHERE SI.external_number = "' . $id . '"');
            
            $qry = "SELECT 
            dl.debtors_ledger_id, 
            dl.trans_date, 
            dl.external_number AS manual_number, 
            (CURRENT_DATE - dl.trans_date) AS age, 
            (dl.amount - dl.paidamount) AS balance,
            SI.sales_invoice_Id,
            SI.your_reference_number 
        FROM 
            debtors_ledgers dl
        LEFT JOIN
            sales_invoices SI ON dl.external_number = SI.external_number
        WHERE 
            dl.customer_id = '" . $cus_id[0]->customer_id . "' 
            AND (dl.amount - dl.paidamount) > 0
        ORDER BY 
            dl.trans_date DESC";


             $result = DB::select($qry);
             if($result){
                return response()->json(['status' => true, 'data' => $result]);
             }else{
                return response()->json(['status' => false, 'data' => []]);
             }


        }catch(Exception $ex){
            return $ex;
        }
    }

     //encode
     private function base64Encode($str) {
        return base64_encode(rawurlencode($str));
    }


    //load return set off data
    public function loadReturnSetoffData($id){
        try{
            $result = DB::select('SELECT SI.manual_number,SRD.setoff_amount FROM sales_return_debtor_setoffs SRD INNER JOIN sales_invoices SI ON SRD.external_number = SI.external_number WHERE SRD.sales_return_Id ='.$id);
            if($result){
                return response()->json(['status' => true, 'data' => $result]);
             }else{
                return response()->json(['status' => false, 'data' => []]);
             }

        }catch(Exception $ex){
            return $ex;
        }
    }
   
    


    //load item info

    public function getItemInfo($Item_id)
    {
        try {
            /* $query = "SELECT item_id, item_Name, unit_of_measure, CASE WHEN balance < 0 THEN 0 ELSE balance END AS balance
            FROM (
                SELECT item_id, item_Name, unit_of_measure, NULL AS balance
                FROM items WHERE items.item_id = '".$Item_id."'
                UNION
                SELECT item_historys.item_id, items.item_Name, items.unit_of_measure, SUM(item_historys.quantity) AS balance
                FROM item_historys
                INNER JOIN items ON item_historys.item_id = items.item_id WHERE item_historys.item_id = '".$Item_id."'
                GROUP BY item_historys.item_id, items.item_Name, items.unit_of_measure
            ) AS combined_data
            "; */
            /* $result = DB::select($query); */
            $info = DB::select('SELECT item_history_set_offs.whole_sale_price AS ih_wh_price,item_history_set_offs.retial_price AS ih_rt_price, items.Item_code,
            items.item_Name,
            items.unit_of_measure,
            items.package_size,
            items.package_unit 
        FROM item_history_set_offs 
        INNER JOIN items ON item_history_set_offs.item_id = items.item_id 
        WHERE item_history_set_offs.item_id = "'.$Item_id.'"
        ORDER BY item_history_set_offs.item_history_setoff_id DESC 
        LIMIT 1');

            if ($info) {
                return response()->json([$info]);
            }else{
                $info = DB::select('SELECT I.*
            FROM items I WHERE I.item_id = '.$Item_id);
            if($info){
                return response()->json([$info]);
            }
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //get returned item details
    public function get_returned_items_details($id,$item_id){
       /*  $result = DB::select("SELECT SUM(ABS(SRI.free_quantity)) from sales_return_items SRI INNER JOIN sales_returns SR ON SRI.sales_return_Id = SR.sales_return_Id WHERE SRI.item_id = $item_id AND SR.sales_invoice_id = $id"); */
       $result = DB::table('sales_return_items as SRI')
    ->join('sales_returns as SR', 'SRI.sales_return_Id', '=', 'SR.sales_return_Id')
    ->where('SRI.item_id', '=', $item_id)
    ->where('SR.sales_invoice_id', '=', $id)
    ->selectRaw('SUM(ABS(SRI.free_quantity)) as total_quantity')
    ->first();
        if ($result) {
            return response()->json($result);
        }

    }


   


    
}
