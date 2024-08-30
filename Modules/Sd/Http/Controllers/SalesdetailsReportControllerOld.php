<?php

namespace Modules\Sd\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Sd\Entities\branch;
use Modules\Sd\Entities\Customer;
use Modules\Sd\Entities\Customer_grade;
use Modules\Sd\Entities\Customer_group;
use Modules\Sd\Entities\employee;
use Modules\Sd\Entities\route;
use RepoEldo\ELD\ReportViewer;

class SalesdetailsReportControllerOld extends Controller
{
    public function salesdetailsReport($search)
    {
        try {


            $searchOption = json_decode($search);


            //dd($searchOption);



            $selecteCustomer = $searchOption[0]->selecteCustomer;
            $selectecustomergroup = $searchOption[1]->selectecustomergroup;
            $selecteCustomerGrade = $searchOption[2]->selecteCustomerGrade;
            $selecteRoute = $searchOption[3]->selecteRoute;
            $selecteBranch = $searchOption[4]->selecteBranch;
            $fromdate = $searchOption[5]->fromdate;
            $todate = $searchOption[6]->todate;
            $selectSalesrep = $searchOption[7]->selectSalesrep;
            $selectSupGroup = $searchOption[9]->cmbSupplyGroup;
            $selectItem = $searchOption[10]->cmbProduct;
            //dd($selectItem);



            $nonNullCount = 0;

            if ($searchOption !== null) {

                if ($searchOption[0]->selecteCustomer !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[1]->selectecustomergroup !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[2]->selecteCustomerGrade !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[3]->selecteRoute !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[4]->selecteBranch !== null) {
                    $nonNullCount++;
                }

                if ($searchOption[5]->fromdate !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[6]->todate !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[7]->selectSalesrep !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[9]->cmbSupplyGroup !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[10]->cmbProduct !== null) {
                    $nonNullCount++;
                }
            }
            if ($nonNullCount > 1) {

                $quryModify1 = " ";
                $quryModify2 = " ";
                if ($selecteBranch != null) {
                    if (is_array($selecteBranch)) {
                        $quryModify1 .= " B.branch_id IN ('" . implode("','", $selecteBranch) . "') AND";
                        $quryModify2 .= " B.branch_id IN ('" . implode("','", $selecteBranch) . "') AND";
                    } else {
                        $quryModify1 .= " B.branch_id = '" . $selecteBranch . "' AND";
                        $quryModify2 .= " B.branch_id = '" . $selecteBranch . "' AND";
                    }
                }

                if ($selectSalesrep != null) {
                    if (is_array($selectSalesrep)) {
                        $quryModify1 .= " E.employee_id IN ('" . implode("','", $selectSalesrep) . "') AND";
                        $quryModify2 .= " E.employee_id IN ('" . implode("','", $selectSalesrep) . "') AND";
                    } else {
                        $quryModify1 .= " E.employee_id = '" . $selectSalesrep . "' AND";
                        $quryModify2 .= " E.employee_id = '" . $selectSalesrep . "' AND";
                    }
                }
                if ($selecteCustomer != null) {
                    if (is_array($selecteCustomer)) {
                        $quryModify1 .= " C.customer_id IN ('" . implode("','", $selecteCustomer) . "') AND";
                        $quryModify2 .= " C.customer_id IN ('" . implode("','", $selecteCustomer) . "') AND";
                    } else {
                        $quryModify1 .= " C.customer_id = '" . $selecteCustomer . "' AND";
                        $quryModify2 .= " C.customer_id = '" . $selecteCustomer . "' AND";
                    }
                }

                if ($selectecustomergroup != null) {
                    if (is_array($selectecustomergroup)) {
                        $quryModify1 .= " C.customer_group_id IN ('" . implode("','", $selectecustomergroup) . "') AND";
                        $quryModify2 .= " C.customer_group_id IN ('" . implode("','", $selectecustomergroup) . "') AND";
                    } else {
                        $quryModify1 .= " C.customer_group_id = '" . $selectecustomergroup . "' AND";
                        $quryModify2 .= " C.customer_group_id = '" . $selectecustomergroup . "' AND";
                    }
                }
                if ($selecteCustomerGrade != null) {
                    if (is_array($selecteCustomerGrade)) {
                        $quryModify1 .= " C.customer_grade_id IN ('" . implode("','", $selecteCustomerGrade) . "') AND";
                        $quryModify2 .= " C.customer_grade_id IN ('" . implode("','", $selecteCustomerGrade) . "') AND";
                    } else {
                        $quryModify1 .= " C.customer_grade_id = '" . $selecteCustomerGrade . "' AND";
                        $quryModify2 .= " C.customer_grade_id = '" . $selecteCustomerGrade . "' AND";
                    }
                }

                if ($selecteRoute != null) {
                    if (is_array($selecteRoute)) {
                        $quryModify1 .= " C.route_id IN ('" . implode("','", $selecteRoute) . "') AND";
                        $quryModify2 .= " C.route_id IN ('" . implode("','", $selecteRoute) . "') AND";
                    } else {
                        $quryModify1 .= " C.route_id = '" . $selecteRoute . "' AND";
                        $quryModify2 .= " C.route_id = '" . $selecteRoute . "' AND";
                    }
                }

                if ($fromdate != null && $todate != null) {
                    if (is_string($fromdate) && is_string($todate)) {
                        $quryModify1 .= " SI.order_date_time BETWEEN '" . $fromdate . "' AND '" . $todate . "' AND ";
                        $quryModify2 .= " SR.order_date BETWEEN '" . $fromdate . "' AND '" . $todate . "' AND ";
                    }
                }
                if ($selectItem != null) {
                    if (is_array($selectItem)) {
                        $quryModify1 .= " I.item_id  IN ('" . implode("','", $selectItem) . "') AND";
                        $quryModify2 .= " I.item_id  IN ('" . implode("','", $selectItem) . "') AND";
                    } else {
                        $quryModify1 .= " I.item_id  = '" . $selectItem . "' AND";
                        $quryModify2 .= " I.item_id  = '" . $selectItem . "' AND";
                    }
                }

                if ($selectSupGroup != null) {
                    if (is_array($selectSupGroup)) {
                        $quryModify1 .= " SG.supply_group_id  IN ('" . implode("','", $selectSupGroup) . "') AND";
                        $quryModify2 .= " SG.supply_group_id  IN ('" . implode("','", $selectSupGroup) . "') AND";
                    } else {
                        $quryModify1 .= " SG.supply_group_id  = '" . $selectSupGroup . "' AND";
                        $quryModify2 .= " SG.supply_group_id  = '" . $selectSupGroup . "' AND";
                    }
                }
                $quryModify1 = preg_replace('/\W\w+\s*(\W*)$/', '$1', $quryModify1);
                $quryModify2 = preg_replace('/\W\w+\s*(\W*)$/', '$1', $quryModify2);
                /* $query = "SELECT  
                SD.supply_group ,
                SD.supply_group_id , 
                SD.Item_code ,
                SD.item_Name , 
                SD.pack_size , 
                SUM(ABS(IF(SD.document_number = 210, CAST(SD.quantity AS SIGNED), 0))) as sales_qty, 
                SUM(ABS(IF(SD.document_number = 220, CAST(SD.quantity AS SIGNED), 0))) as rtn_qty,
                SUM((CAST((SD.quantity * -1) AS SIGNED))) as net_qty, 

                SUM(ABS(IF(SD.document_number = 210, CAST(SD.free_quantity AS SIGNED), 0))) as foc_qty, 
                SUM(ABS(IF(SD.document_number = 220, CAST(SD.free_quantity AS SIGNED), 0))) as rtn_foc_qty,
                SUM((CAST((SD.free_quantity * -1) AS SIGNED))) as net_foc_qty , 
                ABS(SUM( IF(SD.document_number=210,SD.Amount,0  ))) as sales , 
                ABS(SUM( IF(SD.document_number=220,SD.Amount,0  )) *-1) as returns ,
                ABS(SUM(SD.Amount)) as net_amount  
                
                
                 FROM 
                ( SELECT  SI.order_date_time  AS Date ,
                  SI.manual_number AS invoice_number ,
                  C.customer_code , 
                  CONCAT(C.customer_name ,' ', T.townName ) AS customer_name  , 
                  E.employee_name as sales_rep , 
                  I.Item_code , 
                  I.item_Name , 
                  I.package_unit as pack_size , 
                  SII.quantity , 
                  SII.free_quantity , 
                  SII.price - ((SII.price/100)*(SI.discount_percentage+SII.discount_percentage)) AS Price  , 
                  SII.quantity * IF((SI.discount_percentage+SII.discount_percentage) > 0,(SII.price - ((SII.price/100)*(SI.discount_percentage+SII.discount_percentage))),SII.price) AS Amount , 
                  SI.document_number , 
                  SG.supply_group_id , 
                  SG.supply_group  
                   
                
                 FROM sales_invoices  SI 
                LEFT JOIN sales_invoice_items SII  ON SI.sales_invoice_Id=SII.sales_invoice_Id 
                LEFT JOIN items I ON SII.item_id=I.item_id 
                LEFT JOIN supply_groups SG ON SG.supply_group_id=I.supply_group_id
                LEFT JOIN customers C ON C.customer_id=SI.customer_id 
                LEFT JOIN town_non_administratives T ON T.town_id=C.town 
                LEFT JOIN employees E ON E.employee_id=SI.employee_id
                LEFT JOIN branches B ON B.branch_id = SI.branch_id
                WHERE " . $quryModify1 . "
                
                UNION ALL 
                
                SELECT  SR.order_date AS Date ,
                  SR.manual_number AS invoice_number ,
                  C.customer_code , 
                  CONCAT(C.customer_name ,' ', T.townName ) AS customer_name  , 
                  E.employee_name as sales_rep , 
                  I.Item_code , 
                  I.item_Name , 
                  I.package_unit as pack_size ,
                  SRI.quantity , 
                  SRI.free_quantity , 
                  SRI.price - ((SRI.price/100)*(SR.discount_percentage+SRI.discount_percentage)) AS Price  , 
                  SRI.quantity *IF((SR.discount_percentage+SRI.discount_percentage) > 0,(SRI.price - ((SRI.price/100)*(SR.discount_percentage+SRI.discount_percentage))),SRI.price) AS Amount ,
                  SR.document_number , 
                  SG.supply_group_id , 
                  SG.supply_group  
                    
                
                FROM sales_returns  SR
                LEFT JOIN sales_return_items SRI  ON SR.sales_return_Id=SRI.sales_return_Id 
                LEFT JOIN items I ON SRI.item_id=I.item_id 
                LEFT JOIN supply_groups SG ON SG.supply_group_id=I.supply_group_id
                LEFT JOIN customers C ON C.customer_id=SR.customer_id 
                LEFT JOIN town_non_administratives T ON T.town_id=C.town 
                LEFT JOIN employees E ON E.employee_id=SR.employee_id
                LEFT JOIN branches B ON B.branch_id= SR.branch_id
                WHERE " . $quryModify2 . "  ) AS SD  
                GROUP BY SD.supply_group_id , SD.Item_code"; */
                $query = "SELECT  
                SD.supply_group ,
                SD.supply_group_id , 
                SD.Item_code ,
                SD.item_Name , 
                SD.pack_size , 
                SUM(ABS(IF(SD.document_number = 210, CAST(SD.quantity AS SIGNED), 0))) as sales_qty, 
                SUM(ABS(IF(SD.document_number = 220, CAST(SD.quantity AS SIGNED), 0))) as rtn_qty,
                SUM((CAST((SD.quantity * -1) AS SIGNED))) as net_qty, 

                SUM(ABS(IF(SD.document_number = 210, CAST(SD.free_quantity AS SIGNED), 0))) as foc_qty, 
                SUM(ABS(IF(SD.document_number = 220, CAST(SD.free_quantity AS SIGNED), 0))) as rtn_foc_qty,
                SUM((CAST((SD.free_quantity * -1) AS SIGNED))) as net_foc_qty , 
                ABS(SUM( IF(SD.document_number=210,SD.Amount,0  ))) as sales , 
                ABS(SUM( IF(SD.document_number=220,SD.Amount,0  )) *-1) as returns ,
                SUM(SD.Amount)*-1 as net_amount  
                
                
                 FROM 
                ( SELECT  SI.order_date_time  AS Date ,
                  SI.manual_number AS invoice_number ,
                  C.customer_code , 
                  CONCAT(C.customer_name ,' ', T.townName ) AS customer_name  , 
                  E.employee_name as sales_rep , 
                  I.Item_code , 
                  I.item_Name , 
                  I.package_unit as pack_size , 
                  SII.quantity , 
                  SII.free_quantity , 
                  SII.price - ((SII.price/100)*(SI.discount_percentage+SII.discount_percentage)) AS Price  , 
                  SII.quantity * IF((SI.discount_percentage+SII.discount_percentage) > 0,(SII.price - ((SII.price/100)*(SI.discount_percentage+SII.discount_percentage))),SII.price) AS Amount , 
                  SI.document_number , 
                  SG.supply_group_id , 
                  SG.supply_group  
                   
                
                 FROM sales_invoices  SI 
                LEFT JOIN sales_invoice_items SII  ON SI.sales_invoice_Id=SII.sales_invoice_Id 
                LEFT JOIN items I ON SII.item_id=I.item_id 
                LEFT JOIN supply_groups SG ON SG.supply_group_id=I.supply_group_id
                LEFT JOIN customers C ON C.customer_id=SI.customer_id 
                LEFT JOIN town_non_administratives T ON T.town_id=C.town 
                LEFT JOIN employees E ON E.employee_id=SI.employee_id
                LEFT JOIN branches B ON B.branch_id = SI.branch_id
                WHERE " . $quryModify1 . "
                
                UNION ALL 
                
                SELECT  SR.order_date AS Date ,
                  SR.manual_number AS invoice_number ,
                  C.customer_code , 
                  CONCAT(C.customer_name ,' ', T.townName ) AS customer_name  , 
                  E.employee_name as sales_rep , 
                  I.Item_code , 
                  I.item_Name , 
                  I.package_unit as pack_size ,
                  SRI.quantity , 
                  SRI.free_quantity , 
                  SRI.price - ((SRI.price/100)*(SR.discount_percentage+SRI.discount_percentage)) AS Price  , 
                  SRI.quantity *IF((SR.discount_percentage+SRI.discount_percentage) > 0,(SRI.price - ((SRI.price/100)*(SR.discount_percentage+SRI.discount_percentage))),SRI.price) AS Amount ,
                  SR.document_number , 
                  SG.supply_group_id , 
                  SG.supply_group  
                    
                
                FROM sales_returns  SR
                LEFT JOIN sales_return_items SRI  ON SR.sales_return_Id=SRI.sales_return_Id 
                LEFT JOIN items I ON SRI.item_id=I.item_id 
                LEFT JOIN supply_groups SG ON SG.supply_group_id=I.supply_group_id
                LEFT JOIN customers C ON C.customer_id=SR.customer_id 
                LEFT JOIN town_non_administratives T ON T.town_id=C.town 
                LEFT JOIN employees E ON E.employee_id=SR.employee_id
                LEFT JOIN branches B ON B.branch_id= SR.branch_id
                WHERE " . $quryModify2 . "  ) AS SD  
                GROUP BY SD.supply_group_id , SD.Item_code";


                //dd($query);
                $result = DB::select($query);


                $reportViwer = new ReportViewer();
                $resulsupplygroup = DB::select('select supply_groups.supply_group_id ,supply_groups.supply_group from supply_groups');

                $supplygrouparray = [];
                $table = [];
                $titel = [];
                $reportViwer = new ReportViewer();
                foreach ($resulsupplygroup as $supplygroupid) {
                    $table = [];


                    foreach ($result as $supplygroupdata) {
                        //dd($result);
                        if ($supplygroupdata->supply_group_id == $supplygroupid->supply_group_id) {


                            array_push($table, $supplygroupdata);
                        }
                    }



                    if (count($table) > 0) {

                        array_push($supplygrouparray, $table);


                        array_push($titel, $supplygroupid->supply_group);


                        $reportViwer->addParameter('abc', $titel);
                    }
                }
                //dd($supplygrouparray);

                $reportViwer->addParameter("tabale_data", [$supplygrouparray]);

                //$reportViwer->addParameter("tabale_data", $result);
            } else {

                $quryModify1 = " WHERE ";
                $quryModify2 = " WHERE ";
                if (
                    $selecteCustomer == null && $selectecustomergroup == null && $selecteCustomerGrade == null
                    && $selecteRoute == null && $selecteBranch == null && $fromdate == null && $todate == null && $selectSalesrep == null && $selectItem == null
                ) {
                    $quryModify1 = " ";
                    $quryModify2 = " ";
                }

                if ($selecteBranch != null) {
                    if (is_array($selecteBranch)) {
                        $quryModify1 .= " B.branch_id IN ('" . implode("','", $selecteBranch) . "') AND";
                        $quryModify2 .= " B.branch_id IN ('" . implode("','", $selecteBranch) . "') AND";
                    } else {
                        $quryModify1 .= " B.branch_id = '" . $selecteBranch . "' AND";
                        $quryModify2 .= " B.branch_id = '" . $selecteBranch . "' AND";
                    }
                }

                if ($selectSalesrep != null) {
                    if (is_array($selectSalesrep)) {
                        $quryModify1 .= " E.employee_id IN ('" . implode("','", $selectSalesrep) . "') AND";
                        $quryModify2 .= " E.employee_id IN ('" . implode("','", $selectSalesrep) . "') AND";
                    } else {
                        $quryModify1 .= " E.employee_id = '" . $selectSalesrep . "' AND";
                        $quryModify2 .= " E.employee_id = '" . $selectSalesrep . "' AND";
                    }
                }
                if ($selecteCustomer != null) {
                    if (is_array($selecteCustomer)) {
                        $quryModify1 .= " C.customer_id IN ('" . implode("','", $selecteCustomer) . "') AND";
                        $quryModify2 .= " C.customer_id IN ('" . implode("','", $selecteCustomer) . "') AND";
                    } else {
                        $quryModify1 .= " C.customer_id = '" . $selecteCustomer . "' AND";
                        $quryModify2 .= " C.customer_id = '" . $selecteCustomer . "' AND";
                    }
                }

                if ($selectecustomergroup != null) {
                    if (is_array($selectecustomergroup)) {
                        $quryModify1 .= " C.customer_group_id IN ('" . implode("','", $selectecustomergroup) . "') AND";
                        $quryModify2 .= " C.customer_group_id IN ('" . implode("','", $selectecustomergroup) . "') AND";
                    } else {
                        $quryModify1 .= " C.customer_group_id = '" . $selectecustomergroup . "' AND";
                        $quryModify2 .= " C.customer_group_id = '" . $selectecustomergroup . "' AND";
                    }
                }
                if ($selecteCustomerGrade != null) {
                    if (is_array($selecteCustomerGrade)) {
                        $quryModify1 .= " C.customer_grade_id IN ('" . implode("','", $selecteCustomerGrade) . "') AND";
                        $quryModify2 .= " C.customer_grade_id IN ('" . implode("','", $selecteCustomerGrade) . "') AND";
                    } else {
                        $quryModify1 .= " C.customer_grade_id = '" . $selecteCustomerGrade . "' AND";
                        $quryModify2 .= " C.customer_grade_id = '" . $selecteCustomerGrade . "' AND";
                    }
                }

                if ($selecteRoute != null) {
                    if (is_array($selecteRoute)) {
                        $quryModify1 .= " C.route_id IN ('" . implode("','", $selecteRoute) . "') AND";
                        $quryModify2 .= " C.route_id IN ('" . implode("','", $selecteRoute) . "') AND";
                    } else {
                        $quryModify1 .= " C.route_id = '" . $selecteRoute . "' AND";
                        $quryModify2 .= " C.route_id = '" . $selecteRoute . "' AND";
                    }
                }

                if ($fromdate != null && $todate != null) {
                    if (is_string($fromdate) && is_string($todate)) {
                        $quryModify1 .= " SI.order_date_time BETWEEN '" . $fromdate . "' AND '" . $todate . "' AND ";
                        $quryModify2 .= " SR.order_date BETWEEN '" . $fromdate . "' AND '" . $todate . "' AND ";
                    }
                }
                if ($selectItem != null) {
                    if (is_array($selectItem)) {
                        $quryModify1 .= " I.item_id  IN ('" . implode("','", $selectItem) . "') AND";
                        $quryModify2 .= " I.item_id  IN ('" . implode("','", $selectItem) . "') AND";
                    } else {
                        $quryModify1 .= " I.item_id  = '" . $selectItem . "' AND";
                        $quryModify2 .= " I.item_id  = '" . $selectItem . "' AND";
                    }
                }
                $quryModify1 = preg_replace('/\W\w+\s*(\W*)$/', '$1', $quryModify1);
                $quryModify2 = preg_replace('/\W\w+\s*(\W*)$/', '$1', $quryModify2);




                $query = "SELECT  
                SD.supply_group ,
                SD.supply_group_id , 
                SD.Item_code ,
                SD.item_Name , 
                SD.pack_size , 
                SUM(ABS(IF(SD.document_number = 210, CAST(SD.quantity AS SIGNED), 0))) as sales_qty, 
                SUM(ABS(IF(SD.document_number = 220, CAST(SD.quantity AS SIGNED), 0))) as rtn_qty,
                SUM((CAST((SD.quantity * -1) AS SIGNED))) as net_qty, 

                SUM(ABS(IF(SD.document_number = 210, CAST(SD.free_quantity AS SIGNED), 0))) as foc_qty, 
                SUM(ABS(IF(SD.document_number = 220, CAST(SD.free_quantity AS SIGNED), 0))) as rtn_foc_qty,
                SUM((CAST((SD.free_quantity * -1) AS SIGNED))) as net_foc_qty , 
                ABS(SUM( IF(SD.document_number=210,SD.Amount,0  ))) as sales , 
                ABS(SUM( IF(SD.document_number=220,SD.Amount,0  ))*-1) as returns ,
                ABS(SUM(SD.Amount)) as net_amount  
                
                
                 FROM 
                ( SELECT  SI.order_date_time  AS Date ,
                  SI.manual_number AS invoice_number ,
                  C.customer_code , 
                  CONCAT(C.customer_name ,' ', T.townName ) AS customer_name  , 
                  E.employee_name as sales_rep , 
                  I.Item_code , 
                  I.item_Name , 
                  I.package_unit as pack_size , 
                  SII.quantity , 
                  SII.free_quantity , 
                  SII.price - ((SII.price/100)*(SI.discount_percentage+SII.discount_percentage)) AS Price  , 
                  SII.quantity *IF((SI.discount_percentage+SII.discount_percentage)>0,(SII.price - ((SII.price/100)*(SI.discount_percentage+SII.discount_percentage))) ,SII.price)AS Amount , 
                  SI.document_number , 
                  SG.supply_group_id , 
                  SG.supply_group  
                   
                
                 FROM sales_invoices  SI 
                LEFT JOIN sales_invoice_items SII  ON SI.sales_invoice_Id=SII.sales_invoice_Id 
                LEFT JOIN items I ON SII.item_id=I.item_id 
                LEFT JOIN supply_groups SG ON SG.supply_group_id=I.supply_group_id
                LEFT JOIN customers C ON C.customer_id=SI.customer_id 
                LEFT JOIN town_non_administratives T ON T.town_id=C.town 
                LEFT JOIN employees E ON E.employee_id=SI.employee_id
                LEFT JOIN branches B ON B.branch_id = SI.branch_id
                 " . $quryModify1 . "
                
                UNION ALL 
                
                SELECT  SR.order_date AS Date ,
                  SR.manual_number AS invoice_number ,
                  C.customer_code , 
                  CONCAT(C.customer_name ,' ', T.townName ) AS customer_name  , 
                  E.employee_name as sales_rep , 
                  I.Item_code , 
                  I.item_Name , 
                  I.package_unit as pack_size ,
                  SRI.quantity , 
                  SRI.free_quantity , 
                  SRI.price - ((SRI.price/100)*(SR.discount_percentage+SRI.discount_percentage)) AS Price  , 
                  SRI.quantity *IF((SR.discount_percentage+SRI.discount_percentage) > 0,(SRI.price - ((SRI.price/100)*(SR.discount_percentage+SRI.discount_percentage))),SRI.price) AS Amount ,
                  SR.document_number , 
                  SG.supply_group_id , 
                  SG.supply_group  
                    
                
                FROM sales_returns  SR
                LEFT JOIN sales_return_items SRI  ON SR.sales_return_Id=SRI.sales_return_Id 
                LEFT JOIN items I ON SRI.item_id=I.item_id 
                LEFT JOIN supply_groups SG ON SG.supply_group_id=I.supply_group_id
                LEFT JOIN customers C ON C.customer_id=SR.customer_id 
                LEFT JOIN town_non_administratives T ON T.town_id=C.town 
                LEFT JOIN employees E ON E.employee_id=SR.employee_id
                LEFT JOIN branches B ON B.branch_id= SR.branch_id
                 " . $quryModify2 . "  ) AS SD  
                GROUP BY SD.supply_group_id , SD.Item_code";

                //dd($query);
                $result = DB::select($query);
            

                $resulsupplygroup = DB::select('select supply_groups.supply_group_id ,supply_groups.supply_group from supply_groups');

                $supplygrouparray = [];
                $table = [];
                $titel = [];
                $reportViwer = new ReportViewer();
                foreach ($resulsupplygroup as $supplygroupid) {
                    $table = [];


                    foreach ($result as $supplygroupdata) {
                        //dd($result);
                        if ($supplygroupdata->supply_group_id == $supplygroupid->supply_group_id) {


                            array_push($table, $supplygroupdata);
                        }
                    }



                    if (count($table) > 0) {

                        array_push($supplygrouparray, $table);


                        array_push($titel, $supplygroupid->supply_group);


                        $reportViwer->addParameter('abc', $titel);
                    }
                }
                //dd($supplygrouparray);

                $reportViwer->addParameter("tabale_data", [$supplygrouparray]);


                //$query = preg_replace('/\W\w+\s*(\W*)$/', '$1', $query);
                //dd($query);
                /* $result = DB::select($query);

                $reportViwer = new ReportViewer();
                $reportViwer->addParameter("tabale_data", $result);*/
            }
            if ($searchOption !== null) {


                $selecteCustomer = $searchOption[0]->selecteCustomer;
                $selectecustomergroup = $searchOption[1]->selectecustomergroup;
                $selecteCustomerGrade = $searchOption[2]->selecteCustomerGrade;
                $selecteRoute = $searchOption[3]->selecteRoute;
                $selecteBranch = $searchOption[4]->selecteBranch;

                $fromdate = $searchOption[5]->fromdate;
                $todate = $searchOption[6]->todate;
                $selectesalesrep = $searchOption[7]->selectSalesrep;

                // Set parameters for selecteCustomer, selectecustomergroup, selecteCustomerGrade, and selecteRoute


                // Set the "filter" parameter using $fromdate and $todate
                $branch = $this->getBranch($selecteBranch);

                $branchname = '';
                if ($branch) {
                    // Process the data
                    $branchname = $branch->pluck('branch_name')->implode(', ');

                    $branchIds = $branch->pluck('branch_id')->implode(', ');
                }
                /* $branchname = '';
                if ($branch != null) {
                    $branchname = $branch->branch_name;
                }*/
                $customers = $this->customer($selecteCustomer);

                $customer = '';
                if ($customers) {
                    $customer = $customers->pluck('customer_name')->implode(', ');
                }

                $customergroups = $this->customergroup($selectecustomergroup);
                $cusgroup = '';
                if ($customergroups != null) {
                    // $cusgroup = $customergroup->group;
                    $cusgroup = $customergroups->pluck('group')->implode(', ');
                }

                $grade = $this->customergrade($selecteCustomerGrade);
                $cugrade = '';
                if ($grade != null) {
                    // $cugrade = $grade->grade;
                    $cugrade = $grade->pluck('grade')->implode(', ');
                }

                $getroute = $this->route($selecteRoute);
                $route = '';
                if ($getroute != null) {
                    //$route = $getroute->route_name;
                    $route = $getroute->pluck('route_name')->implode(', ');
                }

                $selectesalesrep = $this->getSalesrep($selectSalesrep);
                $selectesalesrep1 = '';
                if ($selectesalesrep != null) {
                    //$route = $getroute->route_name;
                    $selectesalesrep1 = $selectesalesrep->pluck('employee_name')->implode(', ');
                }
                if ($nonNullCount > 1) {

                    $filterLabel = '';
                    if ($nonNullCount > 1) {
                        $filterLabel .= "For";
                    }
                    if ($selecteBranch !== null) {
                        // Add a separator if needed
                        if ($filterLabel !== '') {
                            $filterLabel .= ' ';
                        }
                        $filterLabel .= " Branch: $branchname and";
                    }
                    if ($selectesalesrep !== null) {
                        // Add a separator if needed
                        if ($filterLabel !== '') {
                            $filterLabel .= ' ';
                        }
                        $filterLabel .= " Sales Rep: $selectesalesrep1 and";
                    }


                    if ($fromdate !== null) {
                        $filterLabel .= " From: $fromdate ";
                    }

                    if ($todate !== null) {
                        // Add a separator if needed
                        if ($filterLabel !== '') {
                            $filterLabel .= ' ';
                        }
                        $filterLabel .= " To: $todate and";
                    }

                    if ($selecteCustomer !== null) {
                        // Add a separator if needed
                        if ($filterLabel !== '') {
                            $filterLabel .= ' ';
                        }
                        $filterLabel .= " Customer: $customer and";
                    }

                    if ($selectecustomergroup !== null) {
                        // Add a separator if needed
                        if ($filterLabel !== '') {
                            $filterLabel .= ' ';
                        }
                        $filterLabel .= " Customer Group: $cusgroup and";
                    }

                    if ($selecteCustomerGrade !== null) {
                        // Add a separator if needed
                        if ($filterLabel !== '') {
                            $filterLabel .= ' ';
                        }
                        $filterLabel .= " Customer Grade: $cugrade  and";
                    }

                    if ($selecteRoute !== null) {
                        // Add a separator if needed
                        if ($filterLabel !== '') {
                            $filterLabel .= ' ';
                        }
                        $filterLabel .= " Route: $route and";
                    }

                    if (!empty($filterLabel)) {
                        $filterLabel = rtrim($filterLabel, 'and ');

                        $reportViwer->addParameter("filter", $filterLabel);
                    }
                    //
                } else {

                    if (
                        $selecteBranch == null && $selecteCustomer == null && $selectecustomergroup == null
                        && $selecteCustomerGrade == null && $selecteRoute == null && $selectSalesrep == null
                    ) {
                        $filterLabel = "";
                    } elseif ($selecteBranch !== null) {
                        $filterLabel = "For:  $branchname ";
                    } elseif ($fromdate !== null && $todate !== null) {
                        $filterLabel = "For From: $fromdate  To: $todate";
                    } elseif ($selecteCustomer !== null) {
                        $filterLabel = "For: Customer: $customer";
                    } elseif ($selectecustomergroup !== null) {
                        $filterLabel = "For: customer Group: $cusgroup";
                    } elseif ($selecteCustomerGrade !== null) {
                        $filterLabel = "For: customer Grade: $cugrade";
                    } elseif ($selecteRoute !== null) {
                        $filterLabel = "For: Route: $route";
                    } elseif ($selectSalesrep !== null) {
                        $filterLabel = "For: Sales Rep: $selectesalesrep1";
                    }




                    //$filterLabel = "From: $fromdate  To: $todate  and product: $itemname  and categorylevel1: $cusgroup";


                    $reportViwer->addParameter("filter", $filterLabel);
                }
            }
            $total = 0;
            foreach ($result as $row) {
                $total += $row->net_amount;
            }
if($total == 0){
    $formattedTotal = number_format($total, 2, '.', ',');
    $concatenatedTotal = '' ;
    $reportViwer->addParameter('total', $concatenatedTotal);
}else{
    $formattedTotal = number_format($total, 2, '.', ',');
    $concatenatedTotal = 'Grand Total' . ' ' . ' ' . $formattedTotal;
    $reportViwer->addParameter('total', $concatenatedTotal);
}
           
            $reportViwer->addParameter('companylogo', CompanyDetailsController::companyimage());
            $reportViwer->addParameter('companyName', CompanyDetailsController::CompanyName());
            $reportViwer->addParameter('companyAddress', CompanyDetailsController::CompanyAddress());
            $reportViwer->addParameter('companyNumber', CompanyDetailsController::CompanyNumber());

            $length =  (strlen($filterLabel) / 90);
            $i = floor($length);
            $i2 = 0;
            if (($length - $i) > 0) {
                $i2++;
            }
            $label_height = (($i + $i2) * 20);

            //dd($length . " " . (strlen($filterLabel)));
            $reportViwer->addParameter('hight', $label_height);


            return $reportViwer->viewReport('salesDetails.json');
        } catch (Exception $ex) {
            return $ex;
        }
    }
    public function getBranch($selecteBranch)
    {
        if ($selecteBranch != null) {
            $branch = branch::whereIn('branch_id', $selecteBranch)
                ->select('branch_id', 'branch_name')
                ->get();
            //dd($branch->pluck('branch_name', 'branch_id'));
            return $branch;
        }
    }
    public function getSalesrep($selectesalesrep)
    {
        if ($selectesalesrep != null) {
            $selectesalesrep = employee::whereIn('employee_id', $selectesalesrep)
                ->select('employee_id', 'employee_name')
                ->get();
            //dd($branch->pluck('branch_name', 'branch_id'));
            return $selectesalesrep;
        }
    }


    public function customer($selecteCustomer)
    {
        if ($selecteCustomer != null) {
            $customers = Customer::whereIn('customer_id', $selecteCustomer)
                ->select('customer_id', 'customer_name')
                ->get();

            return $customers;
        }
    }
    public function customergroup($selectecustomergroup)
    {
        if ($selectecustomergroup != null) {
            $cusgroups = Customer_group::whereIn('customer_group_id', $selectecustomergroup)
                ->select('customer_group_id', 'group')
                ->get();


            return $cusgroups;
        }
    }
    public function customergrade($selecteCustomerGrade)
    {
        if ($selecteCustomerGrade != null) {
            $cugrade = Customer_grade::whereIn('customer_grade_id', $selecteCustomerGrade)
                ->select('customer_grade_id', 'grade')
                ->get();
            return $cugrade;
        }
    }
    public function route($selecteRoute)
    {
        if ($selecteRoute != null) {
            $route = route::whereIn('route_id', $selecteRoute)
                ->select('route_id', 'route_name')
                ->get();
            return $route;
        }
    }
}
