<?php

namespace Modules\Dl\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use RepoEldo\ELD\ReportViewer;

class DebitNoteReceiptController extends Controller
{
   public function print_dl($id){
      $qry = DB::select("SELECT D.trans_date, D.external_number,CONCAT(C.customer_code,'-',C.customer_name) AS customer_name,E.employee_name,CONCAT(
    SUBSTRING(D.narration_for_account, 1, 35), 
    '<br>', 
    REPLACE(D.narration_for_account, SUBSTRING(D.narration_for_account, 1, 35), '')
) AS narration_for_account,D.description,D.amount FROM debit_notes D INNER JOIN customers C ON D.customer_id = C.customer_id INNER JOIN employees E ON D.employee_id = E.employee_id WHERE D.debit_notes_id = $id");
      $customer = $qry[0]->customer_name;
      //$signLine  = "Prepared By"."<div>&nbsp</div>      "."Authorized By";
      $reportViwer = new ReportViewer();
      $reportViwer->addParameter("debit_note_data_table", $qry);
      $reportViwer->addParameter("customer", $customer);
      $reportViwer->addParameter('company_name', CompanyDetailsController::CompanyName());
      $reportViwer->addParameter('companyAddress', CompanyDetailsController::CompanyAddress());
     // $reportViwer->addParameter('signature',$signLine);

      return $reportViwer->viewReport('debit_note_receipt.json');
   }
}
