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

class SalesdetailsReportController extends Controller
{
    public function salesdetailsReport($search)
    {
        try {


            $searchOption = json_decode($search);






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
            $selectcategoryone = $searchOption[11]->selectecategory1;




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
                if ($searchOption[11]->selectecategory1 !== null) {
                    $nonNullCount++;
                }
            }
            if ($nonNullCount > 1) {

                $quryModify1 = " ";

                if ($selecteBranch != null) {
                    if (is_array($selecteBranch)) {
                        $quryModify1 .= " B.branch_id IN ('" . implode("','", $selecteBranch) . "') AND";
                    }
                }

                if ($selectSalesrep != null) {
                    if (is_array($selectSalesrep)) {
                        $quryModify1 .= " E.employee_id IN ('" . implode("','", $selectSalesrep) . "') AND";
                    }
                }
                if ($selecteCustomer != null) {
                    if (is_array($selecteCustomer)) {
                        $quryModify1 .= " C.customer_id IN ('" . implode("','", $selecteCustomer) . "') AND";
                    }
                }

                if ($selectecustomergroup != null) {
                    if (is_array($selectecustomergroup)) {
                        $quryModify1 .= " C.customer_group_id IN ('" . implode("','", $selectecustomergroup) . "') AND";
                    }
                }


                if ($selecteRoute != null) {
                    if (is_array($selecteRoute)) {
                        $quryModify1 .= " C.route_id IN ('" . implode("','", $selecteRoute) . "') AND";
                    }
                }

                if ($fromdate != null && $todate != null) {
                    if (is_string($fromdate) && is_string($todate)) {
                        $quryModify1 .= " SWSR.transaction_date BETWEEN '" . $fromdate . "' AND '" . $todate . "' AND ";
                    }
                }
                if ($selectItem != null) {
                    if (is_array($selectItem)) {
                        $quryModify1 .= " I.item_id  IN ('" . implode("','", $selectItem) . "') AND";
                    }
                }

                if ($selectSupGroup != null) {
                    if (is_array($selectSupGroup)) {
                        $quryModify1 .= " SG.supply_group_id  IN ('" . implode("','", $selectSupGroup) . "') AND";
                    }
                }

                if ($selectcategoryone != null) {
                    if (is_array($selectcategoryone)) {
                        $quryModify1 .= " I.category_level_1_id  IN ('" . implode("','", $selectcategoryone) . "') AND";
                    }
                }

                  $quryModify1 = preg_replace('/\W\w+\s*(\W*)$/', '$1', $quryModify1);
               /*  $quryModify2 = preg_replace('/\W\w+\s*(\W*)$/', '$1', $quryModify2);
 */
                $query = "SELECT 
    I.Item_code,
    I.item_Name,
    I.package_unit,
    SUM(CASE WHEN SWSR.transaction_type = 'Sales' THEN (quantity * -1) ELSE 0 END) AS salesQty,
    SUM(CASE WHEN SWSR.transaction_type = 'Sales Return' THEN quantity ELSE 0 END) AS rtnQty,
    SUM(CASE WHEN SWSR.transaction_type = 'Sales' THEN (quantity * -1) ELSE 0 END) -
    SUM(CASE WHEN SWSR.transaction_type = 'Sales Return' THEN quantity ELSE 0 END) AS netQty,
		SUM(CASE WHEN SWSR.transaction_type = 'Sales' THEN (free_quantity * -1) ELSE 0 END) AS focQty,
		SUM(CASE WHEN SWSR.transaction_type = 'Sales Return' THEN (free_quantity) ELSE 0 END) AS RtnfocQty,
		SUM(CASE WHEN SWSR.transaction_type = 'Sales' THEN (free_quantity * -1) ELSE 0 END)  -
		SUM(CASE WHEN SWSR.transaction_type = 'Sales Return' THEN (free_quantity) ELSE 0 END) AS netFocQty,
		SUM(ABS(IF(SWSR.transaction_type = 'Sales', (CAST(SWSR.quantity AS SIGNED) * SWSR.price) + item_discount_amount, 0))) AS sales, 
        SUM(ABS(IF(SWSR.transaction_type = 'Sales Return', (CAST(SWSR.quantity AS SIGNED) * SWSR.price) - item_discount_amount, 0))) AS returns,
		SUM(CASE WHEN SWSR.transaction_type = 'Sales' THEN ((quantity * -1) * SWSR.price) ELSE 0 END) -
		SUM(CASE WHEN SWSR.transaction_type = 'Sales Return' THEN (quantity * SWSR.price) ELSE 0 END) AS netSales,
    I.category_level_1_id
FROM 
    sales_with_sales_returns SWSR
INNER JOIN 
    items I ON SWSR.item_id = I.item_id
LEFT JOIN customers C ON SWSR.customer_id = C.customer_id
LEFT JOIN customer_groups CG ON C.customer_group_id = CG.customer_group_id
LEFT JOIN employees E ON  SWSR.employee_id = E.employee_id
LEFT JOIN routes R ON C.route_id = R.route_id
LEFT JOIN supply_groups SG ON I.supply_group_id = SG.supply_group_id
LEFT JOIN item_category_level_1s ICL ON I.category_level_1_id = ICL.item_category_level_1_id
LEFT JOIN branches B ON SWSR.branch_id = B.branch_id

WHERE ";
   

                if ($nonNullCount > 0) {
                    $query .= $quryModify1;
                }
               // $query = preg_replace('/\W\w+\s*(\W*)$/', '$1', $query);

                $query .= "GROUP BY SWSR.item_id";

                //dd($query);
                $result = DB::select($query);
               

                $reportViwer = new ReportViewer();
               // $resulsupplygroup = DB::select('select supply_groups.supply_group_id ,supply_groups.supply_group from supply_groups');
               $resulsupplygroup = DB::select('select item_category_level_1s.item_category_level_1_id ,item_category_level_1s.category_level_1 from item_category_level_1s');
                $supplygrouparray = [];
                $table = [];
                $titel = [];
                $reportViwer = new ReportViewer();
                foreach ($resulsupplygroup as $supplygroupid) {
                    $table = [];


                    foreach ($result as $supplygroupdata) {
                        //dd($result);
                        if ($supplygroupdata->category_level_1_id == $supplygroupid->item_category_level_1_id) {


                            array_push($table, $supplygroupdata);
                        }
                    }



                    if (count($table) > 0) {

                        array_push($supplygrouparray, $table);


                        array_push($titel, $supplygroupid->category_level_1);


                        $reportViwer->addParameter('abc', $titel);
                    }
                }
                //dd($supplygrouparray);

                $reportViwer->addParameter("tabale_data", [$supplygrouparray]);

                //$reportViwer->addParameter("tabale_data", $result);
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
                $total += $row->netSales;
            }
            if ($total == 0) {
                $formattedTotal = number_format($total, 2, '.', ',');
                $concatenatedTotal = '';
                $reportViwer->addParameter('total', $concatenatedTotal);
            } else {
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

            //dd($reportViwer);
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
