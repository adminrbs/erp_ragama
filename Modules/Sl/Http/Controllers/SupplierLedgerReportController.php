<?php

namespace Modules\Sl\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use RepoEldo\ELD\ReportViewer;

class SupplierLedgerReportController extends Controller
{
   public function supplier_Ledger_reports($search){
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
        } 
       



        $reportViwer->addParameter('companylogo', CompanyDetailsController::companyimage());
        $reportViwer->addParameter('companyName', CompanyDetailsController::CompanyName());
        $reportViwer->addParameter('companyAddress', CompanyDetailsController::CompanyAddress());
        $reportViwer->addParameter('companyNumber', CompanyDetailsController::CompanyNumber());
        $reportViwer->addParameter("total_difference", "<br>Total Balance : " . number_format(($debit_total - $credit_total),2));

        /* $length =  (strlen($filterLabel) / 90);
       $i = floor($length);
        $i2 = 0;
        if (($length - $i) > 0) {
            $i2++;
        }
        $label_height = (($i + $i2) * 20);

        
        $reportViwer->addParameter('hight', $label_height); */

        return $reportViwer->viewReport('customer_ledger.json');
    } catch (Exception $ex) {
        return $ex;
    }

   }
}
