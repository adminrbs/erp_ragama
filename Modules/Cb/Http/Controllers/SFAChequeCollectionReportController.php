<?php

namespace Modules\Cb\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use RepoEldo\ELD\ReportViewer;

class SFAChequeCollectionReportController extends Controller
{
   public function printSFAChequeBundle($id){
    $qry = "SELECT
	CR.customer_receipt_id,
	C.customer_name,
	CR.external_number AS receipt_no,
	CR.receipt_date,
	CR.amount,
	B.bank_name,
	BB.bank_branch_name,
	CRC.cheque_number,
	DL.external_number,
	E.employee_name,
	DATEDIFF( CR.receipt_date, DL.trans_date ) AS age,
	DL.amount AS invoice_amount,
	SRSD.set_off_amount AS receipt_amount 
FROM
	cheque_collections CC
	INNER JOIN sfa_receipts CR ON CC.cheque_collection_id = CR.cheque_collection_id
	INNER JOIN sfa_receipt_cheques CRC ON CR.customer_receipt_id = CRC.customer_receipt_id
	INNER JOIN sfa_receipt_setoff_data SRSD ON SRSD.customer_receipt_id = CR.customer_receipt_id
	INNER JOIN debtors_ledgers DL ON SRSD.debtors_ledger_id = DL.debtors_ledger_id
	INNER JOIN customers C ON CR.customer_id = C.customer_id
	INNER JOIN banks B ON CRC.bank_id = B.bank_id
	INNER JOIN bank_branches BB ON CRC.bank_branch_id = BB.bank_branch_id
	INNER JOIN employees E ON CR.collector_id = E.employee_id 
WHERE
	CC.cheque_collection_id =".$id;

    $result = DB::select($qry);

    DB::select($qry);
    $customerQuery = "SELECT
	DL.external_number,
	C.customer_name,
	CR.customer_receipt_id,
	CRC.cheque_number,
	
FROM
	cheque_collections CC
	INNER JOIN sfa_receipts CR ON CC.cheque_collection_id = CR.cheque_collection_id
	INNER JOIN sfa_receipt_cheques CRC ON CR.customer_receipt_id = CRC.customer_receipt_id
	INNER JOIN sfa_receipt_setoff_data SRSD ON SRSD.customer_receipt_id = CR.customer_receipt_id
	INNER JOIN debtors_ledgers DL ON SRSD.debtors_ledger_id = DL.debtors_ledger_id
	INNER JOIN customers C ON CR.customer_id = C.customer_id
	INNER JOIN banks B ON CRC.bank_id = B.bank_id
	INNER JOIN bank_branches BB ON CRC.bank_branch_id = BB.bank_branch_id
	INNER JOIN employees E ON CR.collector_id = E.employee_id 
WHERE
	CC.cheque_collection_id =".$id;
    $resulcustomer = DB::select($customerQuery);
    $chque_bundle = DB::select("SELECT external_number FROM cheque_collections WHERE cheque_collection_id = $id");
        $customerablearray = [];
        $receipt_number_array = [];
        $titel = [];
        $reportViwer = new ReportViewer();
        $title = $chque_bundle[0]->external_number;
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
        $reportViwer->addParameter("cheque_bundle_table", [$customerablearray]);
        $reportViwer->addParameter('companylogo', CompanyDetailsController::companyimage());
        $reportViwer->addParameter('companyName', CompanyDetailsController::CompanyName());
        $reportViwer->addParameter('companyAddress', CompanyDetailsController::CompanyAddress());
        $reportViwer->addParameter('companyNumber', CompanyDetailsController::CompanyNumber());
        //$reportViwer->addParameter('rep_name', $rep_name);

         //dd($customerablearray);
        return $reportViwer->viewReport('SfaChequeBundleReport.json');

   }
}
