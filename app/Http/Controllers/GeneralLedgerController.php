<?php

namespace App\Http\Controllers;

use App\Models\GeneralLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GeneralLedgerController extends Controller
{
    //save GL
    public static function saveGL($header, $item, $type)
    {
        try {
            $gl = new GeneralLedger();
            $gl->internal_number = $header->internal_number;
            $gl->external_number = $header->external_number;
            $gl->document_number = $header->document_number;
            $gl->transaction_date = $header->transaction_date;
            $gl->branch_id = $header->branch_id;

            if ($type == "item") {
                $gl->gl_account_id = $item->gl_account_id;
                $gl->description = $item->description;
                $gl->paid_amount = $item->amount;
                $gl->gl_account_analyse_id = $item->gl_account_analysis_id;
            } else {
                $gl->gl_account_id = $header->gl_account_id;
                $gl->description = $header->description;

                if ($header->total_amount) {
                    $gl->amount = -$header->total_amount;
                } else if ($header->invoice_amount) {
                    $gl->amount = -$header->invoice_amount;
                }
            }

            $gl->save();
        } catch (\Exception $e) {
            // Handle the exception, you can log it or return a response
           // \Log::error('Error saving General Ledger: ' . $e->getMessage());
            return response()->json(['error' => 'Error saving General Ledger'], 500);
        }
    }

    //update GL
   /*  public static function updateGL($header, $item, $type, $recordIds = [])
{
    try {
        $query = GeneralLedger::where('internal_number', $header->internal_number);

        
        if (!empty($recordIds)) {
            $query->whereIn('id', $recordIds);
        }

        $glRecords = $query->get();

        if ($glRecords->isEmpty()) {
            return response()->json(['error' => 'No records found for update'], 404);
        }

        DB::beginTransaction();

        foreach ($glRecords as $gl) {
            $gl->internal_number = $header->internal_number;
            $gl->external_number = $header->external_number;
            $gl->document_number = $header->document_number;
            $gl->transaction_date = $header->transaction_date;
            $gl->branch_id = $header->branch_id;

            if ($type == "item") {
                $gl->gl_account_id = $item->gl_account_id;
                $gl->description = $item->description;
                $gl->paid_amount = $item->amount;
                $gl->gl_account_analyse_id = $item->gl_account_analysis_id;
            } else {
                $gl->gl_account_id = $header->gl_account_id;
                $gl->description = $header->description;

                if (isset($header->total_amount) && is_numeric($header->total_amount)) {
                    $gl->amount = -$header->total_amount;
                } elseif (isset($header->invoice_amount) && is_numeric($header->invoice_amount)) {
                    $gl->amount = -$header->invoice_amount;
                }
            }

            $gl->update();
        }

        DB::commit();
        return response()->json(['message' => 'Selected records updated successfully'], 200);

    } catch (\Exception $e) {
        DB::rollback();
        
        return response()->json(['error' => 'Error updating records'], 500);
    }
} */

public static function updateGL($header, $item, $type)
{
    try {
        $query = GeneralLedger::where('internal_number', $header->internal_number)->delete();
        try {
            $gl = new GeneralLedger();
            $gl->internal_number = $header->internal_number;
            $gl->external_number = $header->external_number;
            $gl->document_number = $header->document_number;
            $gl->transaction_date = $header->transaction_date;
            $gl->branch_id = $header->branch_id;

            if ($type == "item") {
                $gl->gl_account_id = $item->gl_account_id;
                $gl->description = $item->description;
                $gl->paid_amount = $item->amount;
                $gl->gl_account_analyse_id = $item->gl_account_analysis_id;
            } else {
                $gl->gl_account_id = $header->gl_account_id;
                $gl->description = $header->description;

                if ($header->total_amount) {
                    $gl->amount = -$header->total_amount;
                } else if ($header->invoice_amount) {
                    $gl->amount = -$header->invoice_amount;
                }
            }

            $gl->save();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error updating records'], 500);
        }

        DB::commit();
        return response()->json(['message' => 'Selected records updated successfully'], 200);

    } catch (\Exception $e) {
        DB::rollback();
        return response()->json(['error' => 'Error updating records'], 500);
    }
}
}




