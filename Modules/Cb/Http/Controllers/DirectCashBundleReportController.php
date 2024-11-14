<?php

namespace Modules\Cb\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use RepoEldo\ELD\ReportViewer;

class DirectCashBundleReportController extends Controller
{
    public function printCashBundle($id)
    {
        $query = "
	SELECT
        
        CR.customer_receipt_id,
		C.customer_name,
		CR.external_number AS receipt_no,
		CR.receipt_date,
		CR.amount,
		DL.external_number AS invoice_number,
		E.employee_name,
		DATEDIFF(CR.receipt_date, DL.trans_date) AS age,
		DL.amount AS invoice_amount,
		CRSD.paid_amount,
		CRSD.return_amount,
		CRSD.set_off_amount AS receipt_amount,
		(DL.amount - (COALESCE(CRSD.paid_amount, 0) + COALESCE(CRSD.set_off_amount, 0))) AS balance
	FROM
		direct_cash_bundles DCB
	LEFT JOIN direct_cash_bundle_datas DCBD ON DCB.direct_cash_bundle_id = DCBD.direct_cash_bundles_id
	LEFT JOIN customer_receipts CR ON DCBD.customer_receipt_id = CR.customer_receipt_id
	LEFT JOIN customer_receipt_setoff_data CRSD ON CR.customer_receipt_id = CRSD.customer_receipt_id
	LEFT JOIN debtors_ledgers DL ON CRSD.debtors_ledger_id = DL.debtors_ledger_id
	LEFT JOIN customers C ON CR.customer_id = C.customer_id
	LEFT JOIN employees E ON CR.collector_id = E.employee_id 
	WHERE
		DCB.direct_cash_bundle_id = " . intval($id);

        $result = DB::select($query);


        $resulcustomer_qry = " 
        SELECT
            CR.customer_receipt_id,
            C.customer_name,
            CR.external_number,
            C.customer_id
        FROM
            direct_cash_bundles DCB
        LEFT JOIN direct_cash_bundle_datas DCBD ON DCB.direct_cash_bundle_id = DCBD.direct_cash_bundles_id
        LEFT JOIN customer_receipts CR ON DCBD.customer_receipt_id = CR.customer_receipt_id
        LEFT JOIN customers C ON CR.customer_id = C.customer_id
        WHERE
            DCB.direct_cash_bundle_id = " . intval($id) . " 
       ";
        
 //dd($resulcustomer_qry);
    $resulcustomer = DB::select($resulcustomer_qry);
    /* foreach ($resulcustomer as $customerdata) {
        dump($customerdata->external_number);
    } */

$cash_bundle = DB::select("SELECT external_number FROM direct_cash_bundles WHERE direct_cash_bundle_id = $id");
        $customerablearray = [];
        $receipt_number_array = [];
        $titel = [];
        $reportViwer = new ReportViewer();
        $title = $cash_bundle[0]->external_number;
        //dd($resulcustomer);
       /*  if ($fromDate && $toDate) {
            $title .= " From : " . $fromDate . " To : " . $toDate;
        } */

        $reportViwer->addParameter("title", $title);

       
        foreach ($resulcustomer as $customerid) {


            if (!in_array($customerid->customer_receipt_id, $receipt_number_array, true)) {
                
                $table = [];
                //dd('dd');
                $bool = true;
                array_push($receipt_number_array, $customerid->customer_receipt_id);
                foreach ($result as $customerdata) {
                    
                    if ($customerdata->receipt_no == $customerid->external_number && $customerdata->customer_name == $customerid->customer_name  && $customerdata->customer_receipt_id == $customerid->customer_receipt_id ) {
                        //dump("fff");
                        $title_text =  "<strong>Customer Name : </strong>" . $customerid->customer_name . " - <strong>Receipt No : </strong>" . $customerdata->receipt_no . " - <strong>Receipt Date : </strong>" . $customerdata->receipt_date . " <strong>Amount : </strong>" .$customerdata->amount;
                        if ($bool) {
                            array_push($titel, $title_text);
                            $bool = false;
                        }
                           
                    
                            array_push($table, $customerdata);


                       
                    }
                }
                if (count($table) > 0) {
                    array_push($customerablearray, $table);
                    $reportViwer->addParameter('abc', $titel);
                }
            }
            /* $reportViwer->addParameter("no_of_cheques", "No of cheques :" . $no_of_cheques);
            $reportViwer->addParameter("balance", "Balance :" . $formatted_balance); */
            /* $rep_name_qry = DB::select("SELECT CONCAT(employee_name,' - ',employee_code) as employee_name FROM employees WHERE employees.employee_id = 2");
            $rep_name = $rep_name_qry[0]->employee_name;
 */

            
           
        }
        //dd($customerablearray);
            $reportViwer->addParameter("cash_bundle_table", [$customerablearray]);
            $reportViwer->addParameter('companylogo', CompanyDetailsController::companyimage());
            $reportViwer->addParameter('companyName', CompanyDetailsController::CompanyName());
            $reportViwer->addParameter('companyAddress', CompanyDetailsController::CompanyAddress());
            $reportViwer->addParameter('companyNumber', CompanyDetailsController::CompanyNumber());
            //$reportViwer->addParameter('rep_name', $rep_name);

            // dd($customerablearray);
            return $reportViwer->viewReport('CashBundleReport.json');
    }
}
