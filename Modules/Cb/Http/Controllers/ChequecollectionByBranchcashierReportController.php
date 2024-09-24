<?php

namespace Modules\Cb\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use RepoEldo\ELD\ReportViewer;

class ChequecollectionByBranchcashierReportController extends Controller
{

    /* public function branch_cashier_report($branch_id, $collector, $book, $page)

    {
        try {



            $qry = ' SELECT 
        sfa_receipts.customer_receipt_id,
        customers.customer_id,
        sfa_receipts.receipt_status,
        sfa_receipts.external_number,
        customers.customer_name,
        sfa_receipts.receipt_date,
        sfa_receipt_cheques.banking_date,
        sfa_receipt_cheques.cheque_number,
        IfNull(sales_invoices.manual_number , debtors_ledgers.external_number ) AS InvoiceNo ,
       
        sfa_receipt_setoff_data.set_off_amount As Invoice_amont ,
        CAST(DATEDIFF(sfa_receipts.receipt_date,debtors_ledgers.trans_date)AS SIGNED) AS Age,
        sfa_receipt_cheques.amount    
     
        
 FROM sfa_receipts
 INNER JOIN sfa_receipt_setoff_data ON  sfa_receipt_setoff_data.customer_receipt_id=sfa_receipts.customer_receipt_id 
 INNER JOIN debtors_ledgers ON debtors_ledgers.debtors_ledger_id =  sfa_receipt_setoff_data.debtors_ledger_id  
 
 LEFT JOIN sfa_receipt_cheques ON sfa_receipts.customer_receipt_id = sfa_receipt_cheques.customer_receipt_id
 LEFT JOIN customers ON sfa_receipts.customer_id = customers.customer_id
 LEFT JOIN sales_invoices ON sales_invoices.internal_number=debtors_ledgers.internal_number
 WHERE sfa_receipts.branch_id = ' . $branch_id . ' AND sfa_receipts.collector_id =' . $collector . ' AND sfa_receipts.receipt_status = 0 AND sfa_receipts.receipt_method_id = 2';

            $result = DB::select($qry);



            $resulcustomer = DB::select('select sfa_receipts.customer_receipt_id,customers.customer_id,customers.customer_name 
        from sfa_receipts INNER JOIN customers ON sfa_receipts.customer_id = customers.customer_id');

            $customerablearray = [];
            $titel_array = [];
            $reportViwer = new ReportViewer();
            foreach ($resulcustomer as $customerid) {

                $table = [];

                $title = "";
                $cheque_amount = 0;
                foreach ($result as $customerdata) {
                    //dd($result);
                    if ($customerdata->customer_receipt_id == $customerid->customer_receipt_id && $customerid->customer_id == $customerdata->customer_id) {
                        $cheque_amount += (float)$customerdata->amount;
                        $title = "&emsp;&emsp;<strong>Cheque No : </strong>" . $customerdata->cheque_number . "&emsp;&emsp;<strong>Rff No : </strong>" . $customerdata->external_number. " - <strong>Cheque Amount :</strong>" . number_format($cheque_amount, 2);
                        array_push($table, $customerdata);
                    }
                }



                if (count($table) > 0) {

                    //if ($customerid->customer_id == $customerdata->customer_id) {
                    $title = "<strong>Customer Name : </strong>" . $customerid->customer_name . "" . $title;
                    array_push($customerablearray, $table);
                    array_push($titel_array,$title);
                    $reportViwer->addParameter('abc', $titel_array);
                    //}
                }
            }

            $rep_name_qry = DB::select("SELECT CONCAT(employee_name,' - ',employee_code) as employee_name FROM employees WHERE employees.employee_id = 2");
            $rep_name = $rep_name_qry[0]->employee_name;

            $reportViwer->addParameter("branchCashier_tabaledata", [$customerablearray]);
            $reportViwer->addParameter('companylogo', CompanyDetailsController::companyimage());
            $reportViwer->addParameter('companyName', CompanyDetailsController::CompanyName());
            $reportViwer->addParameter('companyAddress', CompanyDetailsController::CompanyAddress());
            $reportViwer->addParameter('companyNumber', CompanyDetailsController::CompanyNumber());
            $reportViwer->addParameter('rep_name', $rep_name);

          
            $reportViwer->addParameter('route_total', ['']);
            return $reportViwer->viewReport('Cheque_collection_By_Branch_cashier.json');
        } catch (Exception $ex) {
            return $ex;
        }
    } */


    public function print_chq_rcpt_Table($branchids, $collector)

    {
        try {
            $decodedIds = urldecode($branchids);
            $chqIdArray = explode(',', $decodedIds);
            $chqIdString = "'" . implode("', '", $chqIdArray) . "'";


            $qry = "SELECT 
            sfa_receipts.customer_receipt_id,
            customers.customer_id,
            sfa_receipts.receipt_status,
            sfa_receipts.external_number,
            customers.customer_name,
            sfa_receipts.receipt_date,
            sfa_receipt_cheques.banking_date,
            sfa_receipt_cheques.cheque_number,
            IFNULL(sales_invoices.external_number, debtors_ledgers.external_number) AS InvoiceNo,
            sfa_receipt_setoff_data.set_off_amount AS Invoice_amount,
            CAST(DATEDIFF(sfa_receipts.receipt_date, debtors_ledgers.trans_date) AS SIGNED) AS Age,
            sfa_receipt_cheques.amount    
        FROM sfa_receipts
        LEFT JOIN sfa_receipt_setoff_data ON sfa_receipt_setoff_data.customer_receipt_id = sfa_receipts.customer_receipt_id 
        LEFT JOIN debtors_ledgers ON debtors_ledgers.debtors_ledger_id = sfa_receipt_setoff_data.debtors_ledger_id  
        LEFT JOIN sfa_receipt_cheques ON sfa_receipts.customer_receipt_id = sfa_receipt_cheques.customer_receipt_id
        LEFT JOIN customers ON sfa_receipts.customer_id = customers.customer_id
        LEFT JOIN sales_invoices ON sales_invoices.internal_number = debtors_ledgers.internal_number
        WHERE sfa_receipts.customer_receipt_id IN ($chqIdString)";


            $result = DB::select($qry);
           

//dd($qry);

            $resulcustomer = DB::select('select sfa_receipts.customer_receipt_id,customers.customer_id,customers.customer_name 
        from sfa_receipts INNER JOIN customers ON sfa_receipts.customer_id = customers.customer_id');

            $customerablearray = [];
            $titel_array = [];
            $reportViwer = new ReportViewer();
            foreach ($resulcustomer as $customerid) {

                $table = [];

                $title = "";
                $cheque_amount = 0;
                foreach ($result as $customerdata) {
                    //dd($result);
                    if ($customerdata->customer_receipt_id == $customerid->customer_receipt_id && $customerid->customer_id == $customerdata->customer_id) {
                        $cheque_amount = (float)$customerdata->amount;
                        $title = "&emsp;&emsp;<strong>Cheque No : </strong>" . $customerdata->cheque_number . "&emsp;&emsp;<strong>Rff No : </strong>" . $customerdata->external_number. " - <strong>Cheque Amount :</strong>" . number_format($cheque_amount, 2);
                        array_push($table, $customerdata);
                    }
                }



                if (count($table) > 0) {

                    //if ($customerid->customer_id == $customerdata->customer_id) {
                    $title = "<strong>Customer Name : </strong>" . $customerid->customer_name . "" . $title;
                    array_push($customerablearray, $table);
                    array_push($titel_array,$title);
                    $reportViwer->addParameter('abc', $titel_array);
                    //}
                }
            }

            $rep_name_qry = DB::select("SELECT CONCAT(employee_name,' - ',employee_code) as employee_name FROM employees WHERE employees.employee_id = 2");
            $rep_name = $rep_name_qry[0]->employee_name;

            $reportViwer->addParameter("branchCashier_tabaledata", [$customerablearray]);
            $reportViwer->addParameter('companylogo', CompanyDetailsController::companyimage());
            $reportViwer->addParameter('companyName', CompanyDetailsController::CompanyName());
            $reportViwer->addParameter('companyAddress', CompanyDetailsController::CompanyAddress());
            $reportViwer->addParameter('companyNumber', CompanyDetailsController::CompanyNumber());
            $reportViwer->addParameter('rep_name', $rep_name);

            //$book_qry = DB::select("SELECT CONCAT('Book: ',books.book_name,'-',books.book_number) as book_name FROM books WHERE books.book_id = '" . $book . "'");
           // $books_data = $book_qry[0]->book_name;
           // $page_data = 'Page: ' . $page;
          //  $reportViwer->addParameter('Book', $books_data . " - " . $page_data);

          //  $reportViwer->addParameter('page', $page_data);
            $reportViwer->addParameter('route_total', ['']);
            return $reportViwer->viewReport('Cheque_collection_By_Branch_cashier.json');
        } catch (Exception $ex) {
            return $ex;
        }
    }
}
