<?php

namespace Modules\Sc\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Sc\Entities\branch;
use Modules\Sc\Entities\category_level_1;
use Modules\Sc\Entities\category_level_2;
use Modules\Sc\Entities\category_level_3;
use Modules\Sc\Entities\Customer;
use Modules\Sc\Entities\Customer_grade;
use Modules\Sc\Entities\Customer_group;
use Modules\Sc\Entities\employee;
use Modules\Sc\Entities\item;
use Modules\Sc\Entities\location;
use Modules\Sc\Entities\route;
use Modules\Sc\Entities\supply_group;
use RepoEldo\ELD\ReportViewer;

class RDStockWithReportController extends Controller
{
    public function rdStockreport($search)
    {
        try {


            $searchOption = json_decode($search);


            // dd($searchOption );



            $selectSupplygroup = $searchOption[0]->selectSupplygroup;

            $fromdate = $searchOption[1]->fromdate;
            $todate = $searchOption[2]->todate;
            $selecteBranch = $searchOption[3]->selecteBranch;




            $nonNullCount = 0;

            if ($searchOption !== null) {

                if ($searchOption[0]->selectSupplygroup !== null) {
                    $nonNullCount++;
                }


                if ($searchOption[1]->fromdate !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[2]->todate !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[3]->selecteBranch !== null) {
                    $nonNullCount++;
                }
            }
            if ($nonNullCount > 1) {
                $from = date("Y-m-d");
                $quryModify1 = " ";
                $quryModify2 = " ";
                if ($selectSupplygroup != null) {
                    if (is_array($selectSupplygroup)) {
                        $quryModify1 .= " SG.supply_group_id IN ('" . implode("','", $selectSupplygroup) . "') AND";
                        $quryModify2 .= " SG.supply_group_id IN ('" . implode("','", $selectSupplygroup) . "') AND";
                    } else {
                        $quryModify1 .= " SG.supply_group_id = '" . $selectSupplygroup . "' AND";
                        $quryModify2 .= " SG.supply_group_id = '" . $selectSupplygroup . "' AND";
                    }
                }
                if ($selecteBranch != null) {
                    if (is_array($selecteBranch)) {
                        $quryModify1 .= " SI.branch_id IN ('" . implode("','", $selecteBranch) . "') AND";
                        $quryModify2 .= " SR.branch_id IN ('" . implode("','", $selecteBranch) . "') AND";
                    } else {
                        $quryModify1 .= " SI.branch_id = '" . $selecteBranch . "' AND";
                        $quryModify2 .= " SR.branch_id = '" . $selecteBranch . "' AND";
                    }
                }


                if ($fromdate != null && $todate != null) {
                    if (is_string($fromdate) && is_string($todate)) {
                        $quryModify1 .= " SI.order_date_time BETWEEN '" . $fromdate . "' AND '" . $todate . "' AND ";
                        $quryModify2 .= " SR.order_date BETWEEN '" . $fromdate . "' AND '" . $todate . "' AND ";
                    }
                }
                $quryModify1 = preg_replace('/\W\w+\s*(\W*)$/', '$1', $quryModify1);
                $quryModify2 = preg_replace('/\W\w+\s*(\W*)$/', '$1', $quryModify2);



                $query = "SELECT   

                SDD.supply_group ,
                SDD.supply_group_id , 
                SDD.item_id , 
                SDD.Item_code ,
                SDD.item_Name , 
                SDD.pack_size , 
                CAST(SDD.net_sale_qty AS SIGNED)AS net_sale_qty ,
                SDD.free_qty AS free_qty,
                IFNULL(TRUNCATE(SDD.net_amount,2),0) AS net_amount  , 
                IFNULL(CAST(STK.stock_qty AS SIGNED),0) AS stock_qty, 
                IFNULL(TRUNCATE(STK.stock_value,2),0) AS stock_value,
                SDD.branch_id,
                SDD.branch_name  
                
                
                FROM
                
                ( SELECT  
                SD.supply_group ,
                SD.supply_group_id , 
                SD.item_id , 
                SD.Item_code ,
                SD.item_Name , 
                SD.pack_size , 
                
                SUM(SD.quantity)*-1 AS net_sale_qty  ,
                SUM(SD.free_quantity)*-1 AS free_qty,
                SUM(SD.Amount)*-1 as net_amount,
                SD.branch_id,
                SD.branch_name  
                
                
                 FROM 
                ( SELECT  SI.order_date_time  AS Date ,
                  SI.manual_number AS invoice_number ,
                  C.customer_code , 
                  CONCAT(C.customer_name ,' ', T.townName ) AS customer_name  , 
                  E.employee_name as sales_rep , 
                  I.Item_code , 
                  I.item_id , 
                  I.item_Name , 
                  I.package_unit as pack_size , 
                  SII.quantity , 
                  SII.free_quantity , 
                  SII.price - ((SII.price/100)*(SI.discount_percentage+SII.discount_percentage)) AS Price  , 
                  SII.quantity *(SII.price - ((SII.price/100)*(SI.discount_percentage+SII.discount_percentage))) AS Amount , 
                  SI.document_number , 
                  SG.supply_group_id , 
                  SG.supply_group,
                  B.branch_id,
                  B.branch_name  
                   
                
                 FROM sales_invoices  SI 
                INNER JOIN sales_invoice_items SII  ON SI.sales_invoice_Id=SII.sales_invoice_Id 
                INNER JOIN items I ON SII.item_id=I.item_id 
                INNER JOIN supply_groups SG ON SG.supply_group_id=I.supply_group_id
                INNER JOIN customers C ON C.customer_id=SI.customer_id 
                INNER JOIN town_non_administratives T ON T.town_id=C.town 
                INNER JOIN employees E ON E.employee_id=SI.employee_id
                INNER JOIN branches B ON B.branch_id=SI.branch_id
                WHERE " . $quryModify1 . "
                
                UNION ALL 
                
                SELECT  SR.order_date AS Date ,
                  SR.manual_number AS invoice_number ,
                  C.customer_code , 
                  CONCAT(C.customer_name ,' ', T.townName ) AS customer_name  , 
                  E.employee_name as sales_rep , 
                  I.item_id , 
                  I.Item_code , 
                  I.item_Name , 
                  I.package_unit as pack_size ,
                  SRI.quantity , 
                  SRI.free_quantity , 
                  SRI.price - ((SRI.price/100)*(SR.discount_percentage+SRI.discount_percentage)) AS Price  , 
                  SRI.quantity *(SRI.price - ((SRI.price/100)*(SR.discount_percentage+SRI.discount_percentage))) AS Amount ,
                  SR.document_number , 
                  SG.supply_group_id , 
                  SG.supply_group,
                  B.branch_id,
                  B.branch_name  
                    
                
                FROM sales_returns  SR
                INNER JOIN sales_return_items SRI  ON SR.sales_return_Id=SRI.sales_return_Id 
                INNER JOIN items I ON SRI.item_id=I.item_id 
                INNER JOIN supply_groups SG ON SG.supply_group_id=I.supply_group_id
                INNER JOIN customers C ON C.customer_id=SR.customer_id 
                INNER JOIN town_non_administratives T ON T.town_id=C.town 
                INNER JOIN employees E ON E.employee_id=SR.employee_id
                INNER JOIN branches B ON B.branch_id=SR.branch_id
                WHERE " . $quryModify2 . " )  SD  
                GROUP BY SD.supply_group_id , SD.item_id ORDER BY SD.item_Name) SDD 
                
                LEFT JOIN 
                ( SELECT item_id , SUM(quantity) AS stock_qty , SUM(quantity*cost_price) AS stock_value   
                FROM  item_history_set_offs 
                WHERE transaction_date<='$from' 
                 GROUP BY item_id 
                ) STK ON SDD.item_id=STK.item_id";




                //dd($query);
                $result = DB::select($query);


                $resulsupplygroup = DB::select('select supply_groups.supply_group_id ,supply_groups.supply_group from supply_groups');

                $supplygrouparray = [];
                $table = [];
                $titel = [];
                $reportViwer = new ReportViewer();
                foreach ($resulsupplygroup as $supplygroupid) {

                    $table = [];

                    $branch_name = "";
                    foreach ($result as $supplygroupdata) {
                        //$branch = branch::find($supplygroupdata->branch_id);
                        //if($branch){
                        $branch_name = $supplygroupdata->branch_name;
                        //}
                        //dd($result);
                        if ($supplygroupdata->supply_group_id == $supplygroupid->supply_group_id) {
                            array_push($table, $supplygroupdata);
                        }
                    }



                    if (count($table) > 0) {
                        array_push($supplygrouparray, $table);
                        array_push($titel, "<strong>Supply Group : " . $supplygroupid->supply_group . " - Branch : " . $branch_name . "</strong>");
                        $reportViwer->addParameter('abc', $titel);
                    }
                }



                $reportViwer->addParameter("tabale_data", [$supplygrouparray]);
            } else {
                $from = date("Y-m-d");
                $quryModify1 = " WHERE ";
                $quryModify2 = " WHERE ";
                if (
                    $selecteBranch == null && $fromdate == null && $todate == null && $selectSupplygroup == null
                ) {
                    $quryModify1 = " ";
                    $quryModify2 = " ";
                }

                if ($selectSupplygroup != null) {
                    if (is_array($selectSupplygroup)) {
                        $quryModify1 .= " SG.supply_group IN ('" . implode("','", $selectSupplygroup) . "') AND";
                        $quryModify2 .= " SG.supply_group IN ('" . implode("','", $selectSupplygroup) . "') AND";
                    } else {
                        $quryModify1 .= " SG.supply_group = '" . $selectSupplygroup . "' AND";
                        $quryModify2 .= " SG.supply_group = '" . $selectSupplygroup . "' AND";
                    }
                }
                if ($selecteBranch != null) {
                    if (is_array($selecteBranch)) {
                        $quryModify1 .= " SI.branch_id IN ('" . implode("','", $selecteBranch) . "') AND";
                        $quryModify2 .= " SR.branch_id IN ('" . implode("','", $selecteBranch) . "') AND";
                    } else {
                        $quryModify1 .= " SI.branch_id = '" . $selecteBranch . "' AND";
                        $quryModify2 .= " SR.branch_id = '" . $selecteBranch . "' AND";
                    }
                }


                if ($fromdate != null && $todate != null) {
                    if (is_string($fromdate) && is_string($todate)) {
                        $quryModify1 .= " SR.order_date BETWEEN '" . $fromdate . "' AND '" . $todate . "' AND ";
                        $quryModify2 .= " SR.order_date BETWEEN '" . $fromdate . "' AND '" . $todate . "' AND ";
                    }
                }
                $quryModify1 = preg_replace('/\W\w+\s*(\W*)$/', '$1', $quryModify1);
                $quryModify2 = preg_replace('/\W\w+\s*(\W*)$/', '$1', $quryModify2);




               /*  $query = "SELECT   

                SDD.supply_group ,
                SDD.supply_group_id , 
                SDD.item_id , 
                SDD.Item_code ,
                SDD.item_Name , 
                SDD.pack_size , 
                CAST(SDD.net_sale_qty AS SIGNED)AS net_sale_qty ,
                SDD.free_qty AS free_qty,
                IFNULL(TRUNCATE(SDD.net_amount,2),0) AS net_amount , 
                IFNULL(CAST(STK.stock_qty AS SIGNED),0) AS stock_qty, 
                IFNULL(TRUNCATE(STK.stock_value,2),0) AS stock_value,
                SDD.branch_id,
                SDD.branch_name
               
                
                
                FROM
                
                ( SELECT  
                SD.supply_group ,
                SD.supply_group_id , 
                SD.item_id , 
                SD.Item_code ,
                SD.item_Name , 
                SD.pack_size , 
                
                
                SUM(SD.quantity)*-1 AS net_sale_qty  ,
                SUM(SD.free_quantity)*-1 AS free_qty,
                SUM(SD.Amount)*-1 as net_amount,
                SD.branch_id,
                SD.branch_name  
                
                
                 FROM 
                ( SELECT  SI.order_date_time  AS Date ,
                  SI.manual_number AS invoice_number ,
                  C.customer_code , 
                  CONCAT(C.customer_name ,' ', T.townName ) AS customer_name  , 
                  E.employee_name as sales_rep , 
                 
                  I.Item_code , 
                  I.item_id , 
                  I.item_Name , 
                  I.package_unit as pack_size , 
                  SII.quantity , 
                  SII.free_quantity , 
                  SII.price - ((SII.price/100)*(SI.discount_percentage+SII.discount_percentage)) AS Price  , 
                  SII.quantity *(SII.price - ((SII.price/100)*(SI.discount_percentage+SII.discount_percentage))) AS Amount , 
                  SI.document_number , 
                  SG.supply_group_id , 
                  SG.supply_group,
                  B.branch_id,
                  B.branch_name  
                   
                
                 FROM sales_invoices  SI 
                INNER JOIN sales_invoice_items SII  ON SI.sales_invoice_Id=SII.sales_invoice_Id 
                INNER JOIN items I ON SII.item_id=I.item_id 
                INNER JOIN supply_groups SG ON SG.supply_group_id=I.supply_group_id
                INNER JOIN customers C ON C.customer_id=SI.customer_id 
                INNER JOIN town_non_administratives T ON T.town_id=C.town 
                INNER JOIN employees E ON E.employee_id=SI.employee_id
                INNER JOIN branches B ON B.branch_id=SI.branch_id
                $quryModify1
                
                UNION ALL 
                
                SELECT  SR.order_date AS Date ,
                  SR.manual_number AS invoice_number ,
                  C.customer_code , 
                  CONCAT(C.customer_name ,' ', T.townName ) AS customer_name  , 
                  E.employee_name as sales_rep , 
                 
                  I.item_id , 
                  I.Item_code , 
                  I.item_Name , 
                  I.package_unit as pack_size ,
                  SRI.quantity , 
                  SRI.free_quantity , 
                  SRI.price - ((SRI.price/100)*(SR.discount_percentage+SRI.discount_percentage)) AS Price  , 
                  SRI.quantity *(SRI.price - ((SRI.price/100)*(SR.discount_percentage+SRI.discount_percentage))) AS Amount ,
                  SR.document_number , 
                  SG.supply_group_id , 
                  SG.supply_group,
                  B.branch_id,
                  B.branch_name  
                    
                
                FROM sales_returns  SR
                INNER JOIN sales_return_items SRI  ON SR.sales_return_Id=SRI.sales_return_Id 
                INNER JOIN items I ON SRI.item_id=I.item_id 
                INNER JOIN supply_groups SG ON SG.supply_group_id=I.supply_group_id
                INNER JOIN customers C ON C.customer_id=SR.customer_id 
                INNER JOIN town_non_administratives T ON T.town_id=C.town 
                INNER JOIN employees E ON E.employee_id=SR.employee_id
                INNER JOIN branches B ON B.branch_id = SR.branch_id
                $quryModify2 )  SD  
                GROUP BY SD.supply_group_id , SD.item_id ORDER BY SD.item_Name) SDD 
                
                LEFT JOIN 
                ( SELECT item_id , SUM(quantity) AS stock_qty , SUM(quantity*cost_price) AS stock_value   
                FROM  item_history_set_offs 
                WHERE transaction_date<='$from' 
                 GROUP BY item_id 
                ) STK ON SDD.item_id=STK.item_id"; */

                $query = "SELECT 
                I.Item_code,
                I.item_Name,
                I.package_unit,
                sales.rd_quantity,
                sales.free_qty,
                sales.rd_value,
                stock.stock_quantity,
                stock.stock_value,
                ICL.item_category_level_1_id,
                I.category_level_2_id
            FROM 
                items I
            LEFT JOIN (
                SELECT 
                    IH.item_id,
                    SUM(IH.quantity) AS stock_quantity,
                    ABS(SUM((IH.quantity) * IH.cost_price)) AS stock_value
                FROM  
                item_historys IH
                WHERE 
                ".$quryModify1." 
                GROUP BY 
                    item_id 
            ) stock ON I.item_id = stock.item_id 
            LEFT JOIN (
                SELECT 
                    S.item_id,
                   SUM(S.quantity) * -1 AS rd_quantity,
                   ABS(SUM(S.free_quantity * -1)) AS free_qty,
                   SUM((S.quantity * S.price) - ((S.quantity * S.price) / 100 * IF(S.discount_percentage > 0, S.discount_percentage, 0))) * -1 AS rd_value,
                    S.customer_id,
                    S.location_id,
                    S.branch_id
                FROM 
                    sales_with_sales_returns S
            WHERE ".$quryModify2."
                GROUP BY 
                    item_id
            ) sales ON I.item_id = sales.item_id
           
            LEFT JOIN item_category_level_1s ICL ON ICL.item_category_level_1_id = I.category_level_1_id
            LEFT JOIN item_category_level_2s ICLL ON ICLL.Item_category_level_2_id= I.category_level_2_id
            LEFT JOIN branches B ON B.branch_id=sales.branch_id";

                //dd($query);
                $result = DB::select($query);


                $resulsupplygroup = DB::select('select supply_groups.supply_group_id ,supply_groups.supply_group from supply_groups');

                $supplygrouparray = [];
                $table = [];
                $titel = [];
                $reportViwer = new ReportViewer();
                foreach ($resulsupplygroup as $supplygroupid) {
                    $table = [];

                    $branch_name = "";
                    foreach ($result as $supplygroupdata) {
                        //$branch = branch::find($supplygroupdata->branch_id);
                        //if ($branch) {
                        $branch_name = $supplygroupdata->branch_name;
                        //}
                        //dd($result);
                        if ($supplygroupdata->supply_group_id == $supplygroupid->supply_group_id) {


                            array_push($table, $supplygroupdata);
                        }
                    }



                    if (count($table) > 0) {


                        array_push($supplygrouparray, $table);


                        array_push($titel, "<strong>Supply Group : " . $supplygroupid->supply_group . " - Branch : " . $branch_name . "</strong>");


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
            $fromdate = "";
            $todate = "";
            if ($searchOption !== null) {

                $selectSupplygroup = $searchOption[0]->selectSupplygroup;

                $fromdate = $searchOption[1]->fromdate;
                $todate = $searchOption[2]->todate;
                $selecteBranch = $searchOption[3]->selecteBranch;

                // Set parameters for selecteCustomer, selectecustomergroup, selecteCustomerGrade, and selecteRoute


                // Set the "filter" parameter using $fromdate and $todate
                $branch = $this->getBranch($selecteBranch);

                $branchname = '';
                if ($branch) {
                    // Process the data
                    $branchname = $branch->pluck('branch_name')->implode(', ');

                    $branchIds = $branch->pluck('branch_id')->implode(', ');
                }

                $selectSupplygroup = $this->getsupplugroup($selectSupplygroup);

                $supplyname = '';
                if ($selectSupplygroup) {
                    // Process the data
                    $supplyname = $selectSupplygroup->pluck('supply_group')->implode(', ');

                    $branchIds = $selectSupplygroup->pluck('branch_id')->implode(', ');
                }
                /* $branchname = '';
                if ($branch != null) {
                    $branchname = $branch->branch_name;
                }*/

                if ($nonNullCount > 1) {

                    $filterLabel = '';
                    if ($nonNullCount > 1) {
                        $filterLabel .= "";
                    }
                    if ($selecteBranch !== null) {
                        // Add a separator if needed
                        if ($filterLabel !== '') {
                            $filterLabel .= ' ';
                        }
                        //$filterLabel .= " Branch: $branchname and";
                    }

                    if ($selectSupplygroup !== null) {
                        // Add a separator if needed
                        if ($filterLabel !== '') {
                            $filterLabel .= ' ';
                        }
                        //$filterLabel .= " Supply Group: $supplyname and";
                    }


                    if ($fromdate !== null) {
                        $filterLabel .= " From: $fromdate ";
                    }

                    if ($todate !== null) {
                        // Add a separator if needed
                        if ($filterLabel !== '') {
                            $filterLabel .= ' ';
                        }
                        $filterLabel .= " To: $todate";
                    }


                    if (!empty($filterLabel)) {
                        $filterLabel = rtrim($filterLabel, 'and ');

                        //$reportViwer->addParameter("sub_title", "Total RD Stock With Free Issued ".$filterLabel);
                    }
                    //
                    $reportViwer->addParameter("sub_title", "Total RD Stock With Free Issued " . $filterLabel);
                } else {

                    if (
                        $selecteBranch == null && $selectSupplygroup == null
                    ) {
                        $filterLabel = "";
                    } elseif ($selecteBranch !== null) {
                        $filterLabel = "For:  $branchname ";
                    } elseif ($fromdate !== null && $todate !== null) {
                        $filterLabel = "From: $fromdate  To: $todate";
                    } elseif ($selectSupplygroup !== null) {
                        $filterLabel = "Supply Group: $supplyname";
                    }




                    //$filterLabel = "From: $fromdate  To: $todate  and product: $itemname  and categorylevel1: $cusgroup";


                    $reportViwer->addParameter("filter", $filterLabel);
                }
            }
            $total = 0;
            foreach ($result as $row) {
                $total += $row->stock_value;
            }
            $formattedTotal = number_format($total, 2, '.', ',');
            $concatenatedTotal = 'Grand Total' . ' ' . ' ' . $formattedTotal;
            $reportViwer->addParameter('total', $concatenatedTotal);
            $reportViwer->addParameter('companylogo', CompanyDetailsController::companyimage());
            $reportViwer->addParameter('companyName', CompanyDetailsController::CompanyName());
            $reportViwer->addParameter('companyAddress', CompanyDetailsController::CompanyAddress());
            $reportViwer->addParameter('companyNumber', CompanyDetailsController::CompanyNumber());
            $reportViwer->addParameter('dateRange', "From " . $fromdate . " To " . $todate);

            $length =  (strlen($filterLabel) / 90);
            $i = floor($length);
            $i2 = 0;
            if (($length - $i) > 0) {
                $i2++;
            }
            $label_height = (($i + $i2) * 20);

            //dd($length . " " . (strlen($filterLabel)));
            $reportViwer->addParameter('hight', $label_height);


            return $reportViwer->viewReport('RDstockReport_With_Free.json');
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
    public function getsupplugroup($selectSupplygroup)
    {
        if ($selectSupplygroup != null) {
            $selectSupplygroup = supply_group::whereIn('supply_group_id', $selectSupplygroup)
                ->select('supply_group_id', 'supply_group')
                ->get();
            //dd($branch->pluck('branch_name', 'branch_id'));
            return $selectSupplygroup;
        }
    }


    public function customer($selectSupplygroup)
    {
        if ($selectSupplygroup != null) {
            $customers = Customer::whereIn('customer_id', $selectSupplygroup)
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
