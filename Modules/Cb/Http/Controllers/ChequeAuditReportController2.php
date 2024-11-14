<?php

namespace Modules\Cb\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use RepoEldo\ELD\ReportViewer;

class ChequeAuditReportController2 extends Controller
{
    public function chequeAuditReport($filters)

    {
        try {
            $filter_options = json_decode($filters);
            $fromDate = $filter_options->fromDate;
            $toDate = $filter_options->toDate;
            $salesRep = $filter_options->salesRep;
            $customer = $filter_options->customer;
            $branch = $filter_options->branch;


            $query_modify = ' WHERE  customer_receipt_cheques.amount > 0 AND  customer_receipts.receipt_method_id = "2" AND ';
            if ($fromDate != null && $toDate != null) {
                $query_modify .= 'customer_receipts.receipt_date BETWEEN "' . $fromDate . '" AND "' . $toDate . '" AND';
            }
            if ($salesRep != null) {
                //$query_modify .= ' debtors_ledgers.employee_id = "' . $salesRep[0] . '" AND';
                $query_modify .= ' customer_receipts.collector_id = "' . $salesRep[0] . '" AND'; //changed on 16/08/2024

            }
            if ($customer != null) {
                $query_modify .= ' customer_receipts.customer_id = "' . $customer[0] . '" AND';
            }
            if ($branch != null) {
                $query_modify .= ' customer_receipts.branch_id = "' . $branch[0] . '" AND';
            }

            if ($fromDate == null && $toDate == null && $salesRep == null && $customer == null && $branch == null) {
                //$query_modify = "";
            } else {
                //$query_modify = preg_replace('/\W\w+\s*(\W*)$/', '$1', $query_modify);
            }
            $query_modify = preg_replace('/\W\w+\s*(\W*)$/', '$1', $query_modify);
            // $query_modify .= ' GROUP BY customer_receipts.customer_receipt_id';
            //$query_modify .= ' GROUP BY customer_receipt_cheques.cheque_number';


            $qry = 'SELECT
        customer_receipts.customer_receipt_id,
        customers.customer_id,
        customer_receipts.receipt_status,
        customer_receipts.external_number,
        customers.customer_name,
        customer_receipts.receipt_date,
        customer_receipt_cheques.banking_date,
        customer_receipt_cheques.cheque_number,
        debtors_ledgers.external_number AS InvoiceNo ,
        E.employee_name,
        CAST(DATEDIFF(customer_receipts.receipt_date,debtors_ledgers.trans_date)AS SIGNED) AS Age,
        (SELECT debtors_ledgers.amount  FROM debtors_ledgers WHERE customer_receipt_setoff_data.reference_external_number = debtors_ledgers.external_number LIMIT 1) AS  Invoice_amont,
        customer_receipt_setoff_data.paid_amount AS total_paid,
		customer_receipt_setoff_data.return_amount AS total_return,
         customer_receipt_setoff_data.set_off_amount,
        (customer_receipt_setoff_data.Amount - customer_receipt_setoff_data.paid_amount - customer_receipt_setoff_data.set_off_amount ) AS balance,
        customer_receipt_cheques.amount,
        banks.bank_name,
        bank_branches.bank_branch_name
        FROM customer_receipts
        LEFT JOIN customer_receipt_setoff_data ON  customer_receipt_setoff_data.customer_receipt_id=customer_receipts.customer_receipt_id 
        LEFT JOIN debtors_ledgers ON debtors_ledgers.debtors_ledger_id =  customer_receipt_setoff_data.debtors_ledger_id  
        LEFT JOIN employees E ON customer_receipts.collector_id = E.employee_id
        LEFT JOIN customer_receipt_cheques ON customer_receipts.customer_receipt_id = customer_receipt_cheques.customer_receipt_id
        LEFT JOIN customers ON customer_receipts.customer_id = customers.customer_id
        LEFT JOIN banks ON customer_receipt_cheques.bank_id = banks.bank_id
        LEFT JOIN bank_branches ON customer_receipt_cheques.bank_branch_id = bank_branches.bank_branch_id' . $query_modify;
           //Query changed on 06/11 Mr.Janaka sales_return_debtor_setoffs.setoff_amount get twise.

           // dd($qry);
            $result = DB::select($qry);
            //dd($result);
            $resulcustomer = DB::select('select customer_receipts.customer_receipt_id,customers.customer_name,CRC.cheque_number 
            from customer_receipts INNER JOIN customers ON customer_receipts.customer_id = customers.customer_id
						INNER JOIN customer_receipt_cheques CRC ON customer_receipts.customer_receipt_id = CRC.customer_receipt_id');
            //$resulcustomer = DB::select("SELECT CRC.customer_receipt_id,C.customer_name FROM customer_receipt_cheques CRC INNER JOIN customer_receipts CR ON CRC.customer_receipt_id = CR.customer_receipt_id INNER JOIN customers C ON CR.customer_id = C.customer_id");
            /* $resulcustomer = DB::select("SELECT DISTINCT
	CRC.external_number,
	C.customer_name 
FROM
	customer_receipt_cheques CRC
	INNER JOIN customer_receipts CR ON CRC.customer_receipt_id = CR.customer_receipt_id
	INNER JOIN customers C ON CR.customer_id = C.customer_id"); */
            /*  $resulcustomer = DB::select("SELECT DISTINCT
	CRC.cheque_number,
	CRC.customer_receipt_id,
	B.bank_name,
	BB.bank_branch_name,
	C.customer_name
	
FROM
	customer_receipt_cheques CRC
	INNER JOIN customer_receipts CR ON CRC.customer_receipt_id = CR.customer_receipt_id
	INNER JOIN customers C ON CR.customer_id = C.customer_id
	
	INNER JOIN banks B ON CRC.bank_id = B.bank_id
	INNER JOIN bank_branches BB ON CRC.bank_branch_id = BB.bank_branch_id
GROUP BY
	CRC.cheque_number, 
	B.bank_name,
	BB.bank_branch_name;"); */

            $customerablearray = [];
            $cheque_number_array = [];
            $titel = [];
            $reportViwer = new ReportViewer();
            $title = "Cheque Audit";
            if ($fromDate && $toDate) {
                $title .= " From : " . $fromDate . " To : " . $toDate;
            }

            $reportViwer->addParameter("title", $title);

            $no_of_cheques = 0;

            $total_balance = 0;

            foreach ($resulcustomer as $customerid) {


                if (!in_array($customerid->cheque_number,$cheque_number_array,true)) {
                    $table = [];
                    $cheque_amount = 0;
                    $inv_amount = 0;
                    $bool = true;
                    array_push($cheque_number_array, $customerid->cheque_number);
                    foreach ($result as $customerdata) {
                        //dd($result);
                        if ($customerdata->cheque_number == $customerid->cheque_number && $customerdata->customer_receipt_id == $customerid->customer_receipt_id) {
                            $cheque_amount += (float)$customerdata->amount;
                            $title_text =  "<strong>Customer Name : </strong>" . $customerid->customer_name . " - <strong>Ref No : </strong>" . $customerdata->external_number . " - <strong>Receipt Date : </strong>" . $customerdata->receipt_date . " <br> <strong>Bank : </strong>" . $customerdata->bank_name . " - <strong>Branch : </strong>" . $customerdata->bank_branch_name . " - <strong>Cheque No : </strong>" . $customerdata->cheque_number . " - <strong>Banking Date : </strong>" . $customerdata->banking_date . " - <strong>Cheque Amount :</strong>" . number_format($cheque_amount, 2);
                            if ($bool) {
                                array_push($titel, $title_text);
                                $cheque_amount += (float)$customerdata->amount;
                                $no_of_cheques++;
                                $bool = false;
                            }
                            array_push($table, $customerdata);

                            
                            if ($inv_amount == 0) {
                                $inv_amount = $customerdata->amount;
                            }
                            if ($inv_amount > 0) {
                                $inv_amount  = $inv_amount - $customerdata->Invoice_amont;
                            }
                        }
                    }
                    if (count($table) > 0) {
                        array_push($customerablearray, $table);
                        $reportViwer->addParameter('abc', $titel);
                    }
                }
                $total_balance = $total_balance + $inv_amount;
                $formatted_balance = number_format($total_balance, 2, '.', ',');
            }
            //dd($titel);
            $reportViwer->addParameter("no_of_cheques", "No of cheques :" . $no_of_cheques);
            $reportViwer->addParameter("balance", "Balance :" . $formatted_balance);
            $rep_name_qry = DB::select("SELECT CONCAT(employee_name,' - ',employee_code) as employee_name FROM employees WHERE employees.employee_id = 2");
            $rep_name = $rep_name_qry[0]->employee_name;

            $reportViwer->addParameter("cheque_audit_tabaledata", [$customerablearray]);
            $reportViwer->addParameter('companylogo', CompanyDetailsController::companyimage());
            $reportViwer->addParameter('companyName', CompanyDetailsController::CompanyName());
            $reportViwer->addParameter('companyAddress', CompanyDetailsController::CompanyAddress());
            $reportViwer->addParameter('companyNumber', CompanyDetailsController::CompanyNumber());
            $reportViwer->addParameter('rep_name', $rep_name);

            // dd($customerablearray);
            return $reportViwer->viewReport('chequeAuditReport.json');
        } catch (Exception $ex) {
            return $ex;
        }
    }
}
