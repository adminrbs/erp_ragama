<?php

namespace Modules\Sd\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class DebtorsLedgerSetoff extends Model
{
    use HasFactory, LogsActivity;
    protected $primaryKey =  'debtors_ledger_setoff_id';
    protected $fillable = [];
    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->log_name = "debtors_ledger_setoffs";
        $activity->description = $eventName;
        $activity->causer_id = 1;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }
}
