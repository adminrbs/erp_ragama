<?php

namespace Modules\Sc\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Sc\Entities\User;
use RepoEldo\ELD\ReportViewer;

class TrancationAllocationReportController extends Controller
{
	public function loadUsers()
	{
		$users = User::all();
		return $users ?? [];
	}

	public function customer_transaction_allocation_report($search)
	{
		try{
			$searchOption = json_decode($search);

			$fromdate = $searchOption[0]->fromdate;
			$todate = $searchOption[1]->todate;
			$selecteCustomer = $searchOption[2]->selecteCustomer;
			$selectUser = $searchOption[3]->selectUser;
	
			$nonNullCount = 0;
			if ($searchOption !== null) {
	
				if ($searchOption[0]->fromdate !== null) {
					$nonNullCount++;
				}
				if ($searchOption[1]->todate !== null) {
					$nonNullCount++;
				}
				if ($searchOption[2]->selecteCustomer !== null) {
					$nonNullCount++;
				}
				if ($searchOption[3]->selectUser !== null) {
					$nonNullCount++;
				}
			}
	
			$query_modify = " WHERE ";
			if ($fromdate != null && $todate != null) {
				$query_modify .= ' DATE(CTA.created_at) BETWEEN "' . $fromdate . '" AND "' . $todate . '" AND';
			}
			if ($selecteCustomer != null) {
				$query_modify .= ' debtors_ledgers.employee_id IN (' . implode("', '", $selecteCustomer) . ') AND';
			}
			if ($selectUser != null) {
				$query_modify .= ' customer_receipts.customer_id IN (' . implode("', '", $selectUser) . ')';
			}
		  
			$query_modify = rtrim($query_modify, 'AND OR ');
			//('" . implode("', '", $selecteBranch) . "')
	
			$qry = "SELECT
				C.customer_id,
				C.customer_name,
				FORMAT(CTA.amount,2) AS receipt_amount,
				U.name,
				CTA.customer_transaction_alocation_id,
				CTA.external_number,
				DL_SETOFF.external_number AS setoff_record,
				CTAS.reference_external_number AS setoff_from_record,
				DL_SETOFF.amount, 
				CTAS.set_off_amount,
				DL_SETOFF.amount - CTAS.set_off_amount AS balance
				
			FROM
				customer_transaction_alocations CTA
			INNER JOIN customer_transaction_alocations_setoffs CTAS ON CTA.customer_transaction_alocation_id = CTAS.customer_transaction_alocation_id
			INNER JOIN customers C ON CTA.customer_id = C.customer_id
			INNER JOIN debtors_ledgers DL_SETOFF ON CTAS.debtor_ledger_id = DL_SETOFF.debtors_ledger_id
			INNER JOIN debtors_ledgers DL_SETOFF_FROM ON CTAS.reference_debtor_ledger_id = DL_SETOFF_FROM.debtors_ledger_id
			INNER JOIN users U ON CTA.created_by = U.id";
			
			$qry .= $query_modify;
	
			//dd($qry);
			$result = DB::select($qry);

			$resulcustomer = DB::select($qry);
            

            $customerablearray = [];
            $receipt_array = [];
            $titel = [];
            $reportViwer = new ReportViewer();
            $title = "Customer Transaction Allocation";
            if ($fromdate && $todate) {
                $title .= " From : " . $fromdate . " To : " . $todate;
            }

            $reportViwer->addParameter("title", $title);

            $no_of_cheques = 0;

           
            foreach ($resulcustomer as $customerid) {


                if (!in_array($customerid->customer_transaction_alocation_id,$receipt_array,true)) {
                    $table = [];
                   // $cheque_amount = 0;
                    $inv_amount = 0;
                    $bool = true;
                    array_push($receipt_array, $customerid->customer_transaction_alocation_id);
                    foreach ($result as $customerdata) {
                        //dd($result);
                        if ($customerdata->customer_transaction_alocation_id == $customerid->customer_transaction_alocation_id && $customerdata->customer_id == $customerid->customer_id) {
                           // $cheque_amount += (float)$customerdata->amount;
                            $title_text =  "<strong>Ref No : </strong>" . $customerid->external_number . " - <strong>Customer : </strong>" . $customerdata->customer_name . " - <strong>Created By : </strong>" . $customerdata->name ." <strong>Amount :</strong>".$customerdata->receipt_amount;
                            if ($bool) {
                                array_push($titel, $title_text);
                               // $cheque_amount += (float)$customerdata->amount;
                               // $no_of_cheques++;
                                $bool = false;
                            }
                            array_push($table, $customerdata);

                            
                           
                        }
                    }
                    if (count($table) > 0) {
                        array_push($customerablearray, $table);
                        $reportViwer->addParameter('abc', $titel);
                    }
                }
              
            }
            //dd($titel);
            
           /*  $rep_name_qry = DB::select("SELECT CONCAT(employee_name,' - ',employee_code) as employee_name FROM employees WHERE employees.employee_id = 2");
            $rep_name = $rep_name_qry[0]->employee_name; */

            $reportViwer->addParameter("allocation_table", [$customerablearray]);
            $reportViwer->addParameter('companylogo', CompanyDetailsController::companyimage());
            $reportViwer->addParameter('companyName', CompanyDetailsController::CompanyName());
            $reportViwer->addParameter('companyAddress', CompanyDetailsController::CompanyAddress());
            $reportViwer->addParameter('companyNumber', CompanyDetailsController::CompanyNumber());
           // $reportViwer->addParameter('rep_name', $rep_name);

            // dd($customerablearray);
            return $reportViwer->viewReport('customerTransactionAllocation.json');
		}catch(Exception $ex){
			return $ex;
		}
		

		

	}
}
