<?php

namespace Modules\Cb\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use RepoEldo\ELD\ReportViewer;

class ChequeRegisterController extends Controller
{
    public function chequeRegister($filters)
    {
        $filter_options = json_decode($filters);
        $fromDate = $filter_options->fromDate;
        $toDate = $filter_options->toDate;
        $salesRep = $filter_options->salesRep;
        $customer = $filter_options->customer;
        $branch = $filter_options->branch;


        $query_modify = ' WHERE  customer_receipt_cheques.amount > 0 AND  customer_receipts.receipt_method_id = "2" AND  ';
        if ($fromDate != null && $toDate != null) {
            $query_modify .= 'customer_receipts.receipt_date BETWEEN "' . $fromDate . '" AND "' . $toDate . '" AND ';
        }
        if ($salesRep != null) {
            $query_modify .= 'debtors_ledgers.employee_id = "' . $salesRep[0] . '"  AND ';
        }
        if ($customer != null) {
            $query_modify .= 'customer_receipts.customer_id = "' . $customer[0] . '"  AND ';
        }
        if ($branch != null) {
            $query_modify .= 'customer_receipts.branch_id = "' . $branch[0] . '"  AND ';
        }

        if ($fromDate == null && $toDate == null && $salesRep == null && $customer == null && $branch == null) {
            //$query_modify = "";
        } else {
            //$query_modify = preg_replace('/\W\w+\s*(\W*)$/', '$1', $query_modify);
        }
        $query_modify = preg_replace('/\W\w+\s*(\W*)$/', '$1', $query_modify);
        $qry = ' SELECT DISTINCT
        customer_receipts.receipt_date,
        customer_receipts.external_number,
        customers.customer_name,
        banks.bank_name,
        customer_receipt_cheques.cheque_number,
        IFNULL(customer_receipt_cheques.amount,0) AS amount,
        customer_receipt_cheques.banking_date,
        customer_receipt_cheques.cheque_deposit_date AS diposited,
        customer_receipt_cheques.cheque_dishonoured_date AS return_date,
        "" AS rediposit
        
     
        
 FROM customer_receipts
 INNER JOIN customer_receipt_setoff_data ON  customer_receipt_setoff_data.customer_receipt_id=customer_receipts.customer_receipt_id 
 INNER JOIN debtors_ledgers ON debtors_ledgers.debtors_ledger_id =  customer_receipt_setoff_data.debtors_ledger_id  

 LEFT JOIN customer_receipt_cheques ON customer_receipts.customer_receipt_id = customer_receipt_cheques.customer_receipt_id
 LEFT JOIN customers ON customer_receipts.customer_id = customers.customer_id
 LEFT JOIN banks ON customer_receipt_cheques.bank_id = banks.bank_id
 LEFT JOIN bank_branches ON customer_receipt_cheques.bank_branch_id = bank_branches.bank_branch_id
 LEFT JOIN sales_invoices ON sales_invoices.internal_number=debtors_ledgers.internal_number ' . $query_modify . 'ORDER BY banking_date ASC';



        $result = DB::select($qry);

        $reportViwer = new ReportViewer();
        $title = "Customer's Cheque Register";
        if ($fromDate && $toDate) {
            $title .= " From : " . $fromDate . " To : " . $toDate;
        }

        $reportViwer->addParameter("title", $title);


        $reportViwer->addParameter("cheques_tabaledata", $result);
        $reportViwer->addParameter('companyName', CompanyDetailsController::CompanyName());

        return $reportViwer->viewReport('cheque_register.json');
    }


     public function getSalesrepandcollectors()
    {
        try {
            $query = "SELECT E.employee_id,E.employee_name FROM employees E WHERE E.desgination_id=7 OR E.desgination_id = 8";
            $data = DB::select($query);
            return response()->json($data);
        } catch (Exception $ex) {
            return $ex;
        }
    }
}
