<?php

namespace Modules\Sd\Http\Controllers;

use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Sd\Entities\sales_Invoice;
use Modules\Sd\Entities\sales_invoice_tracking_inquery;
use Modules\Sd\Entities\sales_invoice_tracking_inquery_data;

class InvoiceTrackingController extends Controller
{
    public function load_invoices_for_invoice_tracking(Request $request)
    {
        try {

            $from_date = date('Y-m-d', strtotime(str_replace('/', '-', $request->input('from'))));
            $to_date = date('Y-m-d', strtotime(str_replace('/', '-', $request->input('to'))));
            $filter_by_id = $request->input('filter_by_id');

            $qry = 'SELECT
            SI.sales_invoice_Id,
            SI.manual_number AS external_number,
            SI.order_date_time,
            SI.total_amount,
            SI.is_inquery_created,
            CONCAT( C.customer_code, "-", C.customer_name ) AS customer_name,
            R.route_name,
            T.townName,
            E.employee_name,
            (DL.amount - DL.paidamount) AS balance
            FROM
            sales_invoices SI
            INNER JOIN customers C ON SI.customer_id = C.customer_id
            INNER JOIN routes R ON C.route_id = R.route_id
            INNER JOIN town_non_administratives T ON C.town = T.town_id
            INNER JOIN employees E ON SI.employee_id = E.employee_id
            INNER JOIN debtors_ledgers DL ON SI.external_number = DL.external_number';

            if ($filter_by_id == 1) { //office in
                $qry .= ' LEFT JOIN deliveryconfirmations DC ON SI.sales_invoice_Id = DC.sales_invoice_Id
    WHERE (SI.order_date_time BETWEEN "' . $from_date . '" AND "' . $to_date . '") AND SI.sales_invoice_Id IN (SELECT sales_invoice_Id FROM deliveryconfirmations) AND (DL.amount - DL.paidamount) > 0 ORDER BY sales_invoice_Id DESC';
            } else if ($filter_by_id == 2) { // not in
                $qry .= ' LEFT JOIN deliveryconfirmations DC ON SI.sales_invoice_Id = DC.sales_invoice_Id WHERE 
    (SI.order_date_time BETWEEN "' . $from_date . '" AND "' . $to_date . '") AND DC.sales_invoice_Id IS NULL AND (DL.amount - DL.paidamount) > 0 ORDER BY sales_invoice_Id DESC';
            } else { // All
                $qry .= ' WHERE (DL.amount - DL.paidamount) > 0 AND SI.order_date_time BETWEEN "' . $from_date . '" AND "' . $to_date . '" ORDER BY sales_invoice_Id DESC';
            }
            // dd($qry);
            $result = DB::select($qry);

            return response()->json((['success' => 'Data loaded', 'data' => $result]));
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //create inquery
    public function create_inquery($id)
    {
        try {
           // DB::beginTransaction();
            $inqeury = sales_invoice_tracking_inquery::where("sales_invoice_Id", "=", $id)->get();
            $new_inq = new sales_invoice_tracking_inquery();
            $new_inq->sales_invoice_Id = $id;
            $new_inq->created_by = Auth::user()->id;
            $new_inq->inqeury_start_date = Carbon::now();
            if ($new_inq->save()) {
                $si = sales_Invoice::find($id);
                $si->is_inquery_created = 1;
               // dd($si);
                if($si->update()){
                    
                    return response()->json(['status' => true, 'msg' => 'success', 'inq_id' => $new_inq->sales_invoice_tracking_inquery_id]);
                }else{
                    //DB::commit();
                    return response()->json(['status' => false, 'msg' => 'failed']);
                }

                
            } else {
               // DB::commit();
                return response()->json(['status' => false, 'msg' => 'failed']);
            }
        } catch (Exception $ex) {
          //  DB::rollBack();
            return $ex;
        }
    }

    //load pending inquery 
    public function load_pending_inqueries(Request $request)
    {
        try {
            $from_date = date('Y-m-d', strtotime(str_replace('/', '-', $request->input('from'))));
            $to_date = date('Y-m-d', strtotime(str_replace('/', '-', $request->input('to'))));

            $qry = 'SELECT
            INQ.sales_invoice_tracking_inquery_id,
            SI.sales_invoice_Id,
            SI.manual_number AS external_number,
            SI.order_date_time,
            SI.total_amount,
            CONCAT( C.customer_code, "-", C.customer_name ) AS customer_name,
            R.route_name,
            T.townName,
            E.employee_name,
            (DL.amount - DL.paidamount) AS balance,
            U.`name`
            
            FROM sales_invoice_tracking_inqueries INQ
            INNER JOIN sales_invoices SI ON INQ.sales_invoice_Id = SI.sales_invoice_Id
            INNER JOIN customers C ON SI.customer_id = C.customer_id
            INNER JOIN routes R ON C.route_id = R.route_id
            INNER JOIN town_non_administratives T ON C.town = T.town_id
            INNER JOIN employees E ON SI.employee_id = E.employee_id
            INNER JOIN debtors_ledgers DL ON SI.external_number = DL.external_number
            INNER JOIN users U ON INQ.created_by = U.id WHERE (DL.amount - DL.paidamount) > 0 AND INQ.inqeury_start_date BETWEEN "' . $from_date . '" AND "' . $to_date . '" ORDER BY sales_invoice_tracking_inquery_id DESC';
            $result = DB::select($qry);
            if($result){
                return response()->json((['success' => 'Data loaded', 'data' => $result]));
            }else{
                return response()->json((['success' => 'Data not loaded', 'data' => []]));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }



    //save statment
    public function create_inquery_statment(Request $request, $id){
        try{
                DB::beginTransaction();
                $tracking_data = new sales_invoice_tracking_inquery_data();
                $tracking_data->sales_invoice_tracking_inquery_id = $id;
                $tracking_data->inquery_person_id = $request->input('empID');
                $tracking_data->inquery_person_statment = $request->input('statment');
                if($tracking_data->save()){

                    return response()->json((['status'=>true]));

                }else{
                    DB::commit();
                    return response()->json(['status'=>false]);
                }

        }catch(Exception $ex){
            DB::rollBack();
            return $ex;
        }
    }

    //load statment
    public function load_statment($id){
        try{
            $results = DB::table('sales_invoice_tracking_inquery_datas AS S')
            ->select('S.created_at', 'S.inquery_person_statment', 'E.employee_name')
            ->join('employees AS E', 'S.inquery_person_id', '=', 'E.employee_id')
            ->where('S.sales_invoice_tracking_inquery_id', $id)
            ->orderByDesc('S.sales_invoice_tracking_inquery_id') // Ordering by 'created_at' column in descending order
            ->get();



        if($results){
            return response()->json(["data"=>$results]);
        }else{
            return response()->json(["data"=>[]]);
        }
        }catch(Exception $ex){
            return $ex;
        }
    }

    //load statments with sales inv
    public function load_statments_with_inv($id){
        try{
            $results = DB::table('sales_invoice_tracking_inquery_datas AS S')
            ->select('S.created_at', 'S.inquery_person_statment', 'E.employee_name')
            ->join('sales_invoice_tracking_inqueries','S.sales_invoice_tracking_inquery_id','sales_invoice_tracking_inqueries.sales_invoice_tracking_inquery_id')
            ->join('employees AS E', 'S.inquery_person_id', '=', 'E.employee_id')
            ->where('S.sales_invoice_tracking_inquery_id','=',$id)
            ->orderByDesc('S.created_at') 
            ->get();



        if($results){
            return response()->json(["data"=>$results]);
        }else{
            return response()->json(["data"=>[]]);
        }
        }catch(Exception $ex){
            return $ex;
        }
    }
}
