<?php

namespace Modules\Sd\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Sd\Entities\employee;

class SalesInvoiceCoppyIssuedController extends Controller
{

    //load employees
    public function loadEmpforsalesInvoicecopyIssued(){
        $emp = employee::all();
        if($emp){
            return response()->json($emp);
        }
    }

    //load invoice data
    public function load_invoice_details_for_invoie_copy($number){
        $inv_data_header_qry = '
        SELECT
            SI.sales_invoice_Id, 
            SI.external_number, 
            SI.order_date_time,
            C.customer_id, 
            C.customer_name, 
            E.employee_name, 
            DL.amount, 
            DL.paidamount, 
            (DL.amount - DL.paidamount) as balance 
        FROM sales_invoices SI 
       
        LEFT JOIN customers C ON SI.customer_id = C.customer_id  
        LEFT JOIN employees E ON SI.employee_id = E.employee_id
        LEFT JOIN debtors_ledgers DL ON SI.external_number = DL.external_number
        WHERE SI.external_number = \'' . $number . '\'
    ';
    //dd($inv_data_header_qry);
            $result = DB::select($inv_data_header_qry);

            return response()->json(["header" => $result]);
    }

    public function load_inv_for_copy_issued(){
        $data = DB::table('sales_invoices')
        ->join('deliveryconfirmations', 'sales_invoices.sales_invoice_Id', '=', 'deliveryconfirmations.sales_invoice_Id')
        ->select('sales_invoices.external_number')
        ->get();
        return response()->json($data);
    }
}
