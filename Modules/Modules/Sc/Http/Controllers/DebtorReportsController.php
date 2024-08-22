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
use Modules\Sc\Entities\DebtorsLedger;
use Modules\Sc\Entities\employee;
use Modules\Sc\Entities\item;
use Modules\Sc\Entities\Route;
use Modules\Sc\Entities\supply_group;
use RepoEldo\ELD\ReportViewer;

class DebtorReportsController extends Controller
{
    public function getCustomer()
    {
        try {

            $data = Customer::all();
            return response()->json($data);
        } catch (Exception $ex) {
            return $ex;
        }
    }
    public function getCustomergroup()
    {
        try {

            $data = Customer_group::all();
            return response()->json($data);
        } catch (Exception $ex) {
            return $ex;
        }
    }
    public function getcustomergrade()
    {
        try {

            $data = Customer_grade::all();
            return response()->json($data);
        } catch (Exception $ex) {
            return $ex;
        }
    }
    public function getRoute()
    {
        try {

            $data = route::all();
            return response()->json($data);
        } catch (Exception $ex) {
            return $ex;
        }
    }
    public function getSalesrep()
    {
        try {

            $data = employee::all();
            return response()->json($data);
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



    public function debtor_reports($search)
    {
        // return $search;
        //dd($search);
        try {


            $searchOption = json_decode($search);


            // dd($searchOption );
            $select = $searchOption[0]->selected;
            //dd($select == null);
            $selected1 = $searchOption[1]->selected1;
            $selected2 = $searchOption[2]->selected2;
            $selected3 = $searchOption[3]->selected3;
            $selected4 = $searchOption[4]->selected4;
            $selected5 = $searchOption[5]->selected5;

            $selecteCustomer = $searchOption[6]->selecteCustomer;
            $selectecustomergroup = $searchOption[7]->selectecustomergroup;
            $selecteCustomerGrade = $searchOption[8]->selecteCustomerGrade;
            $selecteRoute = $searchOption[9]->selecteRoute;

            $selectSalesrep = $searchOption[10]->selectSalesrep;



            $selecteBranch = $searchOption[11]->selecteBranch;
            $fromdate = $searchOption[12]->fromdate;
            $todate = $searchOption[13]->todate;
            $fromAge = $searchOption[14]->fromAge;
            $toAge = $searchOption[15]->toAge;
            $cmbgreaterthan = $searchOption[16]->cmbgreaterthan;

            $nonNullCount = 0;

            if ($searchOption !== null) {

                if ($searchOption[0]->selected !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[1]->selected1 !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[2]->selected2 !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[3]->selected3 !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[4]->selected4 !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[5]->selected5 !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[6]->selecteCustomer !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[7]->selectecustomergroup !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[8]->selecteCustomerGrade !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[9]->selecteRoute !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[10]->selectSalesrep !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[11]->selecteBranch !== null) {
                    $nonNullCount++;
                }


                if ($searchOption[14]->fromAge !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[15]->toAge !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[16]->cmbgreaterthan !== null) {
                    $nonNullCount++;
                }
            }
            if ($nonNullCount > 1) {
                $query = "SELECT  
                C.customer_code ,
                C.customer_name AS customer_name ,  
                T.townName AS town,
                RT.route_name,
                C.credit_amount_hold_limit AS credit_limit ,
                C.credit_period_hold_limit AS credit_period ,
                
                SUM(D.amount-D.paidamount) AS total_outstanding  ,
                SUM(IF((CURDATE()-D.trans_date)<=30,D.amount-D.paidamount,0)) AS Age1 ,
                SUM(IF((CURDATE()-D.trans_date)>30 AND (CURDATE()-D.trans_date)<=60 ,D.amount-D.paidamount,0)) AS Age2 ,
                SUM(IF((CURDATE()-D.trans_date)>60 AND (CURDATE()-D.trans_date)<=90,D.amount-D.paidamount,0)) AS Age3 ,
                SUM(IF((CURDATE()-D.trans_date)>90,D.amount-D.paidamount,0)) AS Age4 
                
                
                From debtors_ledgers D 
                INNER JOIN customers C ON D.customer_id=C.customer_id 
                INNER JOIN town_non_administratives T ON T.town_id=C.town 
                INNER JOIN branches ON D.branch_id = branches.branch_id
                INNER JOIN routes RT ON C.route_id = RT.route_id";


                $quryModify = "";
                if ($selecteBranch != null) {

                    if (count($selecteBranch) > 1) {
                        $quryModify .= " D.branch_id IN ('" . implode("', '", $selecteBranch) . "') AND";
                    } else {
                        $quryModify .= " D.branch_id ='" . $selecteBranch[0] . "'AND";
                    }
                    //$quryModify .= " D.branch_id ='" . $selecteBranch . "'AND";
                    //$quryModify .= " D.branch_id IN ('" . implode("', '", $selecteBranch) . "') AND";
                }
                if ($selected5 != null) {

                    if ($selected5 == 1) {
                        $quryModify .= " AND";
                    } else {
                        $quryModify .= " OR";
                    }
                }



                if ($selecteCustomer != null) {

                    if (count($selecteCustomer) > 1) {
                        $quryModify .= " D.customer_id IN ('" . implode("', '", $selecteCustomer) . "') AND";
                    } else {
                        $quryModify .= " D.customer_id ='" . $selecteCustomer[0] . "'AND";
                    }

                    //$quryModify .= " D.customer_id ='" . $selecteCustomer . "'AND";
                    //$quryModify .= " D.customer_id IN ('" . implode("', '", $selecteCustomer) . "') AND";
                }
                if ($selected1 != null) {

                    if ($selected1 == 1) {
                        $quryModify .= " AND";
                    } else {
                        $quryModify .= " OR";
                    }
                }


                if ($selectecustomergroup != null) {
                    if (count($selectecustomergroup) > 1) {
                        $quryModify .= " C.customer_group_id IN ('" . implode("', '", $selectecustomergroup) . "') AND";
                    } else {
                        $quryModify .= " C.customer_group_id ='" . $selectecustomergroup[0] . "'AND";
                    }


                    //$quryModify .= " C.customer_group_id ='" . $selectecustomergroup . "'AND";
                    //$quryModify .= " C.customer_group_id IN ('" . implode("', '", $selectecustomergroup) . "') AND";
                }
                if ($selected2 != null) {

                    if ($selected2 == 1) {
                        $quryModify .= " AND";
                    } else {
                        $quryModify .= " OR";
                    }
                }


                if ($selecteCustomerGrade != null) {
                    if (count($selecteCustomerGrade) > 1) {
                        $quryModify .= " C.customer_grade_id IN ('" . implode("', '", $selecteCustomerGrade) . "') AND";
                    } else {
                        $quryModify .= " C.customer_grade_id ='" . $selecteCustomerGrade[0] . "'AND";
                    }


                    //$quryModify .= " C.customer_grade_id ='" . $selecteCustomerGrade . "' AND";
                    //$quryModify .= " C.customer_grade_id IN ('" . implode("', '", $selecteCustomerGrade) . "') AND";
                }
                if ($selected3 != null) {

                    if ($selected3 == 1) {
                        $quryModify .= " AND";
                    } else {
                        $quryModify .= " OR";
                    }
                }


                if ($selecteRoute != null) {
                    if (count($selecteRoute) > 1) {
                        $quryModify .= " C.route_id IN ('" . implode("', '", $selecteRoute) . "') AND";
                    } else {
                        $quryModify .= " C.route_id ='" . $selecteRoute[0] . "'AND";
                    }


                    // $quryModify .= " C.route_id ='" . $selecteRoute . "'AND";
                    ///$quryModify .= " C.route_id IN ('" . implode("', '", $selecteRoute) . "') AND";
                }
                if ($selected4 != null) {

                    if ($selected5 == 1) {
                        $quryModify .= " AND";
                    } else {
                        $quryModify .= " OR";
                    }
                }

                if ($selectSalesrep != null) {
                    //$quryModify .= " items.category_level_3_id ='" . $selectSalesrep . "'AND";
                    $quryModify .= " D.employee_id IN ('" . implode("', '", $selectSalesrep) . "') AND";
                }
                if ($selected5 != null) {

                    if ($selected5 == 1) {
                        $quryModify .= " AND";
                    } else {
                        $quryModify .= " OR";
                    }
                }

                if ($cmbgreaterthan != null) {
                    $quryModify .= "  (CURDATE()-D.trans_date) > " . $cmbgreaterthan . " AND ";
                }
                if ($fromAge != null && $toAge != null) {

                    $quryModify .= "(CURDATE()-D.trans_date) BETWEEN " . $fromAge . " AND " . $toAge . " AND ";
                    //$quryModify .= "D.trans_date BETWEEN '" . min($fromdate) . "' AND '" . max($todate) . "'";

                }

                if ($quryModify != "") {

                    $quryModify = rtrim($quryModify, 'AND OR ');
                    $query .= ' WHERE ' . $quryModify . ' GROUP BY D.customer_id';
                }
                //$query = $query . ' GROUP BY D.customer_id';


                //$query = preg_replace('/\W\w+\s*(\W*)$/', '$1', $query);
                //dd($query);
                $result = DB::select($query);

                $reportViwer = new ReportViewer();
                $reportViwer->addParameter("debtor_reports_tabaledata", $result);
            } else {





                $query = "SELECT  
                C.customer_code ,
                C.customer_name AS customer_name ,  
                T.townName AS town,
                RT.route_name,
                IFNULL(C.credit_amount_hold_limit,0.00) AS credit_limit ,
                IFNULL(C.credit_period_hold_limit,0) AS credit_period ,
                
                IFNULL(SUM(D.amount-D.paidamount),0) AS total_outstanding  ,
                SUM(IF((CURDATE()-D.trans_date)<=30,D.amount-D.paidamount,0)) AS Age1 ,
                SUM(IF((CURDATE()-D.trans_date)>30 AND (CURDATE()-D.trans_date)<=60 ,D.amount-D.paidamount,0)) AS Age2 ,
                SUM(IF((CURDATE()-D.trans_date)>60 AND (CURDATE()-D.trans_date)<=90,D.amount-D.paidamount,0)) AS Age3 ,
                IFNULL(SUM(IF((CURDATE()-D.trans_date)>90,D.amount-D.paidamount,0)),0) AS Age4 
                
                
                From debtors_ledgers D 
                INNER JOIN customers C ON D.customer_id=C.customer_id 
                INNER JOIN branches ON D.branch_id = branches.branch_id
                INNER JOIN town_non_administratives T ON T.town_id=C.town
                INNER JOIN routes RT ON C.route_id = RT.route_id";


                $quryModify = "";
                if ($selecteBranch != null) {
                    if (count($selecteBranch) > 1) {
                        $quryModify .= " D.branch_id IN ('" . implode("', '", $selecteBranch) . "')";
                    } else {
                        $quryModify .= " D.branch_id ='" . $selecteBranch[0] . "'";
                    }

                    //$quryModify .= " D.branch_id ='" . $selecteBranch . "'";
                    //$quryModify .= " D.branch_id IN ('" . implode("', '", $selecteBranch) . "')";
                }
                if ($selected5 != null) {

                    if ($selected5 == 1) {
                        $quryModify .= " AND";
                    } else {
                        $quryModify .= " OR";
                    }
                }





                if ($selecteCustomer != null) {

                    if (count($selecteCustomer) > 1) {
                        $quryModify .= " D.customer_id IN ('" . implode("', '", $selecteCustomer) . "')";
                    } else {
                        $quryModify .= " D.customer_id ='" . $selecteCustomer[0] . "'";
                    }

                    //$quryModify .= " D.customer_id ='" . $selecteCustomer . "'";
                    //$quryModify .= " D.customer_id IN ('" . implode("', '", $selecteCustomer) . "')";
                }
                if ($selected1 != null) {

                    if ($selected1 == 1) {
                        $quryModify .= " AND";
                    } else {
                        $quryModify .= " OR";
                    }
                }


                if ($selectecustomergroup != null) {
                    if (count($selectecustomergroup) > 1) {
                        $quryModify .= " C.customer_group_id IN ('" . implode("', '", $selectecustomergroup) . "')";
                    } else {
                        $quryModify .= " C.customer_group_id ='" . $selectecustomergroup[0] . "'";
                    }


                    //$quryModify .= " C.customer_group_id ='" . $selectecustomergroup . "'";
                    //$quryModify .= " C.customer_group_id IN ('" . implode("', '", $selectecustomergroup) . "')";
                }
                if ($selected2 != null) {

                    if ($selected2 == 1) {
                        $quryModify .= " AND";
                    } else {
                        $quryModify .= " OR";
                    }
                }


                if ($selecteCustomerGrade != null) {
                    if (count($selecteCustomerGrade) > 1) {
                        $quryModify .= " C.customer_grade_id IN ('" . implode("', '", $selecteCustomerGrade) . "')";
                    } else {
                        $quryModify .= " C.customer_grade_id ='" . $selecteCustomerGrade[0] . "'";
                    }

                    //$quryModify .= " C.customer_grade_id ='" . $selecteCustomerGrade . "'";
                    //$quryModify .= " C.customer_grade_id IN ('" . implode("', '", $selecteCustomerGrade) . "')";
                }
                if ($selected3 != null) {

                    if ($selected3 == 1) {
                        $quryModify .= " AND";
                    } else {
                        $quryModify .= " OR";
                    }
                }


                if ($selecteRoute != null) {
                    if (count($selecteRoute) > 1) {
                        $quryModify .= " C.route_id IN ('" . implode("', '", $selecteRoute) . "')";
                    } else {
                        $quryModify .= " C.route_id ='" . $selecteRoute[0] . "'";
                    }

                    //$quryModify .= " C.route_id  ='" . $selecteRoute . "'";
                    //$quryModify .= " C.route_id IN ('" . implode("', '", $selecteRoute) . "')";
                }
                if ($selected4 != null) {

                    if ($selected5 == 1) {
                        $quryModify .= " AND";
                    } else {
                        $quryModify .= " OR";
                    }
                }

                if ($selectSalesrep != null) {
                    $quryModify .= " D.employee_id IN ('" . implode("', '", $selectSalesrep) . "')";
                }
                if ($selected5 != null) {

                    if ($selected5 == 1) {
                        $quryModify .= " AND";
                    } else {
                        $quryModify .= " OR";
                    }
                }

                if ($cmbgreaterthan != null) {
                    $quryModify .= "(CURDATE()-D.trans_date) > " . $cmbgreaterthan . "";
                }
                if ($fromAge != null && $toAge != null) {

                    $quryModify .= "(CURDATE()-D.trans_date) '" . $fromAge . "' AND '" . $toAge . "'";
                    //$quryModify .= "D.trans_date BETWEEN '" . min($fromdate) . "' AND '" . max($todate) . "'";

                }


                if ($quryModify != "") {
                    $query = $query . " where " . $quryModify . ' GROUP BY D.customer_id';
                }
                if ($quryModify == "") {
                    $query = $query . ' GROUP BY D.customer_id';
                }
                //$query = $query . ' GROUP BY items.Item_code';


                //$query = preg_replace('/\W\w+\s*(\W*)$/', '$1', $query);
                //dd($query);
                $result = DB::select($query);

                $reportViwer = new ReportViewer();
                $reportViwer->addParameter("debtor_reports_tabaledata", $result);
            }
            if ($searchOption !== null) {


                $selecteCustomer = $searchOption[6]->selecteCustomer;
                $selectecustomergroup = $searchOption[7]->selectecustomergroup;
                $selecteCustomerGrade = $searchOption[8]->selecteCustomerGrade;
                $selecteRoute = $searchOption[9]->selecteRoute;
                $selectSalesrep = $searchOption[10]->selectSalesrep;

                //$fromdate = $searchOption[11]->fromdate;
                //$todate = $searchOption[12]->todate;
                $selecteBranch = $searchOption[11]->selecteBranch;
                $fromAge = $searchOption[14]->fromAge;
                $toAge = $searchOption[15]->toAge;
                $cmbgreaterthan = $searchOption[16]->cmbgreaterthan;

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


                    /* if ($fromdate !== null) {
                        $filterLabel .= " From: $fromdate ";
                    }

                    if ($todate !== null) {
                        // Add a separator if needed
                        if ($filterLabel !== '') {
                            $filterLabel .= ' ';
                        }
                        $filterLabel .= " To: $todate and";
                    }*/

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
                    if ($cmbgreaterthan !== null) {
                        $filterLabel .= " Age Greater Than : $cmbgreaterthan and";
                    }
                    if ($fromAge !== null) {
                        $filterLabel .= " From(Age): $fromAge";
                    }

                    if ($toAge !== null) {

                        $filterLabel .= " To(Age): $toAge and";
                    }

                    /* if ($selectSalesrep1 !== null) {
                        // Add a separator if needed
                        if ($filterLabel !== '') {
                            $filterLabel .= ' ';
                        }
                        $filterLabel .= " sales Rep: $selectSalesrep and";
                    }*/



                    // Check if the filter label is not empty and then print it
                    if (!empty($filterLabel)) {
                        //$filterLabel = rtrim($filterLabel, 'and ');

                        $reportViwer->addParameter("filter","");
                    }
                    //
                } else {

                    if (
                        $selecteBranch == null && $selecteCustomer == null && $selectecustomergroup == null
                        && $selecteCustomerGrade == null && $selecteRoute == null && $selectSalesrep == null
                    ) {
                        $filterLabel = "";
                    } elseif ($selecteBranch !== null) {
                        //$filterLabel = "For: Branch: $branchname";
                    }/* elseif ($fromdate !== null && $todate !== null) {
                        $filterLabel = "For From: $fromdate  To: $todate";
                    }*/ elseif ($fromAge !== null && $toAge !== null) {
                        //$filterLabel = "For From(Age): $fromAge  To(Age): $toAge";
                    } elseif ($cmbgreaterthan !== null) {
                        //$filterLabel = "For :  Age Greater Than : $cmbgreaterthan ";
                    } elseif ($selecteCustomer !== null) {
                        //$filterLabel = "For: Customer: $customer";
                    } elseif ($selectecustomergroup !== null) {
                        //$filterLabel = "For: customer Group: $cusgroup";
                    } elseif ($selecteCustomerGrade !== null) {
                        //$filterLabel = "For: customer Grade: $cugrade";
                    } elseif ($selecteRoute !== null) {
                        //$filterLabel = "For: Route: $route";
                    } elseif ($selectSalesrep !== null) {
                        //$filterLabel = "For: Sales Rep : $selectSalesrep";
                    }




                    //$filterLabel = "From: $fromdate  To: $todate  and product: $itemname  and categorylevel1: $cusgroup";


                    $reportViwer->addParameter("filter", "");
                }
            }
            $reportViwer->addParameter('companylogo', CompanyDetailsController::companyimage());
            $reportViwer->addParameter('companyName', CompanyDetailsController::CompanyName());
            $reportViwer->addParameter('companyAddress', CompanyDetailsController::CompanyAddress());
            $reportViwer->addParameter('companyNumber', CompanyDetailsController::CompanyNumber());

           /* $length =  (strlen($filterLabel) / 90);
            $i = floor($length);
            $i2 = 0;
            if (($length - $i) > 0) {
                $i2++;
            }*/
            $label_height = 0;//(($i + $i2) * 20);

            //dd($length . " " . (strlen($filterLabel)));
            $reportViwer->addParameter('hight', $label_height);


            return $reportViwer->viewReport('debtor_reports.json');
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
    /* public function getrep($selectSalesrep)
    {
        $supplygroup = employee::find($selectSalesrep);
        if ($supplygroup) {
            return $supplygroup;
        }
        return null;
    }*/





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
            $graetertahan = $jsonData['graetertahan'];
            $frodate = $jsonData['frodate'];
            $todate = $jsonData['todate'];
           

            $froage = $jsonData['froage'];
            $toage = $jsonData['toage'];
            $salesrep = $jsonData['salesrep'];
            if ($id == "Customer_Ledger") {
                return response()->json([
                    'branch' => $branch,
                    //'customer' => $customer,
                    'customergroup' => $customergroup,
                    'customerGrade' => $customerGrade,
                    'route' => $route,
                    'graetertahan' => $graetertahan,
                    // 'frodate' => $frodate,
                    // 'todate' => $todate,
                    'froage' => $froage,
                    'toage' => $toage,
                    //'salesrep' => $salesrep,

                ]);
            } elseif ($id == "debtorleger") {

                return response()->json([
                    //'branch' => $branch,
                    //'customer' => $customer,
                    //'customergroup' => $customergroup,
                    //'customerGrade' => $customerGrade,
                    //'route' => $route,
                    //'graetertahan' => $graetertahan,
                    'frodate' => $frodate,
                    'todate' => $todate,
                    //
                    //'froage' => $froage,
                    //'toage' => $toage,
                    //'salesrep'=> $salesrep,
                ]);
            } elseif ($id == "customerOutstanding") {

                return response()->json([
                    // 'branch' => $branch,
                    //'customer' => $customer,
                    //'customergroup' => $customergroup,
                    //'customerGrade' => $customerGrade,
                    // 'route' => $route,
                    //'graetertahan' => $graetertahan,
                    'frodate' => $frodate,
                    'todate' => $todate,

                    //'froage' => $froage,
                    //'toage' => $toage,
                    //'salesrep' => $salesrep,
                ]);
            } elseif($id == "outstandingInvoiceWise"){
                return response()->json([
                    // 'branch' => $branch,
                    //'customer' => $customer,
                    'customergroup' => $customergroup,
                    'customerGrade' => $customerGrade,
                    // 'route' => $route,
                    //'graetertahan' => $graetertahan,
                    //'frodate' => $frodate,
                    //'todate' => $todate,

                    //'froage' => $froage,
                    //'toage' => $toage,
                    //'salesrep' => $salesrep,
                ]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }
}
