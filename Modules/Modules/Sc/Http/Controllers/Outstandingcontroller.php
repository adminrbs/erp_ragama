<?php

namespace Modules\Sc\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use App\Http\Controllers\CompanyNameController;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;
use Modules\Md\Entities\category_level_1;
use Modules\Md\Entities\category_level_2;
use Modules\Md\Entities\category_level_3;
use Modules\Md\Entities\supply_group;
use Modules\Sc\Entities\branch;
use Modules\Sc\Entities\Customer;
use Modules\Sc\Entities\Customer_grade;
use Modules\Sc\Entities\Customer_group;
use Modules\Sc\Entities\employee;
use Modules\Sc\Entities\location;
use Modules\Sc\Entities\Route;
use Modules\Sd\Entities\item;
use RepoEldo\ELD\ReportViewer;


class Outstandingcontroller extends Controller
{
    /*public function printoutStandinReport()
    {
        try {

            $query = "SELECT  
            customers.customer_code,
            customers.customer_name,
            A.trans_date ,
            A.external_number ,
            A.amount , 
           
           (A.amount-(A.amount - P.paid_amount))AS balance_Amount,
          
           DATEDIFF(CURDATE(), A.trans_date) AS age_days
           
          
           
            FROM 
           ( SELECT  internal_number,reference_internal_number,reference_external_number ,trans_date , external_number,description  , document_number, reference_document_number , amount    FROM  debtors_ledger_setoffs
           WHERE amount>0 ) A    
           
           LEFT JOIN 
           
           ( 
           SELECT  reference_internal_number 
           , SUM(amount) AS paid_amount ,customer_id 
           FROM debtors_ledger_setoffs
           GROUP BY reference_internal_number
           ) P  ON  A.internal_number=P.reference_internal_number 
           LEFT JOIN customers ON P.customer_id = customers.customer_id WHERE (A.amount-(A.amount - P.paid_amount)) > 0";

            $result = DB::select($query);
            $reportViwer = new ReportViewer();

            $query2 = "SELECT
            debtors_ledger_setoffs.internal_number,
            debtors_ledger_setoffs.external_number,
            debtors_ledger_setoffs.trans_date,
            debtors_ledger_setoffs.branch_id,
            debtors_ledger_setoffs.customer_id,
            debtors_ledger_setoffs.customer_code
        FROM
        debtors_ledger_setoffs;";
            $query2Result = DB::select($query2);


            $reportViwer->addParameter("tabale_data", $result);
            $reportViwer->addParameter('companyName',CompanyDetailsController::CompanyName());
            $reportViwer->addParameter('companyAddress',CompanyDetailsController::CompanyAddress());
            $reportViwer->addParameter('companyNumber',CompanyDetailsController::CompanyNumber());

            if ($query2Result) {
                $stockBalance = $query2Result[0]->external_number;
                $date = $query2Result[0]->trans_date;
                $reportViwer->addParameter("referenceNumbers", $stockBalance);
                $reportViwer->addParameter("date", $date);

                
            }


            return $reportViwer->viewReport('outstandingReport.json');
        } catch (Exception $ex) {
            return $ex;
        }


    }
*/

    public function printoutStandinReport($search)
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
            $cmbgreaterthan = $searchOption[12]->cmbgreaterthan;
            $fromdate = $searchOption[13]->fromdate;
            $todate = $searchOption[14]->todate;

            $fromAge = $searchOption[15]->fromAge;
            $toAge = $searchOption[16]->toAge;
           // $selectSupplyGroup = $searchOption[17]->supplyGroup;
            //dd($selectSupplyGroup);
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
                if ($searchOption[12]->cmbgreaterthan !== null) {
                    $nonNullCount++;
                }

                if ($searchOption[15]->fromAge !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[16]->toAge !== null) {
                    $nonNullCount++;
                }
                /* if ($searchOption[17]->toAge !== null) {
                    $nonNullCount++;
                } */
            }

            if ($nonNullCount > 1) {

                $query = "SELECT DISTINCT
                IF(D.document_number=210, D.external_number ,D.external_number)   AS invoice_number,
                                IF(D.document_number=210, D.external_number ,D.ref_no)   AS manual_number ,
                                LEFT(E.employee_name, 7) AS employee_name,
                                DATEDIFF(CURDATE(), D.trans_date) AS age_days ,
                                D.trans_date,
                                D.amount , 
                                D.paidamount , 
                                0 AS pd_cheque  ,
                               (D.amount - D.paidamount) AS balance_amount,
                               C.customer_id,
                               B.branch_id
                              
                               
                            FROM
                                debtors_ledgers D             
                            INNER JOIN 
                                customers C ON D.customer_id = C.customer_id
                 
                          
                            LEFT JOIN  branches B ON D.branch_id = B.branch_id

                            LEFT JOIN employees E ON D.employee_id = E.employee_id

                            WHERE (D.document_number = 210 OR D.document_number = 1600 OR D.document_number = 1900) AND
                                (D.amount - D.paidamount) > 0";



                $quryModify = "";
                if ($selecteBranch != null) {

                    if (count($selecteBranch) > 1) {
                        $quryModify .= " B.branch_id IN ('" . implode("', '", $selecteBranch) . "')  AND";
                    } else {
                        $quryModify .= " B.branch_id ='" . $selecteBranch[0] . "'AND";
                    }
                    // $quryModify .= "B.branch_id ='" . $selecteBranch . "'AND";
                    //$quryModify .= " B.branch_id IN ('" . implode("', '", $selecteBranch) . "') AND";
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
                        $quryModify .= " C.customer_id IN ('" . implode("', '", $selecteCustomer) . "') AND";
                    } else {
                        $quryModify .= " C.customer_id ='" . $selecteCustomer[0] . "'AND";
                    }

                    // $quryModify .= " C.customer_id ='" . $selecteCustomer . "'AND";
                    //$quryModify .= " C.customer_id IN ('" . implode("', '", $selecteCustomer) . "') AND";
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

                    //$quryModify .= " C.route_id IN ('" . implode("', '", $selecteRoute) . "')";
                    //$quryModify .= " C.route_id IN ('" . implode("', '", $selecteRoute) . "') AND";
                }
                if ($selected4 != null) {

                    if ($selected5 == 1) {
                        $quryModify .= " AND";
                    } else {
                        $quryModify .= " OR";
                    }
                }
                if ($selectSalesrep != null) {
                    if (count($selectSalesrep) > 1) {
                        $quryModify .= " D.employee_id IN ('" . implode("', '", $selectSalesrep) . "') AND";
                    } else {
                        $quryModify .= " D.employee_id ='" . $selectSalesrep[0] . "'AND";
                    }

                    //$quryModify .= " D.employee_id IN ('" . implode("', '", $selectSalesrep) . "')";
                    //$quryModify .= " D.employee_id IN ('" . implode("', '", $selectSalesrep) . "') AND";
                }



                if ($selected5 != null) {

                    if ($selected5 == 1) {
                        $quryModify .= " AND";
                    } else {
                        $quryModify .= " OR";
                    }
                }

                if ($cmbgreaterthan != null) {
                    $quryModify .= "  DATEDIFF(CURDATE(), D.trans_date) > " . $cmbgreaterthan . " AND ";
                }
                if ($fromAge != null && $toAge != null) {

                    $quryModify .= " DATEDIFF(CURDATE(), D.trans_date) BETWEEN " . $fromAge . " AND " . $toAge . " AND ";
                    //$quryModify .= "D.trans_date BETWEEN '" . min($fromdate) . "' AND '" . max($todate) . "'";

                }
/* 
                if ($selectSupplyGroup != null) {

                    $quryModify .= " DATEDIFF(CURDATE(), D.trans_date) BETWEEN " . $fromAge . " AND " . $toAge . " AND ";
                    //$quryModify .= "D.trans_date BETWEEN '" . min($fromdate) . "' AND '" . max($todate) . "'";

                } */


                if ($quryModify != "") {

                    $quryModify = rtrim($quryModify, 'AND OR ');
                    $query = $query . " AND " . $quryModify . ' ORDER BY age_days DESC';
                }
                //$query = $query . ' GROUP BY D.customer_id';


                //$query = preg_replace('/\W\w+\s*(\W*)$/', '$1', $query);
               // dd($query);
               //dd('if');
                $result = DB::select($query);

                $resulcustomer = DB::select('select customer_id,customer_name,customer_code,routes.route_name,town_non_administratives.townName from customers
                INNER JOIN routes ON customers.route_id = routes.route_id
                INNER JOIN town_non_administratives ON customers.town = town_non_administratives.town_id 
                LEFT JOIN  route_towns ON  route_towns.town_id=customers.town 
                ORDER BY routes.route_order , IFNULL(route_towns.route_town_id,0)');

                $customerablearray = [];
                $titel = [];
                $routes = [];
                $routes_total = [];
                $reportViwer = new ReportViewer();
                foreach ($resulcustomer as $customerid) {
                    $table = [];


                    foreach ($result as $customerdata) {
                        //dd($result);
                        if ($customerdata->customer_id == $customerid->customer_id) {


                            array_push($table, $customerdata);
                        }
                    }



                    $route_name = $customerid->route_name;
                    if (count($table) > 0) {
                        array_push($customerablearray, $table);

                        if (end($routes) != $route_name) {
                            array_push($routes_total, "Route Total :");
                            array_push($titel, "Route :<strong>" . $route_name . " </strong><br>Customer :<strong>" . $customerid->customer_code . '   ' . ' - ' . $customerid->customer_name . ' - ' . $customerid->townName . "</strong>");
                        } else {
                            array_push($routes_total, "");
                            array_push($titel, "Customer :<strong>" . $customerid->customer_code . '   ' . ' - ' . $customerid->customer_name . ' - ' . $customerid->townName . "</strong>");
                        }
                        array_push($routes, $route_name);
                        $reportViwer->addParameter('abc', $titel);
                        $reportViwer->addParameter('route_total', $routes_total);
                    }
                }
                $reportViwer->addParameter("tabale_data", [$customerablearray]);
            } else {
               // dd('ddd');
                $query = "SELECT DISTINCT
                IF(D.document_number=210, D.external_number ,D.external_number)   AS invoice_number,
                                IF(D.document_number=210, D.external_number ,D.ref_no)   AS manual_number ,
                                LEFT(E.employee_name, 7) AS employee_name,
                                DATEDIFF(CURDATE(), D.trans_date) AS age_days ,
                                D.trans_date,
                                D.amount , 
                                D.paidamount , 
                                0 AS pd_cheque  ,
                               (D.amount - D.paidamount) AS balance_amount,
                               C.customer_id,
                               B.branch_id
                              
                               
                            FROM
                                debtors_ledgers D             
                            INNER JOIN 
                                customers C ON D.customer_id = C.customer_id
                 
                          
                            LEFT JOIN  branches B ON D.branch_id = B.branch_id

                            LEFT JOIN employees E ON D.employee_id = E.employee_id

                            WHERE (D.document_number = 210 OR D.document_number = 1600 OR D.document_number = 1900) AND
                                (D.amount - D.paidamount) > 0";






                $quryModify = "";
                if ($selecteBranch != null) {
                    if (count($selecteBranch) > 1) {
                        $quryModify .= " B.branch_id IN ('" . implode("', '", $selecteBranch) . "')";
                    } else {
                        $quryModify .= " B.branch_id ='" . $selecteBranch[0] . "'";
                    }


                    //$quryModify .= " B.branch_id ='" . $selecteBranch . "'";
                    //$quryModify .= " B.branch_id IN ('" . implode("', '", $selecteBranch) . "')";
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
                        $quryModify .= " C.customer_id IN ('" . implode("', '", $selecteCustomer) . "')";
                    } else {
                        $quryModify .= " C.customer_id ='" . $selecteCustomer[0] . "'";
                    }

                    //$quryModify .= " C.customer_id ='" . $selecteCustomer . "'";
                    //$quryModify .= " C.customer_id IN ('" . implode("', '", $selecteCustomer) . "')";
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

                    // $quryModify .= " C.customer_grade_id ='" . $selecteCustomerGrade . "'";
                    //$quryModify .= " C.customer_grade_id IN ('" . implode("', '", $selecteCustomerGrade) . "')";
                }
                if ($selected3 != null) {

                    if ($selected3 == 1) {
                        $quryModify .= " AND";
                    } else {
                        $quryModify .= " OR";
                    }
                }


                if ($selectSalesrep != null) {
                    if (count($selectSalesrep) > 1) {
                        $quryModify .= " D.employee_id IN ('" . implode("', '",  $selectSalesrep) . "')";
                    } else {
                        $quryModify .= " D.employee_id ='" . $selectSalesrep[0] . "'";
                    }
                }



                if ($selecteRoute != null) {
                    if (count($selecteRoute) > 1) {
                        $quryModify .= " C.route_id IN ('" . implode("', '", $selecteRoute) . "')";
                    } else {
                        $quryModify .= " C.route_id ='" . $selecteRoute[0] . "'";
                    }

                    //$quryModify .= " C.route_id IN ('" . implode("', '", $selecteRoute) . "')";
                }
                if ($selected4 != null) {

                    if ($selected5 == 1) {
                        $quryModify .= " AND";
                    } else {
                        $quryModify .= " OR";
                    }
                }


                if ($cmbgreaterthan != null) {
                    $quryModify .= "DATEDIFF(CURDATE(), D.trans_date) > " . $cmbgreaterthan . "";
                }
                if ($fromAge != null && $toAge != null) {

                    $quryModify .= "D.trans_date between '" . $fromAge . "' AND '" . $toAge . "'";
                    //$quryModify .= "D.trans_date BETWEEN '" . min($fromdate) . "' AND '" . max($todate) . "'";

                }

                if ($quryModify != "") {
                    $query = $query . " AND " . $quryModify . ' ORDER BY age_days DESC';
                }

                if ($quryModify == "") {
                    $query = $query . ' ORDER BY age_days DESC';
                }



                //$query = preg_replace('/\W\w+\s*(\W*)$/', '$1', $query);
                //dd($query);
                $result = DB::select($query);

                /* $resulcustomer = DB::select('select customer_id,customer_name,customer_code,routes.route_name,town_non_administratives.townName from customers
                INNER JOIN routes ON customers.route_id = routes.route_id
                INNER JOIN town_non_administratives ON customers.town = town_non_administratives.town_id 
                LEFT JOIN  route_towns ON  route_towns.town_id=customers.town 
                ORDER BY routes.route_order , IFNULL(route_towns.route_town_id,0)'); */
                $resulcustomer = DB::select('SELECT customer_id,customer_name,customer_code,routes.route_name,town_non_administratives.townName FROM customers INNER JOIN routes ON customers.route_id = routes.route_id INNER JOIN town_non_administratives ON customers.town = town_non_administratives.town_id');
                $resulbranch = DB::select('select branch_id,branch_name from branches');
//dd($resulcustomer);
                $customerablearray = [];
                $branchablearray = [];
                $titel = [];
                $titel2 = [];
                $routes = [];
                $routes_total = [];
                $reportViwer = new ReportViewer();
                foreach ($resulcustomer as $customerid) {
                    $table = [];

                  /*   if($customerid->customer_id == 705){
                        dump($customerid->customer_id);
                    } */



                    foreach ($result as $customerdata) {
                       
                        if ($customerdata->customer_id == $customerid->customer_id) {
                            
                            array_push($table, $customerdata);
                        } else {
                        }
                    }




                    $route_name = $customerid->route_name;
                    if (count($table) > 0) {
                        array_push($customerablearray, $table);

                        if (end($routes) != $route_name) {
                            array_push($routes_total, "Route Total :");
                            array_push($titel, "Route :<strong>" . $route_name . " </strong><br>Customer :<strong>" . $customerid->customer_code . '   ' . ' - ' . $customerid->customer_name . ' - ' . $customerid->townName . "</strong>");
                        } else {
                            array_push($routes_total, "");
                            array_push($titel, "Customer :<strong>" . $customerid->customer_code . '   ' . ' - ' . $customerid->customer_name . ' - ' . $customerid->townName . "</strong>");
                        }
                        array_push($routes, $route_name);
                        $reportViwer->addParameter('abc', $titel);
                        $reportViwer->addParameter('route_total', $routes_total);
                    }
                }

                foreach ($resulbranch as $branchid) {
                    $table2 = [];


                    foreach ($result as $branchdata) {
                        //dd($result);
                        if ($branchdata->branch_id == $branchid->branch_id) {


                            array_push($table2, $branchdata);
                        }
                    }



                    if (count($table2) > 0) {

                        array_push($branchablearray, $table2);


                        array_push($titel2, $branchid->branch_name);


                        $reportViwer->addParameter('branch', $titel2);
                    }
                }
                $reportViwer->addParameter("tabale_data", [$customerablearray]);
            }
            if ($searchOption !== null) {


                $selecteCustomer = $searchOption[6]->selecteCustomer;
                $selectecustomergroup = $searchOption[7]->selectecustomergroup;
                $selecteCustomerGrade = $searchOption[8]->selecteCustomerGrade;
                $selecteRoute = $searchOption[9]->selecteRoute;
                $selectSalesrep = $searchOption[10]->selectSalesrep;


                $selecteBranch = $searchOption[11]->selecteBranch;

                $cmbgreaterthan = $searchOption[12]->cmbgreaterthan;
                $fromdate = $searchOption[13]->fromdate;
                $todate = $searchOption[14]->todate;

                $fromAge = $searchOption[15]->fromAge;
                $toAge = $searchOption[16]->toAge;

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
                $selectSalesrep1 = $this->getrep($selectSalesrep);
                $selectSalesrep = '';
                if ($selectSalesrep1 != null) {
                    $selectSalesrep = $selectSalesrep1->pluck('employee_name')->implode(', ');
                    // $selectSalesrep = $selectSalesrep1->employee_name;
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
                    if ($cmbgreaterthan !== null) {
                        $filterLabel .= " Age Greater Than : $cmbgreaterthan and";
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

                    if ($fromAge !== null) {
                        $filterLabel .= " From(Age): $fromAge";
                    }

                    if ($toAge !== null) {

                        $filterLabel .= " To(Age): $toAge and";
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

                    if ($selectSalesrep1 !== null) {
                        // Add a separator if needed
                        if ($filterLabel !== '') {
                            $filterLabel .= ' ';
                        }
                        $filterLabel .= " sales Rep: $selectSalesrep and";
                    }



                    // Check if the filter label is not empty and then print it
                    if (!empty($filterLabel)) {
                        $filterLabel = rtrim($filterLabel, 'and ');
                        // dd($filterLabel);

                        $reportViwer->addParameter("filter", $filterLabel);
                    }
                    //
                } else {

                    if (
                        $selecteBranch == null && $selecteCustomer == null && $selectecustomergroup == null && $cmbgreaterthan == null
                        && $selecteCustomerGrade == null && $selecteRoute == null && $selectSalesrep == null
                    ) {
                        $filterLabel = "";
                    } elseif ($selecteBranch !== null) {
                        $filterLabel = "For:  $branchname branch";
                    } //elseif ($fromdate !== null && $todate !== null) {
                    // $filterLabel = "For From: $fromdate  To: $todate";
                    //}
                    elseif ($fromAge !== null && $toAge !== null) {
                        $filterLabel = "For From(Age): $fromAge  To(Age): $toAge";
                    } elseif ($cmbgreaterthan !== null) {
                        $filterLabel = "For :  Age Greater Than : $cmbgreaterthan ";
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
            $reportViwer->addParameter('report_title', "Customer's Outstanding as at " . Carbon::now()->format('d-m-Y'));

            $length =  (strlen($filterLabel) / 90);
            $i = floor($length);
            $i2 = 0;
            if (($length - $i) > 0) {
                $i2++;
            }
            $label_height = (($i + $i2) * 20);

            //dd($length . " " . (strlen($filterLabel)));
            $reportViwer->addParameter('hight', $label_height);

            return $reportViwer->viewReport('outstandingReport.json');
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
    public function getrep($selectSalesrep)
    {

        if ($selectSalesrep != null) {
            $selectSalesrep1 = employee::whereIn('employee_id', $selectSalesrep)
                ->select('employee_id', 'employee_name')
                ->get();
            return $selectSalesrep1;
        }
        //        
    }
























    public function printoutsalseinvoiseAndRetirnReport()
    {
        try {

            /*$query = "SELECT
            si.order_date_time,
            sii.external_number,
            c.customer_name,
            sii.price * sii.quantity AS amount, 
            sii.sales_invoice_Id
        FROM
            sales_invoice_items AS sii
        LEFT JOIN
            sales_invoices AS si ON sii.sales_invoice_Id = si.sales_invoice_Id
        LEFT JOIN
            customers AS c ON si.customer_id = c.customer_id
        
        UNION
        
        SELECT
            si.order_date,
            sii.external_number,
            c.customer_name,
            sii.price * sii.quantity AS amount, 
            sii.sales_return_Id
        FROM
            sales_return_items AS sii
        LEFT JOIN
            sales_returns AS si ON sii.sales_return_Id = si.sales_return_Id
        LEFT JOIN
            customers AS c ON si.customer_id = c.customer_id";*/

            $query1 = "SELECT
            
            si.order_date_time,
            sii.external_number,
            c.customer_name,
            sii.price * sii.quantity AS amount
            
        FROM
            sales_invoice_items AS sii
        LEFT JOIN
            sales_invoices AS si ON sii.sales_invoice_Id = si.sales_invoice_Id
        LEFT JOIN
            customers AS c ON si.customer_id = c.customer_id";

            //$result = DB::select($query);

            $query2 = "SELECT
            
            si.order_date,
            sii.external_number,
            c.customer_name,
            sii.price * sii.quantity AS amount
           
        FROM
            sales_return_items AS sii
        LEFT JOIN
            sales_returns AS si ON sii.sales_return_Id = si.sales_return_Id
        LEFT JOIN
            customers AS c ON si.customer_id = c.customer_id";


            $result = DB::select($query1);
            $result2 = DB::select($query2);
            $reportViwer = new ReportViewer();

            $query2 = "SELECT
            debtors_ledgers.internal_number,
            debtors_ledgers.external_number,
            debtors_ledgers.trans_date,
            debtors_ledgers.branch_id,
            debtors_ledgers.customer_id,
            debtors_ledgers.customer_code
            FROM
            debtors_ledgers;";
            $query2Result = DB::select($query2);


            $reportViwer->addParameter("group_data", [[$result, $result2]]);


            $reportViwer->addParameter('companyName', CompanyDetailsController::CompanyName());
            $reportViwer->addParameter('companyAddress', CompanyDetailsController::CompanyAddress());
            $reportViwer->addParameter('companyNumber', CompanyDetailsController::CompanyNumber());

            if ($query2Result) {
                $stockBalance = $query2Result[0]->trans_date;
                $date = $query2Result[0]->trans_date;
                $reportViwer->addParameter("frome", $stockBalance);
                $reportViwer->addParameter("to", $date);
            }


            return $reportViwer->viewReport('outstandingReport.json');
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function getproduct()
    {
        try {

            $data = item::all();
            return response()->json($data);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //get items accroding to supply groups id
    public function getproduct_sup_id(Request $request)
    {
        try {
            $ids  = $request->input('sup_ids');
            $data = item::whereIn("supply_group_id",$ids)->get();
            return response()->json($data);
        } catch (Exception $ex) {
            return $ex;
        }
    }
    public function getItemCategory1()
    {
        try {

            $data = category_level_1::all();
            return response()->json($data);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function getItemCategory2()
    {
        try {

            $data = category_level_2::all();
            return response()->json($data);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function getItemCategory3()
    {
        try {

            $data = category_level_3::all();
            return response()->json($data);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function getsuplygroup()
    {
        try {

            $data = supply_group::all();
            return response()->json($data);
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function getlocation()
    {
        try {

            $data = location::all();
            return response()->json($data);
        } catch (Exception $ex) {
            return $ex;
        }
    }
}
