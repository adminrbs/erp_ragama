<?php

namespace Modules\Prc\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use RepoEldo\ELD\ReportViewer;

class GoodsReturnSummeryReportController extends Controller
{
    public function goods_return_summery_report($filters){
        try{
            $searchOption = json_decode($filters);
            $fromDate = $searchOption[0]->fromDate;
            $toDate = $searchOption[1]->toDate;
            $supplygroup = [];
            foreach($searchOption[2]->supplygroup as $grp){
                array_push($supplygroup,$grp);

            }
            $branch = [];
            foreach($searchOption[3]->branch as $brn){
                array_push($branch,$brn);
            }
            }catch(Exception $ex){
                return $ex;
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

            if ($nonNullCount > 1) {
                $query = "SELECT DISTINCT GRN.external_number AS invoice_number ,
                GRN.goods_received_date_time AS Date ,
                GRN.supppier_invoice_number,
                
                S.supplier_code, 
                S.supplier_name,
                GRN.total_amount 
               FROM goodreceivereturns  GRN
               INNER JOIN suppliers S ON S.supplier_id=GRN.supplier_id
               INNER JOIN branches B ON B.branch_id = GRN.branch_id
               INNER JOIN supply_groups SG ON S.supply_group_id = SG.supply_group_id";
    
    
                $quryModify = "";
               
                if ($fromDate != null && $toDate != null) {
                    if ($nonNullCount > 2) {
                        $quryModify .= "GRN.goods_received_date_time between '" . $fromDate . "' AND '" . $toDate . "' AND ";
                    } else {
                        $quryModify .= "GRN.goods_received_date_time between '" . $fromDate . "' AND '" . $toDate . "'";
                    }
                }
                if ($supplygroup != null) {
    
                    if (count($supplygroup) > 1) {
                        $quryModify .= " SG.supply_group_id IN ('" . implode("', '", $supplygroup) . "') AND ";
                    } else {
                        $quryModify .= " SG.supply_group_id ='" . $supplygroup[0] . "' AND ";
                    }
                }
    
                if ($branch != null) {
                    if (count($branch) > 1) {
                        $quryModify .= " B.branch_id IN ('" . implode("', '", $branch) . "') AND ";
                    } else {
                        $quryModify .= " B.branch_id ='" . $branch[0] . "' AND ";
                    }
                }
    
    
              
    
                if ($quryModify !== "") {
                    $quryModify = rtrim($quryModify, 'AND OR ');
                    $query = $query . " where " . $quryModify;
                }
    
    
                //dd($query);
                $result = DB::select($query);
    
    
                $reportViwer = new ReportViewer();
                $reportViwer->addParameter("goods_receive_return_data_summery_table", $result);
            } 

            $total = 0;
            foreach ($result as $row) {
                $total += $row->total_amount;
            }
            $formattedTotal = number_format($total, 2, '.', ',');
            $concatenatedTotal = 'Grand Total' . ' ' . ' ' . $formattedTotal;
            $reportViwer->addParameter('total', $concatenatedTotal);
            $reportViwer->addParameter('companylogo', CompanyDetailsController::companyimage());
            $reportViwer->addParameter('companyName', CompanyDetailsController::CompanyName());
            $reportViwer->addParameter('companyAddress', CompanyDetailsController::CompanyAddress());
            $reportViwer->addParameter('companyNumber', CompanyDetailsController::CompanyNumber());
    
            //dd($length . " " . (strlen($filterLabel)));
          
    
    
            return $reportViwer->viewReport('goods_receive_return_summery_report.json');
    }
}
