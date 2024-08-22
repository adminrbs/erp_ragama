<?php

namespace Modules\Sd\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class SalesRepWiseMonthlySummaryReportController extends Controller
{
  public function salesRepwiseMonthlySummary($search)
  {

    try {
      $search_option = json_decode($search);
      $selecteBranch = $search_option[0]->selecteBranch;
      $fromdate = $search_option[1]->fromdate;
      $toDate = $search_option[2]->todate;
      $selectSalesrep = $search_option[3]->selectSalesrep;

      $query = "SELECT 
    SWSR.employee_id,SWSR.branch_id, 
    CONCAT(DATE_FORMAT(SWSR.transaction_date, '%Y'), ' - ', DATE_FORMAT(SWSR.transaction_date, '%M')) AS transaction_month,
    SUM(CASE WHEN SWSR.transaction_type = 'Sales Return' THEN (SWSR.quantity - 1) * SWSR.price ELSE (SWSR.quantity - 1) * SWSR.price END) AS total_net_amount,
    E.code,E.employee_name
FROM 
    sales_with_sales_returns SWSR
LEFT JOIN employees E ON SWSR.employee_id = E.employee_id
WHERE 
    SWSR.transaction_date BETWEEN '2024-06-01' AND '2024-07-10'";

      if ($selecteBranch != null) {

        $query .= " AND SWSR.branch_id IN ('" . implode("','", $selecteBranch) . "')";
      }

      if ($selectSalesrep != null) {

        $query .= " AND SWSR.employee_id IN ('" . implode("','", $selectSalesrep) . "')";
      }

      $query .= "
    GROUP BY 
      SWSR.employee_id,
      DATE_FORMAT(SWSR.transaction_date, '%Y-%m')
    ORDER BY
      SWSR.employee_id,
      transaction_month";

      //dd($query);
      $result = DB::select($query);
      //dd($result);
      $branches_list = DB::select("SELECT branch_id,branch_name FROM branches");
      //dd($branches_list);
      $data_array = [];
      $exist_branch_list = [];
      foreach ($branches_list as $branch) {

        foreach ($result as $data) {
          if ($branch->branch_id == $data->branch_id) {
            $data->branch_name = $branch->branch_name;
            $data_array[$branch->branch_id][] = $data;
            if (!in_array($branch->branch_id, $exist_branch_list)) {
              $exist_branch_list[] = $branch->branch_id;
          }
          }
        }
      }






      return response()->json(["body" => $data_array, "report_date" => " From " . $fromdate . " to " . $toDate, "branch_list"=>$exist_branch_list,"company_data" => [
        'company_name' => CompanyDetailsController::CompanyName(),
        'company_address' => CompanyDetailsController::CompanyAddress(),
        'contact_details' => CompanyDetailsController::CompanyNumber()
        
      ]]);
    } catch (Exception $ex) {
      return response()->json($ex);
    }
  }
}
