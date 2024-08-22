<?php

namespace Modules\Sd\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Sd\Entities\Customer;
use Modules\Sd\Entities\customer_block;

class CustomerBlockController extends Controller
{
    //check block status and insert
    public function checkBlockStatus($empid, $cusid)
    {
        $status = 0;
        $block_reason = 0;
        try {
            $result = DB::select('CALL sd_customer_is_blocked(?, ?)', [$cusid, $empid]);
            if ($result) {

                $status = $result[0]->is_blocked;
                $block_insert = $result[0]->block_insert;
            }
            if ($block_insert == 1) {
                $cusotmer_block = new customer_block();
                $cusotmer_block->customer_id = $cusid;
                $cusotmer_block->employee_id = $empid;
                if ($cusotmer_block->save()) {
                    return response()->json(["status" => true,"block_id"=>$cusotmer_block->customer_block_id]);
                } else {
                    return response()->json(["status" => false]);
                }
            }
            if ($status == 1) {
                return response()->json(["status" => true]);
            } else if ($status == 0) {
                return response()->json(["status" => false]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //load customer blocked records
    public function loadCustomerBlockList($id)
    {
        try {



            if ($id == 0) {
                $qry  = "SELECT 
                customer_blocks.customer_block_id,
                customer_blocks.customer_id,
                customer_blocks.employee_id,
                customer_blocks.remark,
                customer_blocks.is_blocked,
                CONCAT(customers.customer_code,'-',customers.customer_name) AS customer_name,
                employees.employee_name,
                T.townName,
                R.route_name,
                (SELECT FORMAT(SUM(total_amount),2) AS total_value 
                 FROM sales_orders SO 
                 WHERE SO.customer_block_id = customer_blocks.customer_block_id) AS total 
            FROM 
                customer_blocks 
            INNER JOIN 
                customers ON customer_blocks.customer_id = customers.customer_id 
            INNER JOIN 
                employees ON customer_blocks.employee_id = employees.employee_id 
            LEFT JOIN 
                town_non_administratives T ON customers.town = T.town_id 
            LEFT JOIN 
                routes R ON customers.route_id = R.route_id 
            ORDER BY 
                customer_blocks.is_blocked DESC;
            ";
                $result = DB::select($qry);
                if ($result) {
                    return response()->json(["status" => true, "data" => $result]);
                } else {
                    return response()->json(["status" => true, "data" => []]);
                }
            } else {
                $qry = "SELECT 
                customer_blocks.customer_block_id,
                customer_blocks.customer_id,
                customer_blocks.employee_id,
                customer_blocks.remark,
                customer_blocks.is_blocked,
                CONCAT(customers.customer_code,'-',customers.customer_name) AS customer_name,
                R.route_name,
                employees.employee_name,
                T.townName,
                (
                    SELECT 
                        SUM(total_amount) AS total_value 
                    FROM 
                        sales_orders SO 
                    WHERE 
                        SO.customer_block_id = customer_blocks.customer_block_id
                ) AS total 
            FROM 
                customer_blocks 
            INNER JOIN 
                customers ON customer_blocks.customer_id = customers.customer_id 
            INNER JOIN 
                employees ON customer_blocks.employee_id = employees.employee_id 
            LEFT JOIN 
                town_non_administratives T ON customers.town = T.town_id 
            LEFT JOIN 
                routes R ON customers.route_id = R.route_id 
            WHERE 
                customer_blocks.employee_id = $id 
            ORDER BY 
                customer_blocks.is_blocked DESC;
            ";
                $result = DB::select($qry);
                if ($result) {
                    return response()->json(["status" => true, "data" => $result]);
                } else {
                    return response()->json(["status" => true, "data" => []]);
                }
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //block or release
    public function block_release(Request $request, $id, $action)
    {
        
        try {
            if ($action == 0) {
                $request->validate([
                    'txtRemark' => 'required',
                    'txtnumOfOrders' => 'required',
                    'txtValue' => 'required',

                ]);

                $customer_block = customer_block::find($id);
                $customer_block->is_blocked = 0;
                $customer_block->remark = $request->input('txtRemark');
                $customer_block->number_of_rders = $request->input('txtnumOfOrders');
                $customer_block->value = $request->input('txtValue');
                $customer_block->release_date = date('Y-m-d');
                $customer_block->customer_remark = $request->input('customer_remark');
                if ($customer_block->update()) {
                    return response()->json(["status" => true, "data" => true]);
                } else {
                    return response()->json(["status" => false, "data" => false]);
                }
            } else {
                $customer_block = customer_block::find($id);
                $customer_block->is_blocked = 1;
                if ($customer_block->update()) {
                    return response()->json(["status" => true, "data" => true]);
                } else {
                    return response()->json(["status" => false, "data" => false]);
                }
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //get customer note and relase details
    public function get_customer_remark($cusid, $blockid)
    {
        try {
            /*  $customer_remark = Customer::select('note')->find($id); */
            $qry = "SELECT customer_blocks.*,customers.note FROM customer_blocks 
           INNER JOIN customers ON customers.customer_id = customer_blocks.customer_id WHERE customers.customer_id = $cusid AND customer_blocks.customer_block_id = $blockid";
            $result = DB::select($qry);
            if ($result) {
                return response()->json($result);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //load block info
    public function CustomerBlockController($id)
    {
        try {
            $data = [];
            //header settings data
            $header_Settings_data = DB::select('SELECT CONCAT(C.customer_name,"-",C.customer_code) as customer_name,C.customer_code,C.credit_amount_alert_limit,C.credit_period_alert_limit,
            C.credit_amount_hold_limit,C.credit_period_hold_limit,C.pd_cheque_limit,C.pd_cheque_max_period,E.employee_name,
            E.credit_amount_alert_limit as e_credit_amount_alert_limit,E.credit_period_alert_limit as e_credit_period_alert_limit,
            E.credit_amount_hold_limit as e_credit_amount_hold_limit,E.credit_period_hold_limit as e_credit_period_hold_limit,
            E.pd_cheque_limit as e_pd_cheque_limit,E.pd_cheque_max_period as e_pd_cheque_max_period,
            (SELECT SUM(DL.amount - DL.paidamount)) as cus_oustanding,(SELECT SUM(DL_rep.amount - DL_rep.paidamount)) as rep_oustanding
            FROM customer_blocks CB INNER JOIN customers C ON CB.customer_id = C.customer_id INNER JOIN employees E ON CB.employee_id = E.employee_id LEFT JOIN debtors_ledgers DL ON CB.customer_id = DL.customer_id
            LEFT JOIN debtors_ledgers DL_rep ON E.employee_id = DL_rep.employee_id
            WHERE CB.customer_id = ' . $id);
            
            if ($header_Settings_data) {
                array_push($data, $header_Settings_data);
            }

            
            //number of blocks
            //$number_of_blocks = DB::select('SELECT COUNT(*) as number_of_blocks FROM customer_blocks WHERE (SELECT customer_blocks.customer_id FROM customer_blocks WHERE customer_blocks.customer_block_id = ' . $id . ') = customer_blocks.customer_id');
            $number_of_blocks = DB::select('SELECT COUNT(*) as number_of_blocks FROM customer_blocks WHERE customer_blocks.customer_id ='.$id);
            
            if ($number_of_blocks) {
                array_push($data, $number_of_blocks);
            }

            //number of returned dchqs
            $number_of_return_cheque = DB::select(
                'SELECT COUNT(*) as no_of_chqs_returned
            FROM customer_receipt_cheques CHQ
            INNER JOIN customer_receipts CR ON CHQ.customer_receipt_id = CR.customer_receipt_id
            LEFT JOIN customer_blocks CB ON CR.customer_id = CB.customer_id
            WHERE CB.customer_id = ' . $id
            );

            if ($number_of_return_cheque) {
                array_push($data, $number_of_return_cheque);
            }

            //Dishonoured cheque (nonpaid)
            /* $nonpaid = DB::select('SELECT IFNULL(SUM(DL.amount - DL.paidamount), 0) AS nonpaid
            FROM debtors_ledgers DL
            INNER JOIN customer_blocks CB ON DL.customer_id = CB.customer_id
            WHERE CB.customer_block_id = '.$id); */

            $nonpaid = DB::select('SELECT IFNULL(SUM(DL.amount - DL.paidamount), 0) AS nonpaid
            FROM debtors_ledgers DL
            INNER JOIN customer_blocks CB ON DL.customer_id = CB.customer_id
            WHERE DL.document_number = 1000 AND (DL.amount - DL.paidamount) > 0 AND CB.customer_id = '.$id);

            if($nonpaid){
                array_push($data,$nonpaid);
            }

            //average last 3 months sales
            $avg = DB::select('SELECT (SUM(SI.total_amount) / 3) AS average_value
            FROM sales_invoices SI LEFT JOIN customer_blocks CB ON SI.customer_id = CB.customer_id
            WHERE SI.order_date_time >= DATE_SUB(CURRENT_DATE, INTERVAL 3 MONTH) AND CB.customer_id = '.$id);

            if($avg){
                array_push($data,$avg);
            }

            //latest details
            $latest_data = DB::select('SELECT 
            MAX(SI.order_date_time) AS latest_order_date_time,
            MAX(CR.receipt_date) AS latest_recpt_date,
            (SELECT external_number 
             FROM customer_receipts 
             WHERE customer_id = SI.customer_id 
             ORDER BY external_number DESC 
             LIMIT 1) AS latest_external_number
        FROM sales_invoices SI
        INNER JOIN customer_blocks CB ON SI.customer_id = CB.customer_id
        INNER JOIN customer_receipts CR ON SI.customer_id = CR.customer_id
        WHERE CB.customer_id ='.$id);
        if($latest_data){
            array_push($data,$latest_data); 
        }

        //outstanding balance
        $cus_outsanding = DB::select('SELECT SUM(DL.amount - DL.paidamount) as cus_oustanding FROM debtors_ledgers DL INNER JOIN customer_blocks CB ON DL.customer_id = CB.customer_id WHERE (DL.amount - DL.paidamount) > 0 AND CB.customer_id = '.$id);
        if($cus_outsanding){
            array_push($data, $cus_outsanding);
        }

        //rep outstanding
        $rep_outstanding = DB::select('SELECT SUM(DL_rep.amount - DL_rep.paidamount) as rep_oustanding FROM debtors_ledgers DL_rep INNER JOIN customer_blocks CB ON DL_rep.employee_id = CB.employee_id WHERE (DL_rep.amount - DL_rep.paidamount) > 0 AND DL_rep.customer_id = CB.customer_id AND CB.customer_id = '.$id);
        if($rep_outstanding){
            array_push($data, $rep_outstanding);
        }


            return response()->json(["data" => $data]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //load block orders to info model
    public function load_block_order_info($id){
        try{
            $qry = "SELECT SO.sales_order_Id,SO.external_number,SO.total_amount,SO.order_date_time,B.branch_name,E.employee_name FROM sales_orders SO INNER JOIN customer_blocks CB ON SO.customer_block_id = CB.customer_block_id INNER JOIN branches B ON SO.branch_id = B.branch_id INNER JOIN employees E ON SO.employee_id = E.employee_id WHERE CB.customer_block_id = $id";
            $result = DB::select($qry);
            if($result){
                return response()->json(["data" => $result]);
            }
        }catch(Exception $ex){
            return $ex;
        }
    }


      //load oustanding data to customer block release and SI customer select.
      public function loadOutstandingDataToTable($id,$br_id){
        try{
            $qry = "SELECT DL.amount,DL.external_number,DL.trans_date,DATEDIFF(CURRENT_DATE,DL.trans_date) AS age 
            FROM debtors_ledgers DL WHERE ((DL.amount - DL.paidamount) > 0) AND DL.customer_id = $id";
            
            if($br_id > 0){
                $qry .= " AND DL.branch_id = $br_id";
            }
            
            $result = DB::select($qry);

            if($result){
                return response()->json(["data" => $result]);
            }else{
                return response()->json(["data" => []]);
            }
        }catch(Exception $ex){
            return $ex;
        }

    }

    
}
