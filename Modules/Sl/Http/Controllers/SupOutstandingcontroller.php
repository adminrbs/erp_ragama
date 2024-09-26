<?php

namespace Modules\Sl\Http\Controllers;

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
use Modules\Sl\Entities\supplier;
use RepoEldo\ELD\ReportViewer;


class SupOutstandingcontroller extends Controller
{
    

    public function printsupoutstandinReport($search)
    {
        // return $search;
        //dd($search);
        try {

            $searchOption = json_decode($search);
           // dd($searchOption );
            $selectSupplier = $searchOption[0]->selectSupplier;
            $selectSupplygroup = $searchOption[1]->selectSupplygroup;
            $selecteBranch = $searchOption[2]->selecteBranch;
            $cmbgreaterthan = $searchOption[3]->cmbgreaterthan;
            $fromdate = $searchOption[4]->fromdate;
            $todate = $searchOption[5]->todate;
            $fromAge = $searchOption[6]->fromAge;
            $toAge = $searchOption[7]->toAge;
         
        
            $nonNullCount = 0;

            if ($searchOption !== null) {

                if ($searchOption[0]->selectSupplier !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[1]->selectSupplygroup !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[2]->selecteBranch !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[3]->cmbgreaterthan !== null) {
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
             
            }

            if ($nonNullCount > 1) {

                $query = "SELECT DISTINCT
                                D.external_number  AS invoice_number,
                              
                                DATEDIFF(CURDATE(), D.trans_date) AS age_days ,
                                D.trans_date,
                                D.amount , 
                                D.paidamount , 
                               
                               (D.amount - D.paidamount) AS balance_amount,
                               D.supplier_id,
                               B.branch_id

                            FROM
                                creditors_ledger D             
                            INNER JOIN 
                                suppliers S ON D.supplier_id = S.supplier_id
                            LEFT JOIN
                                supply_groups SG ON S.supply_group_id =SG.supply_group_id
                          
                            LEFT JOIN  branches B ON D.branch_id = B.branch_id


                            WHERE 
                                (D.amount - D.paidamount) > 0";



                $quryModify = "";
                if ($selecteBranch != null) {

                    if (count($selecteBranch) > 1) {
                        $quryModify .= " B.branch_id IN ('" . implode("', '", $selecteBranch) . "')  AND";
                    } else {
                        $quryModify .= " B.branch_id ='" . $selecteBranch[0] . "'AND";
                    }
                    
                }
               



                if ($selectSupplier != null) {
                    if (count($selectSupplier) > 1) {
                        $quryModify .= " S.supplier_id IN ('" . implode("', '", $selectSupplier) . "') AND";
                    } else {
                        $quryModify .= " S.supplier_id ='" . $selectSupplier[0] . "'AND";
                    }

                   
                }
                


                if ($selectSupplygroup != null) {
                    if (count($selectSupplygroup) > 1) {
                        $quryModify .= " SG.supply_group_id IN ('" . implode("', '", $selectSupplygroup) . "') AND";
                    } else {
                        $quryModify .= " SG.supply_group_id ='" . $selectSupplygroup[0] . "'AND";
                    }
                }

                if ($cmbgreaterthan != null) {
                    $quryModify .= "  DATEDIFF(CURDATE(), D.trans_date) > " . $cmbgreaterthan . " AND ";
                }
                if ($fromAge != null && $toAge != null) {

                    $quryModify .= " DATEDIFF(CURDATE(), D.trans_date) BETWEEN " . $fromAge . " AND " . $toAge . " AND ";
                    

                }



                if ($quryModify != "") {

                    $quryModify = rtrim($quryModify, 'AND OR ');
                    $query = $query . " AND " . $quryModify . ' ORDER BY age_days DESC';
                }
             
                $result = DB::select($query);

               

                $resulcustomer = DB::select('SELECT S.supplier_id,supplier_name,S.supplier_code FROM suppliers S');

                $customerablearray = [];
                $titel = [];
                $routes = [];
                $routes_total = [];
                $reportViwer = new ReportViewer();
                foreach ($resulcustomer as $customerid) {
                    $table = [];


                    foreach ($result as $customerdata) {
                       
                        if ($customerdata->supplier_id == $customerid->supplier_id) {


                            array_push($table, $customerdata);
                        }
                    }



                  
                    if (count($table) > 0) {
                        array_push($customerablearray, $table);

                        
                            
                            array_push($titel, " </strong><br>Customer :<strong>" . $customerid->supplier_code . '   ' . ' - ' . $customerid->supplier_name .  "</strong>");
                       
                     
                        $reportViwer->addParameter('abc', $titel);
                      
                    }
                }
                $reportViwer->addParameter("tabale_data", [$customerablearray]);
            } else {
               // dd('ddd');
               $query = "SELECT DISTINCT
               D.external_number  AS invoice_number,
              
               DATEDIFF(CURDATE(), D.trans_date) AS age_days ,
               D.trans_date,
               D.amount , 
               D.paidamount , 
             
              (D.amount - D.paidamount) AS balance_amount,
              D.supplier_id,
              B.branch_id

           FROM
               creditors_ledger D             
           INNER JOIN 
               suppliers S ON D.supplier_id = S.supplier_id
           LEFT JOIN
               supply_groups SG ON S.supply_group_id =SG.supply_group_id
         
           LEFT JOIN  branches B ON D.branch_id = B.branch_id


           WHERE 
               (D.amount - D.paidamount) > 0";







                $quryModify = "";
                if ($selecteBranch != null) {

                    if (count($selecteBranch) > 1) {
                        $quryModify .= " B.branch_id IN ('" . implode("', '", $selecteBranch) . "')  AND";
                    } else {
                        $quryModify .= " B.branch_id ='" . $selecteBranch[0] . "'AND";
                    }
                    
                }
               



                if ($selectSupplier != null) {
                    if (count($selectSupplier) > 1) {
                        $quryModify .= " S.supplier_id IN ('" . implode("', '", $selectSupplier) . "') AND";
                    } else {
                        $quryModify .= " S.supplier_id ='" . $selectSupplier[0] . "'AND";
                    }

                   
                }
                


                if ($selectSupplygroup != null) {
                    if (count($selectSupplygroup) > 1) {
                        $quryModify .= " SG.supply_group_id IN ('" . implode("', '", $selectSupplygroup) . "') AND";
                    } else {
                        $quryModify .= " SG.supply_group_id ='" . $selectSupplygroup[0] . "'AND";
                    }
                }

                if ($cmbgreaterthan != null) {
                    $quryModify .= "  DATEDIFF(CURDATE(), D.trans_date) > " . $cmbgreaterthan . " AND ";
                }
                if ($fromAge != null && $toAge != null) {

                    $quryModify .= " DATEDIFF(CURDATE(), D.trans_date) BETWEEN " . $fromAge . " AND " . $toAge . " AND ";
                    

                }



                if ($quryModify != "") {

                    $quryModify = rtrim($quryModify, 'AND OR ');
                    $query = $query . " AND " . $quryModify . ' ORDER BY age_days DESC';
                }

                if ($quryModify == "") {
                    $query = $query . ' ORDER BY age_days DESC';
                }



                
                $result = DB::select($query);

               
                $resulcustomer = DB::select('SELECT S.supplier_id,supplier_name,S.supplier_code FROM suppliers S');

                $customerablearray = [];
                $branchablearray = [];
                $titel = [];
                $titel2 = [];
                $routes = [];
                $routes_total = [];
                $reportViwer = new ReportViewer();
                foreach ($resulcustomer as $customerid) {
                    $table = [];


                    foreach ($result as $customerdata) {
                       
                        if ($customerdata->supplier_id == $customerid->supplier_id) {


                            array_push($table, $customerdata);
                        }
                    }



                  
                    if (count($table) > 0) {
                        array_push($customerablearray, $table);

                        
                            
                            array_push($titel, " </strong><br>Supplier :<strong>" . $customerid->supplier_code . '   ' . ' - ' . $customerid->supplier_name .  "</strong>");
                       
                     
                        $reportViwer->addParameter('abc', $titel);
                      
                    }
                }

               
                $reportViwer->addParameter("tabale_data", [$customerablearray]);
            }
            if ($searchOption !== null) {

              /*   $selectSupplier = $searchOption[0]->selectSupplier;
                $selectSupplygroup = $searchOption[1]->selectSupplygroup;
                $selecteBranch = $searchOption[2]->selecteBranch;
                $cmbgreaterthan = $searchOption[3]->cmbgreaterthan;
                $fromdate = $searchOption[4]->fromdate;
                $todate = $searchOption[5]->todate;
                $fromAge = $searchOption[6]->fromAge;
                $toAge = $searchOption[7]->toAge; */


              /*   $selectSupplier = $searchOption[0]->selectSupplier;
                $selectSupplygroup = $searchOption[1]->selectSupplygroup;
                $cmbgreaterthan = $cmbgreaterthan[3]->cmbgreaterthan;
                $selecteBranch = $searchOption[2]->selecteBranch;
                $fromdate = $searchOption[4]->fromdate;
                $todate = $searchOption[5]->todate;
                $fromAge = $searchOption[6]->fromAge;
                $toAge = $searchOption[7]->toAge; */

              

                
              
               

             
              
               
               

                
             

                
            }
            $reportViwer->addParameter('companylogo', CompanyDetailsController::companyimage());
            $reportViwer->addParameter('companyName', CompanyDetailsController::CompanyName());
            $reportViwer->addParameter('companyAddress', CompanyDetailsController::CompanyAddress());
            $reportViwer->addParameter('companyNumber', CompanyDetailsController::CompanyNumber());
            $reportViwer->addParameter('report_title', "Supplier's Outstanding as at " . Carbon::now()->format('d-m-Y'));

           // $length =  (strlen($filterLabel) / 90);
           // $i = floor($length);
         /*    $i2 = 0;
            if (($length - $i) > 0) {
                $i2++;
            }
            $label_height = (($i + $i2) * 20);
 */
            //dd($length . " " . (strlen($filterLabel)));
        //    $reportViwer->addParameter('hight', $label_height);

            return $reportViwer->viewReport('supoutstandingReport.json');
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

    public function getSupplier(){
        try{
            $data = supplier::all();
            return response()->json($data);
        }catch(Exception $ex){
            return $ex;
        }
    }
}
