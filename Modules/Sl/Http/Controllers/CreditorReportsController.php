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

class CreditorReportsController extends Controller
{
   

    public function debtor_reports($search)
    {
        // return $search;
        //dd($search);
        try {
            $searchOption = json_decode($search);
            $selectSupplier = $searchOption[0]->selectSupplier;
            $selectSupplygroup = $searchOption[1]->selectSupplygroup;
            $selecteBranch = $searchOption[2]->selecteBranch;
            $cmbgreaterthan = $searchOption[3]->cmbgreaterthan;
            $fromdate = $searchOption[4]->fromdate;
            $todate = $searchOption[5]->todate;
            $fromAge = $searchOption[6]->fromAge;
            $toAge = $searchOption[7]->toAge;

            $nonNullCount = 0;

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
                $query = "SELECT  
                S.supplier_code,
                S.supplier_name AS supplier_name,  
                S.credit_amount_hold_limit AS credit_limit ,
                S.credit_period_hold_limit AS credit_period,
                SUM(D.amount-D.paidamount) AS total_outstanding ,
                SUM(IF((CURDATE()-D.trans_date)<=30,D.amount-D.paidamount,0)) AS Age1 ,
                SUM(IF((CURDATE()-D.trans_date)>30 AND (CURDATE()-D.trans_date)<=60 ,D.amount-D.paidamount,0)) AS Age2 ,
                SUM(IF((CURDATE()-D.trans_date)>60 AND (CURDATE()-D.trans_date)<=90,D.amount-D.paidamount,0)) AS Age3 ,
                SUM(IF((CURDATE()-D.trans_date)>90,D.amount-D.paidamount,0)) AS Age4 
                
                From creditors_ledger D 
                INNER JOIN supplier S ON D.supplier_id=S.supplier_id 
                INNER JOIN branches ON D.branch_id = branches.branch_id";


                $quryModify = "";
                if ($selecteBranch != null) {

                    if (count($selecteBranch) > 1) {
                        $quryModify .= " D.branch_id IN ('" . implode("', '", $selecteBranch) . "') AND";
                    } else {
                        $quryModify .= " D.branch_id ='" . $selecteBranch[0] . "'AND";
                    }
                   
                }
               
                if ($selectSupplier != null) {
                    if (count($selectSupplier) > 1) {
                        $quryModify .= "S.supplier_id IN ('" . implode("', '", $selecteBranch) . "') AND ";
                    }else{
                        $quryModify .= "S.supplier_id = '" . $selecteBranch[0] . "' AND ";
                    }
                }

                if ($selectSupplygroup != null) {
                    if (count($selectSupplygroup) > 1) {
                        $quryModify .= "S.supply_group_id IN ('" . implode("', '", $selectSupplygroup) . "') AND ";
                    }else{
                        $quryModify .= "S.supply_group_id = '" . $selectSupplygroup[0] . "' AND ";
                    }
                }
                if ($cmbgreaterthan != null) {
                    $quryModify .= "  (CURDATE()-D.trans_date) > " . $cmbgreaterthan . " AND ";
                }
                if ($fromAge != null && $toAge != null) {

                    $quryModify .= "(CURDATE()-D.trans_date) BETWEEN " . $fromAge . " AND " . $toAge . " AND ";
                  
                }


                if ($quryModify != "") {

                    $quryModify = rtrim($quryModify, characters: 'AND OR ');
                    $query .= ' WHERE ' . $quryModify . ' GROUP BY D.supplier_id';
                }
               
                //dd($query);
                $result = DB::select($query);
                $reportViwer = new ReportViewer();
                $reportViwer->addParameter("debtor_reports_tabaledata", $result);
            } else {

                 $query = "SELECT  
                S.supplier_code,
                S.supplier_name AS supplier_name,  
                S.credit_amount_hold_limit AS credit_limit ,
                S.credit_period_hold_limit AS credit_period,
                SUM(D.amount-D.paidamount) AS total_outstanding ,
                SUM(IF((CURDATE()-D.trans_date)<=30,D.amount-D.paidamount,0)) AS Age1 ,
                SUM(IF((CURDATE()-D.trans_date)>30 AND (CURDATE()-D.trans_date)<=60 ,D.amount-D.paidamount,0)) AS Age2 ,
                SUM(IF((CURDATE()-D.trans_date)>60 AND (CURDATE()-D.trans_date)<=90,D.amount-D.paidamount,0)) AS Age3 ,
                SUM(IF((CURDATE()-D.trans_date)>90,D.amount-D.paidamount,0)) AS Age4 
                
                From creditors_ledger D 
                INNER JOIN supplier S ON D.supplier_id=S.supplier_id 
                INNER JOIN branches ON D.branch_id = branches.branch_id";


                $quryModify = "";
                if ($selecteBranch != null) {
                    if (count($selecteBranch) > 1) {
                        $quryModify .= " D.branch_id IN ('" . implode("', '", $selecteBranch) . "')";
                    } else {
                        $quryModify .= " D.branch_id ='" . $selecteBranch[0] . "'";
                    }

                   
                }
              





                if ($selectSupplier != null) {

                    if (count($selectSupplier) > 1) {
                        $quryModify .= " S.supplier_id IN ('" . implode("', '", $selectSupplier) . "')";
                    } else {
                        $quryModify .= " S.supplier_id ='" . $selectSupplier[0] . "'";
                    }

                    
                }
             


                if ($selectSupplygroup != null) {
                    if (count($selectSupplygroup) > 1) {
                        $quryModify .= " C.supply_group_id IN ('" . implode("', '", $selectSupplygroup) . "')";
                    } else {
                        $quryModify .= " C.supply_group_id ='" . $selectSupplygroup[0] . "'";
                    }



                }
             


        
             

                if ($cmbgreaterthan != null) {
                    $quryModify .= "(CURDATE()-D.trans_date) > " . $cmbgreaterthan . "";
                }

                if ($fromAge != null && $toAge != null) {

                    $quryModify .= "(CURDATE()-D.trans_date) '" . $fromAge . "' AND '" . $toAge . "'";
                    

                }


                if ($quryModify != "") {
                    $query = $query . " where " . $quryModify . ' GROUP BY D.supplier_id';
                }
              
               

                //$query = preg_replace('/\W\w+\s*(\W*)$/', '$1', $query);
                //dd($query);
                $result = DB::select($query);

                $reportViwer = new ReportViewer();
                $reportViwer->addParameter("creditor_reports_tabaledata", $result);
            }
            
            $reportViwer->addParameter('companylogo', CompanyDetailsController::companyimage());
            $reportViwer->addParameter('companyName', CompanyDetailsController::CompanyName());
            $reportViwer->addParameter('companyAddress', CompanyDetailsController::CompanyAddress());
            $reportViwer->addParameter('companyNumber', CompanyDetailsController::CompanyNumber());

          
            $label_height = 0;//(($i + $i2) * 20);

            //dd($length . " " . (strlen($filterLabel)));
            $reportViwer->addParameter('hight', $label_height);


            return $reportViwer->viewReport('debtor_reports.json');
        } catch (Exception $ex) {
            return $ex;
        }
    }
   


   
}
