<?php

namespace Modules\Sd\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use RepoEldo\ELD\ReportViewer;

class ItemCustomerReportController extends Controller
{
    public function itemCustomerReport($search)
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
            SD.Date,
            SD.invoice_number,
            SD.sales_rep,
SD.Item_code,
SD.item_Name,
            SD.area AS area,
            SD.customer_name,
            SD.route_name AS route_name,
            'Town' AS town_name,
SUM(ABS(IF(SD.document_number = 210, CAST(SD.quantity AS SIGNED), 0))) as sales_qty, 
SUM(ABS(IF(SD.document_number = 210, CAST(SD.free_quantity AS SIGNED), 0))) as foc_qty



FROM 
( SELECT  SI.order_date_time  AS Date ,
SI.manual_number AS invoice_number ,
C.customer_code , 
CONCAT(C.customer_name ,' ', T.townName ) AS customer_name  , 
E.employee_name as sales_rep , 
I.Item_code , 
I.item_Name ,
MKR.route_name AS area, 
RT.route_name,
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
LEFT JOIN branches B ON B.branch_id = SI.branch_id
LEFT JOIN marketing_routes MKR ON C.marketing_route_id = MKR.marketing_route_id
INNER JOIN routes RT ON C.route_id = RT.route_id ".$query_modify." 



UNION ALL 

SELECT  SR.order_date AS Date,
SR.manual_number AS invoice_number,
C.customer_code, 

CONCAT(C.customer_name ,' ', T.townName ) AS customer_name  , 
E.employee_name as sales_rep , 
I.Item_code , 
I.item_Name , 
MKR.route_name AS area,
RT.route_name,
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
LEFT JOIN marketing_routes MKR ON C.marketing_route_id = MKR.marketing_route_id
LEFT JOIN routes RT ON C.route_id = RT.route_id ".$query_modify2."
) AS SD 
GROUP BY SD.supply_group_id , SD.Item_code";

            //dd($query);

            $result = DB::select($query);


            $reportViewer = new ReportViewer();
            $title = "Item - Customer Report";
            if ($fromDate && $toDate) {
                $title .= " From : " . $fromDate . " To : " . $toDate;
            }

            $reportViewer->addParameter("title", $title);
            $reportViewer->addParameter("companyName", CompanyDetailsController::CompanyName());
            $reportViewer->addParameter("item_customer_data", $result);
            return $reportViewer->viewReport('itemCustomerReport.json');
        } catch (Exception $ex) {
            return $ex;
        }
    }
}
