<?php

namespace Modules\Sc\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use App\Models\branch as ModelsBranch;
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

class RDStockWithoutReportController extends Controller
{
    /*  public function rdStockreport($search)
    {
        try {


            $searchOption = json_decode($search);


            //dd($searchOption);



            $selectSupplygroup = $searchOption[0]->selectSupplygroup;

            $fromdate = $searchOption[1]->fromdate;
            $todate = $searchOption[2]->todate;
            $selecteBranch = $searchOption[3]->selecteBranch;
            $selecteLocation = $searchOption[4]->selecteLocation;




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
                if ($searchOption[4]->selecteLocation !== null) {
                    $nonNullCount++;
                }
            }
            if ($nonNullCount > 1) {
                $from = date("Y-m-d");
                $quryModify1 = " WHERE ";
                $quryModify2 = " WHERE ";





                $sales_query_varieble = "SELECT
                items.item_id,
                SWSR.branch_id,
                SUM( SWSR.quantity *- 1 ) AS invoice_qty,
                SUM( SWSR.free_quantity *- 1 ) AS free_qty,
                SUM( SWSR.quantity * - 1 ) * ( SWSR.price - ( ( SWSR.price / 100 ) * SWSR.item_discount_percentage ) ) AS amount 
            FROM
                sales_with_sales_returns SWSR
                INNER JOIN items ON SWSR.item_id = items.item_id 
            WHERE
                SWSR.transaction_date BETWEEN '".$fromdate."' 
                AND '".$todate."' AND"; 

                if ($selecteBranch != null) {
                    if (is_array($selecteBranch)) {
                        $sales_query_varieble .= " SWSR.branch_id IN ('" . implode("','", $selecteBranch) . "')";
                        
                    } else {
                        $sales_query_varieble .= " SWSR.branch_id = '" . $selecteBranch . "'";
                        
                    }
                }
            

            $sales_query_varieble .= "GROUP BY item_id";



                $stock_query_varieble = "SELECT IHS.item_id,IHS.branch_id,
                SUM( quantity - setoff_quantity ) AS stock_qty,
                SUM(( quantity - setoff_quantity ) * cost_price ) AS stock_value 
            FROM
                item_history_set_offs IHS
                INNER JOIN items I ON I.item_id = IHS.item_id 
            WHERE
                transaction_date <= '".$todate."' AND IHS.quantity > 0 AND";
                
                if ($selecteBranch != null) {
                    if (is_array($selecteBranch)) {
                        $stock_query_varieble .= " IHS.branch_id IN ('" . implode("','", $selecteBranch) . "')";
                        
                    } else {
                        $stock_query_varieble .= " IHS.branch_id = '" . $selecteBranch . "'";
                        
                    }
                }

                $stock_query_varieble .= " GROUP BY
                item_id";

                $query = "SELECT
                SG.supply_group,
                SG.supply_group_id,
                I.item_id,
                I.Item_code,
                I.item_name,
                I.package_unit,
                STOCK.branch_id,
                IFNULL( SALES.invoice_qty, 0 ) AS invoice_qty,
                IFNULL( SALES.Amount, 0 ) AS Amount,
                IFNULL( SALES.free_qty, 0 ) AS free_qty,
                IFNULL( STOCK.stock_qty, 0 ) AS stock_qty,
                IFNULL( STOCK.stock_value, 0 ) AS stock_value 
            FROM
                items I
                INNER JOIN supply_groups SG ON I.supply_group_id = SG.supply_group_id
                LEFT JOIN (" .
                    $sales_query_varieble . " 
                ) SALES ON SALES.item_id = I.Item_id
                LEFT JOIN (" .
                    $stock_query_varieble . "
                    ) STOCK ON STOCK.item_id = I.item_id 
            WHERE
                ABS(
                IFNULL( SALES.invoice_qty, 0 ))+ ABS(
                IFNULL( STOCK.stock_qty, 0 )) > 0";

                
                if ($selectSupplygroup != null) {
                    if (is_array($selectSupplygroup)) {
                        $query .= " SG.supply_group_id IN ('" . implode("','", $selectSupplygroup) . "')";
                        
                    } else {
                        $query .= " SG.supply_group_id = '" . $selectSupplygroup . "'";
                        
                    }
                }





               // dd($query);
               $result = DB::select($query);
                $resulsupplygroup = DB::select('select branches.branch_id ,branches.branch_name from branches');

                $supplygrouparray = [];
                $table = [];
                $titel = [];
                $reportViwer = new ReportViewer();
                foreach ($resulsupplygroup as $supplygroupid) {

                    $table = [];


                    foreach ($result as $supplygroupdata) {


                        if ($supplygroupdata->branch_id == $supplygroupid->branch_id) {

                            array_push($table, $supplygroupdata);
                        }
                    }



                    if (count($table) > 0) {

                        array_push($supplygrouparray, $table);


                        array_push($titel, $supplygroupid->branch_name);
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
                        $quryModify1 .= " SWSR.branch_id IN ('" . implode("','", $selecteBranch) . "') AND";
                        $quryModify2 .= " SWSR.branch_id IN ('" . implode("','", $selecteBranch) . "') AND";
                    } else {
                        $quryModify1 .= " SWSR.branch_id = '" . $selecteBranch . "' AND";
                        $quryModify2 .= " SWSR.branch_id = '" . $selecteBranch . "' AND";
                    }
                }
                if ($selecteLocation != null) {
                    if (is_array($selecteLocation)) {
                        $quryModify1 .= " SWSR.location_id IN ('" . implode("','", $selecteLocation) . "') AND";
                        $quryModify2 .= " SWSR.location_id IN ('" . implode("','", $selecteLocation) . "') AND";
                    } else {
                        $quryModify1 .= " SWSR.location_id = '" . $selecteLocation . "' AND";
                        $quryModify2 .= " SWSR.location_id = '" . $selecteLocation . "' AND";
                    }
                }

                if ($fromdate != null && $todate != null) {
                    if (is_string($fromdate) && is_string($todate)) {
                        $quryModify1 .= " SWSR.transaction_date BETWEEN '" . $fromdate . "' AND '" . $todate . "' AND ";
                        $quryModify2 .= " SWSR.transaction_date BETWEEN '" . $fromdate . "' AND '" . $todate . "' AND ";
                    }
                }
                $quryModify1 = preg_replace('/\W\w+\s*(\W*)$/', '$1', $quryModify1);
                $quryModify2 = preg_replace('/\W\w+\s*(\W*)$/', '$1', $quryModify2);




                $query = "SELECT 
                SG.supply_group,
                SG.supply_group_id,
                items.item_id,
                items.Item_code,
                SWSR.item_name,
                items.package_unit,
                SUM(SWSR.quantity) AS invoice_qty,
                SUM(SWSR.quantity * -1) * (SWSR.price - ((SWSR.price / 100) * SWSR.item_discount_percentage)) AS amount,
                IFNULL(CAST(STK.stock_qty AS SIGNED),0) AS stock_qty,
                IFNULL(TRUNCATE(STK.stock_value,2),0) AS stock_value, 
                TRUNCATE((SWSR.free_quantity *-1),0) AS free_qty,
                B.branch_id,
                B.branch_name
                FROM sales_with_sales_returns SWSR
                INNER JOIN items ON SWSR.item_id = items.item_id
                
                INNER JOIN customers AS C ON SWSR.customer_id = C.customer_id
                INNER JOIN employees E ON E.employee_id = SWSR.employee_id
                INNER JOIN branches B ON B.branch_id = SWSR.branch_id
                INNER JOIN supply_groups SG ON items.supply_group_id = SG.supply_group_id
                INNER JOIN 
                ( SELECT item_id , SUM(quantity) AS stock_qty , SUM(quantity*cost_price) AS stock_value,location_id  
                FROM  item_history_set_offs 
                WHERE transaction_date<='" . $todate . "' 
                GROUP BY item_id 
                ) STK ON SWSR.item_id=STK.item_id AND SWSR.location_id = STK.location_id " . $quryModify1 . " GROUP BY SWSR.item_id,SWSR.location_id ORDER BY SWSR.item_id";

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


                        array_push($titel,  "<strong>Supply Group : " . $supplygroupid->supply_group . " - Branch : " . $branch_name . "</strong>");

                        $reportViwer->addParameter('abc', $titel);
                    }
                }
                //dd($supplygrouparray);

                $reportViwer->addParameter("tabale_data", [$supplygrouparray]);


                
            }
            $fromdate = "";
            $todate = "";
            if ($searchOption !== null) {

                $selectSupplygroup = $searchOption[0]->selectSupplygroup;

                $fromdate = $searchOption[1]->fromdate;
                $todate = $searchOption[2]->todate;
                $selecteBranch = $searchOption[3]->selecteBranch;

                


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

                        //$reportViwer->addParameter("filter", $filterLabel);
                    }
                    //
                    $reportViwer->addParameter("sub_title", "Total RD Stock Report Without Free Issued " . $filterLabel);
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


            return $reportViwer->viewReport('RDstockReport_Without_Free.json');
        } catch (Exception $ex) {
            return $ex;
        }
    } */

    public function rdStockreport($search)
    {
        try {



            $searchOption = json_decode($search);





            $selectSupplygroup = $searchOption[0]->selectSupplygroup;

            $fromdate = $searchOption[1]->fromdate;
            $todate = $searchOption[2]->todate;
            $selecteBranch = $searchOption[3]->selecteBranch;

            $selecteLocation = $searchOption[4]->selecteLocation;
            //dd($selecteLocation);
            /* $selectcmbsalesrep = $searchOption[5]->selectcmbsalesrep; */
            /* $selectcmbarea = $searchOption[6]->selectcmbarea; */

            $user_id = auth()->id();
            $userrole = "SELECT users_roles.role_id FROM users_roles WHERE users_roles.user_id=$user_id";
            $alluserrol = DB::select($userrole);

            if (!empty($alluserrol) && $alluserrol[0]->role_id !== 1  && $alluserrol[0]->role_id !== 3) {

                if ($selecteBranch != null) {
                    if (count($selecteBranch) <= 0) {
                        return;
                    }
                }
            }



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

                if ($searchOption[4]->selecteLocation !== null) {
                    $nonNullCount++;
                }
            }

            if ($nonNullCount > 1) {
                $from = date("Y-m-d");
                $quryModify1 = " ";
                $quryModify2 = " ";
                $quryModify3 = " ";


                if ($selecteBranch != null) {
                    if (is_array($selecteBranch)) {
                        $quryModify1 .= " IH.branch_id IN ('" . implode("','", $selecteBranch) . "') AND";
                        $quryModify2 .= " S.branch_id IN ('" . implode("','", $selecteBranch) . "') AND";
                    } else {
                        $quryModify1 .= " IH.branch_id = '" . $selecteBranch . "' AND";
                        $quryModify2 .= " S.branch_id = '" . $selecteBranch . "' AND";
                    }
                }



                if ($fromdate != null && $todate != null) {
                    if (is_string($fromdate) && is_string($todate)) {
                        $quryModify1 .= " IH.transaction_date <='" . $todate . "' AND ";
                        $quryModify2 .= " S.transaction_date BETWEEN '" . $fromdate . "' AND '" . $todate . "' AND ";
                    }
                }

                $quryModify1 = preg_replace('/\W\w+\s*(\W*)$/', '$1', $quryModify1);
                $quryModify2 = preg_replace('/\W\w+\s*(\W*)$/', '$1', $quryModify2);



                //dd($quryModify3);
                $query = "SELECT 
                I.Item_code,
                I.item_Name,
                I.package_unit,
                IFNULL(sales.rd_quantity,0) AS rd_quantity,
                IFNULL(sales.rd_value,0) AS rd_value,
                stock.stock_quantity,
                stock.stock_value,
                ICL.item_category_level_1_id,
                I.category_level_2_id
            FROM 
                items I
            INNER JOIN (
                SELECT 
                    IH.item_id,
                    SUM(IH.quantity) AS stock_quantity,
                    ABS(SUM((IH.quantity) * IH.whole_sale_price)) AS stock_value
                FROM  
                item_historys IH
                WHERE 
                " . $quryModify1 . " 
                GROUP BY 
                    item_id 
            ) stock ON I.item_id = stock.item_id 
            LEFT JOIN (
                SELECT 
                    S.item_id,
                   SUM(S.quantity) * -1 AS rd_quantity,
                   SUM((S.quantity * S.price) - ((S.quantity * S.price) / 100 * IF(S.discount_percentage > 0, S.discount_percentage, 0))) * -1 AS rd_value,
                    S.customer_id,
                    S.location_id,
                    S.branch_id
                FROM 
                    sales_with_sales_returns S
WHERE " . $quryModify2 . "
                GROUP BY 
                    item_id
            ) sales ON I.item_id = sales.item_id
           
            LEFT JOIN item_category_level_1s ICL ON ICL.item_category_level_1_id = I.category_level_1_id
            LEFT JOIN item_category_level_2s ICLL ON ICLL.Item_category_level_2_id= I.category_level_2_id
            LEFT JOIN branches B ON B.branch_id=sales.branch_id

            " . $quryModify3 . "
            
";


                //dd($query);
                $result = DB::select($query);


                /*$category_levels = DB::select('SELECT ICL1.item_category_level_1_id,
IFNULL(ICL2.item_category_level_2_id,0) as item_category_level_2_id,
ICL1.category_level_1,
ICL2.category_level_2 
FROM item_category_level_1s ICL1 
LEFT JOIN  item_category_level_2s ICL2
ON ICL2.Item_category_level_1_id = ICL1.item_category_level_1_id');*/

                $category_levels = DB::select('SELECT ICL1.item_category_level_1_id,
                ICL1.category_level_1
                FROM item_category_level_1s ICL1');

                $supplygrouparray = [];
                $table = [];
                $titel = [];
                $reportViwer = new ReportViewer();
                $EXISTING_CATEGORY_LEVEL1 = "";
                $BOOL_CHANGE_TITLE = false;
                foreach ($category_levels as $category_level) {

                    $table = [];




                    foreach ($result as $supplygroupdata) {


                        if ($supplygroupdata->item_category_level_1_id == $category_level->item_category_level_1_id) {
                            array_push($table, $supplygroupdata);
                        }
                    }



                    if (count($table) > 0) {

                        array_push($supplygrouparray, $table);

                        if ($EXISTING_CATEGORY_LEVEL1 == "" && !$BOOL_CHANGE_TITLE) {
                            $EXISTING_CATEGORY_LEVEL1 = $category_level->category_level_1;
                            array_push($titel, '<strong>' . $EXISTING_CATEGORY_LEVEL1 . '</strong>');
                        } else {
                            if ($EXISTING_CATEGORY_LEVEL1 != $category_level->category_level_1) {
                                $EXISTING_CATEGORY_LEVEL1 = $category_level->category_level_1;
                                array_push($titel, '<strong>' . $EXISTING_CATEGORY_LEVEL1 . '</strong>');
                            } else {
                                //array_push($titel, '<strong>' . $category_level->category_level_2 . '</strong>');
                            }
                        }

                        $reportViwer->addParameter('abc', $titel);
                    }
                }



                $reportViwer->addParameter("tabale_data", [$supplygrouparray]);
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

                        //$reportViwer->addParameter("filter", $filterLabel);
                    }
                    // dd($filterLabel);
                    $reportViwer->addParameter('sub_title', "Total RD Stock Report Without Free Issued " . $filterLabel);
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

                    $reportViwer->addParameter('sub_title', "Total RD Stock Report Without Free Issued " . $filterLabel);
                    //$reportViwer->addParameter("filter", $filterLabel);
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
            $user_id = auth()->id();
            /*   $query = "SELECT branches.branch_id, branches.branch_name,branches.address FROM branches LEFT JOIN
            user_distributor ON user_distributor.user_id = $user_id WHERE user_distributor.user_id = $user_id
            AND user_distributor.distributor_id = branches.branch_id";
            $reuslt = DB::select($query); */
            /*  $branchIds = array_column($reuslt, 'branch_name');
            $distributorAddress = array_column($reuslt, 'address'); */

            $userrole = "SELECT users_roles.role_id FROM users_roles WHERE users_roles.user_id=$user_id";
            $alluserrol = DB::select($userrole);

            /* if (!empty($alluserrol) && $alluserrol[0]->role_id == 1  || $alluserrol[0]->role_id == 3) {
                $height = 20;
                $selectbranch = "SELECT B.branch_id, B.branch_name FROM branches B WHERE B.branch_id IN ('" . implode("', '", $selecteBranch) . "')";
                $reuslt = DB::select($selectbranch);
                $branchNames = array_column($reuslt, 'branch_name');

                $reportViwer->addParameter('height', $height);
                $distributorValue = implode(",", $branchNames);
                $reportViwer->addParameter('distributor', $distributorValue);
                $reportViwer->addParameter('distributoraddress', "");
            } else {
                $height = 30;
                $reportViwer->addParameter('height', $height);
                $reportViwer->addParameter('distributor', $branchIds[0]);
                //$reportViwer->addParameter('distributoraddress', $distributorAddress[0]);
            } */
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


            return $reportViwer->viewReport('RDstockReport_Without_Free.json');
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
