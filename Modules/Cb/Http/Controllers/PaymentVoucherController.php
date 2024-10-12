<?php

namespace Modules\Cb\Http\Controllers;

use App\Http\Controllers\IntenelNumberController;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Cb\Entities\PatmentVoucherItems;
use Modules\Cb\Entities\Payee;
use Modules\Cb\Entities\PaymentVoucher;
use Modules\Md\Entities\GlAccountAnalysis;

class PaymentVoucherController extends Controller
{
    public function loadPayee()
    {
        $payee = Payee::all();
        if ($payee) {

            return response()->json(["status" => true, "data" => $payee]);
        } else {
            return response()->json(["status" => false, "data" => []]);
        }
    }


    public function loadAccounts()
    {
        $val = 1;
        try {

            $items = DB::table('gl_accounts')
                ->select('account_id', 'account_title', 'account_code')
                ->get();
            $collection = [];
            foreach ($items as $item) {
                array_push($collection, ["hidden_id" => $item->account_id, "id" =>  $item->account_title, "value" =>  $item->account_code, "collection" => [$item->account_id, $item->account_title, $item->account_code]]);
            }
            return response()->json(['success' => true, 'data' => $collection]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function loadAccountAnalysisData()
    {
        try {

            $analysis = GlAccountAnalysis::all();
            return response()->json(['success' => true, 'data' => $analysis]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function saveVoucher(Request $request)
    {
        try {
            //dd($request);
            $collection = json_decode($request->input('collection'));
            $referencenumber = $request->input('LblexternalNumber');
            $bR_id = $request->input('cmbBranch');

            $data = DB::table('branches')->where('branch_id', $bR_id)->get();

            $EXPLODE_ID = explode("-", $referencenumber);
            $externalNumber  = '';
            if ($data->count() > 0) {
                $documentPrefix = $data[0]->prefix;
                $externalNumber  = $documentPrefix . "-" . $EXPLODE_ID[0] . "-" . $EXPLODE_ID[1];
            }

            $PaymentVoucher = new PaymentVoucher();
            $PaymentVoucher->internal_number = IntenelNumberController::getNextID();
            $PaymentVoucher->external_number =  $externalNumber;
            $PaymentVoucher->transaction_date = Carbon::now();
            if ($request->input('option') == 1) {
                $PaymentVoucher->payee_id = $request->input('payee');
            } else {
                $PaymentVoucher->supplier_id = $request->input('supplier');
            }
            $PaymentVoucher->payment_method_id = $request->input('cmbPaymentMethod');
            $PaymentVoucher->branch_id = $request->input('cmbBranch');
            $PaymentVoucher->total_amount = 0;
            $PaymentVoucher->gl_account_id = $request->input('cmbGlAccount');
            $PaymentVoucher->document_number = 2750;
            $PaymentVoucher->remarks = $request->input('remarks');
            $PaymentVoucher->status = 0;
            if ($PaymentVoucher->save()) {

                foreach ($collection as $i) {
                    $item = json_decode($i);
                    $PaymentVoucherItems = new PatmentVoucherItems();
                    $PaymentVoucherItems->payment_voucher_id = $PaymentVoucher->payment_voucher_id;
                    $PaymentVoucherItems->internal_number = $PaymentVoucher->internal_number;
                    $PaymentVoucherItems->external_number = $PaymentVoucher->external_number;
                    $PaymentVoucherItems->gl_account_id = $item->account_id;
                    $PaymentVoucherItems->gl_account_analysis_id = $item->analysis;
                    $PaymentVoucherItems->description = $item->description;
                    $PaymentVoucherItems->amount = $item->amount;
                    $PaymentVoucherItems->save();
                }
            }

            return response()->json(['success' => true]);
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function getGRNdata(){
        try{
            $qry = "SELECT PV.payment_voucher_id,PV.external_number,PV.transaction_date,PV.total_amount,S.supplier_name,P.payee_name,B.branch_name FROM payment_vouchers PV LEFT JOIN suppliers S ON PV.supplier_id = S.supplier_id LEFT JOIN payees P ON PV.payee_id = P.payee_id LEFT JOIN branches B ON PV.branch_id = b.branch_id";
            $result = DB::select($qry);
            if($result){
                return response()->json(['success' => true,'data' => $result]);
            }else{
                return response()->json(['success' => false,'data' => []]);
            }
        }catch(Exception $ex){
            return $ex;
        }
    }
}
