<?php

namespace Modules\Sc\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Md\Entities\branch;
use Modules\Md\Entities\Customer;
use Modules\Md\Entities\Customer_group;
use Modules\Sc\Entities\Customer_grade;
use Modules\Sc\Entities\route;
use RepoEldo\ELD\ReportViewer;

class CustomerOutstandingControllerInvoiceWise extends Controller
{
    public function debtor_reports_invoiceWise($search)
    {
        //dd($search);
        try{
            $searchOption = json_decode($search);


             //dd($searchOption );
           
           
           
            $selecteCustomer = $searchOption[0]->selecteCustomer;
            $selecteRoute = $searchOption[1]->selecteRoute;
            $selectSalesrep = $searchOption[2]->selectSalesrep;
            $selecteBranch = $searchOption[3]->selecteBranch;
            $fromdate = $searchOption[4]->fromdate;
            $todate = $searchOption[5]->todate;
            $fromAge = $searchOption[6]->fromAge;
            $toAge = $searchOption[7]->toAge;
            $cmbgreaterthan = $searchOption[8]->cmbgreaterthan;

            $nonNullCount = 0;

            if ($searchOption !== null) {
                
                if ($searchOption[0]->selecteCustomer !== null) {
                    $nonNullCount++;
                }
              
               
                if ($searchOption[1]->selecteRoute !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[2]->selectSalesrep !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[3]->selecteBranch !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[4]->fromdate !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[5]->todate !== null) {
                    $nonNullCount++;
                }


                if ($searchOption[6]->fromAge !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[7]->toAge !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[8]->cmbgreaterthan !== null) {
                    $nonNullCount++;
                }
            }

            if ($nonNullCount > 1) {
                $style = 'font-size:15px;';
                $query = "SELECT
                CONCAT('<label style=".$style.">',C.customer_code,'</label>') AS customer_code,  
                CONCAT('<label style=".$style.">',C.customer_name,'</label>') AS customer_name,
                CONCAT('<label style=".$style.">',D.external_number,'</label>') AS external_number,
                CONCAT('<label style=".$style.">',E.employee_name,'</label>') AS employee_name,
                D.trans_date,
                CONCAT('<label style=".$style.">',DATEDIFF(CURDATE(), D.trans_date),'</label>')  AS age,
                CONCAT('<label style=".$style.">',C.credit_amount_hold_limit,'</label>') AS credit_limit,
                CONCAT('<label style=".$style.">',C.credit_period_hold_limit,'</label>') AS credit_period,
                SUM(D.amount-D.paidamount) AS total_outstanding  
                 
                From debtors_ledgers D 
                LEFT JOIN customers C ON D.customer_id=C.customer_id 
                LEFT JOIN employees E ON D.employee_id = E.employee_id
                LEFT JOIN town_non_administratives T ON T.town_id=C.town 
                LEFT JOIN branches ON D.branch_id = branches.branch_id
                LEFT JOIN routes RT ON C.route_id = RT.route_id
                WHERE (D.amount - D.paidamount > 0) AND (D.document_number = 1600 OR D.document_number = 210)";

                $quryModify = "";
                if ($selecteBranch != null) {

                    if (count($selecteBranch) > 1) {
                        $quryModify .= " AND D.branch_id IN ('" . implode("', '", $selecteBranch) . "') ";
                    } else {
                        $quryModify .= " AND D.branch_id = '" . $selecteBranch[0] . " ' ";
                    }
                    //$quryModify .= " D.branch_id ='" . $selecteBranch . "'AND";
                    //$quryModify .= " D.branch_id IN ('" . implode("', '", $selecteBranch) . "') AND";
                }

              


                if ($selecteCustomer != null) {

                    if (count($selecteCustomer) > 1) {
                        $quryModify .= " AND D.customer_id IN ('" . implode("', '", $selecteCustomer) . "') ";
                    } else {
                        $quryModify .= " AND D.customer_id = '" . $selecteCustomer[0] . " ' ";
                    }

                    //$quryModify .= " D.customer_id ='" . $selecteCustomer . "'AND";
                    //$quryModify .= " D.customer_id IN ('" . implode("', '", $selecteCustomer) . "') AND";
                }
               


            

                if ($selecteRoute != null) {
                    if (count($selecteRoute) > 1) {
                        $quryModify .= " AND C.route_id IN ('" . implode("', '", $selecteRoute) . "') ";
                    } else {
                        $quryModify .= " AND C.route_id = '" . $selecteRoute[0] . " '";
                    }


                    // $quryModify .= " C.route_id ='" . $selecteRoute . "'AND";
                    ///$quryModify .= " C.route_id IN ('" . implode("', '", $selecteRoute) . "') AND";
                }
               

                if ($selectSalesrep != null) {
                    
                    $quryModify .= " AND D.employee_id IN ('" . implode("', '", $selectSalesrep) . "') ";
                }

                

                if ($cmbgreaterthan != null) {
                    $quryModify .= "  AND ( DATEDIFF( CURDATE(), D.trans_date ) > " . $cmbgreaterthan . ") ";
                }
                if ($fromAge != null && $toAge != null) {

                    $quryModify .= " AND (DATEDIFF(CURDATE(), D.trans_date) BETWEEN " . $fromAge . " AND " . $toAge . ")  ";
                    //$quryModify .= "D.trans_date BETWEEN '" . min($fromdate) . "' AND '" . max($todate) . "'";

                }

                if ($fromdate != null) {
                    $quryModify .= "  AND ( D.trans_date BETWEEN  '" . $fromdate . "' AND '" .$todate. "') ";
                }
                /* if ($fromAge != null && $toAge != null) {

                    $quryModify .= " AND (DATEDIFF(CURDATE(), D.trans_date) BETWEEN " . $fromAge . " AND " . $toAge . ")  ";
                    //$quryModify .= "D.trans_date BETWEEN '" . min($fromdate) . "' AND '" . max($todate) . "'";

                } */

                if ($quryModify != "") {
                 
                    $quryModify = rtrim($quryModify, 'AND OR ');
                      
                    
                    
                }
                //$query = $query . ' GROUP BY D.customer_id';

                $query .=  $quryModify . ' GROUP BY D.external_number ORDER BY DATEDIFF(CURDATE(), D.trans_date) DESC';
                //$query = preg_replace('/\W\w+\s*(\W*)$/', '$1', $query);
                //dd($query);
                $result = DB::select($query);

                $reportViwer = new ReportViewer();
                $reportViwer->addParameter("debtor_reports_tabaledata", $result);
            } else {





                $style = 'font-size:15px;';
                $query = "SELECT
                CONCAT('<label style=".$style.">',C.customer_code,'</label>') AS customer_code,  
                CONCAT('<label style=".$style.">',C.customer_name,'</label>') AS customer_name,
                CONCAT('<label style=".$style.">',D.external_number,'</label>') AS external_number,
                CONCAT('<label style=".$style.">',E.employee_name,'</label>') AS employee_name,
                CONCAT('<label style=".$style.">',T.townName,'</label>') AS town,
                CONCAT('<label style=".$style.">',DATEDIFF(CURDATE(), D.trans_date),'</label>')  AS age,
                CONCAT('<label style=".$style.">',C.credit_amount_hold_limit,'</label>') AS credit_limit,
                CONCAT('<label style=".$style.">',C.credit_period_hold_limit,'</label>') AS credit_period,
                FORMAT(SUM(D.amount-D.paidamount),2) AS total_outstanding  
                
                
                
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
                        $quryModify .= " D.branch_id = '" . $selecteBranch[0] . "'";
                    }

                    //$quryModify .= " D.branch_id ='" . $selecteBranch . "'";
                    //$quryModify .= " D.branch_id IN ('" . implode("', '", $selecteBranch) . "')";
                }
               





                if ($selecteCustomer != null) {

                    if (count($selecteCustomer) > 1) {
                        $quryModify .= " D.customer_id IN ('" . implode("', '", $selecteCustomer) . "')";
                    } else {
                        $quryModify .= " D.customer_id = '" . $selecteCustomer[0] . "'";
                    }

                    //$quryModify .= " D.customer_id ='" . $selecteCustomer . "'";
                    //$quryModify .= " D.customer_id IN ('" . implode("', '", $selecteCustomer) . "')";
                }
              

               
               


              
               


                if ($selecteRoute != null) {
                    if (count($selecteRoute) > 1) {
                        $quryModify .= " C.route_id IN ('" . implode("', '", $selecteRoute) . "')";
                    } else {
                        $quryModify .= " C.route_id = '" . $selecteRoute[0] . "'";
                    }

                    //$quryModify .= " C.route_id  ='" . $selecteRoute . "'";
                    //$quryModify .= " C.route_id IN ('" . implode("', '", $selecteRoute) . "')";
                }
               

                if ($selectSalesrep != null) {
                    $quryModify .= " D.employee_id IN ('" . implode("', '", $selectSalesrep) . "')";
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
                    $query = $query . ' GROUP BY D.external_number DATEDIFF(CURDATE(), D.trans_date) DESC';
                }
                //$query = $query . ' GROUP BY items.Item_code';


                //$query = preg_replace('/\W\w+\s*(\W*)$/', '$1', $query);
                //dd($query);
                $result = DB::select($query);

                $reportViwer = new ReportViewer();
                $reportViwer->addParameter("debtor_reports_tabaledata", $result);
            }
            if ($searchOption !== null) {


                $selecteCustomer = $searchOption[0]->selecteCustomer;
                $selecteRoute = $searchOption[1]->selecteRoute;
                $selectSalesrep = $searchOption[2]->selectSalesrep;
                $selecteBranch = $searchOption[3]->selecteBranch;
                $fromdate = $searchOption[4]->fromdate;
                $todate = $searchOption[5]->todate;
                $fromAge = $searchOption[6]->fromAge;
                $toAge = $searchOption[7]->toAge;
                $cmbgreaterthan = $searchOption[8]->cmbgreaterthan;
    

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


            return $reportViwer->viewReport('CustomerOustandingInvoiceWise.json');

        }catch (Exception $ex) {
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
}