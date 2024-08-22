<?php

namespace Modules\Sl\Http\Controllers;

use App\Http\Controllers\IntenelNumberController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Md\Entities\Customer;
use Modules\Sl\Entities\creditor_credit_note;
use Modules\Sl\Entities\creditor_debit_note;
use Modules\Sl\Entities\creditor_ledger_setoff;
use Modules\Sl\Entities\creditors_ledger;
use Modules\Sl\Entities\supplier;

class SupplierCreditController extends Controller
{
    //add credit note
    public function addCreditNotesupplier(Request $request){
        try{
            DB::beginTransaction();
            $referencenumber = $request->input('LblexternalNumber');
            $bR_id = $request->input('cmbBranch');

            $data = DB::table('branches')->where('branch_id', $bR_id)->get();

            $EXPLODE_ID = explode("-", $referencenumber);
            $externalNumber  = '';
            if ($data->count() > 0) {
                $documentPrefix = $data[0]->prefix;
                $externalNumber  = $documentPrefix . "-" . $EXPLODE_ID[0] . "-" . $EXPLODE_ID[1];
            }



            $credit_note =  new creditor_credit_note();
            $credit_note->internal_number = IntenelNumberController::getNextID();
            $credit_note->external_number = $externalNumber;
            $credit_note->branch_id = $bR_id;
            $credit_note->supplier_id = $request->input('supplier_id');
            $credit_note->amount = -$request->input('grandTotal');
            $credit_note->trans_date = $request->input('date');
            $credit_note->narration_for_account = $request->input('narration');
            $credit_note->description = $request->input('txtRemarks');
            $credit_note->created_by = Auth::user()->id;
            $credit_note->document_number = 2300;
            if ($credit_note->save()) {

                $supplier = supplier::find($credit_note->supplier_id);
                $sup_name = $supplier->supplier_name;
                $sup_code = $supplier->customer_code;

                $debtors_ledger = new creditors_ledger();
                $debtors_ledger->internal_number = $credit_note->internal_number;
                $debtors_ledger->external_number = $credit_note->external_number;
                $debtors_ledger->document_number = $credit_note->document_number;
                $debtors_ledger->trans_date = $credit_note->trans_date;
                $debtors_ledger->description = "Credit note for " . $sup_name;
                $debtors_ledger->branch_id = $credit_note->branch_id;
                $debtors_ledger->supplier_id = $credit_note->supplier_id;
                $debtors_ledger->supplier_code = $sup_code;
                $debtors_ledger->amount = $credit_note->amount;
                if ($debtors_ledger->save()) {
                    $debtors_ledger_setoff = new creditor_ledger_setoff();
                    $debtors_ledger_setoff->internal_number = $debtors_ledger->internal_number;
                    $debtors_ledger_setoff->external_number = $debtors_ledger->external_number;
                    $debtors_ledger_setoff->document_number = $debtors_ledger->document_number;
                    $debtors_ledger_setoff->reference_internal_number = $debtors_ledger->internal_number;
                    $debtors_ledger_setoff->reference_external_number = $debtors_ledger->external_number;
                    $debtors_ledger_setoff->reference_document_number = $debtors_ledger->document_number;
                    $debtors_ledger_setoff->trans_date = $debtors_ledger->trans_date;
                    $debtors_ledger_setoff->description = $debtors_ledger->description;
                    $debtors_ledger_setoff->supplier_id = $debtors_ledger->supplier_id;
                    $debtors_ledger_setoff->supplier_code = $debtors_ledger->supplier_code;
                    $debtors_ledger_setoff->amount = $debtors_ledger->amount;
                    $debtors_ledger_setoff->save();
                }

                DB::commit();
                return response()->json(["status" => true]);
            }
        }catch(Exception $ex){
            DB::rollBack();
            return $ex;
        }
    }


    //get data to the list
    public function get_credit_note_supplier_details(Request $request)
    {
        try {
            $pageNumber = ($request->start / $request->length) + 1;
            $pageLength = $request->length;
            $skip = ($pageNumber - 1) * $pageLength;
            $searchValue = request('search.value');


            $query = DB::table('creditor_credit_note')
                ->select(
                    'creditor_credit_note.creditor_credit_notes_id',
                    'creditor_credit_note.trans_date',
                    'creditor_credit_note.external_number',
                    'creditor_credit_note.narration_for_account',
                    'branches.branch_name',
                    'users.name',
                    DB::raw("FORMAT(amount, 0) as amount"),
                    DB::raw("SUBSTRING(suppliers.supplier_name, 1, 20) as supplier_name")
                    

                )
                ->join('suppliers', 'creditor_credit_note.supplier_id', '=', 'suppliers.supplier_id')
                ->join('branches', 'creditor_credit_note.branch_id', '=', 'branches.branch_id')
                ->leftJoin('users', 'creditor_credit_note.created_by', '=', 'users.id')
                ->orderBy('creditor_credit_note.external_number', 'DESC');

            if (!empty($searchValue)) {

                $query->where(function ($query) use ($searchValue) {
                    $search_amount = str_replace(',', '', $searchValue);
                    $query->where('external_number', 'like', '%' . $searchValue . '%')
                        ->orWhere('trans_date', 'like', '%' . $searchValue . '%')
                        ->orWhere('amount', 'like', '%' . $search_amount . '%')
                        ->orWhere('users.name', 'like', '%' . $searchValue . '%')
                        ->orWhere('suppliers.supplier_name', 'like', '%' . $searchValue . '%');
                });
            }

            $results = $query->take($pageLength)->skip($skip)->get();


            $results->transform(function ($item) {
              // $status = "Original";
                //  $disabled = "disabled";
                //   $buttons = '<button class="btn btn-primary btn-sm" id="btnEdit_' . $item->debit_notes_id . '" onclick="btnEdit_(' . $item->sales_order_Id . ', \'' . $status . '\')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>&#160';
                $buttons = '<button class="btn btn-success btn-sm" onclick="view(' . $item->creditor_credit_notes_id . ')"><i class="fa fa-eye" aria-hidden="true"></i></button>&#160';
                //  $buttons .= '<button class="btn btn-secondary btn-sm" onclick="salesorderReport(' . $item->debit_notes_id . ')"><i class="fa fa-print" aria-hidden="true"></i></button>';

                $item->buttons = $buttons;
                return $item;
            });

            return response()->json([
                'data' => $results,
                'draw' => request('draw'),
            ]);
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //get each credit note supplier
    public function getEachCreditNote($id){
        try{
            $qry = DB::select("SELECT CRN.external_number,CRN.trans_date,CRN.branch_id,CRN.supplier_id,CRN.amount,CRN.description,CRN.narration_for_account,S.supplier_code,S.supplier_name,S.primary_address FROM creditor_credit_note CRN INNER JOIN suppliers S ON CRN.supplier_id = S.supplier_id WHERE CRN.creditor_credit_notes_id =$id");
            if($qry){
                return response()->json(["data" => $qry]);
            }

        }catch(Exception $ex){
            return $ex;
        }
    }
}
