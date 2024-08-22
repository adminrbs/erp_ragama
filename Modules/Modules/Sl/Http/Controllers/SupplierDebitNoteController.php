<?php

namespace Modules\Sl\Http\Controllers;

use App\Http\Controllers\IntenelNumberController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Sl\Entities\creditor_debit_note;
use Modules\Sl\Entities\creditor_ledger_setoff;
use Modules\Sl\Entities\creditors_ledger;
use Modules\Sl\Entities\supplier;

class SupplierDebitNoteController extends Controller
{
   //savedebit note
   public function addDebitNotesupplier(Request $request){
    try {
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
        



        $debit_note =  new creditor_debit_note();
        $debit_note->internal_number = IntenelNumberController::getNextID();
        $debit_note->external_number = $externalNumber;
        $debit_note->branch_id = $bR_id;
        $debit_note->supplier_id = $request->input('supplier_id');
        $debit_note->employee_id = $request->input('sales_rep');
        $debit_note->amount = $request->input('grandTotal');
        $debit_note->trans_date = $request->input('date');
        $debit_note->narration_for_account = $request->input('narration');
        $debit_note->description = $request->input('txtRemarks');
        $debit_note->created_by = Auth::user()->id;
        $debit_note->document_number = 2200;
        
        if ($debit_note->save()) {

            $supplier = supplier::find($debit_note->supplier_id);
            $supplier_name = $supplier->supplier_name;
            $supplier_code = $supplier->supplier_code;

            $debtors_ledger = new creditors_ledger();
            $debtors_ledger->internal_number = $debit_note->internal_number;
            $debtors_ledger->external_number = $debit_note->external_number;
            $debtors_ledger->document_number = $debit_note->document_number;
            $debtors_ledger->trans_date = $debit_note->trans_date;
            $debtors_ledger->description = "Debit note for " . $supplier_name;
            $debtors_ledger->branch_id = $debit_note->branch_id;
            $debtors_ledger->supplier_id = $debit_note->supplier_id;
            $debtors_ledger->supplier_code = $supplier_code;
            $debtors_ledger->amount = $debit_note->amount;
           // $debtors_ledger->employee_id = $debit_note->employee_id;
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
                $debtors_ledger_setoff->branch_id = $debtors_ledger->branch_id;
                $debtors_ledger_setoff->supplier_id = $debtors_ledger->supplier_id;
                $debtors_ledger_setoff->supplier_code = $debtors_ledger->supplier_code;
                $debtors_ledger_setoff->amount = $debtors_ledger->amount;
                $debtors_ledger_setoff->save();
            }

            DB::commit();
            return response()->json(["status" => true]);
        }
    } catch (Exception $ex) {
        DB::rollBack();
        return $ex;
    }
   }

    //load data to debit note list
    public function get_debit_note_supplier_details(Request $request)
    {
        try {
            $pageNumber = ($request->start / $request->length) + 1;
            $pageLength = $request->length;
            $skip = ($pageNumber - 1) * $pageLength;
            $searchValue = request('search.value');


            $query = DB::table('creditor_debit_notes')
                ->select(
                    'creditor_debit_notes.creditor_debit_notes_id',
                    'creditor_debit_notes.trans_date',
                    'creditor_debit_notes.external_number',
                    'creditor_debit_notes.narration_for_account',
                    'branches.branch_name',
                    'users.name',
                    DB::raw("FORMAT(amount, 0) as amount"),
                    DB::raw("SUBSTRING(suppliers.supplier_name, 1, 20) as supplier_name")
                    

                )
                ->join('suppliers', 'creditor_debit_notes.supplier_id', '=', 'suppliers.supplier_id')
                ->join('branches', 'creditor_debit_notes.branch_id', '=', 'branches.branch_id')
                ->leftJoin('employees','creditor_debit_notes.employee_id','=','employees.employee_id')
                ->leftJoin('users', 'creditor_debit_notes.created_by', '=', 'users.id')
                ->orderBy('creditor_debit_notes.external_number', 'DESC');

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
                $buttons = '<button class="btn btn-success btn-sm" onclick="view(' . $item->creditor_debit_notes_id . ')"><i class="fa fa-eye" aria-hidden="true"></i></button>&#160';
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


    public function getEachsupplierDebitNote($id){
        try{
            $query = DB::table('creditor_debit_notes')
            ->select(
                'creditor_debit_notes.creditor_debit_notes_id',
                'creditor_debit_notes.trans_date',
                'creditor_debit_notes.external_number',
                'creditor_debit_notes.narration_for_account',
                'creditor_debit_notes.description',
                'creditor_debit_notes.branch_id',
                
                'creditor_debit_notes.supplier_id',
                'suppliers.supplier_name',
                'suppliers.primary_address',
                'suppliers.supplier_code',
                DB::raw("FORMAT(amount, 0) as amount"),
            )
            ->join('suppliers', 'creditor_debit_notes.supplier_id', '=', 'suppliers.supplier_id')
            ->join('branches', 'creditor_debit_notes.branch_id', '=', 'branches.branch_id')
            ->where('creditor_debit_notes.creditor_debit_notes_id', '=', $id)
            ->orderBy('creditor_debit_notes.external_number', 'DESC');

            $result = $query->first();

            return response()->json(["status" => true,"data"=>$result]);
        }catch(Exception $ex){
            return $ex;
        }
    }
}
