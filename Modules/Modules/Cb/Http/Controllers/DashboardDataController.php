<?php

namespace Modules\Cb\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class DashboardDataController extends Controller
{
   //load cash data
   public function load_cash_with_sales_rep(){
    try{
        
        $cash_in_hand = DB::select('SELECT 
        E.employee_name,
        E.employee_id,
        SUM(SFA.amount) AS total_cash,
        SUM(IF(DATEDIFF(CURDATE(), SFA.created_at)> 3,SFA.amount,0)) AS total_late 
    
    FROM 
        employees E
    LEFT JOIN 
        sfa_receipts SFA ON SFA.collector_id = E.employee_id
    WHERE 
        SFA.receipt_status = 0 
        AND SFA.receipt_method_id = 1
    GROUP BY 
        E.employee_id;');

        return response()->json(["cih" => $cash_in_hand]);

    }catch(Exception $ex){
        return $ex;
    }
   }


   //load cash with rep details wise
   public function load_cash_with_rep_data($id){
        try{
            $late = DB::select('SELECT E.employee_name, E.employee_id, SFA.amount, SFA.external_number, SFA.created_at, C.customer_name, DATEDIFF(CURDATE(), SFA.created_at) AS age FROM sfa_receipts SFA INNER JOIN employees E ON SFA.collector_id = E.employee_id INNER JOIN customers C ON SFA.customer_id = C.customer_id WHERE SFA.receipt_status = 0 AND SFA.receipt_method_id = 1 AND (DATEDIFF(CURDATE(), SFA.created_at) > 3) AND collector_id = "'.$id.'"');

            $total = DB::select('SELECT E.employee_name,E.employee_id,SFA.amount, SFA.external_number,SFA.created_at,C.customer_name FROM sfa_receipts SFA INNER JOIN employees E ON SFA.collector_id = E.employee_id INNER JOIN customers C ON SFA.customer_id = C.customer_id WHERE SFA.receipt_status = 0 AND SFA.receipt_method_id = 1 AND collector_id = '.$id);

            return response()->json(["late" => $late, "total" => $total]);
        }catch(Exception $ex){
            return $ex;
        }
   }


   //load check with rep
   public function load_cheque_with_sales_rep(){
    try{
        
        $cheque_in_hand = DB::select('SELECT 
        E.employee_name,
        E.employee_id,
        SUM(SFA.amount) AS total_cheque,
        SUM(IF(DATEDIFF(CURDATE(), SFA.created_at)> 3,SFA.amount,0)) AS total_late 
    
    FROM 
        employees E
    LEFT JOIN 
        sfa_receipts SFA ON SFA.collector_id = E.employee_id
    WHERE 
        SFA.receipt_status = 0 
        AND SFA.receipt_method_id = 2
    GROUP BY 
        E.employee_id;');

        return response()->json(["cheque" => $cheque_in_hand]);

    }catch(Exception $ex){
        return $ex;
    }

   }

   public function load_cheque_with_rep_data($id){
    try{
        $late = DB::select('SELECT 
        E.employee_name, 
        E.employee_id, 
        SFA.amount, 
        SFA.external_number, 
        DATE(SFA.created_at) AS created_at, 
        C.customer_name, 
        DATEDIFF(CURDATE(), SFA.created_at) AS age,
        SFAC.cheque_number,
        B.bank_code,
        BB.bank_branch_code
    FROM 
        sfa_receipts SFA 
    INNER JOIN 
        employees E ON SFA.collector_id = E.employee_id 
    INNER JOIN 
        customers C ON SFA.customer_id = C.customer_id 
    INNER JOIN 
        sfa_receipt_cheques SFAC ON SFA.customer_receipt_id = SFAC.customer_receipt_id 
    INNER JOIN 
        banks B ON SFAC.bank_id = B.bank_id 
    INNER JOIN 
        bank_branches BB ON SFAC.bank_branch_id = BB.bank_branch_id 
    WHERE 
        SFA.receipt_status = 0 
        AND SFA.receipt_method_id = 2 
        AND (DATEDIFF(CURDATE(), SFA.created_at) > 3) 
        AND collector_id = "'.$id.'" 
    ');

        $total = DB::select('SELECT 
        E.employee_name,
        E.employee_id,
        SFA.amount,
        SFA.external_number,
        DATE(SFA.created_at) AS created_at,
        C.customer_name,
        SFAC.cheque_number,
        B.bank_code,
        BB.bank_branch_code
    FROM 
        sfa_receipts SFA
    INNER JOIN 
        employees E ON SFA.collector_id = E.employee_id
    INNER JOIN 
        customers C ON SFA.customer_id = C.customer_id
    INNER JOIN 
        sfa_receipt_cheques SFAC ON SFA.customer_receipt_id = SFAC.customer_receipt_id
    INNER JOIN 
        banks B ON SFAC.bank_id = B.bank_id
    INNER JOIN 
        bank_branches BB ON SFAC.bank_branch_id = BB.bank_branch_id
    WHERE 
        SFA.receipt_status = 0 
        AND SFA.receipt_method_id = 2 
        AND collector_id = '.$id.'
    ');

        return response()->json(["late" => $late, "total" => $total]);
    }catch(Exception $ex){
        return $ex;
    }

   }
}
