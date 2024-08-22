<?php

namespace Modules\Prc\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class creditors_ledger_setoff extends Model
{
    use HasFactory, LogsActivity;
    protected $primaryKey =  'creditors_ledger_setoff_id';
    protected $fillable = [];
    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->log_name = "creditors_ledger_setoff";
        $activity->description = $eventName;
        $activity->causer_id = Auth::user()->id;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }
}
