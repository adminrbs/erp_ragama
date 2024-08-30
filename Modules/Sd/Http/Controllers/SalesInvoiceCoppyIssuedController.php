<?php

namespace Modules\Sd\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Sd\Entities\employee;
use Modules\Sd\Entities\SalesInvoiceCopyIssued;

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
    LEFT JOIN sales_invoice_copy_issueds SII ON SI.sales_invoice_Id = SII.sales_invoice_Id
    WHERE SI.external_number = \'' . $number . '\'
    AND (SII.sales_invoice_Id IS NULL OR SII.status = 0)
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

    public function saveInvoiceCopyIssued(Request $request){
        try{
            $collection = json_decode($request->input('collection'));
            $empId = $request->input('emp');
            
            foreach($collection as $id){
                $copyIssued = new SalesInvoiceCopyIssued();
                $copyIssued->sales_invoice_Id = $id;
                $copyIssued->user_id = Auth::user()->id;
                $copyIssued->empoyee_id = $empId;
                $copyIssued->status = 0;
                $copyIssued->remark = $request->input('txtRemark');
                $copyIssued->save();

            }
            return response()->json(["status" => true]);
        }catch(Exception $ex){
            return $ex;
        }
    }

    public function load_invoice_details_for_invoie_copy_received(){
        try{
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
                (DL.amount - DL.paidamount) as balance,
                SII.sales_invoice_copy_issued_id 
            FROM sales_invoices SI 
           
            LEFT JOIN customers C ON SI.customer_id = C.customer_id  
            LEFT JOIN employees E ON SI.employee_id = E.employee_id
            LEFT JOIN debtors_ledgers DL ON SI.external_number = DL.external_number
            LEFT JOIN sales_invoice_copy_issueds SII ON SI.sales_invoice_Id = SII.sales_invoice_Id
            WHERE SII.status = 0
        ';

        $result = DB::select($inv_data_header_qry);

        return response()->json(["header" => $result]);
        }catch(Exception $ex){
            return $ex;
        }
    }

    public function saveInvoiceCopyReceived(Request $request){
        try{
            $collection = json_decode($request->input('collection'));
            foreach($collection as $id){
                $copyIssued = SalesInvoiceCopyIssued::find($id);
                $copyIssued->status = 1;
                $copyIssued->update();
            }
            return response()->json(["status" => true]);
        }catch(Exception $ex){
            return $ex;
        }
    }
}
