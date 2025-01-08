<?php

namespace Modules\Gl\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Gl\Entities\gl_account;
use RepoEldo\ELD\ReportViewer;

class GlAccountReportController extends Controller
{
   public function loadGLAccounts()
   {
       $glAccounts = gl_account::all();
       return response()->json($glAccounts);
   }

   public function ledger($filters)
   {

   // dd($filters);
    $filter_options = json_decode($filters);
  // dd($filter_options);

    $selectfromdate = $filter_options[1]->selectfromdate;
    $selecttodate = $filter_options[2]->selecttodate;
    $selectAccount = $filter_options[0]->selectAccount;
  


    $query_modify = ' WHERE ';
    if ($selectfromdate != null && $selecttodate != null) {
        $query_modify .= 'GL.transaction_date BETWEEN "' . $selectfromdate . '" AND "' . $selecttodate . '" AND';
    }
    if ($selectAccount != null) {
        
        $query_modify .= ' GL.gl_account_id = "' . $selectAccount[0] . '" AND';
    }
   
    $query_modify = preg_replace('/\W\w+\s*(\W*)$/', '$1', $query_modify);

    $query = 'SELECT 
    GL.external_number,
    GL.transaction_date,
    GL.description,
    IFNULL(ABS(GL.amount), 0) AS amount,
    IFNULL(ABS(GL.paid_amount), 0) AS paid_amount,
    GL.gl_account_id
FROM 
    general_ledger GL';

    $query .= $query_modify;
    //dd($query);
    $result = DB::select($query);

    $reportViwer = new ReportViewer();
    $reportViwer->addParameter("debtor_reports_tabaledata", $result);
    $reportViwer->addParameter('companylogo', CompanyDetailsController::companyimage());
    $reportViwer->addParameter('companyName', CompanyDetailsController::CompanyName());
    $reportViwer->addParameter('companyAddress', CompanyDetailsController::CompanyAddress());
    $reportViwer->addParameter('companyNumber', CompanyDetailsController::CompanyNumber());
    return $reportViwer->viewReport('gl_report.json');
   }
}
