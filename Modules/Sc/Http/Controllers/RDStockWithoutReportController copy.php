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

class RDStockWithoutReportControllercopy extends Controller
{
    public function rdStockreport($search)
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
                        $branch_name =  $supplygroupdata->branch_name;

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
