<?php

namespace Modules\Cb\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use App\Models\branch;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Cb\Entities\Customer;
use Modules\Md\Entities\employee;
use RepoEldo\ELD\ReportViewer;

class ChqueAuditReportController extends Controller
{

    public function chequeAuditReport($filters)
    {
        
        $filter_options = json_decode($filters);
        $fromDate = $filter_options->fromDate;
        $toDate = $filter_options->toDate;
        $salesRep = $filter_options->salesRep;
        $customer = $filter_options->customer;
        $branch = $filter_options->branch;


        $query_modify = ' WHERE customer_receipt_cheques.amount > 0 AND  customer_receipts.receipt_method_id = "2" AND ';
        if ($fromDate != null && $toDate != null) {
            $query_modify .= 'customer_receipts.receipt_date BETWEEN "' . $fromDate . '" AND "' . $toDate . '" AND';
        }
        if ($salesRep != null) {
            $query_modify .= ' debtors_ledgers.employee_id = "' . $salesRep[0] . '" AND';
        }
        if ($customer != null) {
            $query_modify .= ' customer_receipts.customer_id = "' . $customer[0] . '" AND';
        }
        if ($branch != null) {
            $query_modify .= ' customer_receipts.branch_id = "' . $branch[0] . '" AND';
        }

        if ($fromDate == null && $toDate == null && $salesRep == null && $customer == null && $branch == null) {
            //$query_modify = "";
        } else {
            //$query_modify = preg_replace('/\W\w+\s*(\W*)$/', '$1', $query_modify);
        }
        $query_modify = preg_replace('/\W\w+\s*(\W*)$/', '$1', $query_modify);
        $qry = ' SELECT DISTINCT
        customer_receipts.customer_receipt_id,
        customers.customer_id,
        customer_receipts.receipt_status,
        customer_receipts.external_number,
        customers.customer_name,
        customer_receipts.receipt_date,
        customer_receipt_cheques.banking_date,
        customer_receipt_cheques.cheque_number,
        IfNull(sales_invoices.external_number , debtors_ledgers.external_number ) AS InvoiceNo ,
        banks.bank_name,
        bank_branches.bank_branch_name,
        IFNULL(debtors_ledgers.amount,0) As Invoice_amont,
        CAST(DATEDIFF(customer_receipts.receipt_date,debtors_ledgers.trans_date)AS SIGNED) AS Age,
        IFNULL(customer_receipt_cheques.amount,0) AS amount
     
        
 FROM customer_receipts
 INNER JOIN customer_receipt_setoff_data ON  customer_receipt_setoff_data.customer_receipt_id=customer_receipts.customer_receipt_id 
 INNER JOIN debtors_ledgers ON debtors_ledgers.debtors_ledger_id =  customer_receipt_setoff_data.debtors_ledger_id  

 LEFT JOIN customer_receipt_cheques ON customer_receipts.customer_receipt_id = customer_receipt_cheques.customer_receipt_id
 LEFT JOIN customers ON customer_receipts.customer_id = customers.customer_id
 LEFT JOIN banks ON customer_receipt_cheques.bank_id = banks.bank_id
 LEFT JOIN bank_branches ON customer_receipt_cheques.bank_branch_id = bank_branches.bank_branch_id
 LEFT JOIN sales_invoices ON sales_invoices.internal_number=debtors_ledgers.internal_number ' . $query_modify;


//dd($qry);
        $result = DB::select($qry);



        $resulcustomer = DB::select('select customer_id,customer_name from customers');

        $customerablearray = [];
        $titel = [];
        $reportViwer = new ReportViewer();
        foreach ($resulcustomer as $customerid) {
            $table = [];


            foreach ($result as $customerdata) {
                //dd($result);
                if ($customerdata->customer_id == $customerid->customer_id) {


                    array_push($table, $customerdata);
                }
            }



            if (count($table) > 0) {

                array_push($customerablearray, $table);


                array_push($titel, $customerid->customer_name);

                $reportViwer->addParameter('abc', $titel);
            }
        }

        $reportViwer->addParameter("cheque_audit_tabaledata", [$customerablearray]);
        $reportViwer->addParameter('companyName', CompanyDetailsController::CompanyName());

        return $reportViwer->viewReport('chequeAuditReport.json');
    }
}
