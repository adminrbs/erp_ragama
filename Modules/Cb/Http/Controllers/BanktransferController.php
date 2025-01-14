<?php

namespace Modules\Cb\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use RepoEldo\ELD\ReportViewer;

class BanktransferController extends Controller
{


    public function bankTransfer($filters)
    {
        //dd('f');
        $filter_options = json_decode($filters);
        $fromDate = $filter_options->fromDate;
        $toDate = $filter_options->toDate;
        $salesRep = $filter_options->salesRep;
        $customer = $filter_options->customer;
        $branch = $filter_options->branch;


        $query_modify = ' WHERE customer_receipts.receipt_method_id = "7" AND ';
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
        $qry = ' SELECT customer_receipts.customer_receipt_id,
        debtors_ledgers.external_number AS InvoiceNo ,
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
        ((SELECT debtors_ledgers.amount  FROM debtors_ledgers WHERE customer_receipt_setoff_data.reference_external_number = debtors_ledgers.external_number LIMIT 1) - (SELECT debtors_ledgers.paidamount  FROM debtors_ledgers WHERE customer_receipt_setoff_data.reference_external_number = debtors_ledgers.external_number LIMIT 1)) AS balance,
        CRBS.reference,
        CRBS.slip_time,
        CRBS.slip_date
 FROM customer_receipts
 LEFT JOIN customers ON customer_receipts.customer_id = customers.customer_id
 LEFT JOIN customer_receipt_setoff_data ON customer_receipt_setoff_data.customer_receipt_id = customer_receipts.customer_receipt_id
 LEFT JOIN customer_receipt_bank_slips CRBS ON  customer_receipts.customer_receipt_id = CRBS.customer_receipt_id
 LEFT JOIN debtors_ledgers ON customer_receipt_setoff_data.debtors_ledger_id = debtors_ledgers.debtors_ledger_id
 LEFT JOIN employees E ON customer_receipts.collector_id = E.employee_id
 
 LEFT JOIN sales_invoices ON debtors_ledgers.external_number = sales_invoices.external_number ' . $query_modify;


        //dd($qry);
        $result = DB::select($qry);



        // $resulcustomer = DB::select('select customer_id,customer_name from customers');
        $resulcustomer = DB::select('SELECT DISTINCT
	customer_receipts.customer_receipt_id,
	customers.customer_name,
    customers.customer_id,
	CRBS.reference
FROM
	customer_receipts
	INNER JOIN customers ON customer_receipts.customer_id = customers.customer_id
	INNER JOIN customer_receipt_bank_slips CRBS ON customer_receipts.customer_receipt_id = CRBS.customer_receipt_id');
    //dd($resulcustomer);
        $customerablearray = [];
        $reference_array = [];
        $titel = [];
        $reportViwer = new ReportViewer();
        $title = "Bank Transfers";
        if ($fromDate && $toDate) {
            $title .= " From : " . $fromDate . " To : " . $toDate;
        }

        $reportViwer->addParameter("title", $title);
        foreach ($resulcustomer as $customerid) {
            if (!in_array($customerid->customer_receipt_id, $reference_array, true)) {
                $table = [];

                $bool = true;
                array_push($reference_array, $customerid->customer_receipt_id);
                foreach ($result as $customerdata) {
                    //dump($customerdata->customer_id);
                    //dd($result);
                    if ($customerdata->customer_id == $customerid->customer_id && $customerdata->reference == $customerid->reference && $customerdata->customer_receipt_id == $customerid->customer_receipt_id) {
                        $title_text =  "<strong>Customer Name : </strong>" . $customerid->customer_name . " - <strong>Reference : </strong>" . $customerdata->reference . " - <strong>Slip Date : </strong>" . $customerdata->slip_date . " - " . $customerdata->slip_time;
                        if ($bool) {
                            array_push($titel, $title_text);
                          
                            $bool = false;
                        }
                        array_push($table, $customerdata);
                        //array_push($titel, $title_text);
                        //array_push($table, $customerdata);
                    }
                }



                if (count($table) > 0) {

                    array_push($customerablearray, $table);


                    // array_push($titel, $customerid->customer_name);

                    $reportViwer->addParameter('abc', $titel);
                }
            }
        }

        $reportViwer->addParameter("cash_audit_tabaledata", [$customerablearray]);
        $reportViwer->addParameter('companyName', CompanyDetailsController::CompanyName());

        return $reportViwer->viewReport('bankTransferReport.json');
    }
}
