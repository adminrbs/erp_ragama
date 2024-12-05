<?php

namespace Modules\Cb\Http\Controllers;

use App\Http\Controllers\chequeNumberController;
use App\Http\Controllers\CompanyDetailsController;
use App\Http\Controllers\IntenelNumberController;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Cb\Entities\gl_account;
use Modules\Cb\Entities\PatmentVoucherItems;
use Modules\Cb\Entities\Payee;
use Modules\Cb\Entities\PaymentVoucher;
use Modules\Cb\Entities\PaymentVoucherBankSlip;
use Modules\Cb\Entities\PaymentVoucherCheque;
use Modules\Cb\Entities\PaymentVoucherItems;
use Modules\Md\Entities\GlAccountAnalysis;
use Modules\Md\Entities\supplier;

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
                ->select(
                    'gl_accounts.account_id',
                    'gl_accounts.account_title',
                    'gl_accounts.account_code',
                    'gl_account_types.gl_account_type_id'
                )
                ->join(
                    'gl_account_types',
                    'gl_accounts.account_type_id',
                    '=',
                    'gl_account_types.gl_account_type_id'
                )
                ->get();

            $collection = [];
            foreach ($items as $item) {
                array_push($collection, ["hidden_id" => $item->account_id, "id" =>  $item->account_title, "value" =>  $item->account_code, "type_id" => $item->gl_account_type_id, "collection" => [$item->account_id, $item->account_title, $item->account_code]]);
            }
            return response()->json(['success' => true, 'data' => $collection]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function loadAccountAnalysisData($id)
    {
        try {
            if ($id == 0) {
                $analysis = GlAccountAnalysis::all();
                return response()->json(['success' => true, 'data' => $analysis]);
            }
            $analysis = GlAccountAnalysis::where("account_id", "=", $id)->get();
            return response()->json(['success' => true, 'data' => $analysis]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function saveVoucher(Request $request)
    {
        try {

            $collection = json_decode($request->input('collection'));
            $referencenumber = $request->input('LblexternalNumber');
            $bR_id = $request->input('cmbBranch');

            $data = DB::table('branches')->where('branch_id', $bR_id)->get();

            $EXPLODE_ID = explode("-", $referencenumber);
            // dd($referencenumber);
            $externalNumber  = '';
            if ($data->count() > 0) {
                $documentPrefix = $data[0]->prefix;
                $externalNumber  = $documentPrefix . "-" . $EXPLODE_ID[0] . "-" . $EXPLODE_ID[1];
            }
            if ($request->input('cmbPaymentMethod') == 2) {
                if (!chequeNumberController::validateChequeNo($request->input('cmbPaymentMethod'))) {
                    return response()->json(["cheque" => "invalidChq"]);
                }
            }
            $transDate = $request->input('transDate');
            $date = new DateTime($transDate);
            $formattedDate = $date->format('Y-m-d');

            $PaymentVoucher = new PaymentVoucher();
            $PaymentVoucher->internal_number = IntenelNumberController::getNextID();
            $PaymentVoucher->external_number =  $externalNumber;
            $PaymentVoucher->transaction_date = $formattedDate;
            if ($request->input('option') == 1) {
                $PaymentVoucher->payee_id = $request->input('payee');
                if ($request->input('payee') == 1) {
                    $PaymentVoucher->payee_name = $request->input('notApplicablePayee');
                }
            } else {
                $PaymentVoucher->supplier_id = $request->input('supplier');
            }
            $PaymentVoucher->payment_method_id = $request->input('cmbPaymentMethod');
            $PaymentVoucher->branch_id = $request->input('cmbBranch');
            $PaymentVoucher->total_amount = 0;
            $PaymentVoucher->gl_account_id = $request->input('cmbGlAccount');
            $PaymentVoucher->document_number = 2750;
            $PaymentVoucher->remarks = $request->input('remarks');
            $PaymentVoucher->description = $request->input('description');
            $PaymentVoucher->status = 0;
            $total_amount = 0;
            if ($PaymentVoucher->save()) {

                foreach ($collection as $i) {
                    $item = json_decode($i);
                    $PaymentVoucherItems = new PaymentVoucherItems();
                    $PaymentVoucherItems->payment_voucher_id = $PaymentVoucher->payment_voucher_id;
                    $PaymentVoucherItems->internal_number = $PaymentVoucher->internal_number;
                    $PaymentVoucherItems->external_number = $PaymentVoucher->external_number;
                    $PaymentVoucherItems->gl_account_id = $item->account_id;
                    $PaymentVoucherItems->gl_account_analysis_id = $item->analysis;
                    $PaymentVoucherItems->description = $item->description;
                    $PaymentVoucherItems->amount = $item->amount;
                    $PaymentVoucherItems->save();

                    $total_amount += $item->amount;
                }
            }
            $PaymentVoucher->total_amount = $total_amount;
            $PaymentVoucher->update();

            if ($PaymentVoucher->payment_method_id == 2) {
                $this->saveCheque($request->input('single_cheque'), $PaymentVoucher);
            }

            if ($PaymentVoucher->payment_method_id == 7) {
                $this->saveBankSlip($request->input('payment_slip'), $PaymentVoucher);
            }

            return response()->json(['success' => true]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function saveCheque($data, $voucher_obj)
    {
        try {
            $cheque_data = json_decode($data);
            $p_cheque = new PaymentVoucherCheque();
            $p_cheque->payment_voucher_id = $voucher_obj->payment_voucher_id;
            $p_cheque->internal_number = $voucher_obj->internal_number;
            $p_cheque->external_number = $voucher_obj->external_number;
            $p_cheque->cheque_referenceNo = $cheque_data->cheque_referenceNo;
            $p_cheque->cheque_number = $cheque_data->cheque_number;
            //$p_cheque->payment_voucher_cheque_reference_number	
            $p_cheque->bank_code = $cheque_data->bank_code;
            $p_cheque->banking_date = $cheque_data->banking_date;
            $p_cheque->amount = $cheque_data->amount;
            $p_cheque->bank_id = $cheque_data->bank_id;
            $p_cheque->bank_branch_id = $cheque_data->bank_branch_id;
            $p_cheque->cheque_status = 0;
            $p_cheque->cheque_deposit_date = $cheque_data->cheque_deposit_date;
            //$P_cheque->cheque_dishonoured_date = $data->
            $p_cheque->gl_account_id = $voucher_obj->gl_account_id;
            $p_cheque->save();
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function saveBankSlip($data, $voucher_obj)
    {
        try {

            $slip_data = json_decode($data);
            $slip = new PaymentVoucherBankSlip();
            $slip->payment_voucher_id = $voucher_obj->payment_voucher_id;
            $slip->internal_number = $voucher_obj->internal_number;
            $slip->external_number = $voucher_obj->external_number;
            $slip->reference = $slip_data->cheque_referenceNo;
            $slip->slip_time = $slip_data->slip_time;
            $slip->slip_date = $slip_data->slip_date;
            $slip->save();
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function getGRNdata()
    {
        try {
            $qry = "SELECT PV.payment_voucher_id,PV.external_number,PV.transaction_date,PV.total_amount,S.supplier_name,P.payee_name,B.branch_name,PV.payee_name AS not_applicable_payee FROM payment_vouchers PV LEFT JOIN suppliers S ON PV.supplier_id = S.supplier_id LEFT JOIN payees P ON PV.payee_id = P.payee_id LEFT JOIN branches B ON PV.branch_id = B.branch_id";
            $result = DB::select($qry);
            if ($result) {
                return response()->json(['success' => true, 'data' => $result]);
            } else {
                return response()->json(['success' => false, 'data' => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function getEachPaymentVoucher($id)
    {
        try {
            $pv = PaymentVoucher::find($id);
            $s_code = "";
            if ($pv->supplier_id) {
                $supplier = supplier::find($pv->supplier_id);
                $s_code = $supplier->supplier_code;
            }

            // $pv_item = PatmentVoucherItems::where("payment_voucher_id","=",$id)->get();
            $pv_item = DB::select("SELECT GL.account_code,GL.account_title AS account_name,GLA.gl_account_analyse_name,PVI.description,PVI.amount,PVI.gl_account_analysis_id FROM payment_voucher_items PVI LEFT JOIN gl_accounts GL ON PVI.gl_account_id = GL.account_id LEFT JOIN gl_account_analyses GLA ON GL.account_id = GLA.account_id  WHERE PVI.payment_voucher_id = $id");

            $pv_cheques = DB::select("SELECT
	payment_voucher_cheques.*,
	B.bank_name,
	BB.bank_branch_name
		
FROM
	payment_voucher_cheques
INNER JOIN
	banks B ON payment_voucher_cheques.bank_id = B.bank_id
INNER JOIN 
	bank_branches BB ON payment_voucher_cheques.bank_branch_id = payment_voucher_cheques.bank_branch_id

WHERE
	payment_voucher_cheques.payment_voucher_id = $id");
            $pv_slips =  DB::select("SELECT * FROM payment_voucher_bank_slips WHERE payment_voucher_bank_slips.payment_voucher_id = $id");

            if ($pv) {
                return response()->json(['success' => true, 'pv' => $pv, 'pv_item' => $pv_item, 'sup_code' => $s_code, 'pv_cheque' => $pv_cheques, 'pv_slip' => $pv_slips]);
            } else {
                return response()->json(['success' => false, 'data' => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function updateVoucher(Request $request, $id)
    {
        try {
            //dd($request);
            $collection = json_decode($request->input('collection'));

            $PaymentVoucher = PaymentVoucher::find($id);
            // $PaymentVoucher->internal_number = IntenelNumberController::getNextID();
            //  $PaymentVoucher->external_number =  $externalNumber;
            //$PaymentVoucher->transaction_date = Carbon::now();

            $transDate = $request->input('transDate');
            $date = new DateTime($transDate);
            $formattedDate = $date->format('Y-m-d');

            if ($request->input('cmbPaymentMethod') == 2) {
                if (!chequeNumberController::validateChequeNo($request->input('cmbPaymentMethod'))) {
                    return response()->json(["cheque" => "invalidChq"]);
                }
            }
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
            $PaymentVoucher->description = $request->input('description');
            $PaymentVoucher->status = 0;
            $PaymentVoucher->transaction_date = $formattedDate;
            if ($PaymentVoucher->update()) {
                $payment_voucher = PaymentVoucherItems::where("payment_voucher_id", "=", $id)->delete();
                foreach ($collection as $i) {
                    $item = json_decode($i);
                    $PaymentVoucherItems = new PaymentVoucherItems();
                    $PaymentVoucherItems->payment_voucher_id = $PaymentVoucher->payment_voucher_id;
                    $PaymentVoucherItems->internal_number = $PaymentVoucher->internal_number;
                    $PaymentVoucherItems->external_number = $PaymentVoucher->external_number;
                    $PaymentVoucherItems->gl_account_id = $item->account_id;
                    $PaymentVoucherItems->gl_account_analysis_id = $item->analysis;
                    $PaymentVoucherItems->description = $item->description;
                    $PaymentVoucherItems->amount = $item->amount;
                    $PaymentVoucherItems->save();
                }

                $existing_cheque = PaymentVoucherCheque::where("payment_voucher_id", "=", $id)->delete();
                $existing_slip = PaymentVoucherBankSlip::where("payment_voucher_id", "=", $id);
                if ($PaymentVoucher->payment_method_id == 2) {

                    $this->saveCheque($request->input('single_cheque'), $PaymentVoucher);
                }

                if ($PaymentVoucher->payment_method_id == 7) {
                    $this->saveBankSlip($request->input('payment_slip'), $PaymentVoucher);
                }
            }

            return response()->json(['success' => true]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function deleteVOucher($id)
    {
        try {
            $PaymentVoucher = PaymentVoucher::find($id);
            if ($PaymentVoucher) {
                if ($PaymentVoucher->delete()) {
                    $payment_voucher = PaymentVoucherItems::where("payment_voucher_id", "=", $id)->delete();
                    return response()->json(['success' => true]);
                }
            }
            return response()->json(['success' => false]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function paymentVoucher_Receipt($id)
    {
        try {
            $header_qry = DB::select("SELECT PV.external_number,PV.transaction_date, B.branch_name,P.payee_name,S.supplier_name,CPM.customer_payment_method FROM payment_vouchers PV INNER JOIN branches B ON PV.branch_id = B.branch_id LEFT JOIN payees P ON PV.payee_id = P.payee_id LEFT JOIN suppliers S ON PV.supplier_id = S.supplier_id INNER JOIN customer_payment_modes CPM ON PV.payment_method_id = CPM.customer_payment_method_id WHERE PV.payment_voucher_id = $id");
            $item_qry = DB::select("SELECT GL.account_code,PVI.description,PVI.amount FROM payment_voucher_items PVI INNER JOIN gl_accounts GL ON PVI.gl_account_id = GL.account_id WHERE PVI.payment_voucher_id = $id");
            return response()->json([
                'header' => $header_qry,
                'item' => $item_qry,
                'company' => CompanyDetailsController::CompanyName(),
                'adderess' => CompanyDetailsController::CompanyAddress(),
                'phoneNumber' => CompanyDetailsController::CompanyContactDetails(),
            ]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function get_gl_account_name($id)
    {
        $acc_ = gl_account::find($id);
        return response()->json(["data" => $acc_->account_title]);
    }
}
