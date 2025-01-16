<?php

namespace Modules\Dl\Http\Controllers;

use App\Http\Controllers\IntenelNumberController;
use App\Models\DebtorsLedger;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Dl\Entities\Customer;
use Modules\Dl\Entities\debit_note;
use Modules\Dl\Entities\DebtorsLedgerSetoff;

class DebitNoteController extends Controller
{
    //save debit note
    public function addDebitNote(Request $request)
    {
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
            



            $debit_note =  new debit_note();
            $debit_note->internal_number = IntenelNumberController::getNextID();
            $debit_note->external_number = $externalNumber;
            $debit_note->branch_id = $bR_id;
            $debit_note->customer_id = $request->input('customerID');
            $debit_note->employee_id = $request->input('sales_rep');
            $debit_note->amount = $request->input('grandTotal');
            $debit_note->trans_date = $request->input('date');
            $debit_note->narration_for_account = $request->input('narration');
            $debit_note->description = $request->input('txtRemarks');
            $debit_note->created_by = Auth::user()->id;
            $debit_note->document_number = 1600;
            
            if ($debit_note->save()) {

                /* $customer = Customer::find($debit_note->customer_id);
                $customer_name = $customer->customer_name;
                $customer_code = $customer->customer_code;

                $debtors_ledger = new DebtorsLedger();
                $debtors_ledger->internal_number = $debit_note->internal_number;
                $debtors_ledger->external_number = $debit_note->external_number;
                $debtors_ledger->document_number = $debit_note->document_number;
                $debtors_ledger->trans_date = $debit_note->trans_date;
                $debtors_ledger->description = "Debit note for " . $customer_name;
                $debtors_ledger->branch_id = $debit_note->branch_id;
                $debtors_ledger->customer_id = $debit_note->customer_id;
                $debtors_ledger->customer_code = $customer_code;
                $debtors_ledger->amount = $debit_note->amount;
                $debtors_ledger->employee_id = $debit_note->employee_id;
                if ($debtors_ledger->save()) {
                    $debtors_ledger_setoff = new DebtorsLedgerSetoff();
                    $debtors_ledger_setoff->internal_number = $debtors_ledger->internal_number;
                    $debtors_ledger_setoff->external_number = $debtors_ledger->external_number;
                    $debtors_ledger_setoff->document_number = $debtors_ledger->document_number;
                    $debtors_ledger_setoff->reference_internal_number = $debtors_ledger->internal_number;
                    $debtors_ledger_setoff->reference_external_number = $debtors_ledger->external_number;
                    $debtors_ledger_setoff->reference_document_number = $debtors_ledger->document_number;
                    $debtors_ledger_setoff->trans_date = $debtors_ledger->trans_date;
                    $debtors_ledger_setoff->description = $debtors_ledger->description;
                    $debtors_ledger_setoff->branch_id = $debtors_ledger->branch_id;
                    $debtors_ledger_setoff->customer_id = $debtors_ledger->customer_id;
                    $debtors_ledger_setoff->customer_code = $debtors_ledger->customer_code;
                    $debtors_ledger_setoff->amount = $debtors_ledger->amount;
                    $debtors_ledger_setoff->save();
                }
 */
                DB::commit();
                return response()->json(["status" => true]);
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return $ex;
        }
    }

    public function approveDebitNote($id){
        try{    
                DB::beginTransaction();
                $debit_note = debit_note::find($id);
                $debit_note->approval_status = 1;
                $debit_note->approved_by = Auth::user()->id;
                $debit_note->update();

                $customer = Customer::find($debit_note->customer_id);
                $customer_name = $customer->customer_name;
                $customer_code = $customer->customer_code;

                $debtors_ledger = new DebtorsLedger();
                $debtors_ledger->internal_number = $debit_note->internal_number;
                $debtors_ledger->external_number = $debit_note->external_number;
                $debtors_ledger->document_number = $debit_note->document_number;
                $debtors_ledger->trans_date = $debit_note->trans_date;
                $debtors_ledger->description = "Debit note for " . $customer_name;
                $debtors_ledger->branch_id = $debit_note->branch_id;
                $debtors_ledger->customer_id = $debit_note->customer_id;
                $debtors_ledger->customer_code = $customer_code;
                $debtors_ledger->amount = $debit_note->amount;
                $debtors_ledger->employee_id = $debit_note->employee_id;
                if ($debtors_ledger->save()) {
                    $debtors_ledger_setoff = new DebtorsLedgerSetoff();
                    $debtors_ledger_setoff->internal_number = $debtors_ledger->internal_number;
                    $debtors_ledger_setoff->external_number = $debtors_ledger->external_number;
                    $debtors_ledger_setoff->document_number = $debtors_ledger->document_number;
                    $debtors_ledger_setoff->reference_internal_number = $debtors_ledger->internal_number;
                    $debtors_ledger_setoff->reference_external_number = $debtors_ledger->external_number;
                    $debtors_ledger_setoff->reference_document_number = $debtors_ledger->document_number;
                    $debtors_ledger_setoff->trans_date = $debtors_ledger->trans_date;
                    $debtors_ledger_setoff->description = $debtors_ledger->description;
                    $debtors_ledger_setoff->branch_id = $debtors_ledger->branch_id;
                    $debtors_ledger_setoff->customer_id = $debtors_ledger->customer_id;
                    $debtors_ledger_setoff->customer_code = $debtors_ledger->customer_code;
                    $debtors_ledger_setoff->amount = $debtors_ledger->amount;
                    $debtors_ledger_setoff->save();
                }

                DB::commit();
                return response()->json(["status" => true]);
        }catch(Exception $ex){
            Db::rollBack();
            return $ex;
        }
    }

    public function rejectDebitNote($id){
        try{    
                DB::beginTransaction();
                $debit_note = debit_note::find($id);
                $debit_note->approval_status = 2;
                $debit_note->approved_by = Auth::user()->id;
                $debit_note->update();

                DB::commit();
                return response()->json(["status" => true]);
        }catch(Exception $ex){
            Db::rollBack();
            return $ex;
        }
    }

    public function reviseDebitNote(Request $request,$id){
        try{    
                DB::beginTransaction();
                $debit_note = debit_note::find($id);
                $debit_note->approval_status = 3;
                $debit_note->approved_by = Auth::user()->id;
                $debit_note->revise_remark = $request->inout('revise_remark');
                $debit_note->update();
                DB::commit();
                return response()->json(["status" => true]);
        }catch(Exception $ex){
            Db::rollBack();
            return $ex;
        }
    }

    //load data to debit note list
    public function get_debit_note_details(Request $request)
    {
        try {
            $pageNumber = ($request->start / $request->length) + 1;
            $pageLength = $request->length;
            $skip = ($pageNumber - 1) * $pageLength;
            $searchValue = request('search.value');


            $query = DB::table('debit_notes')
                ->select(
                    'debit_notes.debit_notes_id',
                    'debit_notes.trans_date',
                    'debit_notes.external_number',
                    'debit_notes.narration_for_account',
                    'branches.branch_name',
                    'users.name',
                    DB::raw("FORMAT(amount, 0) as amount"),
                    DB::raw("SUBSTRING(customers.customer_name, 1, 20) as customer_name"),
                    DB::raw("SUBSTRING(employees.employee_name, 1, 15) as employee_name")

                )
                ->join('customers', 'debit_notes.customer_id', '=', 'customers.customer_id')
                ->join('branches', 'debit_notes.branch_id', '=', 'branches.branch_id')
                ->leftJoin('employees','debit_notes.employee_id','=','employees.employee_id')
                ->leftJoin('users', 'debit_notes.created_by', '=', 'users.id')
                ->orderBy('debit_notes.external_number', 'DESC');

            if (!empty($searchValue)) {

                $query->where(function ($query) use ($searchValue) {
                    $search_amount = str_replace(',', '', $searchValue);
                    $query->where('external_number', 'like', '%' . $searchValue . '%')
                        ->orWhere('trans_date', 'like', '%' . $searchValue . '%')
                        ->orWhere('amount', 'like', '%' . $search_amount . '%')
                        ->orWhere('users.name', 'like', '%' . $searchValue . '%')
                        ->orWhere('customers.customer_name', 'like', '%' . $searchValue . '%');
                });
            }

            $results = $query->take($pageLength)->skip($skip)->get();


            $results->transform(function ($item) {
                $status = "Original";
                //  $disabled = "disabled";
                //   $buttons = '<button class="btn btn-primary btn-sm" id="btnEdit_' . $item->debit_notes_id . '" onclick="btnEdit_(' . $item->sales_order_Id . ', \'' . $status . '\')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>&#160';
                $buttons = '<button class="btn btn-success btn-sm" onclick="view(' . $item->debit_notes_id . ')"><i class="fa fa-eye" aria-hidden="true"></i></button>&#160';
                $buttons .= '<button class="btn btn-secondary btn-sm" onclick="print(' . $item->debit_notes_id . ')"><i class="fa fa-print" aria-hidden="true"></i></button>';

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


    public function getPendings(Request $request)
    {
        try {
            $pageNumber = ($request->start / $request->length) + 1;
            $pageLength = $request->length;
            $skip = ($pageNumber - 1) * $pageLength;
            $searchValue = request('search.value');


            $query = DB::table('debit_notes')
                ->select(
                    'debit_notes.debit_notes_id',
                    'debit_notes.trans_date',
                    'debit_notes.external_number',
                    'debit_notes.narration_for_account',
                    'branches.branch_name',
                    'users.name',
                    'debit_notes.approval_status',
                    DB::raw("FORMAT(amount, 0) as amount"),
                    DB::raw("SUBSTRING(customers.customer_name, 1, 20) as customer_name"),
                    DB::raw("SUBSTRING(employees.employee_name, 1, 15) as employee_name")

                )
                ->join('customers', 'debit_notes.customer_id', '=', 'customers.customer_id')
                ->join('branches', 'debit_notes.branch_id', '=', 'branches.branch_id')
                ->leftJoin('employees','debit_notes.employee_id','=','employees.employee_id')
                ->leftJoin('users', 'debit_notes.created_by', '=', 'users.id')
                ->where('debit_notes.approval_status', '=', 0)
                ->orderBy('debit_notes.external_number', 'DESC');

            if (!empty($searchValue)) {

                $query->where(function ($query) use ($searchValue) {
                    $search_amount = str_replace(',', '', $searchValue);
                    $query->where('external_number', 'like', '%' . $searchValue . '%')
                        ->orWhere('trans_date', 'like', '%' . $searchValue . '%')
                        ->orWhere('amount', 'like', '%' . $search_amount . '%')
                        ->orWhere('users.name', 'like', '%' . $searchValue . '%')
                        ->orWhere('customers.customer_name', 'like', '%' . $searchValue . '%');
                });
            }

            $results = $query->take($pageLength)->skip($skip)->get();


            $results->transform(function ($item) {
                $status = "Original";
                //  $disabled = "disabled";
                //   $buttons = '<button class="btn btn-primary btn-sm" id="btnEdit_' . $item->debit_notes_id . '" onclick="btnEdit_(' . $item->sales_order_Id . ', \'' . $status . '\')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>&#160';
                $buttons = '<button class="btn btn-success btn-sm" onclick="view(' . $item->debit_notes_id . ')"><i class="fa fa-eye" aria-hidden="true"></i></button>&#160';
                $buttons .= '<button class="btn btn-secondary btn-sm" onclick="print(' . $item->debit_notes_id . ')"><i class="fa fa-print" aria-hidden="true"></i></button>';
                $buttons .= '<button class="btn btn-success btn-sm" onclick="approve(' . $item->debit_notes_id . ')"><i class="fa fa-check" aria-hidden="true"></i></button>&#160';
                $buttons .= '<button class="btn btn-danger btn-sm" onclick="reject(' . $item->debit_notes_id . ')"><i class="fa fa-times" aria-hidden="true"></i></button>&#160';
                $buttons .= '<button class="btn btn-secondary btn-sm" onclick="revise(' . $item->debit_notes_id . ')"><i class="fa fa-refresh" aria-hidden="true"></i></button>'; 
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

    //get each debitn note
    public function getEachDebitNote($id)
    {
        try {

            $query = DB::table('debit_notes')
            ->select(
                'debit_notes.debit_notes_id',
                'debit_notes.trans_date',
                'debit_notes.external_number',
                'debit_notes.narration_for_account',
                'debit_notes.description',
                'debit_notes.branch_id',
                'debit_notes.employee_id',
                'debit_notes.customer_id',
                'customers.customer_name',
                'customers.primary_address',
                'customers.customer_code',
                DB::raw("FORMAT(amount, 0) as amount"),
            )
            ->join('customers', 'debit_notes.customer_id', '=', 'customers.customer_id')
            ->join('branches', 'debit_notes.branch_id', '=', 'branches.branch_id')
            ->where('debit_notes.debit_notes_id', '=', $id)
            ->orderBy('debit_notes.external_number', 'DESC');

            $result = $query->first();

            return response()->json(["status" => true,"data"=>$result]);

        } catch (Exception $ex) {
            return $ex;
        }
    }
}
