<?php

namespace Modules\Sd\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Md\Entities\marketingRoute;
use Modules\Sd\Entities\branch;
use Modules\Sd\Entities\Customer;
use Modules\Sd\Entities\Customer_grade;
use Modules\Sd\Entities\Customer_group;
use Modules\Sd\Entities\employee;
use Modules\Sd\Entities\route;
use RepoEldo\ELD\ReportViewer;

class ProductwisequantitysalestypeReportController extends Controller
{
    public function productwisequantitysalestype($search)

    {
        try {


            $searchOption = json_decode($search);


            //dd($searchOption );



            $selecteCustomer = $searchOption[0]->selecteCustomer;
            $selectecustomergroup = $searchOption[1]->selectecustomergroup;
            $selecteCustomerGrade = $searchOption[2]->selecteCustomerGrade;
            $selecteRoute = $searchOption[3]->selecteRoute;
            $selecteBranch = $searchOption[4]->selecteBranch;
            $fromdate = $searchOption[5]->fromdate;
            $todate = $searchOption[6]->todate;
            $selectSalesrep = $searchOption[7]->selectSalesrep;
            $cmbMarketingRoute = $searchOption[8]->cmbMarketingRoute;
            $cmbSupplyGroup = $searchOption[9]->cmbSupplyGroup;



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
                if ($searchOption[8]->cmbMarketingRoute !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[9]->cmbSupplyGroup !== null) {
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
                if ($cmbMarketingRoute != null) {
                    if (is_array($cmbMarketingRoute)) {
                        $quryModify1 .= " C.marketing_route_id IN ('" . implode("','", $cmbMarketingRoute) . "') AND";
                        $quryModify2 .= " C.marketing_route_id IN ('" . implode("','", $cmbMarketingRoute) . "') AND";
                    } else {
                        $quryModify1 .= " C.marketing_route_id = '" . $cmbMarketingRoute . "' AND";
                        $quryModify2 .= " C.marketing_route_id = '" . $cmbMarketingRoute . "' AND";
                    }
                }

                if ($cmbSupplyGroup != null) {
                    if (is_array($cmbSupplyGroup)) {
                        $quryModify1 .= " SG.supply_group_id IN ('" . implode("','", $cmbSupplyGroup) . "') AND";
                        $quryModify2 .= " SG.supply_group_id IN ('" . implode("','", $cmbSupplyGroup) . "') AND";
                    } else {
                        $quryModify1 .= " SG.supply_group_id = '" . $cmbSupplyGroup . "' AND";
                        $quryModify2 .= " SG.supply_group_id = '" . $cmbSupplyGroup . "' AND";
                    }
                }

                if ($fromdate != null && $todate != null) {
                    if (is_string($fromdate) && is_string($todate)) {
                        $quryModify1 .= " SI.order_date_time  BETWEEN '" . $fromdate . "' AND '" . $todate . "' AND ";
                        $quryModify2 .= " SI.order_date  BETWEEN '" . $fromdate . "' AND '" . $todate . "' AND ";
                    }
                }
                $quryModify1 = preg_replace('/\W\w+\s*(\W*)$/', '$1', $quryModify1);
                $quryModify2 = preg_replace('/\W\w+\s*(\W*)$/', '$1', $quryModify2);




                $query = "SELECT I.Item_code, I.item_Name, DT.sales, DT.free,DT.sample,DT.customer_id
                FROM (
                    SELECT
                        SI.customer_id,
                        SII.item_id,
                        SI.branch_id,
                        SI.employee_id,
                        SUM(SII.quantity) * -1 AS sales,
                        SUM(free_quantity) * -1 AS free,
						IFNULL(SUM(SDP.quantity) * -1,0) AS sample
                    FROM
                        sales_invoices SI
                        INNER JOIN sales_invoice_items SII ON SI.sales_invoice_Id = SII.sales_invoice_Id
                        INNER JOIN customers C ON C.customer_id = SI.customer_id
                        INNER JOIN employees E ON E.employee_id = SI.employee_id
                        INNER JOIN branches B ON B.branch_id = SI.branch_id
                        INNER JOIN marketing_routes M ON C.marketing_route_id = M.marketing_route_id
                        INNER JOIN items ITM ON SII.item_id = ITM.item_id
                        INNER JOIN supply_groups SG ON ITM.supply_group_id = SG.supply_group_id
                        LEFT JOIN sample_dispatch_items SDP ON ITM.item_id = SDP.item_id
                        WHERE  " . $quryModify1 . " 
                    GROUP BY
                        SI.customer_id,
                        SII.item_id,
                        SI.branch_id,
                        SI.employee_id
                
                    UNION ALL
                
                    SELECT
                        SI.customer_id,
                        SII.item_id,
                        SI.branch_id,
                        SI.employee_id,
                        SUM(SII.quantity) * -1 AS sales,
                        SUM(free_quantity) * -1 AS free,
                        IFNULL(SUM(SDP.quantity) * -1,0) AS sample
                    FROM
                        sales_returns SI
                        INNER JOIN sales_return_items SII ON SI.sales_return_Id = SII.sales_return_Id
                        INNER JOIN customers C ON C.customer_id = SI.customer_id
                        INNER JOIN employees E ON E.employee_id = SI.employee_id
                        INNER JOIN branches B ON B.branch_id = SI.branch_id
                        INNER JOIN marketing_routes M ON C.marketing_route_id = M.marketing_route_id
                        INNER JOIN items ITM ON SII.item_id = ITM.item_id
                        INNER JOIN supply_groups SG ON ITM.supply_group_id = SG.supply_group_id
                        LEFT JOIN sample_dispatch_items SDP ON ITM.item_id = SDP.item_id
                        WHERE " . $quryModify2 . " 
                    GROUP BY
                        SI.customer_id,
                        SII.item_id,
                        SI.branch_id,
                        SI.employee_id
                ) DT
                INNER JOIN items I ON I.item_id = DT.item_id
                INNER JOIN supply_groups SG ON SG.supply_group_id = I.supply_group_id
                INNER JOIN customers C ON C.customer_id = DT.customer_id
                INNER JOIN town_non_administratives T ON T.town_id = C.town
                INNER JOIN employees E ON E.employee_id = DT.employee_id
                INNER JOIN branches B ON B.branch_id = DT.branch_id
                LEFT JOIN sample_dispatch_items SDP ON I.item_id = SDP.item_id;
                
";



                //$query = preg_replace('/\W\w+\s*(\W*)$/', '$1', $query);
                //dd($query);
                $result = DB::select($query);

                $resulcustomer = DB::select('select customer_id,customer_name,customer_code,marketing_routes.route_name from customers
                INNER JOIN marketing_routes ON customers.marketing_route_id = marketing_routes.marketing_route_id
                ORDER BY marketing_routes.marketing_route_id');

                $customerablearray = [];
                $titel = [];
                $titel2 = [];
                $routes = [];

                $reportViwer = new ReportViewer();
                foreach ($resulcustomer as $customerid) {
                    $table = [];
                    foreach ($result as $customerdata) {
                        //dd($result);

                        if ($customerdata->customer_id == $customerid->customer_id) {

                            array_push($table, $customerdata);
                        } else {
                        }
                    }




                    $route_name = $customerid->route_name;
                    if (count($table) > 0) {
                        array_push($customerablearray, $table);

                        if (end($routes) != $route_name) {
                            array_push($titel, "Marketing Route :<strong>" . $route_name . " </strong>");
                        } else {
                            //array_push($titel, "Customer :<strong>" . $customerid->customer_code . '   ' . ' - ' . $customerid->customer_name . "</strong>");
                        }
                        array_push($routes, $route_name);
                        $reportViwer->addParameter('abc', $titel);
                    }
                }

                $reportViwer->addParameter("tabaledata", [$customerablearray]);
            } else {
                $quryModify1 = " WHERE ";
                $quryModify2 = " WHERE ";
                if (
                    $selecteCustomer == null && $selectecustomergroup == null && $selecteCustomerGrade == null
                    && $selecteRoute == null && $selecteBranch == null && $fromdate == null && $todate == null && $selectSalesrep == null && $cmbMarketingRoute == null && $cmbSupplyGroup == null
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
               
                if ($cmbMarketingRoute != null) {
                    if (is_array($cmbMarketingRoute)) {
                        $quryModify1 .= " C.marketing_route_id IN ('" . implode("','", $cmbMarketingRoute) . "') AND";
                        $quryModify2 .= " C.marketing_route_id IN ('" . implode("','", $cmbMarketingRoute) . "') AND";
                    } else {
                        $quryModify1 .= " C.marketing_route_id = '" . $cmbMarketingRoute . "' AND";
                        $quryModify2 .= " C.marketing_route_id = '" . $cmbMarketingRoute . "' AND";
                    }
                }

                if ($cmbSupplyGroup != null) {
                    if (is_array($cmbSupplyGroup)) {
                        $quryModify1 .= " SG.supply_group_id IN ('" . implode("','", $cmbSupplyGroup) . "') AND";
                        $quryModify2 .= " SG.supply_group_id IN ('" . implode("','", $cmbSupplyGroup) . "') AND";
                    } else {
                        $quryModify1 .= " SG.supply_group_id = '" . $cmbSupplyGroup . "' AND";
                        $quryModify2 .= " SG.supply_group_id = '" . $cmbSupplyGroup . "' AND";
                    }
                }
                

                if ($fromdate != null && $todate != null) {
                    if (is_string($fromdate) && is_string($todate)) {
                        $quryModify1 .= " SI.order_date_time  BETWEEN '" . $fromdate . "' AND '" . $todate . "' AND ";
                        $quryModify2 .= " SI.order_date  BETWEEN '" . $fromdate . "' AND '" . $todate . "' AND ";
                    }
                }
                $quryModify1 = preg_replace('/\W\w+\s*(\W*)$/', '$1', $quryModify1);
                $quryModify2 = preg_replace('/\W\w+\s*(\W*)$/', '$1', $quryModify2);





                $query = "SELECT I.Item_code, I.item_Name, DT.sales, DT.free,DT.sample,DT.customer_id
                FROM (
                    SELECT
                        SI.customer_id,
                        SII.item_id,
                        SI.branch_id,
                        SI.employee_id,
                        SUM(SII.quantity) * -1 AS sales,
                        SUM(free_quantity) * -1 AS free,
						IFNULL(SUM(SDP.quantity) * -1,0) AS sample
                    FROM
                        sales_invoices SI
                        INNER JOIN sales_invoice_items SII ON SI.sales_invoice_Id = SII.sales_invoice_Id
                        INNER JOIN customers C ON C.customer_id = SI.customer_id
                        INNER JOIN employees E ON E.employee_id = SI.employee_id
                        INNER JOIN branches B ON B.branch_id = SI.branch_id
                        INNER JOIN marketing_routes M ON C.marketing_route_id = M.marketing_route_id
                        INNER JOIN items ITM ON SII.item_id = ITM.item_id
                        INNER JOIN supply_groups SG ON ITM.supply_group_id = SG.supply_group_id
                        LEFT JOIN sample_dispatch_items SDP ON ITM.item_id = SDP.item_id
                        " . $quryModify1 . " 
                    GROUP BY
                        SI.customer_id,
                        SII.item_id,
                        SI.branch_id,
                        SI.employee_id
                
                    UNION ALL
                
                    SELECT
                        SI.customer_id,
                        SII.item_id,
                        SI.branch_id,
                        SI.employee_id,
                        SUM(SII.quantity) * -1 AS sales,
                        SUM(free_quantity) * -1 AS free,
                        IFNULL(SUM(SDP.quantity) * -1,0) AS sample
                    FROM
                        sales_returns SI
                        INNER JOIN sales_return_items SII ON SI.sales_return_Id = SII.sales_return_Id
                        INNER JOIN customers C ON C.customer_id = SI.customer_id
                        INNER JOIN employees E ON E.employee_id = SI.employee_id
                        INNER JOIN branches B ON B.branch_id = SI.branch_id
                        INNER JOIN marketing_routes M ON C.marketing_route_id = M.marketing_route_id
                        INNER JOIN items ITM ON SII.item_id = ITM.item_id
                        INNER JOIN supply_groups SG ON ITM.supply_group_id = SG.supply_group_id
                        LEFT JOIN sample_dispatch_items SDP ON ITM.item_id = SDP.item_id
                        " . $quryModify2 . " 
                    GROUP BY
                        SI.customer_id,
                        SII.item_id,
                        SI.branch_id,
                        SI.employee_id
                ) DT
                INNER JOIN items I ON I.item_id = DT.item_id
                INNER JOIN supply_groups SG ON SG.supply_group_id = I.supply_group_id
                INNER JOIN customers C ON C.customer_id = DT.customer_id
                INNER JOIN town_non_administratives T ON T.town_id = C.town
                INNER JOIN employees E ON E.employee_id = DT.employee_id
                INNER JOIN branches B ON B.branch_id = DT.branch_id
                LEFT JOIN sample_dispatch_items SDP ON I.item_id = SDP.item_id";



                //dd($query);
                $result = DB::select($query);

                $resulcustomer = DB::select('select customer_id,customer_name,customer_code,marketing_routes.route_name from customers
                INNER JOIN marketing_routes ON customers.marketing_route_id = marketing_routes.marketing_route_id
                ORDER BY marketing_routes.marketing_route_id');

                $customerablearray = [];
                $titel = [];
                $titel2 = [];
                $routes = [];

                $reportViwer = new ReportViewer();
                foreach ($resulcustomer as $customerid) {
                    $table = [];
                    foreach ($result as $customerdata) {
                        //dd($result);

                        if ($customerdata->customer_id == $customerid->customer_id) {

                            array_push($table, $customerdata);
                        } else {
                        }
                    }




                    $route_name = $customerid->route_name;
                    if (count($table) > 0) {
                        

                        if (end($routes) != $route_name) {
                            array_push($titel, "Marketing Route :<strong>" . $route_name . " </strong>");
                            array_push($customerablearray, $table);
                        } else {
                           // array_push($titel, "Customer :<strong>" . $customerid->customer_code . '   ' . ' - ' . $customerid->customer_name . "</strong>");
                        }
                        array_push($routes, $route_name);
                        $reportViwer->addParameter('abc', $titel);
                    }
                }


                $reportViwer->addParameter("tabaledata", [$customerablearray]);
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
                /* $selectSalesrep1 = $this->getrep($selectSalesrep);
                $selectSalesrep = '';
                if ($selectSalesrep1 != null) {
                    $selectSalesrep = $selectSalesrep1->employee_name;
                }*/
                $selectesalesrep = $this->getSalesrepdata($selectesalesrep);
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
                    if ($selectesalesrep !== null) {
                        // Add a separator if needed
                        if ($filterLabel !== '') {
                            $filterLabel .= ' ';
                        }
                        $filterLabel .= " Sales Rep: $selectesalesrep1 and";
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
            $total = 0; // Initialize total amount

            foreach ($result as $row) {
                $total += $row->free;
            }
            $formattedTotal = number_format($total, 2, '.', ',');
            $concatenatedTotal = 'Grand Total' . ' ' . ' ' . $formattedTotal;

            $reportViwer->addParameter('total', $concatenatedTotal);
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


            return $reportViwer->viewReport('product_wise_quantity_sales_type.json');
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
    public function getSalesrepdata($selectesalesrep)
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
    public function marketing_route($selecteMarketingRoute)
    {
        if ($selecteMarketingRoute != null) {
            $route = marketingRoute::whereIn('marketing_route_id', $selecteMarketingRoute)
                ->select('marketing_route_id', 'route_name')
                ->get();
            return $route;
        }
    }
    public function getMarketingRoute()
    {
        try {
            $query = "select marketing_routes.* FROM marketing_routes";
            $data = DB::select($query);
            return response()->json($data);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function getSupplyGroup()
    {
        try {
            $query = "select supply_groups.supply_group_id ,supply_groups.supply_group from supply_groups";
            $data = DB::select($query);
            return response()->json($data);
        } catch (Exception $ex) {
            return $ex;
        }
    }
}
