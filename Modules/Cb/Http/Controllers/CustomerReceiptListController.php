<?php

namespace Modules\Cb\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class CustomerReceiptListController extends Controller
{
    public function getReceiptList(Request $request)
    {
       
           /*  $query = "SELECT 
            customer_receipts.customer_receipt_id,
            customer_receipts.external_number,
            customer_receipts.receipt_date,
            customer_receipts.amount,
            customer_receipt_cheques.banking_date,
            customer_receipt_cheques.cheque_number,
            customers.customer_name,
            CASE
                WHEN customer_receipts.receipt_method_id = 1 THEN 'Cash'
                WHEN customer_receipts.receipt_method_id = 2 THEN 'Cheque'
                ELSE 'Bank Slip'
            END AS payment_mode,
            (
                SELECT 
                    GROUP_CONCAT(debtors_ledgers.external_number SEPARATOR ',') AS external_numbers
                FROM 
                    customer_receipt_setoff_data
                LEFT JOIN 
                    debtors_ledgers ON customer_receipt_setoff_data.debtors_ledger_id = debtors_ledgers.debtors_ledger_id
                WHERE 
                    customer_receipt_setoff_data.customer_receipt_id = customer_receipts.customer_receipt_id
            ) AS invoice_numbers
        FROM 
            customer_receipts
        LEFT JOIN 
            customer_receipt_cheques ON customer_receipts.customer_receipt_id = customer_receipt_cheques.customer_receipt_id
        INNER JOIN 
            customers ON customer_receipts.customer_id = customers.customer_id
        ORDER BY 
            customer_receipts.customer_receipt_id DESC";

            $data = DB::select($query);
            return response()->json(["status" => true, "data" => $data]); 
        } catch (Exception $ex) {
            return response()->json(["status" => false, "exception" => $ex]);
        }*/
        try {
            $pageNumber = ($request->start / $request->length) + 1;
            $pageLength = $request->length;
            $skip = ($pageNumber - 1) * $pageLength;
            $searchValue = request('search.value');
        
            $query = DB::table('customer_receipts')
                ->select(
                    'customer_receipts.customer_receipt_id',
                    'customer_receipts.external_number',
                    'customer_receipts.receipt_date',
                    'customer_receipts.amount',
                    'customer_receipt_cheques.banking_date',
                    'customer_receipt_cheques.cheque_number',
                    'customers.customer_name',
                    DB::raw("CASE 
                                WHEN customer_receipts.receipt_method_id = 1 THEN 'Cash'
                                WHEN customer_receipts.receipt_method_id = 2 THEN 'Cheque'
                                ELSE 'Bank Slip'
                            END AS payment_mode"),
                    DB::raw("GROUP_CONCAT(debtors_ledgers.external_number SEPARATOR ', ') AS invoice_numbers") // Concatenate the values to avoid duplicates
                )
                ->leftJoin('customer_receipt_cheques', 'customer_receipts.customer_receipt_id', '=', 'customer_receipt_cheques.customer_receipt_id')
                ->leftJoin('customers', 'customer_receipts.customer_id', '=', 'customers.customer_id')
                ->leftJoin('customer_receipt_setoff_data', 'customer_receipts.customer_receipt_id', '=', 'customer_receipt_setoff_data.customer_receipt_id')
                ->leftJoin('debtors_ledgers', 'customer_receipt_setoff_data.debtors_ledger_id', '=', 'debtors_ledgers.debtors_ledger_id')
                ->groupBy('customer_receipts.customer_receipt_id', 'customer_receipts.external_number', 'customer_receipts.receipt_date', 'customer_receipts.amount', 'customer_receipt_cheques.banking_date', 'customer_receipt_cheques.cheque_number', 'customers.customer_name', 'customer_receipts.receipt_method_id');
            
            // Add search conditions if a search value is provided
            if (!empty($searchValue)) {
                $query->where(function ($query) use ($searchValue) {
                    $search_amount = str_replace(',', '', $searchValue);
                    $query->where('customer_receipts.external_number', 'like', '%' . $searchValue . '%')
                          ->orWhere('customer_receipts.receipt_date', 'like', '%' . $searchValue . '%')
                          ->orWhere('customer_receipts.amount', 'like', '%' . $search_amount . '%')
                          ->orWhere('customers.customer_name', 'like', '%' . $searchValue . '%')
                          ->orWhere('customer_receipt_cheques.cheque_number', 'like', '%' . $searchValue . '%')
                          ->orWhere('debtors_ledgers.external_number', 'like', '%' . $searchValue . '%');
                });
            }
        
            // Order the results
            $query->orderBy('customer_receipts.customer_receipt_id', 'desc');
        
            // Paginate the results
            $results = $query->take($pageLength)->skip($skip)->get();
        
            // Transform and modify results (similar to the second code)
            $results->transform(function ($item) {
                $buttons = '<button class="btn btn-primary btn-sm" id="btnEdit_12" onclick="edit(' . $item->customer_receipt_id . ')" style="display:none;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>&#160';
                $buttons .= '<button class="btn btn-success btn-sm" onclick="view(' . $item->customer_receipt_id . ')"><i class="fa fa-eye" aria-hidden="true"></i></button>';
                $buttons .= '<button class="btn btn-danger btn-sm" onclick="_delete(' . $item->customer_receipt_id . ')"><i class="fa fa-trash" aria-hidden="true"></i></button>';
                $buttons .= '<button class="btn btn-secondary btn-sm" onclick="generateReceiptList(' . $item->customer_receipt_id . ')"><i class="fa fa-print" aria-hidden="true"></i></button>';
                
                $item->buttons = $buttons;
        
                return $item;
            });
        
            return response()->json([
                'success' => 'Data loaded',
                'data' => $results,
            ]);
        } catch (Exception $ex) {
            return response()->json(["status" => false, "exception" => $ex->getMessage()]);
        }
        
    }
        
    
}

