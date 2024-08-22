<?php

namespace Modules\Prc\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use RepoEldo\ELD\ReportViewer;

class PoHelpReportController extends Controller
{
   /*  public function poHelpReport($filters)
    {
        $filter_options = json_decode($filters);
        $fromDate = $filter_options->fromDate;
        $toDate = $filter_options->toDate;
        //$supply_group = $filter_options->supply_group;
        $branch = $filter_options->branch;

        $in_para_sales_where = "1";
        $in_para_stock_where = "1";

        if ($fromDate && $toDate && !$branch) {
            $in_para_sales_where = " SI.transaction_date BETWEEN '" . $fromDate . "'  AND '" . $toDate . "'";
            $in_para_stock_where .= " AND item_historys.transaction_date<='" . $toDate . "'";
        } else if ($fromDate && $toDate && $branch) {
            $in_para_sales_where = " SI.transaction_date BETWEEN '" . $fromDate . "'  AND '" . $toDate . "' AND B.branch_id IN ('" . implode("', '", $branch) . "') ";
            $in_para_stock_where .= " AND item_historys.transaction_date<='" . $toDate . "'  AND '" . $toDate . "' AND branch_id IN ('" . implode("', '", $branch) . "') ";
        } else if (!$fromDate && !$toDate && $branch) {
            $in_para_stock_where = " branch_id IN ('" . implode("', '", $branch) . "') ";
            $in_para_sales_where = " B.branch_id IN ('" . implode("', '", $branch) . "') ";
        }


        $result = DB::select('CALL report_po_help("' . $in_para_sales_where . '","' . $in_para_stock_where . '")');
        return response()->json(["data" => $result, "report_date" => "From " . $fromDate . " to " . $toDate]);
    } */


    public function poHelpReport($filters)
    {
        $filter_options = json_decode($filters);
        $fromDate = $filter_options->fromDate;
        $toDate = $filter_options->toDate;
        $supplygroup = $filter_options->supplygroup;
        $branch = $filter_options->branch;

        $in_para_sales_where = "1";
        $in_para_stock_where = "1";

        if ($fromDate && $toDate && !$branch && !$supplygroup) {
            $in_para_sales_where = " SI.transaction_date BETWEEN '" . $fromDate . "'  AND '" . $toDate . "'";
            $in_para_stock_where .= " AND IH.transaction_date<='" . $toDate . "'";
        } else if ($fromDate && $toDate && $branch && $supplygroup) {
            $in_para_sales_where = " SI.transaction_date BETWEEN '" . $fromDate . "'  AND '" . $toDate . "' AND B.branch_id IN ('" . implode("', '", $branch) . "') AND I.supply_group_id IN('".implode("','",$supplygroup) ."')";
            $in_para_stock_where .= " AND IH.transaction_date<='" . $toDate . "'  AND '" . $toDate . "' AND branch_id IN ('" . implode("', '", $branch) . "') AND I.supply_group_id IN('".implode("','",$supplygroup) ."')";
        } else if (!$fromDate && !$toDate && $branch  &&!$supplygroup) {
            $in_para_stock_where = " branch_id IN ('" . implode("', '", $branch) . "') ";
            $in_para_sales_where = " B.branch_id IN ('" . implode("', '", $branch) . "') ";
        }else if (!$fromDate && !$toDate && !$branch  && $supplygroup) {
            $in_para_stock_where = " I.supply_group_id IN('".implode("','",$supplygroup) ."') ";
            $in_para_sales_where = " I.supply_group_id IN('".implode("','",$supplygroup) ."') ";
        }else if (!$fromDate && !$toDate && $branch  && $supplygroup) {
            $in_para_stock_where = " supply_group_id IN('".implode("','",$supplygroup) ."') AND branch_id IN ('" . implode("', '", $branch) . "')";
            $in_para_sales_where = " I.supply_group_id IN('".implode("','",$supplygroup) ."') AND B.branch_id IN ('" . implode("', '", $branch) . "') ";
        }else if ($fromDate && $toDate && !$branch && $supplygroup) {
            $in_para_sales_where = " SI.transaction_date BETWEEN '" . $fromDate . "'  AND '" . $toDate . "' AND I.supply_group_id IN('".implode("','",$supplygroup) ."')";
            $in_para_stock_where .= " AND IH.transaction_date<='" . $toDate . "'  AND '" . $toDate . "' AND supply_group_id IN('".implode("','",$supplygroup) ."')";
            
        }


        $result = DB::select('CALL report_po_help("' . $in_para_sales_where . '","' . $in_para_stock_where . '")');
        
        return response()->json(["data" => $result, "report_date" => "From " . $fromDate . " to " . $toDate,"company_data"=>[
            'company_name' =>CompanyDetailsController::CompanyName(),
                 'company_address' => CompanyDetailsController::CompanyAddress(),
                 'contact_details' => CompanyDetailsController::CompanyNumber()

        ]]);
    }
}
