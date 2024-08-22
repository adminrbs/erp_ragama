<?php

namespace Modules\Sd\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Sd\Entities\sales_Invoice;

class InvoiceInfoController extends Controller
{
    //load invoice info tp auto complete textbox
    public function load_inv()
    {
        $data = DB::table('sales_invoices')->select('manual_number')->get();
        return response()->json($data);
    }

    //load invoice data
    public function load_invoice_details($number)
    {

        $inv_data_header_qry = '
    SELECT 
        SI.external_number, 
        SI.order_date_time, 
        B.branch_name, 
        L.location_name, 
        C.customer_name, 
        E.employee_name, 
        DL.amount, 
        DL.paidamount, 
        (DL.amount - DL.paidamount) as balance, 
        SO.external_number as so_number, 
        SO.order_date_time as s_order_date, 
        DATEDIFF(SI.order_date_time, SO.order_date_time) AS date_gap
    FROM sales_invoices SI 
    LEFT JOIN branches B ON SI.branch_id = B.branch_id 
    LEFT JOIN locations L ON SI.location_id = L.location_id 
    LEFT JOIN customers C ON SI.customer_id = C.customer_id  
    LEFT JOIN employees E ON SI.employee_id = E.employee_id
    LEFT JOIN debtors_ledgers DL ON SI.external_number = DL.external_number
    LEFT JOIN sales_orders SO ON SI.sales_order_Id = SO.sales_order_Id
    WHERE SI.external_number = \'' . $number . '\'
';

        $result = DB::select($inv_data_header_qry);

        //items
        $item_data = DB::select('
    SELECT 
        SI.item_id,
        SI.item_name,
        SI.quantity,
        SI.free_quantity,
        SI.unit_of_measure,
        SI.package_size,
        SI.discount_percentage,
        SI.price,
        IT.Item_code 
    FROM 
        sales_invoice_items SI 
    INNER JOIN 
        sales_invoices S ON SI.sales_invoice_Id = S.sales_invoice_Id 
    INNER JOIN 
        items IT ON SI.item_id = IT.item_id 
    WHERE 
        S.external_number = \'' . $number . '\'
');




$return_data = DB::select('
SELECT 
    SR.sales_return_Id, 
    SR.manual_number, 
    SR.external_number, 
    SR.order_date, 
    SR.total_amount, 
    C.customer_name, 
    E.employee_name, 
    U.name
FROM 
    sales_returns SR
LEFT JOIN 
    sales_invoices SI ON SR.sales_invoice_id = SI.sales_invoice_Id
LEFT JOIN 
    customers C ON SR.customer_id = C.customer_id
LEFT JOIN 
    employees E ON SR.employee_id = E.employee_id
LEFT JOIN 
    users U ON SR.prepaired_by = U.id
WHERE 
    SI.external_number = ?
', [$number]);



        //customer receipt
        $cus = DB::select('
        SELECT 
            CR.receipt_date, 
            CR.external_number,
            CR.amount,
            E.employee_name,
            CRH.cheque_number, 
            DATEDIFF(SI.order_date_time, CR.receipt_date) AS Gap,
            CRS.set_off_amount 
        FROM 
            customer_receipts CR 
        LEFT JOIN 
            employees E ON CR.collector_id = E.employee_id 
        LEFT JOIN 
            customer_receipt_setoff_data CRS ON CR.customer_receipt_id = CRS.customer_receipt_id 
        INNER JOIN 
            sales_invoices SI ON CRS.reference_internal_number = SI.internal_number 
        LEFT JOIN 
            customer_receipt_cheques CRH ON CR.customer_receipt_id = CRH.customer_receipt_id 
        WHERE 
            SI.external_number = \'' . $number . '\'
    ');
    

        //sfa receipt
        $sfa = DB::select('
    SELECT
        SFA.receipt_date,
        SFA.external_number,
        SFA.amount,
        SFC.cheque_number,
        E.employee_name,
        DATEDIFF(SFA.receipt_date, SI.order_date_time) AS Gap,
        SFS.set_off_amount
    FROM
        sfa_receipts SFA
    LEFT JOIN
        sfa_receipt_cheques SFC ON SFA.customer_receipt_id = SFC.customer_receipt_id
    LEFT JOIN
        sfa_receipt_setoff_data SFS ON SFA.customer_receipt_id = SFS.customer_receipt_id
    INNER JOIN
        debtors_ledgers DL ON SFS.debtors_ledger_id = DL.debtors_ledger_id
    INNER JOIN
        sales_invoices SI ON DL.external_number = SI.external_number
    LEFT JOIN
        employees E ON SI.employee_id = E.employee_id
    WHERE
        SI.external_number = ?
', [$number]);


$delivery_plan = DB::select('
SELECT 
    DP.delivery_plan_id,
    DP.external_number,
    emp_d.employee_name AS driver_name,
    emp_h.employee_name AS helper_name,
    V.vehicle_no,
    U.name 
FROM 
    sales_invoices SI 
INNER JOIN 
    delivery_plans DP ON SI.delivery_plan_id = DP.delivery_plan_id 
INNER JOIN 
    employees emp_d ON DP.driver_id = emp_d.employee_id 
INNER JOIN 
    employees emp_h ON DP.helper_id = emp_h.employee_id 
INNER JOIN 
    vehicles V ON DP.vehicle_id = V.vehicle_id 
LEFT JOIN 
    users U ON DP.created_by = U.id 
WHERE 
    SI.external_number = ?
', [$number]);


$picking_list = DB::select('
SELECT 
    DATE(P.created_at) AS created_date,
    P.delivery_plan_packing_list_id,
    SI.picking_list_id 
FROM 
    sales_invoices SI
INNER JOIN 
    delivery_plan_packing_lists P ON SI.picking_list_id = P.delivery_plan_packing_list_id
WHERE 
    SI.external_number = ?
', [$number]);



        // $dp_id = $delivery_plan[0]->delivery_plan_id;

        $inv_ = DB::select('
    SELECT 
        sales_invoice_Id 
    FROM 
        sales_invoices 
    WHERE 
        external_number = ?
', [$number]);

        $inv_id = $inv_[0]->sales_invoice_Id;
        $delivery_confirmation_data = "";
        if (intval($inv_id) > 0) {
            $delivery_confirmation_data = DB::select('SELECT 
        
        deliveryconfirmations.delivered,
        deliveryconfirmations.Seal,
        deliveryconfirmations.Signature,
        deliveryconfirmations.Cash,
        deliveryconfirmations.Cheque,
        deliveryconfirmations.noSeal,
        deliveryconfirmations.cancel,
        U.name

    FROM deliveryconfirmations
    LEFT JOIN users U ON deliveryconfirmations.created_by = U.id
    
    WHERE deliveryconfirmations.sales_invoice_Id =' . $inv_id);
        } else {
            $delivery_confirmation_data = [];
        }


        return response()->json(["header" => $result, "item" => $item_data, "return_data" => $return_data, "customer_receipt" => $cus, "sfa" => $sfa, "delivery_plan" => $delivery_plan, "picking_list" => $picking_list, "delivery_confirmation_data" => $delivery_confirmation_data]);
    }


    //return items
    public function load_return_item($id)
    {
        $return_items = DB::select('SELECT SRI.item_id, SRI.item_name, SRI.quantity, SRI.free_quantity, SRI.unit_of_measure, SRI.package_unit, SRI.price,SRI.discount_percentage,IT.Item_code
        FROM sales_return_items SRI
        INNER JOIN items IT ON SRI.item_id = IT.item_id WHERE SRI.sales_return_Id =' . $id);

        if ($return_items) {
            return response()->json(["items" => $return_items]);
        }
    }



    //load invoice to model table
    public function getInvoices_inv_info(Request $request)
    {
        try {


            $from_date = $request->input('from_date');
            $to_date = $request->input('to_date');
            $cusID = $request->input('cmbCustomer');
            $repID = $request->input('cmbSalesRep');

            if ($cusID == 0 && $repID == 0) {
                $query = "SELECT DISTINCT sales_invoices.sales_invoice_Id,order_date_time,sales_invoices.external_number,sales_invoices.manual_number,total_amount,customers.customer_name,employees.employee_name
                    FROM sales_invoices
                    INNER JOIN customers on sales_invoices.customer_id = customers.customer_id
                    INNER JOIN employees ON sales_invoices.employee_id = employees.employee_id
                    INNER JOIN sales_invoice_items ON sales_invoices.sales_invoice_Id = sales_invoice_items.sales_invoice_Id
                    WHERE order_date_time BETWEEN '" . $from_date . "' AND '" . $to_date . "'
                     AND (ABS(sales_invoice_items.quantity) - sales_invoice_items.returned_qty) > 0 ORDER BY
                    sales_invoice_Id DESC";
                $invoices = DB::select($query);
                if ($invoices) {
                    return response()->json(['status' => true, "data" => $invoices]);
                } else {
                    return response()->json(['status' => false, "data" => []]);
                }
            } else if ($cusID == 0) {
                $query = "SELECT DISTINCT sales_invoices.sales_invoice_Id,order_date_time,sales_invoices.external_number,sales_invoices.manual_number,total_amount,customers.customer_name,employees.employee_name
                    FROM sales_invoices
                    INNER JOIN customers on sales_invoices.customer_id = customers.customer_id
                    INNER JOIN employees ON sales_invoices.employee_id = employees.employee_id
                    INNER JOIN sales_invoice_items ON sales_invoices.sales_invoice_Id = sales_invoice_items.sales_invoice_Id
                    WHERE order_date_time BETWEEN '" . $from_date . "' AND '" . $to_date . "'
                    AND sales_invoices.employee_id = '" . $repID . "'AND (ABS(sales_invoice_items.quantity) - sales_invoice_items.returned_qty) > 0
                    ORDER BY
                    sales_invoices.sales_invoice_Id DESC";
                $invoices = DB::select($query);
                if ($invoices) {
                    return response()->json(['status' => true, "data" => $invoices]);
                } else {
                    return response()->json(['status' => false, "data" => []]);
                }
            } else if ($repID == 0) {
                $query = "SELECT DISTINCT sales_invoices.sales_invoice_Id,order_date_time,sales_invoices.external_number,sales_invoices.manual_number,total_amount,customers.customer_name,employees.employee_name
                    FROM sales_invoices
                    INNER JOIN customers on sales_invoices.customer_id = customers.customer_id
                    INNER JOIN employees ON sales_invoices.employee_id = employees.employee_id
                    INNER JOIN sales_invoice_items ON sales_invoices.sales_invoice_Id = sales_invoice_items.sales_invoice_Id
                    WHERE order_date_time BETWEEN '" . $from_date . "' AND '" . $to_date . "'
                    AND sales_invoices.customer_id = '" . $cusID . "'AND (ABS(sales_invoice_items.quantity) - sales_invoice_items.returned_qty) > 0 ORDER BY
                    sales_invoices.sales_invoice_Id DESC
                    ";
                $invoices = DB::select($query);
                if ($invoices) {
                    return response()->json(['status' => true, "data" => $invoices]);
                } else {
                    return response()->json(['status' => false, "data" => []]);
                }
            } else {
                $query = "SELECT DISTINCT sales_invoices.sales_invoice_Id,order_date_time,sales_invoices.external_number,total_amount,customers.customer_name,employees.employee_name
                    FROM sales_invoices
                    INNER JOIN customers on sales_invoices.customer_id = customers.customer_id
                    INNER JOIN employees ON sales_invoices.employee_id = employees.employee_id
                    INNER JOIN sales_invoice_items ON sales_invoices.sales_invoice_Id = sales_invoice_items.sales_invoice_Id
                    WHERE order_date_time BETWEEN '" . $from_date . "' AND '" . $to_date . "'
                    AND sales_invoices.customer_id = '" . $cusID . "'
                    AND sales_invoices.employee_id = '" . $repID . "'AND (ABS(sales_invoice_items.quantity) - sales_invoice_items.returned_qty) > 0 ORDER BY
                    sales_invoices.sales_invoice_Id DESC";
                $invoices = DB::select($query);
                if ($invoices) {
                    return response()->json(['status' => true, "data" => $invoices]);
                } else {
                    return response()->json(['status' => false, "data" => []]);
                }
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }
}
