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
      $fromdate = $search_option[5]->fromdate;
      $toDate = $search_option[6]->todate;

      $query = "SELECT
      branch_name,
      supplier_name,
      sales_rep_id,
      sales_rep,
      SUM(CASE WHEN MONTH(transaction_date) = 1 THEN net_amount ELSE 0 END) AS January_Amount,
      SUM(CASE WHEN MONTH(transaction_date) = 2 THEN net_amount ELSE 0 END) AS February_Amount,
      SUM(CASE WHEN MONTH(transaction_date) = 3 THEN net_amount ELSE 0 END) AS March_Amount,
      SUM(CASE WHEN MONTH(transaction_date) = 4 THEN net_amount ELSE 0 END) AS April_Amount,
      SUM(CASE WHEN MONTH(transaction_date) = 5 THEN net_amount ELSE 0 END) AS May_Amount,
      SUM(CASE WHEN MONTH(transaction_date) = 6 THEN net_amount ELSE 0 END) AS June_Amount,
      SUM(CASE WHEN MONTH(transaction_date) = 7 THEN net_amount ELSE 0 END) AS July_Amount,
        SUM(CASE WHEN MONTH(transaction_date) = 8 THEN net_amount ELSE 0 END) AS August_Amount,
        SUM(CASE WHEN MONTH(transaction_date) = 9 THEN net_amount ELSE 0 END) AS September_Amount,
        SUM(CASE WHEN MONTH(transaction_date) = 10 THEN net_amount ELSE 0 END) AS October_Amount,
        SUM(CASE WHEN MONTH(transaction_date) = 11 THEN net_amount ELSE 0 END) AS November_Amount,
        SUM(CASE WHEN MONTH(transaction_date) = 12 THEN net_amount ELSE 0 END) AS December_Amount,
    
      -- Add more lines for other months (3 for March, 4 for April, and so on)
      IFNULL(SUM(net_amount),0) AS Total_Amount
    FROM
      (SELECT
          SD.supply_group,
          SD.supply_group_id,
          SD.item_id,
          SD.Item_code,
          SD.item_Name,
          SD.pack_size,
          SD.quantity + SD.free_quantity * -1 AS net_sale_qty,
          SD.Amount * -1 AS net_amount,
          SD.transaction_date,
          SD.sales_rep,
          SD.sales_rep_id,
          SD.branch_name,
          SD.supplier_name
        FROM
          (SELECT
            SI.order_date_time AS transaction_date ,
            SI.manual_number AS invoice_number,
            C.customer_code,
            CONCAT(C.customer_name, ' ', T.townName) AS customer_name,
            E.employee_name AS sales_rep,
            E.employee_code AS sales_rep_id,
            I.Item_code,
            I.item_id,
            I.item_Name,
            I.package_unit AS pack_size,
            SII.quantity,
            SII.free_quantity,
            SII.price - ((SII.price / 100) * (SI.discount_percentage + SII.discount_percentage)) AS Price,
            SII.quantity * IF((SI.discount_percentage + SII.discount_percentage)>0,(SII.price - ((SII.price / 100) * (SI.discount_percentage + SII.discount_percentage))),SII.price) AS Amount,
            SI.document_number,
            SG.supply_group_id,
            SG.supply_group,
            B.branch_name,
            SUP.supplier_name
          FROM
            sales_invoices SI
            INNER JOIN sales_invoice_items SII ON SI.sales_invoice_Id = SII.sales_invoice_Id
            INNER JOIN items I ON SII.item_id = I.item_id
            INNER JOIN supply_groups SG ON SG.supply_group_id = I.supply_group_id
            INNER JOIN customers C ON C.customer_id = SI.customer_id
            INNER JOIN town_non_administratives T ON T.town_id = C.town
            INNER JOIN employees E ON E.employee_id = SI.employee_id
            INNER JOIN branches B ON B.branch_id = SI.branch_id
            INNER JOIN suppliers SUP ON SG.supply_group_id = SUP.supply_group_id
            WHERE SI.order_date_time BETWEEN   '".$fromdate."' AND '".$toDate."'
          UNION ALL
          SELECT
            SR.order_date AS transaction_date,
            SR.manual_number AS invoice_number,
            C.customer_code,
            CONCAT(C.customer_name, ' ', T.townName) AS customer_name,
            E.employee_name AS sales_rep,
            E.employee_code AS sales_rep_id,
            I.item_id,
            I.Item_code,
            I.item_Name,
            I.package_unit AS pack_size,
            SRI.quantity,
            SRI.free_quantity,
            SRI.price - ((SRI.price / 100) * (SR.discount_percentage + SRI.discount_percentage)) AS Price,
            SRI.quantity * IF((SR.discount_percentage + SRI.discount_percentage)>0,(SRI.price - ((SRI.price / 100) * (SR.discount_percentage + SRI.discount_percentage))),SRI.price) AS Amount,
            SR.document_number,
            SG.supply_group_id,
            SG.supply_group,
            B.branch_name,
            SUP.supplier_name
          FROM
            sales_returns SR
            INNER JOIN sales_return_items SRI ON SR.sales_return_Id = SRI.sales_return_Id
            INNER JOIN items I ON SRI.item_id = I.item_id
            INNER JOIN supply_groups SG ON SG.supply_group_id = I.supply_group_id
            INNER JOIN customers C ON C.customer_id = SR.customer_id
            INNER JOIN town_non_administratives T ON T.town_id = C.town
            INNER JOIN employees E ON E.employee_id = SR.employee_id
            INNER JOIN branches B ON B.branch_id = SR.branch_id
            INNER JOIN suppliers SUP ON SG.supply_group_id = SUP.supply_group_id
            WHERE SR.order_date BETWEEN  '".$fromdate."' AND '".$toDate."'
        ) AS SD
          
  ) AS SDD";

      //dd($query);
      $result = DB::select($query);


      return response()->json(["body" => $result, "report_date" => " From " . $fromdate . " to " . $toDate, "company_data"=>[
        'company_name' =>CompanyDetailsController::CompanyName(),
                 'company_address' => CompanyDetailsController::CompanyAddress(),
                 'contact_details' => CompanyDetailsController::CompanyNumber()
      ]]);
    } catch (Exception $ex) {
      return response()->json($ex);
    }
  }
}
