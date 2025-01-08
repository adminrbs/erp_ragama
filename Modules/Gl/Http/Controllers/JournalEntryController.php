<?php

namespace Modules\Gl\Http\Controllers;

use App\Http\Controllers\IntenelNumberController;
use App\Models\GeneralLedger;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Cb\Entities\gl_account;
use Modules\Gl\Entities\JournalEntry;
use Modules\Gl\Entities\JournalEntryItem;
use Modules\Md\Entities\GlAccountAnalysis;

class JournalEntryController extends Controller
{
    public function saveJournal(Request $request)
    {

        try {
            $external_number = $request->get("external_number");
            $branch = $request->get("branch");
            $data = DB::table('branches')->where('branch_id', $branch)->get();
            $EXPLODE_ID = explode("-", $external_number);
            if ($data->count() > 0) {
                $documentPrefix = $data[0]->prefix;
                $external_number  = $documentPrefix . "-" . $EXPLODE_ID[0] . "-" . $EXPLODE_ID[1];
            }
            //dd($external_number);

            $date = $request->get("date");
            $remark = $request->get("remark");
            $created_by = $request->get("created_by");
            $amount =  (float)$request->get("amount") * -1;
            $collection = json_decode($request->get("collection"));

            if (Auth::user()->user_id != null) {
                $created_by = Auth::user()->user_id;
            }

            $approved_by = $request->get("approved_by");
            $approval_status = $request->get("approval_status");

            $journalEntry = new JournalEntry();
            $journalEntry->external_number = $external_number;
            $journalEntry->internal_number = IntenelNumberController::getNextID();
            $journalEntry->document_number = 2800;
            $journalEntry->transaction_date = $date;
            $journalEntry->remark = $remark;
            $journalEntry->branch_id = $branch;
            $journalEntry->created_by = $created_by;
            $journalEntry->approved_by = $approved_by;
            $journalEntry->approval_status = $approval_status;

            if ($journalEntry->save()) {
                $general_ledger = new GeneralLedger();
                $general_ledger->internal_number = $journalEntry->internal_number ?? null;
                $general_ledger->external_number = $journalEntry->external_number ?? null;
                $general_ledger->document_number = $journalEntry->document_number ?? null;
                $general_ledger->transaction_date = $journalEntry->transaction_date ?? null;
                $general_ledger->branch_id = $journalEntry->branch_id ?? null;
                $general_ledger->is_bank_rec = $journalEntry->is_bank_rec ?? 0;
                $general_ledger->bank_rec_date = $journalEntry->bank_rec_date ?? null;
                $general_ledger->created_by = $journalEntry->created_by ?? null;
                $general_ledger->amount = $amount;
                $general_ledger->gl_account_id = $journalEntry->gl_account_id ?? null;
                $general_ledger->paid_amount = $journalEntry->paid_amount ?? 0;
                $general_ledger->gl_account_analyse_id = $journalEntry->gl_account_analyse_id ?? null;
                $general_ledger->description = $journalEntry->description ?? null;
                $general_ledger->save();
                foreach ($collection as $data) {
                    $dt = json_decode($data);
                    $jurnalItem = new JournalEntryItem();
                    $jurnalItem->gl_journal_id = $journalEntry->gl_journal_id;
                    $jurnalItem->gl_account_id = $dt->account_id;
                    $jurnalItem->gl_account_analyse_id = $dt->analysis;
                    if ($dt->narration == 1) {
                        $jurnalItem->amount = ($dt->amount * -1);
                    } else if ($dt->narration == 2) {
                        $jurnalItem->amount = $dt->amount;
                    }
                    $jurnalItem->descriptions = $dt->description;
                    if ($jurnalItem->save()) {
                        $general_ledger = new GeneralLedger();
                        $general_ledger->internal_number = $journalEntry->internal_number ?? null;
                        $general_ledger->external_number = $journalEntry->external_number ?? null;
                        $general_ledger->document_number = $journalEntry->document_number ?? null;
                        $general_ledger->transaction_date = $journalEntry->transaction_date ?? null;
                        $general_ledger->is_bank_rec = $journalEntry->is_bank_rec ?? 0;
                        $general_ledger->bank_rec_date = $journalEntry->bank_rec_date ?? null;
                        $general_ledger->created_by = $journalEntry->created_by ?? null;
                        $general_ledger->amount = null;

                        $general_ledger->branch_id = $journalEntry->branch_id ?? null;
                        $general_ledger->gl_account_id = $jurnalItem->account_id  ?? null;
                        $general_ledger->paid_amount = $jurnalItem->amount  ?? 0;
                        $general_ledger->gl_account_analyse_id = $jurnalItem->gl_account_analyse_id  ?? null;
                        $general_ledger->description = $jurnalItem->descriptions ?? null;
                        $general_ledger->save();
                    }
                }
                return response()->json(["success" => true]);
            }
            return response()->json(["success" => false]);
        } catch (Exception $ex) {
            dd($ex);
            return response()->json(["success" => false]);
        }
    }



    public function updateJournal(Request $request, $id)
    {

        try {
            $external_number = $request->get("external_number");
            $date = $request->get("date");
            $branch = $request->get("branch");
            $remark = $request->get("remark");
            $created_by = $request->get("created_by");
            $amount = (float) $request->get("amount") * -1;
            $collection = json_decode($request->get("collection"));
            $branch_data = DB::table('branches')->where('branch_id', $branch)->get();
            $EXPLODE_ID = explode("-", $external_number);
            if ($branch_data->count() > 0) {
                $documentPrefix = $branch_data[0]->prefix;
                $external_number  = $documentPrefix . "-" . $EXPLODE_ID[1] . "-" . $EXPLODE_ID[2];
            }

            if (Auth::user()->user_id != null) {
                $created_by = Auth::user()->user_id;
            }

            $approved_by = $request->get("approved_by");
            $approval_status = $request->get("approval_status");

            $journalEntry =  JournalEntry::find($id);
            if ($journalEntry) {
                $journalEntry->external_number = $external_number;
                $journalEntry->transaction_date = $date;
                $journalEntry->remark = $remark;
                $journalEntry->branch_id = $branch;
                $journalEntry->created_by = $created_by;
                $journalEntry->approved_by = $approved_by;
                $journalEntry->approval_status = $approval_status;

                if ($journalEntry->update()) {
                    GeneralLedger::where([['internal_number', '=', $journalEntry->internal_number], ['external_number', '=', $journalEntry->external_number], ['document_number', '=', $journalEntry->document_number]])->delete();

                    $general_ledger = new GeneralLedger();
                    $general_ledger->internal_number = $journalEntry->internal_number ?? null;
                    $general_ledger->external_number = $journalEntry->external_number ?? null;
                    $general_ledger->document_number = $journalEntry->document_number ?? null;
                    $general_ledger->transaction_date = $journalEntry->transaction_date ?? null;
                    $general_ledger->branch_id = $journalEntry->branch_id ?? null;
                    $general_ledger->is_bank_rec = $journalEntry->is_bank_rec ?? 0;
                    $general_ledger->bank_rec_date = $journalEntry->bank_rec_date ?? null;
                    $general_ledger->created_by = $journalEntry->created_by ?? null;
                    $general_ledger->amount = $amount;
                    $general_ledger->gl_account_id = $journalEntry->gl_account_id ?? null;
                    $general_ledger->paid_amount = $journalEntry->paid_amount ?? 0;
                    $general_ledger->gl_account_analyse_id = $journalEntry->gl_account_analyse_id ?? null;
                    $general_ledger->description = $journalEntry->description ?? null;
                    $general_ledger->save();


                    JournalEntryItem::where('gl_journal_id', '=', $id)->delete();
                    foreach ($collection as $data) {
                        $dt = json_decode($data);
                        $jurnalItem = new JournalEntryItem();
                        $jurnalItem->gl_journal_id = $journalEntry->gl_journal_id;
                        $jurnalItem->gl_account_id = $dt->account_id;
                        $jurnalItem->gl_account_analyse_id = $dt->analysis;
                        if ($dt->narration == 1) {
                            $jurnalItem->amount = ($dt->amount * -1);
                        } else if ($dt->narration == 2) {
                            $jurnalItem->amount = $dt->amount;
                        }
                        $jurnalItem->descriptions = $dt->description;
                        if ($jurnalItem->save()) {
                            $general_ledger = new GeneralLedger();
                            $general_ledger->internal_number = $journalEntry->internal_number ?? null;
                            $general_ledger->external_number = $journalEntry->external_number ?? null;
                            $general_ledger->document_number = $journalEntry->document_number ?? null;
                            $general_ledger->transaction_date = $journalEntry->transaction_date ?? null;
                            $general_ledger->is_bank_rec = $journalEntry->is_bank_rec ?? 0;
                            $general_ledger->bank_rec_date = $journalEntry->bank_rec_date ?? null;
                            $general_ledger->created_by = $journalEntry->created_by ?? null;
                            $general_ledger->amount = null;

                            $general_ledger->branch_id = $journalEntry->branch_id ?? null;
                            $general_ledger->gl_account_id = $jurnalItem->account_id  ?? null;
                            $general_ledger->paid_amount = $jurnalItem->amount  ?? 0;
                            $general_ledger->gl_account_analyse_id = $jurnalItem->gl_account_analyse_id  ?? null;
                            $general_ledger->description = $jurnalItem->descriptions ?? null;
                            $general_ledger->save();
                        }
                    }
                    return response()->json(["success" => true]);
                }
            }
            return response()->json(["success" => false]);
        } catch (Exception $ex) {
            //dd($ex);
            return response()->json(["success" => false]);
        }
    }



    public function approvalJournal(Request $request, $id)
    {

        try {

            $status = $request->get("status");
            $journalEntry =  JournalEntry::find($id);
            if ($journalEntry) {
                $journalEntry->approval_status = $status;
                $journalEntry->approved_by = Auth::user()->user_id;

                if ($journalEntry->update()) {
                    return response()->json(["success" => true]);
                }
            }
            return response()->json(["success" => false]);
        } catch (Exception $ex) {
            //dd($ex);
            return response()->json(["success" => false]);
        }
    }



    public function deleteJournal($id)
    {

        try {
            $journalEntry =  JournalEntry::find($id);
            if ($journalEntry) {
                if ($journalEntry->delete()) {
                    JournalEntryItem::where('gl_journal_id', '=', $id)->delete();
                    return response()->json(["success" => true]);
                }
            }
            return response()->json(["success" => false]);
        } catch (Exception $ex) {
            //dd($ex);
            return response()->json(["success" => false]);
        }
    }





    public function getJournalEntries()
    {

        try {

            $query = "SELECT journal_entries.gl_journal_id,
            journal_entries.external_number,
            journal_entries.transaction_date,
            journal_entries.remark,
            branches.branch_name,
            employees.employee_name AS created_by,
            journal_entries.approval_status
            FROM journal_entries
            INNER JOIN branches ON journal_entries.branch_id = branches.branch_id
            LEFT JOIN employees ON journal_entries.created_by = employees.employee_id";

            $result = DB::select($query);
            return response()->json(["data" => $result]);
        } catch (Exception $ex) {
            return response()->json(["success" => false]);
        }
    }


    public function getJournalEntry($id)
    {

        $journalEntry = JournalEntry::find($id);
        $journalItems = [];
        if ($journalEntry) {
            $query = "SELECT GL.account_code,
            GL.account_id,
            GL.account_title AS account_name,
            JEI.descriptions,
            IF(JEI.amount < 0,2,1) AS narration,
            ABS(JEI.amount) AS amount,
            JEI.gl_account_analyse_id,
            '' AS analysisTableArray
            FROM journal_entry_items JEI 
            LEFT JOIN gl_accounts GL 
            ON JEI.gl_account_id = GL.account_id 
            WHERE JEI.gl_journal_id = '" . $id . "'";
            $journalItems = DB::select($query);
        }

        foreach ($journalItems as $item) {
            $result = GlAccountAnalysis::where("account_id", "=", $item->account_id)->get();
            $array = array();
            foreach ($result as $res) {
                array_push($array, ["text" => $res->gl_account_analyse_name, "value" => $res->gl_account_analyse_id]);
            }
            $item->analysisTableArray = $array;
        }
        return response()->json(["header" => $journalEntry, "items" => $journalItems]);
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


    public function get_gl_account_name($id)
    {
        $acc_ = gl_account::find($id);
        return response()->json(["data" => $acc_->account_title]);
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
}
