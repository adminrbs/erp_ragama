<?php

namespace Modules\Dl\Http\Controllers;

use App\Http\Controllers\IntenelNumberController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Dl\Entities\credit_note;
use Modules\Dl\Entities\Customer;
use Modules\Dl\Entities\DebtorsLedger;
use Modules\Dl\Entities\DebtorsLedgerSetoff;
use Modules\Md\Entities\employee;

class CreditNoteController extends Controller
{
    //save credit note
    public function addCreditNote(Request $request)
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



            $credit_note =  new credit_note();
            $credit_note->internal_number = IntenelNumberController::getNextID();
            $credit_note->external_number = $externalNumber;
            $credit_note->branch_id = $bR_id;
            $credit_note->customer_id = $request->input('customerID');
            $credit_note->employee_id = $request->input('sales_rep');
            $credit_note->amount = -$request->input('grandTotal');
            $credit_note->trans_date = $request->input('date');
            $credit_note->narration_for_account = $request->input('narration');
            $credit_note->description = $request->input('txtRemarks');
            $credit_note->created_by = Auth::user()->id;
            $credit_note->document_number = 1700;
            if ($credit_note->save()) {

                /* $customer = Customer::find($credit_note->customer_id);
                $customer_name = $customer->customer_name;
                $customer_code = $customer->customer_code;

                $debtors_ledger = new DebtorsLedger();
                $debtors_ledger->internal_number = $credit_note->internal_number;
                $debtors_ledger->external_number = $credit_note->external_number;
                $debtors_ledger->document_number = $credit_note->document_number;
                $debtors_ledger->trans_date = $credit_note->trans_date;
                $debtors_ledger->description = "Credit note for " . $customer_name;
                $debtors_ledger->branch_id = $credit_note->branch_id;
                $debtors_ledger->customer_id = $credit_note->customer_id;
                $debtors_ledger->customer_code = $customer_code;
                $debtors_ledger->amount = $credit_note->amount;
                $debtors_ledger->employee_id = $credit_note->employee_id;
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

    //approve credit note
    public function approveCreditNote($id){
        try{
            DB::beginTransaction();
            $credit_note =  credit_note::find($id);
            $credit_note->approval_status = 1;
            $credit_note->approved_by = Auth::user()->id;
            $credit_note->update();

                $customer = Customer::find($credit_note->customer_id);
                $customer_name = $customer->customer_name;
                $customer_code = $customer->customer_code;

                $debtors_ledger = new DebtorsLedger();
                $debtors_ledger->internal_number = $credit_note->internal_number;
                $debtors_ledger->external_number = $credit_note->external_number;
                $debtors_ledger->document_number = $credit_note->document_number;
                $debtors_ledger->trans_date = $credit_note->trans_date;
                $debtors_ledger->description = "Credit note for " . $customer_name;
                $debtors_ledger->branch_id = $credit_note->branch_id;
                $debtors_ledger->customer_id = $credit_note->customer_id;
                $debtors_ledger->customer_code = $customer_code;
                $debtors_ledger->amount = $credit_note->amount;
                $debtors_ledger->employee_id = $credit_note->employee_id;
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
            DB::rollBack();
            return $ex;
        }
    }

    public function rejectCreditNote($id){
        try{
            DB::beginTransaction();
            $credit_note =  credit_note::find($id);
            $credit_note->approval_status = 2;
            $credit_note->approved_by = Auth::user()->id;
            $credit_note->update();

            DB::commit();
            return response()->json(["status" => true]);

        }catch(Exception $ex){
            DB::rollBack();
            return $ex;
        }
    }

    public function reviseCreditNote($id){
        try{
            DB::beginTransaction();
            $credit_note =  credit_note::find($id);
            $credit_note->approval_status = 0;
            $credit_note->approved_by = Auth::user()->id;
            $credit_note->update();

                

                DB::commit();
                return response()->json(["status" => true]);

        }catch(Exception $ex){
            DB::rollBack();
            return $ex;
        }
    }

    //load credit note data to list
    public function get_credit_note_details(Request $request)
    {
        try {
            $pageNumber = ($request->start / $request->length) + 1;
            $pageLength = $request->length;
            $skip = ($pageNumber - 1) * $pageLength;
            $searchValue = request('search.value');


            $query = DB::table('credit_notes')
                ->select(
                    'credit_notes.credit_notes_id',
                    'credit_notes.trans_date',
                    'credit_notes.external_number',
                    'credit_notes.narration_for_account',
                    'branches.branch_name',
                    'users.name',
                    'credit_notes.approval_status',
                    DB::raw("FORMAT(ABS(amount), 0) as amount"),
                    DB::raw("SUBSTRING(customers.customer_name, 1, 20) as customer_name"),
                    DB::raw("SUBSTRING(employees.employee_name, 1, 15) as employee_name")

                )
                ->join('customers', 'credit_notes.customer_id', '=', 'customers.customer_id')
                ->join('branches', 'credit_notes.branch_id', '=', 'branches.branch_id')
                ->leftJoin('employees','credit_notes.employee_id','=','employees.employee_id')
                ->leftJoin('users', 'credit_notes.created_by', '=', 'users.id')
                ->orderBy('credit_notes.external_number', 'DESC');

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

                 // Generate status badge
            $statusBadge = match ($item->approval_status) {
                0 => '<span class="badge bg-warning">Pending</span>',
                1 => '<span class="badge bg-success">Approved</span>',
                2 => '<span class="badge bg-danger">Rejected</span>',
                3 => '<span class="badge bg-secondary">Revised</span>',
                default => '<span class="badge bg-light">Unknown</span>',
            };

            // Append status badge
            $item->status_badge = $statusBadge;
                // $status = "Original";
                //  $disabled = "disabled";
                //   $buttons = '<button class="btn btn-primary btn-sm" id="btnEdit_' . $item->debit_notes_id . '" onclick="btnEdit_(' . $item->sales_order_Id . ', \'' . $status . '\')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>&#160';
                $buttons = '<button class="btn btn-success btn-sm" onclick="view(' . $item->credit_notes_id . ')"><i class="fa fa-eye" aria-hidden="true"></i></button>&#160';
                $buttons .= '<button class="btn btn-secondary btn-sm" onclick="print(' . $item->credit_notes_id . ')"><i class="fa fa-print" aria-hidden="true"></i></button>';

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


    public function get_credit_note_pending_details(Request $request)
    {
        try {
            $status = 0;
            $pageNumber = ($request->start / $request->length) + 1;
            $pageLength = $request->length;
            $skip = ($pageNumber - 1) * $pageLength;
            $searchValue = request('search.value');


            $query = DB::table('credit_notes')
                ->select(
                    'credit_notes.credit_notes_id',
                    'credit_notes.trans_date',
                    'credit_notes.external_number',
                    'credit_notes.narration_for_account',
                    'branches.branch_name',
                    'users.name',
                    DB::raw("FORMAT(ABS(amount), 0) as amount"),
                    DB::raw("SUBSTRING(customers.customer_name, 1, 20) as customer_name"),
                    DB::raw("SUBSTRING(employees.employee_name, 1, 15) as employee_name")

                )
                ->join('customers', 'credit_notes.customer_id', '=', 'customers.customer_id')
                ->join('branches', 'credit_notes.branch_id', '=', 'branches.branch_id')
                ->leftJoin('employees','credit_notes.employee_id','=','employees.employee_id')
                ->leftJoin('users', 'credit_notes.created_by', '=', 'users.id')
                ->where('credit_notes.approval_status', $status)
                ->orderBy('credit_notes.external_number', 'DESC');

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
                // $status = "Original";
                //  $disabled = "disabled";
                //   $buttons = '<button class="btn btn-primary btn-sm" id="btnEdit_' . $item->debit_notes_id . '" onclick="btnEdit_(' . $item->sales_order_Id . ', \'' . $status . '\')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>&#160';
                $buttons = '<button class="btn btn-success btn-sm" onclick="view(' . $item->credit_notes_id . ')"><i class="fa fa-eye" aria-hidden="true"></i></button>&#160';
                  $buttons .= '<button class="btn btn-secondary btn-sm" onclick="print(' . $item->credit_notes_id . ')"><i class="fa fa-print" aria-hidden="true"></i></button>&#160';
                  $buttons .= '<button class="btn btn-success btn-sm" onclick="approve(' . $item->credit_notes_id . ')"><i class="fa fa-check" aria-hidden="true"></i></button>&#160';
                  $buttons .= '<button class="btn btn-danger btn-sm" onclick="reject(' . $item->credit_notes_id . ')"><i class="fa fa-times" aria-hidden="true"></i></button>&#160';
                 /*  $buttons .= '<button class="btn btn-secondary btn-sm" onclick="revise(' . $item->credit_notes_id . ')"><i class="fa fa-refresh" aria-hidden="true"></i></button>'; */

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


    //get each credit note
    public function getEachcreditNote($id)
    {
        try {
            $query = DB::table('credit_notes')
                ->select(
                    'credit_notes.credit_notes_id',
                    'credit_notes.trans_date',
                    'credit_notes.external_number',
                    'credit_notes.narration_for_account',
                    'credit_notes.description',
                    'credit_notes.employee_id',
                    'credit_notes.branch_id',
                    'credit_notes.customer_id',
                    'customers.customer_name',
                    'customers.primary_address',
                    'customers.customer_code',
                    DB::raw("FORMAT(ABS(amount), 0) as amount"),
                )
                ->join('customers', 'credit_notes.customer_id', '=', 'customers.customer_id')
                ->join('branches', 'credit_notes.branch_id', '=', 'branches.branch_id')
                ->where('credit_notes.credit_notes_id', '=', $id)
                ->orderBy('credit_notes.external_number', 'DESC');

            $result = $query->first();

            return response()->json(["status" => true, "data" => $result]);
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function getSalesRep()
    {
        try {
            $employees =  employee::where('desgination_id','=','7')->get();
            return response()->json(["status" => true, "data" => $employees]);
        } catch (Exception $ex) {
            return response()->json(["status" => false, "exception" => $ex]);
        }
    }
}
