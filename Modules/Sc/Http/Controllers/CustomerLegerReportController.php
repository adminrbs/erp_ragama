<?php

namespace Modules\Sc\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Sc\Entities\branch;
use Modules\Sc\Entities\Customer;
use Modules\Sc\Entities\Customer_grade;
use Modules\Sc\Entities\Customer_group;
use Modules\Sc\Entities\employee;
use Modules\Sc\Entities\Route;
use RepoEldo\ELD\ReportViewer;

class CustomerLegerReportController extends Controller
{
    public function Customer_Ledger_reports($search)
    {
        // return $search;
        //dd($search);
        try {

            $searchOption = json_decode($search);
            //dd($searchOption );
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
                if ($searchOption[12]->fromdate !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[13]->todate !== null) {
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

                DB::select("SET @running_total := 0;");
                DB::select("SET @prev_customer_id := NULL;");


                $query = " SELECT
                    D.trans_date,
                    D.external_number,
                    D.description,
                    D.customer_id,
                    IF(D.amount > 0, D.amount, 0) AS Debit,
                    IF(D.amount < 0, -D.amount, 0) AS Credit,
                    CASE
                        WHEN D.customer_id = @prev_customer_id THEN
                            ABS(@running_total := @running_total + IF(D.amount > 0, D.amount, 0) - IF(D.amount < 0, -D.amount, 0))
                        ELSE
                            ABS(@running_total := IF(D.amount > 0, D.amount, 0) - IF(D.amount < 0, -D.amount, 0))
                    END AS RunningTotal,
                    (@prev_customer_id := D.customer_id) AS prev_customer_id
                FROM (
                    SELECT
                        D.*,
                        c.customer_id AS customer_id_alias 
                    FROM debtors_ledgers D
                    INNER JOIN customers c ON c.customer_id = D.customer_id
                    INNER JOIN branches B ON D.branch_id = B.branch_id";



                $quryModify = "";
                if ($fromdate != null && $todate != null) {
                    $quryModify .= "D.trans_date between '" . $fromdate . "' AND '" . $todate . "'AND";
                }
                if ($selecteBranch != null) {
                    if (count($selecteBranch) > 1) {
                        $quryModify .= " D.branch_id IN ('" . implode("', '", $selecteBranch) . "') AND";
                    } else {
                        $quryModify .= " D.branch_id ='" . $selecteBranch[0] . "'AND";
                    }


                    // $quryModify .= " D.branch_id ='" . $selecteBranch . "'AND";

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
                        $quryModify .= " c.customer_group_id IN ('" . implode("', '", $selectecustomergroup) . "') AND";
                    } else {
                        $quryModify .= " c.customer_group_id ='" . $selectecustomergroup[0] . "'AND";
                    }

                    //$quryModify .= " c.customer_group_id ='" . $selectecustomergroup . "'AND";
                    //$quryModify .= " c.customer_group_id IN ('" . implode("', '", $selectecustomergroup) . "') AND";
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
                        $quryModify .= " c.customer_grade_id IN ('" . implode("', '", $selecteCustomerGrade) . "') AND";
                    } else {
                        $quryModify .= " c.customer_grade_id ='" . $selecteCustomerGrade[0] . "'AND";
                    }


                    //$quryModify .= " c.customer_grade_id ='" . $c . "' AND";
                    //$quryModify .= " c.customer_grade_id IN ('" . implode("', '", $selecteCustomerGrade) . "') AND";
                }
                if ($selected3 != null) {

                    if ($selected3 == 1) {
                        $quryModify .= " AND";
                    } else {
                        $quryModify .= " OR";
                    }
                }


                /*if ($selecteRoute != null) {
                    if (count($selecteCustomerGrade) > 1) {
                        $quryModify .= " c.customer_grade_id IN ('" . implode("', '", $selecteCustomerGrade) . "') AND";
                    } else {
                        $quryModify .= " c.customer_grade_id ='" . $selecteCustomerGrade[0] . "'AND";
                    }


                    //$quryModify .= " c.customer_grade_id ='" . $selecteRoute . "'AND";
                    $quryModify .= " c.customer_grade_id IN ('" . implode("', '", $selecteRoute) . "') AND";
                }*/
                if ($selected4 != null) {

                    if ($selected5 == 1) {
                        $quryModify .= " AND";
                    } else {
                        $quryModify .= " OR";
                    }
                }

                /* if ($cmbgreaterthan != null) {
                    $quryModify .= "  (CURDATE()-D.trans_date) > " . $cmbgreaterthan . " AND ";
                }
                if ($fromAge != null && $toAge != null) {

                    $quryModify .= "(CURDATE()-D.trans_date) BETWEEN " . $fromAge . " AND " . $toAge . " AND ";
                    //$quryModify .= "D.trans_date BETWEEN '" . min($fromdate) . "' AND '" . max($todate) . "'";

                }*/

                /*if ($selectSalesrep != null) {
                    $quryModify .= " items.category_level_3_id ='" . $selectSalesrep . "'AND";
                }
                if ($selected5 != null) {

                    if ($selected5 == 1) {
                        $quryModify .= " AND";
                    } else {
                        $quryModify .= " OR";
                    }
                }*/


                if ($quryModify != "") {
                    $quryModify = rtrim($quryModify, 'AND OR ');
                    $query = $query . " where " . $quryModify . 'GROUP BY D.trans_date, D.external_number, D.description, c.customer_id
                    ) AS D
                    ORDER BY D.customer_id';
                }

                //$query = $query . ' GROUP BY D.customer_id';


                //$query = preg_replace('/\W\w+\s*(\W*)$/', '$1', $query);
                //dd($query);
                $result = DB::select($query);

                $resulcustomer = DB::select('select customer_id,customer_name,customer_code from customers');

                $customerablearray = [];
                $titel = [];
                $reportViwer = new ReportViewer();
                $debit_total = 0;
                $credit_total = 0;

                foreach ($resulcustomer as $customerid) {
                    $table = [];


                    foreach ($result as $customerdata) {
                        //dd($result);
                        if ($customerdata->customer_id == $customerid->customer_id) {

                            $debit_total += $customerdata->Debit;
                            $credit_total += $customerdata->Credit;
                            array_push($table, $customerdata);
                        }
                    }



                    if (count($table) > 0) {

                        array_push($customerablearray, $table);


                        array_push($titel, $customerid->customer_code . '   ' . ' - ' . $customerid->customer_name);


                        $reportViwer->addParameter('abc', $titel);
                    }
                }


                $reportViwer->addParameter("Customerledger_tabaledata", [$customerablearray]);
                $reportViwer->addParameter("total_difference", "<br>Total Balance : " . number_format(($debit_total - $credit_total),2));
            } else {



                DB::select("SET @running_total := 0;");
                DB::select("SET @prev_customer_id := NULL;");


                $query = " SELECT
                    D.trans_date,
                    D.external_number,
                    D.description,
                    D.customer_id,
                    IF(D.amount > 0, D.amount, 0) AS Debit,
                    IF(D.amount < 0, -D.amount, 0) AS Credit,
                    CASE
                        WHEN D.customer_id = @prev_customer_id THEN
                            ABS(@running_total := @running_total + IF(D.amount > 0, D.amount, 0) - IF(D.amount < 0, -D.amount, 0))
                        ELSE
                            ABS(@running_total := IF(D.amount > 0, D.amount, 0) - IF(D.amount < 0, -D.amount, 0))
                    END AS RunningTotal,
                    (@prev_customer_id := D.customer_id) AS prev_customer_id
                FROM (
                    SELECT
                        D.*,
                        c.customer_id AS customer_id_alias 
                    FROM debtors_ledgers D
                    INNER JOIN customers c ON c.customer_id = D.customer_id
                   
                    INNER JOIN branches B ON D.branch_id = B.branch_id";


                $quryModify = "";
                if ($fromdate != null && $todate != null) {
                    $quryModify .= "D.trans_date between '" . $fromdate . "' AND '" . $todate . "'";
                    //$quryModify .= "D.trans_date BETWEEN '" . min($fromdate) . "' AND '" . max($todate) . "'";

                }
                if ($selecteBranch != null) {

                    if (count($selecteBranch) > 1) {
                        $quryModify .= " D.branch_id IN ('" . implode("', '", $selecteBranch) . "')";
                    } else {
                        $quryModify .= " D.branch_id ='" . $selecteBranch[0] . "'";
                    }
                    // $quryModify .= " D.branch_id ='" . $selecteBranch . "'";
                    //$quryModify .= "D.branch_id IN ('" . implode("', '", $selecteBranch) . "')";
                }
                if ($selected5 != null) {

                    if ($selected5 == 1) {
                        $quryModify .= " AND";
                    } else {
                        $quryModify .= " OR";
                    }
                }





                if ($selecteCustomer != null) {
                    //dd(count($selecteCustomer));
                    //dd($selecteCustomer);
                    if (count($selecteCustomer) > 1) {
                        $quryModify .= " D.customer_id IN ('" . implode("', '", $selecteCustomer) . "')";
                    } else {
                        $quryModify .= " D.customer_id ='" . $selecteCustomer[0] . "'";
                    }
                    // $quryModify .= " D.customer_id ='" . $selecteCustomer . "'";

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
                        $quryModify .= " D.customer_group_id IN ('" . implode("', '", $selectecustomergroup) . "')";
                    } else {
                        $quryModify .= " D.customer_group_id ='" . $selectecustomergroup[0] . "'";
                    }


                    // $quryModify .= " c.customer_group_id ='" . $selectecustomergroup . "'";
                    //$quryModify .= " c.customer_group_id IN ('" . implode("', '", $selectecustomergroup) . "')";
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
                        $quryModify .= " D.customer_grade_id IN ('" . implode("', '", $selecteCustomerGrade) . "')";
                    } else {
                        $quryModify .= " D.customer_grade_id ='" . $selecteCustomerGrade[0] . "'";
                    }
                    // $quryModify .= " c.customer_grade_id ='" . $selecteCustomerGrade . "'";
                    //$quryModify .= " c.customer_grade_id IN ('" . implode("', '", $selecteCustomerGrade) . "')";
                }
                if ($selected3 != null) {

                    if ($selected3 == 1) {
                        $quryModify .= " AND";
                    } else {
                        $quryModify .= " OR";
                    }
                }


                /* if ($selecteRoute != null) {
                    if (count($selecteCustomerGrade) > 1) {
                        $quryModify .= " D.customer_grade_id IN ('" . implode("', '", $selecteCustomerGrade) . "')";
                    } else {
                        $quryModify .= " D.customer_grade_id ='" . $selecteCustomerGrade[0] . "'";
                    }


                    // $quryModify .= " c.customer_grade_id  ='" . $selecteRoute . "'";
                   // $quryModify .= "c.customer_grade_id IN ('" . implode("', '", $selecteRoute) . "')";
                }*/
                if ($selected4 != null) {

                    if ($selected5 == 1) {
                        $quryModify .= " AND";
                    } else {
                        $quryModify .= " OR";
                    }
                }

                /* if ($selectSalesrep != null) {
                    $quryModify .= " items.category_level_3_id ='" . $selectSalesrep . "'";
                }
                if ($selected5 != null) {

                    if ($selected5 == 1) {
                        $quryModify .= " AND";
                    } else {
                        $quryModify .= " OR";
                    }
                }*/

                if ($quryModify != "") {
                    $query = $query . " where " . $quryModify . ' GROUP BY D.trans_date, D.external_number, D.description, c.customer_id
                    ) AS D
                    ORDER BY D.customer_id';
                }
                if ($quryModify == "") {

                    //$query = $query . " where " . $quryModify . ' GROUP BY items.Item_code';
                    $query = $query . '  GROUP BY D.trans_date, D.external_number, D.description, c.customer_id
                   ) AS D
                   ORDER BY D.customer_id';
                }

                //$query = $query . ' GROUP BY items.Item_code';


                //$query = preg_replace('/\W\w+\s*(\W*)$/', '$1', $query);
                // dd($query);
                $result = DB::select($query);

                $resulcustomer = DB::select('select customer_id,customer_name,customer_code from customers');

                $customerablearray = [];
                $titel = [];
                $reportViwer = new ReportViewer();
                $debit_total = 0;
                $credit_total = 0;
                foreach ($resulcustomer as $customerid) {
                    $table = [];


                    foreach ($result as $customerdata) {
                        //dd($result);
                        if ($customerdata->customer_id == $customerid->customer_id) {

                            $debit_total += $customerdata->Debit;
                            $credit_total += $customerdata->Credit;
                            array_push($table, $customerdata);
                        }
                    }



                    if (count($table) > 0) {

                        array_push($customerablearray, $table);


                        array_push($titel, $customerid->customer_code . '   ' . ' - ' . $customerid->customer_name);


                        $reportViwer->addParameter('abc', $titel);
                    }
                }

                //$reportViwer = new ReportViewer();
                $reportViwer->addParameter("Customerledger_tabaledata", [$customerablearray]);
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

                    /*  if ($selectSalesrep1 !== null) {
                        // Add a separator if needed
                        if ($filterLabel !== '') {
                            $filterLabel .= ' ';
                        }
                        $filterLabel .= " sales Rep: $selectSalesrep and";
                    }*/



                    // Check if the filter label is not empty and then print it
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
                        $filterLabel = "For: Branch: $branchname";
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
                        $filterLabel = "For: Sales Rep : $selectSalesrep";
                    }




                    //$filterLabel = "From: $fromdate  To: $todate  and product: $itemname  and categorylevel1: $cusgroup";

                    $reportViwer->addParameter("filter", $filterLabel);
                }
            }



            $reportViwer->addParameter('companylogo', CompanyDetailsController::companyimage());
            $reportViwer->addParameter('companyName', CompanyDetailsController::CompanyName());
            $reportViwer->addParameter('companyAddress', CompanyDetailsController::CompanyAddress());
            $reportViwer->addParameter('companyNumber', CompanyDetailsController::CompanyNumber());
            $reportViwer->addParameter("total_difference", "<br>Total Balance : " . number_format(($debit_total - $credit_total),2));

            $length =  (strlen($filterLabel) / 90);
            $i = floor($length);
            $i2 = 0;
            if (($length - $i) > 0) {
                $i2++;
            }
            $label_height = (($i + $i2) * 20);

            //dd($length . " " . (strlen($filterLabel)));
            $reportViwer->addParameter('hight', $label_height);

            return $reportViwer->viewReport('customer_ledger.json');
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
            $cusgroups = Customer_group::whereIn('customer_group_id ', $selectecustomergroup)
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
}
