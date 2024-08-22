<?php

namespace Modules\Cb\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use RepoEldo\ELD\ReportViewer;

class ReportController extends Controller
{

    /* public function printreport($branch_id, $collector_id)

    { 
        $qry = 'SELECT sfa_receipts.customer_receipt_id,
        debtors_ledgers.external_number AS InvoiceNo ,
        sfa_receipts.receipt_status,
        customers.customer_id,
        debtors_ledgers.debtors_ledger_id,
        sfa_receipt_setoff_data.customer_receipt_setoff_data_id,


        sfa_receipts.external_number,
        sfa_receipts.receipt_date,
        debtors_ledgers.external_number as EX_num,
        debtors_ledgers.trans_date,
        LEFT(customers.customer_name, 30) AS customer_name,
        DATEDIFF(sfa_receipts.receipt_date,debtors_ledgers.trans_date) AS Gap,
        town_non_administratives.townName,
        sfa_receipt_setoff_data.set_off_amount
        
 FROM sfa_receipts
 LEFT JOIN customers ON sfa_receipts.customer_id = customers.customer_id
 LEFT JOIN sfa_receipt_setoff_data ON sfa_receipt_setoff_data.customer_receipt_id = sfa_receipts.customer_receipt_id 
 LEFT JOIN debtors_ledgers ON sfa_receipt_setoff_data.debtors_ledger_id = debtors_ledgers.debtors_ledger_id
 LEFT JOIN town_non_administratives ON customers.town = town_non_administratives.town_id
 LEFT JOIN sales_invoices ON debtors_ledgers.external_number = sales_invoices.external_number
 WHERE  sfa_receipts.collector_id = ' . $collector_id . ' AND receipt_status = 0 AND sfa_receipts.receipt_method_id = 1'; */
       // dd($qry);
       /*  $result = DB::select($qry);



        $resulcustomer = DB::select('select customer_id,customer_name from customers');

        $customerablearray = [];
        $titel = [];
        $reportViwer = new ReportViewer();
        foreach ($resulcustomer as $customerid) {
            $table = [];


            foreach ($result as $customerdata) { */
                //dd($result);
             /*    if ($customerdata->customer_id == $customerid->customer_id) {


                    array_push($table, $customerdata);
                }
            }



            if (count($table) > 0) {

                array_push($customerablearray, $table);


                array_push($titel, $customerid->customer_name);

                $reportViwer->addParameter('abc', $titel);
            }
        }

        $total = 0;
        foreach ($result as $row) {
            $total += $row->set_off_amount;
        }
        $formattedTotal = number_format($total, 2, '.', ',');
        $concatenatedTotal = 'Grand Total' . ' ' . ' ' . $formattedTotal;

        $reportViwer->addParameter('total', $concatenatedTotal);

        $rep_name_qry = DB::select("SELECT CONCAT(employee_name,' - ',employee_code) as employee_name FROM employees WHERE employees.employee_id = $collector_id");
        $rep_name = $rep_name_qry[0]->employee_name;

        $reportViwer->addParameter("sfareceipt_tabaledata", [$customerablearray]);
        $reportViwer->addParameter('companylogo', CompanyDetailsController::companyimage());
        $reportViwer->addParameter('companyName', CompanyDetailsController::CompanyName());
        $reportViwer->addParameter('companyAddress', CompanyDetailsController::CompanyAddress());
        $reportViwer->addParameter('companyNumber', CompanyDetailsController::CompanyNumber());
        $reportViwer->addParameter('rep_name', $rep_name); */

       // $book_qry = DB::select("SELECT CONCAT('Book: ',books.book_name,'-',books.book_number) as book_name FROM books WHERE books.book_id = '" . $book . "'");
      //  $books_data = $book_qry[0]->book_name;
        //$page_data = 'Page: ' . $page;
      /*   $reportViwer->addParameter('Book', $books_data . " - " . $page_data);

        $reportViwer->addParameter('page', $page_data); */


    /*     return $reportViwer->viewReport('Cash_collected_by_branch_cashier.json');
    }
 */



    public function printCashTable($request,$collector_id)

    { 
        $decodedIds = urldecode($request);
        $cashIdArray = explode(',', $decodedIds);
        $cashIdString = "'" . implode("', '", $cashIdArray) . "'";
        $qry = "SELECT sfa_receipts.customer_receipt_id,
                    debtors_ledgers.external_number AS InvoiceNo,
                    sfa_receipts.receipt_status,
                    customers.customer_id,
                    debtors_ledgers.debtors_ledger_id,
                    sfa_receipt_setoff_data.customer_receipt_setoff_data_id,
                    sfa_receipts.external_number,
                    sfa_receipts.receipt_date,
                    debtors_ledgers.external_number as EX_num,
                    debtors_ledgers.trans_date,
                    LEFT(customers.customer_name, 30) AS customer_name,
                    DATEDIFF(sfa_receipts.receipt_date, debtors_ledgers.trans_date) AS Gap,
                    town_non_administratives.townName,
                    sfa_receipt_setoff_data.set_off_amount
             FROM sfa_receipts
             LEFT JOIN customers ON sfa_receipts.customer_id = customers.customer_id
             LEFT JOIN sfa_receipt_setoff_data ON sfa_receipt_setoff_data.customer_receipt_id = sfa_receipts.customer_receipt_id
             LEFT JOIN debtors_ledgers ON sfa_receipt_setoff_data.debtors_ledger_id = debtors_ledgers.debtors_ledger_id
             LEFT JOIN town_non_administratives ON customers.town = town_non_administratives.town_id
             LEFT JOIN sales_invoices ON debtors_ledgers.external_number = sales_invoices.external_number
             WHERE sfa_receipts.customer_receipt_id IN ($cashIdString)";
    
        $result = DB::select($qry);



        $resulcustomer = DB::select('select customer_id,customer_name from customers');

        $customerablearray = [];
        $titel = [];
        $reportViwer = new ReportViewer();
        foreach ($resulcustomer as $customerid) {
            $table = [];


            foreach ($result as $customerdata) {
                
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

        $total = 0;
        foreach ($result as $row) {
            $total += $row->set_off_amount;
        }
        $formattedTotal = number_format($total, 2, '.', ',');
        $concatenatedTotal = 'Grand Total' . ' ' . ' ' . $formattedTotal;

        $reportViwer->addParameter('total', $concatenatedTotal);

        $rep_name_qry = DB::select("SELECT CONCAT(employee_name,' - ',employee_code) as employee_name FROM employees WHERE employees.employee_id = $collector_id");
        if (count($rep_name_qry) > 0) {
            $rep_name = $rep_name_qry[0]->employee_name;
        } else {
            $rep_name = '';
        }

        $reportViwer->addParameter("sfareceipt_tabaledata", [$customerablearray]);
        $reportViwer->addParameter('companylogo', CompanyDetailsController::companyimage());
        $reportViwer->addParameter('companyName', CompanyDetailsController::CompanyName());
        $reportViwer->addParameter('companyAddress', CompanyDetailsController::CompanyAddress());
        $reportViwer->addParameter('companyNumber', CompanyDetailsController::CompanyNumber());
        $reportViwer->addParameter('rep_name', 'Sales Rep :'.$rep_name);

        return $reportViwer->viewReport('Cash_collected_by_branch_cashier.json');
    }
}
