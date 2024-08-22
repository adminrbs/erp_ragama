<?php

namespace Modules\Sd\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Sd\Entities\Customer;
use Modules\Sd\Entities\sales_invoice;

class SalesinvoiceReportController extends Controller
{

    public function printsalesinvoicePdf($id, $status)
    {
        try {



            $si = sales_Invoice::find($id);
            $customer_id = $si->customer_id;
           /*  $outstanding_qry = DB::select(
                'SELECT DL.trans_date,
    DL.external_number, 
    (DL.amount - DL.paidamount) AS balance, 
    DATEDIFF(CURDATE(), DL.trans_date) AS age,
    C.customer_group_id 
FROM 
    debtors_ledgers DL
    LEFT JOIN customers C ON DL.customer_id = C.customer_id
WHERE 
    DL.customer_id = ' . $customer_id . ' 
    AND (DL.amount - DL.paidamount) > 0
    AND DL.branch_id = ' . $si->branch_id
            ); */
          /*   $outstanding_qry = DB::select(
                'SELECT DL.trans_date,
                        DL.external_number, 
                        (DL.amount - DL.paidamount) AS balance, 
                        DATEDIFF(CURDATE(), DL.trans_date) AS age,
                        C.customer_group_id 
                 FROM debtors_ledgers DL
                 LEFT JOIN customers C ON DL.customer_id = C.customer_id
                 WHERE DL.customer_id = ' . $customer_id . ' 
                   AND (DL.amount - DL.paidamount) > 0
                   AND DL.branch_id = ' . $si->branch_id . ' 
                 ORDER BY trans_date ASC'
            ); */
            $outstanding_qry = DB::select(
                'SELECT
	DL.trans_date,
	DL.external_number,
	( DL.amount - DL.paidamount ) AS balance,
	DATEDIFF( CURDATE(), DL.trans_date ) AS age,
	C.customer_group_id,
	(
	SELECT
		IPT.payment_term_id 
	FROM
		item_payment_terms IPT
		LEFT JOIN sales_invoice_items SII ON DL.external_number = SII.external_number 
	WHERE
		SII.item_id = IPT.item_id 
		LIMIT 1 
	) AS payment_term
	
FROM
	debtors_ledgers DL
	LEFT JOIN customers C ON DL.customer_id = C.customer_id 
WHERE
	DL.customer_id = '.$customer_id.'
	
	AND ( DL.amount - DL.paidamount ) > 0 
	AND DL.branch_id = '.$si->branch_id.' 
ORDER BY
	DL.trans_date ASC;');
            

            $sup_emp = DB::select('SELECT supply_group_id 
                FROM supplygroup_employees 
                WHERE sales_rep_id = ' . $si->employee_id);

            $supply_group = [""];
            if ($sup_emp) {
                $supply_group = DB::select(
                    'SELECT supply_group,supply_group_id 
                FROM supply_groups 
                WHERE supply_group_id =' . $sup_emp[0]->supply_group_id
                );

                $branch = DB::select('SELECT branch_name,branch_id FROM branches WHERE branch_id =' . $si->branch_id);
            }
            //dd($branch);

            // 1 REP -> 1 SUPPLY GROUP - sachin
            // dump($outstanding_qry);
            /*  $si->is_printed = 1;
            $si->update(); */
            return response()->json(['success' => true, 'data' => [
                'salesInvoiceRequests' => $this->getDatagrnRqst($id),
                'salesInvoiceReqestItems' => $this->getDatagrnItem($id),
                'companyAddress' => CompanyDetailsController::CompanyAddress(),
                'companyName' => CompanyDetailsController::CompanyName(),
                'companyContactDetails' => CompanyDetailsController::CompanyContactDetails(),
                'outstanding' => $outstanding_qry,
                'sup_group' => $supply_group,
                'branch' => $branch



            ]]);
        } catch (\Exception $ex) {
            return response()->json(['status' => false, 'error' => $ex->getMessage()]);
        }
    }

    private function getDatagrnRqst($id)
    {
        $qry = 'SELECT
        SI.external_number,
        SI.order_date_time,
        SI.your_reference_number,
        branches.branch_name,
        branches.address,
        branches.fixed_number,
        branches.email AS distributoremail,
        customers.primary_address,
        customers.primary_fixed_number,
        employees.employee_name,
        customers.customer_name,
        customers.customer_code,
        SI.is_printed,
        NOW() as current_date_time
    FROM
        sales_invoices SI
        LEFT JOIN locations ON SI.location_id = locations.location_id
        LEFT JOIN users ON SI.prepaired_by = users.id
        LEFT JOIN customers ON SI.customer_id = customers.customer_id
        LEFT JOIN employees ON SI.employee_id = employees.employee_id
        LEFT JOIN branches ON SI.branch_id = branches.branch_id
        LEFT JOIN town_non_administratives ON customers.town = town_non_administratives.town_id
        LEFT JOIN routes ON customers.route_id = routes.route_id 
    WHERE
        SI.sales_invoice_Id ="' . $id . '"';
        //  dd($qry);
        $result = DB::select($qry);

        $si = sales_Invoice::find($id);
        $si->is_printed = 1;
        $si->update();
        return $result;
    }
    private function getDatagrnItem($id)
    {
        $qry = 'SELECT *,sales_invoice_items.retial_price as inv_rt_price
                     FROM sales_invoice_items
                     LEFT JOIN items ON sales_invoice_items.item_id = items.item_id
                     WHERE sales_invoice_Id  = "' . $id . '" ORDER BY sales_invoice_items.item_name ASC';
        return DB::select($qry);
    }
}
