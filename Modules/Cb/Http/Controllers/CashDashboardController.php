<?php

namespace Modules\Cb\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class CashDashboardController extends Controller
{
    //load data
    public function loadDatatoDashboard()
    {
        try {
            //Rep
            $sfa_cash_qry = DB::select("SELECT 

        SUM(SFA.amount) AS total_cash,
        SUM(IF(DATEDIFF(CURDATE(), SFA.receipt_date)> 3,SFA.amount,0)) AS total_late 
    
    FROM 
        sfa_receipts SFA
				
    WHERE 
        SFA.receipt_status = 0 
        AND SFA.receipt_method_id = 1");


            $sfa_cheque_qry = DB::select("SELECT
	SUM( SFA.amount ) AS total_cheque,
	SUM(
	IF
	( DATEDIFF( CURDATE(), SFA.receipt_date )> 3, SFA.amount, 0 )) AS total_late 
FROM
	sfa_receipts SFA
	
WHERE
	SFA.receipt_status = 0 
	AND SFA.receipt_method_id = 2 
");


            $cash_with_cashier_qry = DB::select("SELECT
	SUM( CBD.amount ) AS total_rep_cash_with_cashier,
	SUM( IF ( DATEDIFF( CURDATE(), CBD.cash_bundle_date ) > 3, CBD.amount, 0 ) ) AS total_late_rep_cash_with_cashier 
FROM
	cash_bundles CB
	INNER JOIN cash_bundles_datas CBD ON CB.cash_bundles_id = CBD.cash_bundles_id 
WHERE
	CB.receipt_created = 0");

            $cheque_with_cashier_qry = DB::select("SELECT
	SUM( amount ) AS total_rep_cheque_with_cashier,
	SUM( IF ( DATEDIFF( CURDATE(), SR.receipt_date ) > 3, amount, 0 ) ) AS total_late_rep_cheque_with_cashier 
FROM
	cheque_collections CC
	INNER JOIN sfa_receipts SR ON CC.cheque_collection_id = SR.cheque_collection_id 
WHERE
	SR.receipt_status = 1 
	AND SR.receipt_method_id = 2");

            /* $direct_cash_with_Cashier_qry = DB::select("SELECT
	SUM( DCBD.amount ) AS total_direct_cash_with_cashier,
	SUM(
	IF
	( DATEDIFF( CURDATE(), DCBD.cash_bundle_date ) > 2, DCBD.amount, 0 )) AS direct_late_cash_with_cashier 
FROM
	direct_cash_bundles DCB
	INNER JOIN direct_cash_bundle_datas DCBD ON DCB.direct_cash_bundle_id = DCBD.direct_cash_bundles_id 
WHERE
	DCB.`status` = 0"); */

    $direct_cash_with_Cashier_qry = DB::select("SELECT
	SUM( DCBD.amount ) AS total_direct_cash_with_cashier,
	SUM(
	IF
	( DATEDIFF( CURDATE(), DCBD.cash_bundle_date ) > 2, DCBD.amount, 0 )) AS direct_late_cash_with_cashier 
FROM
	direct_cash_bundles DCB
	INNER JOIN direct_cash_bundle_datas DCBD ON DCB.direct_cash_bundle_id = DCBD.direct_cash_bundles_id 
WHERE
	DCB.ho_Received = 0");

            $direct_cheque_with_qry = DB::select("SELECT
	SUM( amount ) AS total_direct_cheque,
	SUM(
	IF
	( DATEDIFF( CURDATE(), receipt_date ) > 3, amount, 0 )) AS late_direct_cheque  
FROM
	customer_receipts CR
	INNER JOIN direct_cheque_collections DCC ON CR.cheque_collection_id = DCC.direct_cheque_collection_id 
WHERE
	is_direct_receipt_collected = 0");



            return response()->json(['cash_with_rep' => $sfa_cash_qry, 'cheque_with_rep' => $sfa_cheque_qry, 'cash_with_cashier' => $cash_with_cashier_qry, 'cheque_with_Cashier' => $cheque_with_cashier_qry, "direct_Cash_with_Cashier" => $direct_cash_with_Cashier_qry, "direct_cheque_with_Cashier" => $direct_cheque_with_qry]);
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //get data according to emp
    public function loadDataAccordingToRep($id)
    {
        try {

            if ($id ==  0) {
                $sfa_combined_qry = DB::select("
                SELECT 
                    E.employee_name,
                    IFNULL(SUM(CASE WHEN SFA.receipt_method_id = 1 THEN SFA.amount ELSE 0 END), 0) AS total_cash,
                    IFNULL(SUM(CASE WHEN SFA.receipt_method_id = 1 AND DATEDIFF(CURDATE(), SFA.receipt_date) > 3 THEN SFA.amount ELSE 0 END), 0) AS total_cash_late,
                    IFNULL(SUM(CASE WHEN SFA.receipt_method_id = 2 THEN SFA.amount ELSE 0 END), 0) AS total_cheque,
                    IFNULL(SUM(CASE WHEN SFA.receipt_method_id = 2 AND DATEDIFF(CURDATE(), SFA.receipt_date) > 3 THEN SFA.amount ELSE 0 END), 0) AS total_cheque_late
                FROM 
                    sfa_receipts SFA
                INNER JOIN 
                    employees E ON SFA.collector_id = E.employee_id       
                WHERE 
                    SFA.receipt_status = 0 
                GROUP BY 
                    E.employee_name
            ");
            } else {
                $sfa_combined_qry = DB::select("
                SELECT 
                    E.employee_name,
                    IFNULL(SUM(CASE WHEN SFA.receipt_method_id = 1 THEN SFA.amount ELSE 0 END), 0) AS total_cash,
                    IFNULL(SUM(CASE WHEN SFA.receipt_method_id = 1 AND DATEDIFF(CURDATE(), SFA.receipt_date) > 3 THEN SFA.amount ELSE 0 END), 0) AS total_cash_late,
                    IFNULL(SUM(CASE WHEN SFA.receipt_method_id = 2 THEN SFA.amount ELSE 0 END), 0) AS total_cheque,
                    IFNULL(SUM(CASE WHEN SFA.receipt_method_id = 2 AND DATEDIFF(CURDATE(), SFA.receipt_date) > 3 THEN SFA.amount ELSE 0 END), 0) AS total_cheque_late
                FROM 
                    sfa_receipts SFA
                INNER JOIN 
                    employees E ON SFA.collector_id = E.employee_id       
                WHERE 
                    SFA.receipt_status = 0
                AND
                    SFA.collector_id = $id  
                GROUP BY 
                    E.employee_name
            ");
            }


            return response()->json(['rep_data' => $sfa_combined_qry]);
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function loadEmployeesCashDashBoard(){
        try{
            $qry = "SELECT E.employee_id, E.employee_name FROM employees E WHERE E.desgination_id = 7 OR E.desgination_id = 8";
            $result = DB::select($qry);
            return response()->json(['data'=>$result]);
        }catch(Exception $ex){
            return $ex;
        }
    }
}
