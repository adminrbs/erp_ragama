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

class SalesreturndetailsReportController extends Controller
{
    public function salesreturndetailsReport($search)
    {
        try {


            $searchOption = json_decode($search);


            /////// dd($searchOption );



            $selecteCustomer = $searchOption[0]->selecteCustomer;
            $selectecustomergroup = $searchOption[1]->selectecustomergroup;
            $selecteCustomerGrade = $searchOption[2]->selecteCustomerGrade;
            $selecteRoute = $searchOption[3]->selecteRoute;
            $selecteBranch = $searchOption[4]->selecteBranch;
            $fromdate = $searchOption[5]->fromdate;
            $todate = $searchOption[6]->todate;
            $selectSalesrep = $searchOption[7]->selectSalesrep;
           // $selectItem = $searchOption[10]->cmbProduct;
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
                if ($searchOption[10]->cmbProduct !== null) {
                    $nonNullCount++;
                }
            }
            if ($nonNullCount > 1) {
                $query = "SELECT 
                SR.sales_return_Id,
                SR.order_date AS Date ,
                SR.external_number AS invoice_number ,
                LEFT(C.customer_name,100),
                LEFT(L.location_name,100),
                SR.external_number,
                LEFT(E.employee_name, 100) AS sales_rep,
                 LEFT(I.Item_Name,100),
                 (ABS(CAST(SRI.quantity AS SIGNED))) as qty ,
                 (ABS(CAST(SRI.free_quantity AS SIGNED))) as bonus,
                 SRI.quantity* SRI.price AS Value, 
                 U.name
                 
                 
                               FROM sales_returns  SR 
                              LEFT JOIN sales_return_items SRI  ON SR.sales_return_Id=SRI.sales_return_Id 
                              LEFT JOIN items I ON SRI.item_id=I.item_id 
                              LEFT JOIN customers C ON C.customer_id=SR.customer_id 
                              LEFT JOIN employees E ON E.employee_id=SR.employee_id
                              LEFT JOIN branches D ON D.branch_id = SR.branch_id
                              LEFT JOIN locations L ON L.location_id=SR.location_id
                              LEFT JOIN users U ON U.id=SR.prepaired_by";




                $quryModify = "";
                if ($selectSalesrep != null) {
                    if (count($selectSalesrep) > 1) {
                        $quryModify .= " E.employee_id IN ('" . implode("','", $selectSalesrep) . "') AND";
                       
                    }  else {
                        $quryModify .= " E.employee_id ='" . $selectSalesrep[0] . "' AND";
                    }
                }
                if ($fromdate != null && $todate != null) {
                    if ($nonNullCount > 2) {
                        $quryModify .= " SR.order_date between '" . $fromdate . "' AND '" . $todate . "' AND";
                    } else {
                        $quryModify .= " SR.order_date between '" . $fromdate . "' AND '" . $todate . "'";
                    }
                }
                if ($selecteCustomer != null) {

                    if (count($selecteCustomer) > 1) {
                        $quryModify .= " SR.customer_id IN ('" . implode("', '", $selecteCustomer) . "') AND";
                    } else {
                        $quryModify .= " SR.customer_id ='" . $selecteCustomer[0] . "' AND";
                    }
                }

                if ($selectecustomergroup != null) {
                    if (count($selectecustomergroup) > 1) {
                        $quryModify .= " C.customer_group_id IN ('" . implode("', '", $selectecustomergroup) . "') AND";
                    } else {
                        $quryModify .= " C.customer_group_id ='" . $selectecustomergroup[0] . "' AND";
                    }
                }


                if ($selecteCustomerGrade != null) {
                    if (count($selecteCustomerGrade) > 1) {
                        $quryModify .= " C.customer_grade_id IN ('" . implode("', '", $selecteCustomerGrade) . "') AND";
                    } else {
                        $quryModify .= " C.customer_grade_id ='" . $selecteCustomerGrade[0] . "' AND";
                    }
                }


                if ($selecteRoute != null) {
                    if (count($selecteRoute) > 1) {
                        $quryModify .= " C.route_id IN ('" . implode("', '", $selecteRoute) . "') AND";
                    } else {
                        $quryModify .= " C.route_id ='" . $selecteRoute[0] . "' AND";
                    }
                }
                if ($selecteBranch != null) {
                    if (count($selecteBranch) > 1) {
                        $quryModify .= " SR.branch_id IN ('" . implode("', '", $selecteBranch) . "') AND";
                    } else {
                        $quryModify .= " SR.branch_id ='" . $selecteBranch[0] . "' AND";
                    }
                }

                /* if ($selectItem != null) {
                    if (is_array($selectItem)) {
                        $quryModify .= " I.item_id  IN ('" . implode("','", $selectItem) . "') AND";
                    } else {
                        $quryModify .= " I.item_id  = '" . $selectItem . "' AND";
                    }
                } */

                if ($quryModify !== "") {
                    $quryModify = rtrim($quryModify, 'AND OR ');
                    $query = $query . " where " . $quryModify;
                }



                //$query = preg_replace('/\W\w+\s*(\W*)$/', '$1', $query);
                //dd($query);
                $result = DB::select($query);

                $reportViwer = new ReportViewer();
                $manualanumber = DB::select('select sales_return_Id,manual_number,order_date from sales_returns');

                $manualnumberarray = [];
                $table = [];
                $titel = [];
                $reportViwer = new ReportViewer();
                foreach ($manualanumber as $manualanumber) {
                    $table = [];


                    foreach ($result as $manualdata) {
                        //dd($result);
                        if ($manualdata->sales_return_Id == $manualanumber->sales_return_Id) {


                            array_push($table, $manualdata);
                        }
                    }



                    if (count($table) > 0) {

                        array_push($manualnumberarray, $table);


                        array_push($titel, $manualanumber->manual_number . ' - ' .$manualanumber->order_date);


                        $reportViwer->addParameter('abc', $titel);
                    }
                }
               // dd($manualnumberarray);

                $reportViwer->addParameter("sales_return_tabaledata", [$manualnumberarray]);
            } else {

                $query = "SELECT 
                SR.sales_return_Id,
                SR.order_date AS Date ,
                SR.external_number AS invoice_number ,
                LEFT(C.customer_name,100),
                LEFT(L.location_name,100),
                SR.external_number,
                LEFT(E.employee_name, 100) AS sales_rep,
                 LEFT(I.Item_Name,100),
                 (ABS(CAST(SRI.quantity AS SIGNED))) as qty ,
                 (ABS(CAST(SRI.free_quantity AS SIGNED))) as bonus,
                 SRI.quantity* SRI.price AS Value,
                 U.name
                 
                               FROM sales_returns  SR 
                              LEFT JOIN sales_return_items SRI  ON SR.sales_return_Id=SRI.sales_return_Id 
                              LEFT JOIN items I ON SRI.item_id=I.item_id 
                              LEFT JOIN customers C ON C.customer_id=SR.customer_id 
                              LEFT JOIN employees E ON E.employee_id=SR.employee_id
                              LEFT JOIN branches D ON D.branch_id = SR.branch_id
                              LEFT JOIN locations L ON L.location_id=SR.location_id
                              LEFT JOIN users U ON U.id=SR.prepaired_by";


                $quryModify = "";
                if ($fromdate != null && $todate != null) {
                    $quryModify .= "SR.order_date between '" . $fromdate . "' AND '" . $todate . "'";
                }
                if ($selectSalesrep != null) {
                    if (count($selectSalesrep) > 1) {
                        $quryModify .= " E.employee_id IN ('" . implode("','", $selectSalesrep) . "') ";
                       
                    }  else {
                        $quryModify .= " E.employee_id ='" . $selectSalesrep[0] . "' ";
                    }
                }
                if ($selecteCustomer != null) {

                    if (count($selecteCustomer) > 1) {
                        $quryModify .= " SR.customer_id IN ('" . implode("', '", $selecteCustomer) . "')";
                    } else {
                        $quryModify .= " SR.customer_id ='" . $selecteCustomer[0] . "'";
                    }
                }

                if ($selectecustomergroup != null) {
                    if (count($selectecustomergroup) > 1) {
                        $quryModify .= " C.customer_group_id IN ('" . implode("', '", $selectecustomergroup) . "')";
                    } else {
                        $quryModify .= " C.customer_group_id ='" . $selectecustomergroup[0] . "'";
                    }
                }


                if ($selecteCustomerGrade != null) {
                    if (count($selecteCustomerGrade) > 1) {
                        $quryModify .= " C.customer_grade_id IN ('" . implode("', '", $selecteCustomerGrade) . "')";
                    } else {
                        $quryModify .= " C.customer_grade_id ='" . $selecteCustomerGrade[0] . "'";
                    }
                }


                if ($selecteRoute != null) {
                    if (count($selecteRoute) > 1) {
                        $quryModify .= " C.route_id IN ('" . implode("', '", $selecteRoute) . "')";
                    } else {
                        $quryModify .= " C.route_id ='" . $selecteRoute[0] . "'";
                    }
                }
                if ($selecteBranch != null) {
                    if (count($selecteBranch) > 1) {
                        $quryModify .= " SR.branch_id IN ('" . implode("', '", $selecteBranch) . "')";
                    } else {
                        $quryModify .= " SR.branch_id ='" . $selecteBranch[0] . "'";
                    }
                }

                /* if ($selectItem != null) {
                    if (is_array($selectItem)) {
                        $quryModify .= " I.item_id  IN ('" . implode("','", $selectItem) . "') AND";
                    } else {
                        $quryModify .= " I.item_id  = '" . $selectItem . "' AND";
                    }
                } */

                if ($quryModify !== "") {

                    $query = $query . " WHERE " . $quryModify;
                }
                if ($quryModify == "") {

                    $query = $query;
                }



                //$query = preg_replace('/\W\w+\s*(\W*)$/', '$1', $query);
                //dd($query);
                $result = DB::select($query);

                // $reportViwer = new ReportViewer();
                // $reportViwer->addParameter("sales_return_tabaledata", $result);





                $reportViwer = new ReportViewer();
                $manualanumber = DB::select('select sales_return_Id,manual_number,order_date from sales_returns');

                $manualnumberarray = [];
                $table = [];
                $titel = [];
                $reportViwer = new ReportViewer();
                foreach ($manualanumber as $manualanumber) {
                    $table = [];


                    foreach ($result as $manualdata) {
                        //dd($result);
                        if ($manualdata->sales_return_Id == $manualanumber->sales_return_Id) {


                            array_push($table, $manualdata);
                        }
                    }



                    if (count($table) > 0) {

                        array_push($manualnumberarray, $table);


                        array_push($titel, $manualanumber->manual_number . ' - ' .$manualanumber->order_date);


                        $reportViwer->addParameter('abc', $titel);
                    }
                }
               // dd($manualnumberarray);

                $reportViwer->addParameter("sales_return_tabaledata", [$manualnumberarray]);
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
                        $reportViwer->addParameter("customer", $customer);
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
                    }elseif ($selectSalesrep !== null) {
                        $filterLabel = "For: Sales Rep: $selectesalesrep1";
                    }


                    $reportViwer->addParameter("customer", $customer);

                    //$filterLabel = "From: $fromdate  To: $todate  and product: $itemname  and categorylevel1: $cusgroup";

                    $filterLabel = "";
                    $reportViwer->addParameter("filter", $filterLabel);
                }
            }
            $total = 0;
            foreach ($result as $row) {
                $total += $row->bonus;
            }
            

            if($total == 0){
                $formattedTotal =$total;
                $concatenatedTotal = '' ;
                $reportViwer->addParameter('total', $concatenatedTotal);
            }else{
                $formattedTotal = $total;
                $concatenatedTotal = 'Bonus QTY' . ' ' . ' ' . $formattedTotal;
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


            return $reportViwer->viewReport('salesreturnDetailsSalesreport.json');
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

}
