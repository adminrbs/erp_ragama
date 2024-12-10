<?php

namespace Modules\Sl\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use RepoEldo\ELD\ReportViewer;

class SupplierChequeAuditReportController extends Controller
{
   public function supplier_cheque_audit_report($search)
   {
      //dd($search);
      $searchOption = json_decode($search);
      $selectSupplier = $searchOption[0]->selectSupplier;
      $selectSupplygroup = $searchOption[1]->selectSupplygroup;
      $selecteBranch = $searchOption[2]->selecteBranch;
      $cmbgreaterthan = $searchOption[3]->cmbgreaterthan;
      $fromdate = $searchOption[4]->fromdate;
      $todate = $searchOption[5]->todate;
      $fromAge = $searchOption[6]->fromAge;
      $toAge = $searchOption[7]->toAge;

      $nonNullCount = 0;

      if ($searchOption !== null) {

         if ($searchOption[0]->selectSupplier !== null) {
            $nonNullCount++;
         }
         if ($searchOption[1]->selectSupplygroup !== null) {
            $nonNullCount++;
         }
         if ($searchOption[2]->selecteBranch !== null) {
            $nonNullCount++;
         }
         if ($searchOption[3]->cmbgreaterthan !== null) {
            $nonNullCount++;
         }
         if ($searchOption[4]->fromdate !== null) {
            $nonNullCount++;
         }
         if ($searchOption[5]->todate !== null) {
            $nonNullCount++;
         }
         if ($searchOption[6]->fromAge !== null) {
            $nonNullCount++;
         }
         if ($searchOption[7]->toAge !== null) {
            $nonNullCount++;
         }
      }

      $query_modify = ' WHERE  SPC.amount > 0 AND  SP.receipt_method_id = "2" AND ';
      if ($fromdate != null && $todate != null) {
         $query_modify .= 'SP.receipt_date BETWEEN "' . $fromdate . '" AND "' . $todate . '" AND';
      }

      if ($selectSupplier != null) {
         $query_modify .= ' SP.supplier_id = "' . $selectSupplier[0] . '" AND';
      }
      if ($selecteBranch != null) {
         $query_modify .= ' SP.branch_id = "' . $selecteBranch[0] . '" AND';
      }

      if ($fromdate == null && $todate == null  && $selectSupplier == null && $selecteBranch == null) {
         //$query_modify = "";
      } else {
         //$query_modify = preg_replace('/\W\w+\s*(\W*)$/', '$1', $query_modify);
      }
      $query_modify = preg_replace('/\W\w+\s*(\W*)$/', '$1', $query_modify);
      $qry = 'SELECT
      SP.supplier_id,
	    S.supplier_name,
	    SP.external_number,
	    SP.receipt_date,
		SPC.banking_date,
		SPC.cheque_number,
	   SPSD.reference_external_number AS invoice_no,
	    E.employee_name,
        CL.trans_date,
	    DATEDIFF( SP.receipt_date, CL.trans_date ) AS gap,
	    CL.amount,
	    ( SELECT CL.paidamount FROM creditors_ledger CL WHERE SPSD.reference_external_number = CL.external_number LIMIT 1 ) AS total_paid,
	    CL.return_amount,
	    SPSD.set_off_amount,
	    (
		    ( SELECT CL.amount FROM creditors_ledger WHERE SPSD.reference_external_number = CL.external_number LIMIT 1 ) - ( SELECT CL.paidamount FROM creditors_ledger WHERE SPSD.reference_external_number = CL.external_number LIMIT 1 ) 
	    ) AS balance,
		SPC.amount	
    FROM
	    supplier_payments SP
    LEFT JOIN supplier_payment_setoff_data SPSD ON SP.supplier_payment_id = SPSD.supplier_payments_id
    LEFT JOIN suppliers S ON SP.supplier_id = S.supplier_id
    LEFT JOIN employees E ON SP.collector_id = E.employee_id
    LEFT JOIN creditors_ledger CL ON SPSD.creditors_ledger_id = CL.creditors_ledger_id
		LEFT JOIN supplier_payment_cheques SPC ON SP.supplier_payment_id = SPC.supplier_payment_id
		LEFT JOIN banks B ON SPC.bank_id = B.bank_id
		LEFT JOIN bank_branches BB ON SPC.bank_branch_id' . $query_modify;
dd($qry);
      $result = DB::select($qry);

      $resultsupplier = DB::select("SELECT
	SP.supplier_payment_id,
	S.supplier_name,
   SP.receipt_date,
	SPC.cheque_number 
FROM
	supplier_payments SP
	LEFT JOIN suppliers S ON SP.supplier_id = S.supplier_id
	LEFT JOIN supplier_payment_cheques SPC ON SP.supplier_payment_id = SPC.supplier_payment_id");

      $customerablearray = [];
      $cheque_number_array = [];
      $titel = [];
      $reportViwer = new ReportViewer();
      $title = "Cheque Audit";
      if ($fromdate && $todate) {
         $title .= " From : " . $fromdate . " To : " . $todate;
      }

      $reportViwer->addParameter("title", $title);

      $no_of_cheques = 0;

      $total_balance = 0;

      foreach ($resultsupplier as $supplierid) {


         if (!in_array($supplierid->cheque_number, $cheque_number_array, true)) {
            $table = [];
            $cheque_amount = 0;
            $inv_amount = 0;
            $bool = true;
            array_push($cheque_number_array, $supplierid->cheque_number);
            foreach ($result as $supplierdata) {
               //dd($result);
               if ($supplierdata->cheque_number == $supplierid->cheque_number && $supplierdata->customer_receipt_id == $supplierid->customer_receipt_id) {
                  $cheque_amount += (float)$supplierdata->amount;
                  $title_text =  "<strong>Supplier Name : </strong>" . $supplierid->supplier_name . " - <strong>Ref No : </strong>" . $supplierdata->external_number . " - <strong>Receipt Date : </strong>" . $supplierdata->receipt_date . " <br> <strong>Bank : </strong>" . $supplierdata->bank_name . " - <strong>Branch : </strong>" . $supplierdata->bank_branch_name . " - <strong>Cheque No : </strong>" . $supplierdata->cheque_number . " - <strong>Banking Date : </strong>" . $supplierdata->banking_date . " - <strong>Cheque Amount :</strong>" . number_format($cheque_amount, 2);
                  if ($bool) {
                     array_push($titel, $title_text);
                     $cheque_amount += (float)$supplierdata->amount;
                     $no_of_cheques++;
                     $bool = false;
                     $total_balance = $total_balance + $supplierdata->amount;
                  }
                  array_push($table, $supplierdata);


                  if ($inv_amount == 0) {
                     $inv_amount = $supplierdata->amount;
                  }
                  if ($inv_amount > 0) {
                     $inv_amount  = $inv_amount - $supplierdata->Invoice_amont;
                  }
                  //$total_balance = $total_balance + $inv_amount;
               }
            }
            if (count($table) > 0) {
               array_push($customerablearray, $table);
               $reportViwer->addParameter('abc', $titel);
            }
         }
         /* $total_balance = $total_balance + $inv_amount; */
         $formatted_balance = number_format($total_balance, 2, '.', ',');
      }
      //dd($titel);
      $reportViwer->addParameter("no_of_cheques", "No of cheques :" . $no_of_cheques);
      $reportViwer->addParameter("balance", "Balance :" . $formatted_balance);
      //$rep_name_qry = DB::select("SELECT CONCAT(employee_name,' - ',employee_code) as employee_name FROM employees WHERE employees.employee_id = 2");
      //$rep_name = $rep_name_qry[0]->employee_name;

      $reportViwer->addParameter("cheque_audit_tabaledata", [$customerablearray]);
      $reportViwer->addParameter('companylogo', CompanyDetailsController::companyimage());
      $reportViwer->addParameter('companyName', CompanyDetailsController::CompanyName());
      $reportViwer->addParameter('companyAddress', CompanyDetailsController::CompanyAddress());
      $reportViwer->addParameter('companyNumber', CompanyDetailsController::CompanyNumber());
      // $reportViwer->addParameter('rep_name', $rep_name);

      // dd($customerablearray);
      return $reportViwer->viewReport('SupplierchequeAuditReport.json');
   }
}
