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

class SalessummaryController extends Controller
{
    public function getSalesrep()
    {
        try {
            $query = "SELECT E.employee_id,E.employee_name FROM employees E WHERE E.desgination_id=7";
            $data = DB::select($query);
            return response()->json($data);
        } catch (Exception $ex) {
            return $ex;
        }
    }
    public function sales_summaryReport($search)
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
            $selectItem = $searchOption[10]->cmbProduct;



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
                $query = "SELECT DISTINCT SI.manual_number AS invoice_number ,
                SI.order_date_time AS Date ,
                C.customer_code , 
                CONCAT(C.customer_name ,' ', T.townName ) AS customer_name  , 
                E.employee_name as sales_rep,
                SI.total_amount as amount 
                 
              
               FROM sales_invoices  SI 
              INNER JOIN sales_invoice_items SII  ON SI.sales_invoice_Id=SII.sales_invoice_Id 
              INNER JOIN items I ON SII.item_id=I.item_id 
              INNER JOIN customers C ON C.customer_id=SI.customer_id 
              INNER JOIN town_non_administratives T ON T.town_id=C.town 
              INNER JOIN employees E ON E.employee_id=SI.employee_id
              INNER JOIN branches D ON D.branch_id = SI.branch_id
";


                $quryModify = "";
                if ($selectSalesrep != null) {
                    if (count($selectSalesrep) > 1) {
                        $quryModify .= " E.employee_id IN ('" . implode("','", $selectSalesrep) . "') AND ";
                    } else {
                        $quryModify .= " E.employee_id ='" . $selectSalesrep[0] . "' AND ";
                    }
                }
                if ($fromdate != null && $todate != null) {
                    if ($nonNullCount > 2) {
                        $quryModify .= "SI.order_date_time between '" . $fromdate . "' AND '" . $todate . "' AND ";
                    } else {
                        $quryModify .= "SI.order_date_time between '" . $fromdate . "' AND '" . $todate . "'";
                    }
                }
                if ($selecteCustomer != null) {

                    if (count($selecteCustomer) > 1) {
                        $quryModify .= " SI.customer_id IN ('" . implode("', '", $selecteCustomer) . "') AND ";
                    } else {
                        $quryModify .= " SI.customer_id ='" . $selecteCustomer[0] . "' AND ";
                    }
                }

                if ($selectecustomergroup != null) {
                    if (count($selectecustomergroup) > 1) {
                        $quryModify .= " C.customer_group_id IN ('" . implode("', '", $selectecustomergroup) . "') AND ";
                    } else {
                        $quryModify .= " C.customer_group_id ='" . $selectecustomergroup[0] . "' AND ";
                    }
                }


                if ($selecteCustomerGrade != null) {
                    if (count($selecteCustomerGrade) > 1) {
                        $quryModify .= " C.customer_grade_id IN ('" . implode("', '", $selecteCustomerGrade) . "') AND ";
                    } else {
                        $quryModify .= " C.customer_grade_id ='" . $selecteCustomerGrade[0] . "' AND ";
                    }
                }


                if ($selecteRoute != null) {
                    if (count($selecteRoute) > 1) {
                        $quryModify .= " C.route_id IN ('" . implode("', '", $selecteRoute) . "') AND ";
                    } else {
                        $quryModify .= " C.route_id ='" . $selecteRoute[0] . "' AND ";
                    }
                }
                if ($selecteBranch != null) {
                    if (count($selecteBranch) > 1) {
                        $quryModify .= " SI.branch_id IN ('" . implode("', '", $selecteBranch) . "') AND ";
                    } else {
                        $quryModify .= " SI.branch_id ='" . $selecteBranch[0] . "' AND ";
                    }
                }
                if ($selectItem != null) {
                    if (count($selectItem) > 1) {
                        $quryModify .= " I.item_id IN ('" . implode("', '", $selectItem) . "') ";
                    } else {
                        $quryModify .= " I.item_id ='" . $selectItem[0] . "' ";
                    }
                }

                if ($quryModify !== "") {
                    $quryModify = rtrim($quryModify, 'AND OR ');
                    $query = $query . " where " . $quryModify;
                }



                //$query = preg_replace('/\W\w+\s*(\W*)$/', '$1', $query);
                //dd($query);
                $result = DB::select($query);


                $reportViwer = new ReportViewer();
                $reportViwer->addParameter("sales_summary_tabaledata", $result);
            } else {





                $query = "SELECT DISTINCT SI.manual_number AS invoice_number ,
                SI.order_date_time AS Date ,
                C.customer_code , 
                CONCAT(C.customer_name ,' ', T.townName ) AS customer_name  , 
                E.employee_name as sales_rep,
                SI.total_amount as amount  
              
               FROM sales_invoices  SI 
              INNER JOIN sales_invoice_items SII  ON SI.sales_invoice_Id=SII.sales_invoice_Id 
              INNER JOIN items I ON SII.item_id=I.item_id 
              INNER JOIN customers C ON C.customer_id=SI.customer_id 
              INNER JOIN town_non_administratives T ON T.town_id=C.town 
              INNER JOIN employees E ON E.employee_id=SI.employee_id
              INNER JOIN branches D ON D.branch_id = SI.branch_id";


                $quryModify = "";
                if ($fromdate != null && $todate != null) {
                    $quryModify .= "SI.order_date_time between '" . $fromdate . "' AND '" . $todate . "'";
                }
                if ($selectSalesrep != null) {
                    if (count($selectSalesrep) > 1) {
                        $quryModify .= " E.employee_id IN ('" . implode("','", $selectSalesrep) . "') ";
                    } else {
                        $quryModify .= " E.employee_id ='" . $selectSalesrep[0] . "' ";
                    }
                }
                if ($selecteCustomer != null) {

                    if (count($selecteCustomer) > 1) {
                        $quryModify .= " SI.customer_id IN ('" . implode("', '", $selecteCustomer) . "')";
                    } else {
                        $quryModify .= " SI.customer_id ='" . $selecteCustomer[0] . "'";
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
                        $quryModify .= " SI.branch_id IN ('" . implode("', '", $selecteBranch) . "')";
                    } else {
                        $quryModify .= " SI.branch_id ='" . $selecteBranch[0] . "'";
                    }
                }

                if ($selectItem != null) {
                    if (count($selectItem) > 1) {
                        $quryModify .= " I.item_id IN ('" . implode("', '", $selectItem) . "') ";
                    } else {
                        $quryModify .= " I.item_id ='" . $selectItem[0] . "' ";
                    }
                }

                if ($quryModify !== "") {

                    $query = $query . " WHERE " . $quryModify;
                }
                if ($quryModify == "") {

                    $query = $query;
                }



                //$query = preg_replace('/\W\w+\s*(\W*)$/', '$1', $query);
                //dd($query);
                $result = DB::select($query);

                $reportViwer = new ReportViewer();
                $reportViwer->addParameter("sales_summary_tabaledata", $result);
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
                        //$filterLabel .= "For";
                    }
                    if ($selecteBranch !== null) {
                        // Add a separator if needed
                        if ($filterLabel !== '') {
                            $filterLabel .= ' ';
                        }
                        //$filterLabel .= " Branch: $branchname and";
                    }
                    if ($selectesalesrep !== null) {
                        // Add a separator if needed
                        if ($filterLabel !== '') {
                            $filterLabel .= ' ';
                        }
                        //$filterLabel .= " Sales Rep: $selectesalesrep1 and";
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

                    if ($selecteCustomer !== null) {
                        // Add a separator if needed
                        if ($filterLabel !== '') {
                            $filterLabel .= ' ';
                        }
                        //$filterLabel .= " Customer: $customer and";
                    }

                    if ($selectecustomergroup !== null) {
                        // Add a separator if needed
                        if ($filterLabel !== '') {
                            $filterLabel .= ' ';
                        }
                        //$filterLabel .= " Customer Group: $cusgroup and";
                    }

                    if ($selecteCustomerGrade !== null) {
                        // Add a separator if needed
                        if ($filterLabel !== '') {
                            $filterLabel .= ' ';
                        }
                        //$filterLabel .= " Customer Grade: $cugrade  and";
                    }

                    if ($selecteRoute !== null) {
                        // Add a separator if needed
                        if ($filterLabel !== '') {
                            $filterLabel .= ' ';
                        }
                        //$filterLabel .= " Route: $route and";
                    }

                    if (!empty($filterLabel)) {
                        $filterLabel = rtrim($filterLabel, 'and ');

                        $reportViwer->addParameter("filter", $filterLabel);
                    }

                    //
                } else {

                    if (
                        $selecteBranch == null && $selecteCustomer == null && $selectecustomergroup == null
                        && $selecteCustomerGrade == null && $selecteRoute == null && $selectSalesrep == null && $selectItem == null
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

                    $filterLabel = "";
                    if ($fromdate &&  $todate) {
                        $filterLabel =  "From : " . $fromdate  . "To : " . $todate;
                    }
                    $reportViwer->addParameter("filter", $filterLabel);
                }
            }
            $total = 0;
            foreach ($result as $row) {
                $total += $row->amount;
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


            return $reportViwer->viewReport('salesSummery.json');
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



    //hiddn filter

    public function hidefilter(Request $request, $id)
    {
        try {

            $jsonData = json_decode($request->getContent(), true);

            $branch = $jsonData['branch'];
            $customer = $jsonData['customer'];
            $customergroup = $jsonData['customergroup'];
            $customerGrade = $jsonData['customerGrade'];
            $route = $jsonData['route'];

            $frodate = $jsonData['frodate'];
            $todate = $jsonData['todate'];
            $salesrep = $jsonData['salesrep'];
            $item = $jsonData['item'];

            if ($id == "salesreturnReport") {
                return response()->json([
                    //'branch' => $branch,
                    //'customer' => $customer,
                    //'customergroup' => $customergroup,
                    // 'customerGrade' => $customerGrade,
                    // 'route' => $route,
                    // 'frodate' => $frodate,
                    //  'todate' => $todate,
                    //  'salesrep'=>$salesrep,
                    //'item' => $item,


                ]);
            } elseif ($id == "salesReport") {

                return response()->json([
                    // 'branch' => $branch,
                    // 'customer' => $customer,
                    //'customergroup' => $customergroup,
                    // 'customerGrade' => $customerGrade,
                    // 'route' => $route,
                    // 'frodate' => $frodate,
                    //'todate' => $todate,
                    //'salesrep'=>$salesrep,
                    //'item'=>$item,


                ]);
            } elseif ($id == "salesdetailsReport") {

                return response()->json([
                    // 'branch' => $branch,
                    // 'customer' => $customer,
                    //'customergroup' => $customergroup,
                    // 'customerGrade' => $customerGrade,
                    // 'route' => $route,
                    // 'frodate' => $frodate,
                    //'todate' => $todate,
                    //'salesrep'=>$salesrep,



                ]);
            } elseif ($id == "salesRepwiseMonthlySummary") {

                return response()->json([
                    // 'branch' => $branch,
                    // 'customer' => $customer,
                    //'customergroup' => $customergroup,
                    // 'customerGrade' => $customerGrade,
                    // 'route' => $route,
                    // 'frodate' => $frodate,
                    //'todate' => $todate,
                    //'salesrep'=>$salesrep,
                    'item' => $item,



                ]);
            } elseif ($id == "itemCustomerReport") {

                return response()->json([
                    // 'branch' => $branch,
                    // 'customer' => $customer,
                    'customergroup' => $customergroup,
                    'customerGrade' => $customerGrade,
                    // 'route' => $route,
                    // 'frodate' => $frodate,
                    //'todate' => $todate,
                    'salesrep' => $salesrep,


                ]);
            } elseif ($id == "freeSummaryReport") {

                return response()->json([
                    // 'branch' => $branch,
                    // 'customer' => $customer,
                    'customergroup' => $customergroup,
                    'customerGrade' => $customerGrade,
                     'route' => $route,
                    // 'frodate' => $frodate,
                    //'todate' => $todate,
                    'salesrep' => $salesrep,
                    //'item'=>$salesrep,


                ]);
            }elseif ($id == "salessummeryreport") {

                return response()->json([
                    // 'branch' => $branch,
                     'customer' => $customer,
                    'customergroup' => $customergroup,
                    'customerGrade' => $customerGrade,
                     'route' => $route,
                    // 'frodate' => $frodate,
                    //'todate' => $todate,
                    //'salesrep' => $salesrep,
                    'item'=>$salesrep,
                    'marketing_route'=>'marketing_route',
                    'supply_group'=>'supply_group'


                ]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function getSalesrepfor_report()
    {
        try {

            $data = DB::select("SELECT employees.employee_name,employees.employee_id FROM employees WHERE employees.desgination_id = 7;");

            return response()->json($data);
        } catch (Exception $ex) {
            return $ex;
        }
    }
}
