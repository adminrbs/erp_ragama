<?php

namespace Modules\Sd\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use RepoEldo\ELD\ReportViewer;

class MonthendSalesSummeryReportController extends Controller
{
    public function salessummeryreport($search)
    {
        try {


            $searchOption = json_decode($search);
            //dd($searchOption);

            $branch = $searchOption[0]->selecteBranch;
            $fromDate = $searchOption[1]->fromdate;
            $toDate = $searchOption[2]->todate;
            $salesRep = $searchOption[3]->selectSalesrep;

            /* $query_modify = ' WHERE ';
          
            if ($fromDate != null && $toDate != null) {
                
                $query_modify .= 'SWSR.transaction_date BETWEEN "' . $fromDate . '" AND "' . $toDate . '" AND';
               
            
            }
      
            if ($branch != null) {
                $query_modify .= ' SWSR.branch_id IN "' . $branch . '" AND';
                
            }

            if ($salesRep != null) {
                $query_modify .= ' SWSR.employee_id IN "' . $salesRep . '" AND';
                
            } */




            $branch = implode(',', $branch); // Convert branch array to comma-separated string
            $salesRep = implode(',', $salesRep); // Convert salesRep array to comma-separated string
            
            $query = "
                WITH Sales AS (
                    SELECT
                        employee_id,
                        SUM(quantity * price) * -1 AS gross 
                    FROM
                        sales_with_sales_returns 
                    WHERE
                        transaction_type = 'Sales' 
                        AND transaction_date BETWEEN '" . $fromDate . "' 
                        AND '" . $toDate . "' 
                        AND branch_id IN (" . $branch . ")
                    GROUP BY
                        employee_id
                ),
                Discounts AS (
                    SELECT
                        employee_id,
                        SUM(item_discount_amount) AS totaldiscount 
                    FROM
                        sales_with_sales_returns 
                    WHERE
                        transaction_type = 'Sales' 
                        AND transaction_date BETWEEN '" . $fromDate . "' 
                        AND '" . $toDate . "' 
                        AND branch_id IN (" . $branch . ")
                    GROUP BY
                        employee_id
                ),
                SalesReturns AS (
                    SELECT
                        employee_id,
                        SUM(quantity * price) AS sales_returns 
                    FROM
                        sales_with_sales_returns 
                    WHERE
                        transaction_type = 'Sales Return' 
                        AND transaction_date BETWEEN '" . $fromDate . "' 
                        AND '" . $toDate . "'
                        AND branch_id IN (" . $branch . ")
                    GROUP BY
                        employee_id
                )
                SELECT
                    E.employee_code,
                    E.employee_name,
                   
                    COALESCE(Sales.gross, 0) AS gross,
                    COALESCE(Discounts.totaldiscount, 0) AS totaldiscount,
                    COALESCE(SalesReturns.sales_returns, 0) AS sales_returns,
                    COALESCE(Sales.gross, 0) - COALESCE(SalesReturns.sales_returns, 0) - COALESCE(Discounts.totaldiscount, 0) AS net_sale 
                FROM
                    employees E
                    LEFT JOIN Sales ON E.employee_id = Sales.employee_id
                    LEFT JOIN Discounts ON E.employee_id = Discounts.employee_id
                    LEFT JOIN SalesReturns ON E.employee_id = SalesReturns.employee_id 
                WHERE
                    E.employee_id IN (" . $salesRep . ")
                GROUP BY
                    E.employee_code,
                    E.employee_name,
                    E.employee_id,
                    Sales.gross,
                    Discounts.totaldiscount,
                    SalesReturns.sales_returns;
            ";
            


            //dd($query);

            $result = DB::select($query);
//dd($result);

            $reportViewer = new ReportViewer();
            $title = "Sales Summery Report";
            if ($fromDate && $toDate) {
                $title .= " From : " . $fromDate . " To : " . $toDate;
            }

            $reportViewer->addParameter("title", $title);
            $reportViewer->addParameter("companyName", CompanyDetailsController::CompanyName());
            $reportViewer->addParameter("sales_summery_data", $result);
            return $reportViewer->viewReport('MonthendSalesSummeryReport.json');
        } catch (Exception $ex) {
            return $ex;
        }
    }
}
