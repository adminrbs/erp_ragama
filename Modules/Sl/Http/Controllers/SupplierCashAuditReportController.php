<?php

namespace Modules\Sl\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use RepoEldo\ELD\ReportViewer;

class SupplierCashAuditReportController extends Controller
{
    public function supplier_cash_audit_report($search)
    {
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
        $query_modify = ' WHERE SP.receipt_method_id = "1" AND ';
        if ($fromdate != null && $todate != null) {
            $query_modify .= 'SP.receipt_date BETWEEN "' . $fromdate . '" AND "' . $todate . '" AND';
        }

        if ($selectSupplier != null) {
            $query_modify .= ' SP.supplier_id = "' . $selectSupplier[0] . '" AND';
        }
        if ($selecteBranch != null) {
            $query_modify .= ' SP.branch_id = "' . $selecteBranch[0] . '" AND';
        }

        if ($fromdate == null && $todate == null  && $selectSupplier == null && $selecteBranch == null) {
            //$query_modify = "";
        } else {
            //$query_modify = preg_replace('/\W\w+\s*(\W*)$/', '$1', $query_modify);
        }
        $query_modify = preg_replace('/\W\w+\s*(\W*)$/', '$1', $query_modify);
        $qry = ' SELECT
        SP.supplier_id,
	    S.supplier_name,
	    SP.external_number,
	    SP.receipt_date,
	    SPSD.reference_external_number AS invoice_no,
	    E.employee_name,
        CL.trans_date,
	    DATEDIFF( SP.receipt_date, CL.trans_date ) AS gap,
	    CL.amount,
	    ( SELECT CL.paidamount FROM creditors_ledger CL WHERE SPSD.reference_external_number = CL.external_number LIMIT 1 ) AS total_paid,
	    CL.return_amount,
	    SPSD.set_off_amount,
	    (
		    ( SELECT CL.amount FROM creditors_ledger WHERE SPSD.reference_external_number = CL.external_number LIMIT 1 ) - ( SELECT CL.paidamount FROM creditors_ledger WHERE SPSD.reference_external_number = CL.external_number LIMIT 1 ) 
	    ) AS balance 
    FROM
	    supplier_payments SP
    INNER JOIN supplier_payment_setoff_data SPSD ON SP.supplier_payment_id = SPSD.supplier_payments_id
    INNER JOIN suppliers S ON SP.supplier_id = S.supplier_id
    INNER JOIN employees E ON SP.collector_id = E.employee_id
    INNER JOIN creditors_ledger CL ON SPSD.creditors_ledger_id = CL.creditors_ledger_id;' . $query_modify;

        $result = DB::select($qry);

        $resulsupplier = DB::select('select supplier_id,supplier_name from suppliers');
       

        $customerablearray = [];
        $titel = [];
        $reportViwer = new ReportViewer();
        $title = "Cash Audit";
        if ($fromdate && $todate) {
            $title .= " From : " . $fromdate . " To : " . $todate;
        }

        $reportViwer->addParameter("title", $title);
        foreach ($resulsupplier as $customerId) {
            $table = [];


            foreach ($result as $supplierData) {
                //dd($result);
                if ($supplierData->supplier_id == $customerId->supplier_id) {


                    array_push($table, $supplierData);
                }
            }



            if (count($table) > 0) {

                array_push($customerablearray, $table);


                array_push($titel, $customerId->supplier_name);

                $reportViwer->addParameter('abc', $titel);
            }
        }

        $reportViwer->addParameter("cash_audit_tabaledata", [$customerablearray]);
        $reportViwer->addParameter('companyName', CompanyDetailsController::CompanyName());

        return $reportViwer->viewReport('suppliercashAuditReport.json');

    }
}
