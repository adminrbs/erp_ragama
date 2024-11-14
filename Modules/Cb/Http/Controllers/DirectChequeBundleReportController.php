<?php

namespace Modules\Cb\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use RepoEldo\ELD\ReportViewer;

class DirectChequeBundleReportController extends Controller
{
    public function printChequeBundle($id)
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
		(DL.amount - (COALESCE(CRSD.paid_amount, 0) + COALESCE(CRSD.set_off_amount, 0))) AS balance,
        B.bank_name,
        BB.bank_branch_name,
        CRC.cheque_number

	FROM
		direct_cheque_collections DCB
	LEFT JOIN customer_receipts CR ON DCB.direct_cheque_collection_id = CR.cheque_collection_id
    LEFT JOIN customer_receipt_cheques CRC ON CR.customer_receipt_id = CRC.customer_receipt_id
	LEFT JOIN customer_receipt_setoff_data CRSD ON CR.customer_receipt_id = CRSD.customer_receipt_id
	LEFT JOIN debtors_ledgers DL ON CRSD.debtors_ledger_id = DL.debtors_ledger_id
	LEFT JOIN customers C ON CR.customer_id = C.customer_id
	LEFT JOIN employees E ON CR.collector_id = E.employee_id
    LEFT JOIN banks B ON CRC.bank_id = B.bank_id
    LEFT JOIN bank_branches BB ON CRC.bank_branch_id = BB.bank_branch_id 
	WHERE
		DCB.direct_cheque_collection_id = " . intval($id);

        $result = DB::select($query);


        $resulcustomer_qry = " 
        SELECT
            CR.customer_receipt_id,
            C.customer_name,
            CR.external_number,
            C.customer_id,
            CRC.cheque_number
        FROM
		direct_cheque_collections DCB
	LEFT JOIN customer_receipts CR ON DCB.direct_cheque_collection_id = CR.cheque_collection_id
    LEFT JOIN customer_receipt_cheques CRC ON CR.customer_receipt_id = CRC.customer_receipt_id
	LEFT JOIN customer_receipt_setoff_data CRSD ON CR.customer_receipt_id = CRSD.customer_receipt_id
	LEFT JOIN debtors_ledgers DL ON CRSD.debtors_ledger_id = DL.debtors_ledger_id
	LEFT JOIN customers C ON CR.customer_id = C.customer_id
	

	WHERE
		DCB.direct_cheque_collection_id = " . intval($id) . " 
       ";

        //dd($resulcustomer_qry);
        $resulcustomer = DB::select($resulcustomer_qry);
        

        $cheque_bundle = DB::select("SELECT external_number FROM direct_cheque_collections WHERE direct_cheque_collection_id = $id");
        $customerablearray = [];
        $receipt_number_array = [];
        $titel = [];
        $reportViwer = new ReportViewer();
        $title = $cheque_bundle[0]->external_number;
       

        $reportViwer->addParameter("title", $title);


        foreach ($resulcustomer as $customerid) {


            if (!in_array($customerid->customer_receipt_id, $receipt_number_array, true)) {

                $table = [];
                $bool = true;
                array_push($receipt_number_array, $customerid->customer_receipt_id);
                foreach ($result as $customerdata) {

                    if ($customerdata->receipt_no == $customerid->external_number && $customerdata->customer_name == $customerid->customer_name  && $customerdata->customer_receipt_id == $customerid->customer_receipt_id && $customerdata->cheque_number == $customerid->cheque_number) {
                        //dump("fff");
                        $title_text =  "<strong>Customer Name : </strong>" . $customerid->customer_name . " - <strong>Receipt No : </strong>" . $customerdata->receipt_no . " - <strong>Receipt Date : </strong>" . $customerdata->receipt_date . " <strong>Amount : </strong>" . $customerdata->amount . "<br><strong>Bank :".$customerdata->bank_name ."</strong><strong>Branch :".$customerdata->bank_branch_name."</strong><strong>".$customerdata->cheque_number."</strong>";
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
        $reportViwer->addParameter("cheque_bundle_table", [$customerablearray]);
        $reportViwer->addParameter('companylogo', CompanyDetailsController::companyimage());
        $reportViwer->addParameter('companyName', CompanyDetailsController::CompanyName());
        $reportViwer->addParameter('companyAddress', CompanyDetailsController::CompanyAddress());
        $reportViwer->addParameter('companyNumber', CompanyDetailsController::CompanyNumber());
        //$reportViwer->addParameter('rep_name', $rep_name);

        // dd($customerablearray);
        return $reportViwer->viewReport('ChequeBundleReport.json');
    }
}
