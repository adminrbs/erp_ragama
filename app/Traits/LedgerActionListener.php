<?php

namespace App\Traits;

use App\Models\GeneralLedger;

trait LedgerActionListener
{

    public function saveLedger()
    {
        $ledger = GeneralLedger::where([['internal_number', '=', $this->internal_number], ['external_number', '=', $this->external_number], ['document_number', '=', $this->document_number]])->first();
        if (!$ledger) {
            $ledger = new GeneralLedger();
        }
        $ledger->internal_number = $this->internal_number;
        $ledger->external_number = $this->external_number;
        $ledger->gl_account_id = $this->gl_account_id ?? null;
        $ledger->description = $this->description;
        if($this->invoice_amount){
            $ledger->amount = $this->invoice_amount;
        }else if($this->total_amount){
            $ledger->amount = $this->total_amount;
        }
        $ledger->document_number = $this->document_number;
        $ledger->save();
    }

    public function deleteLedger()
    {
        GeneralLedger::where([['internal_number', '=', $this->internal_number], ['external_number', '=', $this->external_number], ['document_number', '=', $this->document_number]])->delete();
    }
}
