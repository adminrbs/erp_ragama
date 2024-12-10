<?php

namespace Modules\Prc\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use RepoEldo\ELD\ReportViewer;

class BonusClaimSummeryReportController extends Controller
{
    public function bonusClaimSummeryReport($filters)
    {
        try {
            $searchOption = json_decode($filters);
            $fromDate = $searchOption[0]->fromDate;
            $toDate = $searchOption[1]->toDate;

            $branch = [];
            foreach ($searchOption[3]->branch as $brn) {
                array_push($branch, $brn);
            }

            $nonNullCount = 0;

            if ($searchOption !== null) {

                if ($searchOption[0]->fromDate !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[1]->toDate !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[2]->supplygroup !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[3]->branch !== null) {
                    $nonNullCount++;
                }
            }

            $quryModify = "";

            if ($fromDate != null && $toDate != null) {
                if ($nonNullCount > 2) {
                    $quryModify .= "DATE(BCI.created_at) between '" . $fromDate . "' AND '" . $toDate . "' AND ";
                } else {
                    $quryModify .= "DATE(BCI.created_at) between '" . $fromDate . "' AND '" . $toDate . "'";
                }
            }


            if ($branch != null) {
                if (count($branch) > 1) {
                    $quryModify .= " BC.branch_id IN ('" . implode("', '", $branch) . "') AND ";
                } else {
                    $quryModify .= " BC.branch_id ='" . $branch[0] . "' AND ";
                }
            }

            $qry = "SELECT
            BC.bonus_claim_Id,
	BC.external_number,
	DATE( BC.created_at ) AS created_at,
	BC.supppier_invoice_number,
	I.Item_code,
	BCI.item_name,
	ABS(BCI.quantity),
	ABS(BCI.price),
    ABS(BCI.quantity * BCI.price) AS Amount
FROM
	bonus_claims BC
	INNER JOIN bonus_claim_items BCI ON BC.bonus_claim_Id = BCI.bonus_claim_Id
	INNER JOIN items I ON BCI.item_id = I.item_id ";
            if ($quryModify !== "") {
                $quryModify = rtrim($quryModify, 'AND OR ');
                $qry = $qry . " where " . $quryModify;
            }
            $qry = $qry . "ORDER BY I.Item_code ASC";
            $result = DB::select($qry);

            $bonus_data = $result;

            $customerablearray = [];
      $ref_number_array = [];
      $titel = [];
      $reportViwer = new ReportViewer();
      $title = "Bonus Claim Summery";
      //$branch_title = "";
      if ($fromDate && $toDate) {
         $title .= " From : " . $fromDate . " To : " . $toDate;
      }

      $reportViwer->addParameter("title", $title);

      foreach ($bonus_data as $supplierid) {


         if (!in_array($supplierid->external_number, $ref_number_array, true)) {
            $table = [];
           
            $bool = true;
            array_push($ref_number_array, $supplierid->external_number);
            foreach ($result as $supplierdata) {
               
               if ($supplierdata->external_number == $supplierid->external_number && $supplierdata->bonus_claim_Id == $supplierid->bonus_claim_Id) {
                 
                  $title_text =  "<strong>Reference Name : </strong>" . $supplierid->external_number . " - <strong>Date: </strong>" . $supplierdata->created_at ." <strong>Supplier Invoice :</strong>" .$supplierdata->supppier_invoice_number;
                  if ($bool) {
                     array_push($titel, $title_text);
                    
                     $bool = false;
                    
                  }
                  array_push($table, $supplierdata);


                 
                  
                  
               }
            }
            if (count($table) > 0) {
               array_push($customerablearray, $table);
               $reportViwer->addParameter('abc', $titel);
            }
         }
        
      }
     

      $reportViwer->addParameter("bonus_tabaledata", [$customerablearray]);
      $reportViwer->addParameter('companylogo', CompanyDetailsController::companyimage());
      $reportViwer->addParameter('companyName', CompanyDetailsController::CompanyName());
      $reportViwer->addParameter('companyAddress', CompanyDetailsController::CompanyAddress());
      $reportViwer->addParameter('companyNumber', CompanyDetailsController::CompanyNumber());
    
            return $reportViwer->viewReport('BonusClaimReport.json');
        } catch (Exception $ex) {
            return $ex;
        }
    }
}
