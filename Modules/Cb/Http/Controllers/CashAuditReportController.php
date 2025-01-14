<?php

namespace Modules\Cb\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use RepoEldo\ELD\ReportViewer;

class CashAuditReportController extends Controller
{
    public function cashAuditReport($filters)
    {
        $filter_options = json_decode($filters);
        $fromDate = $filter_options->fromDate;
        $toDate = $filter_options->toDate;
        $salesRep = $filter_options->salesRep;
        $customer = $filter_options->customer;
        $branch = $filter_options->branch;


        $query_modify = ' WHERE customer_receipts.receipt_method_id = "1" AND ';
        if ($fromDate != null && $toDate != null) {
            $query_modify .= 'customer_receipts.receipt_date BETWEEN "' . $fromDate . '" AND "' . $toDate . '" AND';
        }
        if ($salesRep != null) {
            //$query_modify .= ' debtors_ledgers.employee_id = "' . $salesRep[0] . '" AND';
            $query_modify .= ' customer_receipts.collector_id = "' . $salesRep[0] . '" AND';
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
        /*$qry = ' SELECT customer_receipts.customer_receipt_id,
        debtors_ledgers.external_number AS InvoiceNo,
        customer_receipts.receipt_status,
        customers.customer_id,
        debtors_ledgers.debtors_ledger_id,
        customer_receipt_setoff_data.customer_receipt_setoff_data_id,
        customer_receipts.external_number,
        customer_receipts.receipt_date,
        debtors_ledgers.external_number as EX_num,
        E.employee_name,
        debtors_ledgers.trans_date,
        customers.customer_name AS customer_name,
        DATEDIFF(customer_receipts.receipt_date,debtors_ledgers.trans_date) AS Gap,
        (SELECT debtors_ledgers.amount  FROM debtors_ledgers WHERE customer_receipt_setoff_data.reference_external_number = debtors_ledgers.external_number LIMIT 1),
        (SELECT debtors_ledgers.paidamount  FROM debtors_ledgers WHERE customer_receipt_setoff_data.reference_external_number = debtors_ledgers.external_number LIMIT 1),
        (SELECT SUM(sales_return_debtor_setoffs.setoff_amount) FROM sales_return_debtor_setoffs WHERE sales_return_debtor_setoffs.external_number = debtors_ledgers.external_number),
        customer_receipt_setoff_data.set_off_amount,
        ((SELECT debtors_ledgers.amount  FROM debtors_ledgers WHERE customer_receipt_setoff_data.reference_external_number = debtors_ledgers.external_number LIMIT 1) - (SELECT debtors_ledgers.paidamount  FROM debtors_ledgers WHERE customer_receipt_setoff_data.reference_external_number = debtors_ledgers.external_number LIMIT 1)) AS balance    
 FROM customer_receipts
        LEFT JOIN customers ON customer_receipts.customer_id = customers.customer_id
        LEFT JOIN customer_receipt_setoff_data ON customer_receipt_setoff_data.customer_receipt_id = customer_receipts.customer_receipt_id 
        LEFT JOIN debtors_ledgers ON customer_receipt_setoff_data.debtors_ledger_id = debtors_ledgers.debtors_ledger_id
        LEFT JOIN employees E ON customer_receipts.collector_id = E.employee_id
        LEFT JOIN sales_invoices ON debtors_ledgers.external_number = sales_invoices.external_number ' . $query_modify;*/

        $qry = 'WITH aggregated_setoffs AS (
            SELECT external_number, SUM(setoff_amount) AS total_setoff_amount
            FROM sales_return_debtor_setoffs
            GROUP BY external_number
        ),
        reference_debtors AS (
            SELECT external_number, MAX(amount) AS amount, MAX(paidamount) AS paidamount
            FROM debtors_ledgers
            GROUP BY external_number
        )
        SELECT 
            customer_receipts.customer_receipt_id,
            debtors_ledgers.external_number AS InvoiceNo,
            customer_receipts.receipt_status,
            customers.customer_id,
            debtors_ledgers.debtors_ledger_id,
            customer_receipt_setoff_data.customer_receipt_setoff_data_id,
            customer_receipts.external_number,
            customer_receipts.receipt_date,
            debtors_ledgers.external_number as EX_num,
            E.employee_name,
            debtors_ledgers.trans_date,
            customers.customer_name AS customer_name,
            DATEDIFF(customer_receipts.receipt_date, debtors_ledgers.trans_date) AS Gap,
            reference_debtors.amount,
            reference_debtors.paidamount,
            aggregated_setoffs.total_setoff_amount,
            customer_receipt_setoff_data.set_off_amount,
            (reference_debtors.amount - reference_debtors.paidamount) AS balance    
        FROM customer_receipts
        LEFT JOIN customers 
            ON customer_receipts.customer_id = customers.customer_id
        LEFT JOIN customer_receipt_setoff_data 
            ON customer_receipt_setoff_data.customer_receipt_id = customer_receipts.customer_receipt_id 
        LEFT JOIN debtors_ledgers 
            ON customer_receipt_setoff_data.debtors_ledger_id = debtors_ledgers.debtors_ledger_id
        LEFT JOIN employees E 
            ON customer_receipts.collector_id = E.employee_id
        LEFT JOIN sales_invoices 
            ON debtors_ledgers.external_number = sales_invoices.external_number
        LEFT JOIN aggregated_setoffs 
            ON debtors_ledgers.external_number = aggregated_setoffs.external_number
        LEFT JOIN reference_debtors 
            ON customer_receipt_setoff_data.reference_external_number = reference_debtors.external_number ' . $query_modify;


        $result = DB::select($qry);

        $query2 = 'SELECT SUM(customer_receipts.amount) AS total_cash_amount FROM customer_receipts ' . $query_modify;
        $result2 = DB::select($query2);
        $total_cash_amount = 0;
        foreach ($result2 as $res) {
            $total_cash_amount = $res->total_cash_amount;
        }

        //dd($result);

        $resulcustomer = DB::select('select customer_id,customer_name from customers');

        $customerablearray = [];
        $titel = [];
        $reportViwer = new ReportViewer();
        $title = "Cash Audit";
        if ($fromDate && $toDate) {
            $title .= " From : " . $fromDate . " To : " . $toDate;
        }
        $totalCash = 0;
        $reportViwer->addParameter("title", $title);
        foreach ($resulcustomer as $customerid) {
            $table = [];


            foreach ($result as $customerdata) {
                //dd($result);
                if ($customerdata->customer_id == $customerid->customer_id) {

                    $totalCash += $customerdata->set_off_amount;
                    array_push($table, $customerdata);
                }
            }



            if (count($table) > 0) {

                array_push($customerablearray, $table);


                array_push($titel, $customerid->customer_name);

                $reportViwer->addParameter('abc', $titel);
            }
            $formatted_balance = number_format($totalCash, 2, '.', ',');
        }
        $reportViwer->addParameter("balance", "Total Cash Amount :" . number_format($total_cash_amount, 2, '.', ','));
        $reportViwer->addParameter("cash_audit_tabaledata", [$customerablearray]);
        $reportViwer->addParameter('companyName', CompanyDetailsController::CompanyName());

        return $reportViwer->viewReport('cashAuditReport.json');
    }
}
