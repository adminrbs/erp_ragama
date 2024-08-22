<?php

namespace Modules\Sd\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class DebtorsLedger extends Model
{
    use HasFactory,LogsActivity;
    protected $primaryKey =  'debtors_ledger_id';
    protected $fillable = [];

    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->log_name = "debtors_ledgers";
        $activity->description = $eventName;
        $activity->causer_id = Auth::user()->id;;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }
    
    /* protected static function newFactory()
    {
        return \Modules\Sd\Database\factories\DebtorsLedgerFactory::new();
    } */
}
