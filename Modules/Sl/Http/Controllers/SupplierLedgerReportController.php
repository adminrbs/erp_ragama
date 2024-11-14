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
    public function supplier_Ledger_reports($search)
    {
        try {

            //dd($search);

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

                DB::select("SET @running_total := 0;");
                DB::select("SET @prev_supplier_id := NULL;");

                $query = "SELECT
                D.trans_date,
                D.external_number,
                D.description,
                D.supplier_id,
                IF(D.amount > 0, D.amount, 0) AS Debit,
                IF(D.amount < 0, -D.amount, 0) AS Credit,
                CASE
                    WHEN D.supplier_id = @prev_supplier_id THEN
                        ABS(@running_total := @running_total + IF(D.amount > 0, D.amount, 0) - IF(D.amount < 0, -D.amount, 0))
                ELSE
                    ABS(@running_total := IF(D.amount > 0, D.amount, 0) - IF(D.amount < 0, -D.amount, 0))
                END AS RunningTotal,
                (@prev_supplier_id := D.supplier_id) AS prev_supplier_id
            FROM (
                SELECT
                C.*,
                C.supplier_id AS supplier_id_alias
            FROM creditors_ledger C
                INNER JOIN suppliers S ON C.supplier_id = S.supplier_id
                INNER JOIN branches B ON C.branch_id = B.branch_id";

                $quryModify = "";

                if ($fromdate != null && $todate != null) {
                    $quryModify .= "C.trans_date BETWEEN '" . $fromdate . "' AND '" . $todate . "' AND ";
                }

                if ($selecteBranch != null) {
                    if (count($selecteBranch) > 1) {
                        $quryModify .= "C.branch_id IN ('" . implode("', '", $selecteBranch) . "') AND ";
                    } else {
                        $quryModify .= "C.branch_id = '" . $selecteBranch[0] . "' AND ";
                    }
                }

                if ($selectSupplier != null) {
                    if (count($selectSupplier) > 1) {
                        $quryModify .= "S.supplier_id IN ('" . implode("', '", $selectSupplier) . "') AND ";
                    }else{
                        $quryModify .= "S.supplier_id = '" . $selectSupplier[0] . "' AND ";
                    }
                }

                if ($selectSupplygroup != null) {
                    if (count($selectSupplygroup) > 1) {
                        $quryModify .= "S.supply_group_id IN ('" . implode("', '", $selectSupplygroup) . "') AND ";
                    }else{
                        $quryModify .= "S.supply_group_id = '" . $selectSupplygroup[0] . "' AND ";
                    }
                }


                if ($quryModify != "") {
                    $quryModify = rtrim($quryModify, 'AND ');
                    $query = $query . " WHERE " . $quryModify . " GROUP BY C.trans_date, C.external_number, C.description, S.supplier_id
        ) AS D
        ORDER BY D.supplier_id";
                } else {
                    $query = $query . " GROUP BY C.trans_date, C.external_number, C.description, S.supplier_id
        ) AS D
        ORDER BY D.supplier_id";
                }



               // dd($query);
                $result = DB::select($query);

                $resulcustomer = DB::select('select supplier_id,supplier_name,supplier_code from suppliers');

                $customerablearray = [];
                $titel = [];
                $reportViwer = new ReportViewer();
                $debit_total = 0;
                $credit_total = 0;

                foreach ($resulcustomer as $customerid) {
                    $table = [];


                    foreach ($result as $customerdata) {
                        //dd($result);
                        if ($customerdata->supplier_id == $customerid->supplier_id) {

                            $debit_total += $customerdata->Debit;
                            $credit_total += $customerdata->Credit;
                            array_push($table, $customerdata);
                        }
                    }



                    if (count($table) > 0) {

                        array_push($customerablearray, $table);


                        array_push($titel, '<h3>'.$customerid->supplier_code . '   ' . ' - ' . $customerid->supplier_name.'</h3>');


                        $reportViwer->addParameter('abc', $titel);
                    }
                }

                $reportViwer->addParameter("Customerledger_tabaledata", [$customerablearray]);
                $reportViwer->addParameter("total_difference", "<br>Total Balance : " . number_format(($debit_total - $credit_total), 2));
            }




            $reportViwer->addParameter('companylogo', CompanyDetailsController::companyimage());
            $reportViwer->addParameter('companyName', CompanyDetailsController::CompanyName());
            $reportViwer->addParameter('companyAddress', CompanyDetailsController::CompanyAddress());
            $reportViwer->addParameter('companyNumber', CompanyDetailsController::CompanyNumber());
            $reportViwer->addParameter("total_difference", "<br>Total Balance : " . number_format(($credit_total - $debit_total), 2));

            return $reportViwer->viewReport('supplier_ledger.json');
        } catch (Exception $ex) {
            return $ex;
        }
    }
}
