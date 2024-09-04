<?php

namespace Modules\Cb\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class CustomerReceiptListController extends Controller
{
    public function getReceiptList()
    {
        try {
            $query = "SELECT customer_receipts.customer_receipt_id,
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
    END AS payment_mode FROM customer_receipts
            LEFT JOIN customer_receipt_cheques ON customer_receipts.customer_receipt_id = customer_receipt_cheques.customer_receipt_id
            INNER JOIN customers ON customer_receipts.customer_id = customers.customer_id ORDER BY customer_receipts.external_number DESC";
            $data = DB::select($query);
            return response()->json(["status" => true, "data" => $data]);
        } catch (Exception $ex) {
            return response()->json(["status" => false, "exception" => $ex]);
        }
    }
}
