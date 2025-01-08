<?php

namespace App\Traits;

use Modules\Gl\Entities\GeneralLedger;

trait LedgerActionListener
{


    public function saveLedger($header, $item)
    {
        /*$ledger = GeneralLedger::where([['internal_number', '=', $header->internal_number], ['external_number', '=', $header->external_number], ['document_number', '=', $header->document_number]])->first();
        if (!$ledger) {
            $ledger = new GeneralLedger();
        }*/
        $ledger = new GeneralLedger();
        if ($header && $item) {
            $ledger->internal_number = $header->internal_number ?? null;
            $ledger->external_number = $header->external_number ?? null;
            $ledger->document_number = $header->document_number ?? null;
            $ledger->transaction_date = $header->transaction_date ?? null;
            $ledger->branch_id = $header->branch_id ?? null;
            $ledger->is_bank_rec = $header->is_bank_rec ?? 0;
            $ledger->bank_rec_date = $header->bank_rec_date ?? null;
            $ledger->created_by = $header->created_by ?? null;
            $ledger->amount = $header->amount ?? null;



            $ledger->gl_account_id = $item->gl_account_id ?? null;
            $ledger->paid_amount = $item->amount ?? null;
            $ledger->gl_account_analyse_id = $item->gl_account_analyse_id ?? null;
            $ledger->description = $item->description ?? null;

            $ledger->save();
        }
    }

    public function deleteLedger()
    {
        //GeneralLedger::where([['internal_number', '=', $this->internal_number], ['external_number', '=', $this->external_number], ['document_number', '=', $this->document_number]])->delete();
    }
}
