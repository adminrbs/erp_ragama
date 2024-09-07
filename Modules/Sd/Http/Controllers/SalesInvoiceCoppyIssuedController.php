<?php

namespace Modules\Sd\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Sd\Entities\employee;
use Modules\Sd\Entities\SalesInvoiceCopyIssued;
use RepoEldo\ELD\ReportViewer;

class SalesInvoiceCoppyIssuedController extends Controller
{

    //load employees
    public function loadEmpforsalesInvoicecopyIssued()
    {
        $emp = employee::all();
        if ($emp) {
            return response()->json($emp);
        }
    }

    //load invoice data
    public function load_invoice_details_for_invoie_copy($number)
    {
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
    AND SII.sales_invoice_Id IS NULL';

        //dd($inv_data_header_qry);
        $result = DB::select($inv_data_header_qry);

        if ($result) {
            return response()->json(["header" => $result, "status" => true]);
        } else {
            return response()->json(["status" => false]);
        }
    }

    public function load_inv_for_copy_issued()
    {
        $data = DB::table('sales_invoices')
            ->join('deliveryconfirmations', 'sales_invoices.sales_invoice_Id', '=', 'deliveryconfirmations.sales_invoice_Id')
            ->select('sales_invoices.external_number')
            ->get();
        return response()->json($data);
    }

    public function saveInvoiceCopyIssued(Request $request)
    {
        try {
            $collection = json_decode($request->input('collection'));
            $empId = $request->input('emp');

            foreach ($collection as $id) {
                $copyIssued = new SalesInvoiceCopyIssued();
                $copyIssued->sales_invoice_Id = $id;
                $copyIssued->user_id = Auth::user()->id;
                $copyIssued->employee_id = $empId;
                $copyIssued->status = 0;
                $copyIssued->remark = $request->input('txtRemark');
                $copyIssued->save();
            }
            return response()->json(["status" => true]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function load_invoice_details_for_invoie_copy_received($id)
    {
        try {
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
            WHERE SII.status = 0 AND SII.employee_id = ' . $id;

            $result = DB::select($inv_data_header_qry);

            return response()->json(["header" => $result]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function saveInvoiceCopyReceived(Request $request)
    {
        try {
            $collection = json_decode($request->input('collection'));
            foreach ($collection as $id) {
                $copyIssued = SalesInvoiceCopyIssued::find($id);
                $copyIssued->status = 1;
                $copyIssued->update();
            }
            return response()->json(["status" => true]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function loadEmpforsalesInvoiceRecieved()
    {
        try {
            $qry = "SELECT DISTINCT E.employee_name,E.employee_id FROM employees E INNER JOIN sales_invoice_copy_issueds SICI ON E.employee_id = SICI.employee_id";
            $result = DB::select($qry);

            if ($result) {
                return response()->json(["status" => true, "data" => $result]);
            } else {
                return response()->json(["status" => false, "data" => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function sales_invoice_copy_issued_report($collection, $collector_id)
    {

        $col = json_decode($collection);
        $col_list = implode(',', array_map('intval', $col));
        $qry = "
    SELECT
       
        SI.external_number, 
         C.customer_name, 
        SI.order_date_time,
        DL.amount, 
        DL.paidamount, 
        (DL.amount - DL.paidamount) AS balance 
    FROM sales_invoices SI 
    LEFT JOIN customers C ON SI.customer_id = C.customer_id  
    LEFT JOIN debtors_ledgers DL ON SI.external_number = DL.external_number
    LEFT JOIN sales_invoice_copy_issueds SII ON SI.sales_invoice_Id = SII.sales_invoice_Id
    WHERE SI.sales_invoice_Id IN (" . $col_list . ");
";
        //dd($qry);
        $result = DB::select($qry);

        //dd($result);
        $reportViwer = new ReportViewer();



        $rep_name_qry = DB::select("SELECT CONCAT(employee_name,' - ',employee_code) as employee_name FROM employees WHERE employees.employee_id = $collector_id");
        if (count($rep_name_qry) > 0) {
            $rep_name = $rep_name_qry[0]->employee_name;
        } else {
            $rep_name = '';
        }

        $reportViwer->addParameter("invoice_table", $result);
        $reportViwer->addParameter('companylogo', CompanyDetailsController::companyimage());
        $reportViwer->addParameter('companyName', CompanyDetailsController::CompanyName());
        $reportViwer->addParameter('companyAddress', CompanyDetailsController::CompanyAddress());
        $reportViwer->addParameter('companyNumber', CompanyDetailsController::CompanyNumber());
        $reportViwer->addParameter('rep_name', 'Sales Rep :' . $rep_name);

        return $reportViwer->viewReport('invoice_copy_issued.json');
    }


    public function sales_invoice_copy_received_report($collection, $collector_id)
    {
        $col = json_decode($collection);
        $col_list = implode(',', array_map('intval', $col));

        $qry = "
    SELECT
       
        SI.external_number, 
         C.customer_name, 
        SI.order_date_time,
        DL.amount, 
        DL.paidamount, 
        (DL.amount - DL.paidamount) AS balance 
    FROM sales_invoices SI 
    LEFT JOIN customers C ON SI.customer_id = C.customer_id  
    LEFT JOIN debtors_ledgers DL ON SI.external_number = DL.external_number
    LEFT JOIN sales_invoice_copy_issueds SII ON SI.sales_invoice_Id = SII.sales_invoice_Id
    WHERE SI.sales_invoice_Id IN (" . $col_list . ");";
        //dd($qry);
        $result = DB::select($qry);

        //dd($result);
        $reportViwer = new ReportViewer();



        $rep_name_qry = DB::select("SELECT CONCAT(employee_name,' - ',employee_code) as employee_name FROM employees WHERE employees.employee_id = $collector_id");
        if (count($rep_name_qry) > 0) {
            $rep_name = $rep_name_qry[0]->employee_name;
        } else {
            $rep_name = '';
        }

        $reportViwer->addParameter("invoice_table", $result);
        $reportViwer->addParameter('companylogo', CompanyDetailsController::companyimage());
        $reportViwer->addParameter('companyName', CompanyDetailsController::CompanyName());
        $reportViwer->addParameter('companyAddress', CompanyDetailsController::CompanyAddress());
        $reportViwer->addParameter('companyNumber', CompanyDetailsController::CompanyNumber());
        $reportViwer->addParameter('rep_name', 'Sales Rep :' . $rep_name);

        return $reportViwer->viewReport('invoice_copy_received.json');
    }
}
