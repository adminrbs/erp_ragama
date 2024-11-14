<?php

namespace Modules\Cb\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use RepoEldo\ELD\ReportViewer;

class SfaCashBundleReportController extends Controller
{
    public function printSfaCashBundle($id)
    {
        $query = "SELECT
    sfa_receipts.customer_receipt_id,
    sfa_receipts.amount,
    C.customer_name,
    DL.external_number AS receipt_no,
    E.employee_name,
    sfa_receipts.receipt_date,
    DATEDIFF(sfa_receipts.receipt_date, DL.trans_date) AS age,
    DL.amount AS invoice_amount,
	sfa_receipt_setoff_data.set_off_amount AS receipt_amount
        
FROM
    cash_bundles CB
    LEFT JOIN cash_bundles_datas CBD ON CB.cash_bundles_id = CBD.cash_bundles_id
    LEFT JOIN sfa_receipts ON CBD.customer_receipt_id = sfa_receipts.customer_receipt_id
    LEFT JOIN sfa_receipt_setoff_data ON sfa_receipts.customer_receipt_id = sfa_receipt_setoff_data.customer_receipt_id
    LEFT JOIN debtors_ledgers DL ON CBD.sales_invoice_Id = DL.debtors_ledger_id
    LEFT JOIN employees E ON sfa_receipts.collector_id = E.employee_id
	LEFT JOIN customers C ON DL.customer_id = C.customer_id	 
WHERE
    CB.cash_bundles_id = " . $id;
//dd( $query);
        $result = DB::select($query);

        $resulcustomer_qry = "SELECT
    
    DL.external_number,
		C.customer_name,
        sfa_receipts.customer_receipt_id
FROM
    cash_bundles CB
    LEFT JOIN cash_bundles_datas CBD ON CB.cash_bundles_id = CBD.cash_bundles_id
    LEFT JOIN sfa_receipts ON CBD.customer_receipt_id = sfa_receipts.customer_receipt_id
    LEFT JOIN sfa_receipt_setoff_data ON sfa_receipts.customer_receipt_id = sfa_receipt_setoff_data.customer_receipt_id
    LEFT JOIN debtors_ledgers DL ON CBD.sales_invoice_Id = DL.debtors_ledger_id
	LEFT JOIN customers C ON DL.customer_id = C.customer_id	 
WHERE
    CB.cash_bundles_id = " . $id;

    $resulcustomer = DB::select($resulcustomer_qry);
    $cash_bundle = DB::select("SELECT external_number FROM cash_bundles WHERE cash_bundles_id = $id");
        $customerablearray = [];
        $receipt_number_array = [];
        $titel = [];
        $reportViwer = new ReportViewer();
        $title = $cash_bundle[0]->external_number;
        //dd($resulcustomer);
      
        $reportViwer->addParameter("title", $title);

       
        foreach ($resulcustomer as $customerid) {


            if (!in_array($customerid->customer_receipt_id, $receipt_number_array, true)) {
                
                $table = [];
                //dd('dd');
                $bool = true;
                array_push($receipt_number_array, $customerid->customer_receipt_id);
                foreach ($result as $customerdata) {
                    
                    if ($customerdata->receipt_no == $customerid->external_number && $customerdata->customer_name == $customerid->customer_name ) {
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
            

            
           
        }
        //dd($customerablearray);
            $reportViwer->addParameter("cash_bundle_table", [$customerablearray]);
            $reportViwer->addParameter('companylogo', CompanyDetailsController::companyimage());
            $reportViwer->addParameter('companyName', CompanyDetailsController::CompanyName());
            $reportViwer->addParameter('companyAddress', CompanyDetailsController::CompanyAddress());
            $reportViwer->addParameter('companyNumber', CompanyDetailsController::CompanyNumber());
            //$reportViwer->addParameter('rep_name', $rep_name);

             //dd($customerablearray);
            return $reportViwer->viewReport('SfaCashBundleReport.json');
    }
}
