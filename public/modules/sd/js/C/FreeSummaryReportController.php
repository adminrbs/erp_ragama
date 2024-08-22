<?php

namespace Modules\Sd\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use RepoEldo\ELD\ReportViewer;

class FreeSummaryReportController extends Controller
{

    public function freeSummaryReport($search)
    {
        try {


            $searchOption = json_decode($search);
            //dd($searchOption);

            $customer = $searchOption[0]->selecteCustomer;
            $selectecustomergroup = $searchOption[1]->selectecustomergroup;
            $selecteCustomerGrade = $searchOption[2]->selecteCustomerGrade;
            $route = $searchOption[3]->selecteRoute;
            $branch = $searchOption[4]->selecteBranch;
            $fromDate = $searchOption[5]->fromdate;
            $toDate = $searchOption[6]->todate;
            $salesRep = $searchOption[7]->selectSalesrep;
            $selectItem = $searchOption[10]->cmbProduct;

            $query_modify = ' WHERE ';
            $query_modify2 = ' WHERE ';
            if ($fromDate != null && $toDate != null) {
                
                $query_modify .= 'SI.order_date_time BETWEEN "' . $fromDate . '" AND "' . $toDate . '" AND';
                $query_modify2 .= 'SR.order_date BETWEEN "' . $fromDate . '" AND "' . $toDate . '" AND';
            
            }
            if ($route != null) {
                $query_modify .= ' RT.route_id = "' . $route[0] . '" AND';
                $query_modify2 .= ' RT.route_id = "' . $route[0] . '" AND';
            }
            if ($customer != null) {
                $query_modify .= ' C.customer_id = "' . $customer[0] . '" AND';
                $query_modify2 .= ' C.customer_id = "' . $customer[0] . '" AND';
            }
            if ($branch != null) {
                $query_modify .= ' B.branch_id = "' . $branch[0] . '" AND';
                $query_modify2 .= ' B.branch_id = "' . $branch[0] . '" AND';
            }
            if ($selectItem != null) {
                if (is_array($selectItem)) {
                    $query_modify .= " I.item_id  IN ('" . implode("','", $selectItem) . "') AND";
                    $query_modify2 .= " I.item_id  IN ('" . implode("','", $selectItem) . "') AND";
                } else {
                    $query_modify .= " I.item_id  = '" . $selectItem . "' AND";
                    $query_modify2 .= " I.item_id  = '" . $selectItem . "' AND";
                }
            }
    
            if ($fromDate == null && $toDate == null && $route == null && $customer == null && $branch == null && $selectItem == null) {
                $query_modify = "";
                $query_modify2 = "";
            }else{
                $query_modify = preg_replace('/\W\w+\s*(\W*)$/', '$1', $query_modify);
                $query_modify2 = preg_replace('/\W\w+\s*(\W*)$/', '$1', $query_modify2);
            }

            $query = "SELECT  
            SD.category_level_1 AS category,
            SD.Item_code,
            SD.item_Name,
            SD.unit_of_measure,
            SUM(ABS(IF(SD.document_number = 210, CAST(SD.quantity AS SIGNED), 0))) as sales_qty, 
            SUM(ABS(IF(SD.document_number = 210, CAST(SD.free_quantity AS SIGNED), 0))) as foc_qty
            
            
            
            FROM 
            ( SELECT 
CAT1.category_level_1,  
            I.Item_code , 
            I.item_Name ,
            SII.unit_of_measure,
            I.package_unit as pack_size , 
            SII.quantity , 
            SII.free_quantity , 
            SII.price - ((SII.price/100)*(SI.discount_percentage+SII.discount_percentage)) AS Price  , 
            SII.quantity *(SII.price - ((SII.price/100)*(SI.discount_percentage+SII.discount_percentage))) AS Amount , 
            SI.document_number , 
            SG.supply_group_id , 
            SG.supply_group  
            
            
            FROM sales_invoices  SI 
            INNER JOIN sales_invoice_items SII  ON SI.sales_invoice_Id=SII.sales_invoice_Id 
            INNER JOIN items I ON SII.item_id=I.item_id 
            INNER JOIN supply_groups SG ON SG.supply_group_id=I.supply_group_id
            INNER JOIN customers C ON C.customer_id=SI.customer_id 
            INNER JOIN town_non_administratives T ON T.town_id=C.town 
            INNER JOIN employees E ON E.employee_id=SI.employee_id
            INNER JOIN branches B ON B.branch_id = SI.branch_id  
LEFT JOIN item_category_level_1s CAT1 ON I.category_level_1_id = CAT1.item_category_level_1_id ".$query_modify." 



UNION ALL 

SELECT  
CAT1.category_level_1,
I.Item_code , 
I.item_Name , 
SRI.unit_of_measure,
I.package_unit as pack_size ,
SRI.quantity , 
SRI.free_quantity , 
SRI.price - ((SRI.price/100)*(SR.discount_percentage+SRI.discount_percentage)) AS Price  , 
SRI.quantity *(SRI.price - ((SRI.price/100)*(SR.discount_percentage+SRI.discount_percentage))) AS Amount ,
SR.document_number , 
SG.supply_group_id , 
SG.supply_group  


FROM sales_returns  SR
INNER JOIN sales_return_items SRI  ON SR.sales_return_Id=SRI.sales_return_Id 
INNER JOIN items I ON SRI.item_id=I.item_id 
INNER JOIN supply_groups SG ON SG.supply_group_id=I.supply_group_id
INNER JOIN customers C ON C.customer_id=SR.customer_id 
INNER JOIN town_non_administratives T ON T.town_id=C.town 
INNER JOIN employees E ON E.employee_id=SR.employee_id
INNER JOIN branches B ON B.branch_id= SR.branch_id 
LEFT JOIN item_category_level_1s CAT1 ON I.category_level_1_id = CAT1.item_category_level_1_id ".$query_modify2."
) AS SD 
GROUP BY SD.supply_group_id , SD.Item_code";

            //dd($query);

            $result = DB::select($query);


            $reportViewer = new ReportViewer();
            $reportViewer->addParameter("companyName", CompanyDetailsController::CompanyName());
            $reportViewer->addParameter("sub_title","Free Summary Report for the period of ".$fromDate." to ".$toDate);
            $reportViewer->addParameter("free_summary_data", $result);
            return $reportViewer->viewReport('freeSummaryReport.json');
        } catch (Exception $ex) {
            return $ex;
        }
    }
}
