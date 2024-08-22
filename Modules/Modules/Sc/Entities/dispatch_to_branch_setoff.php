<?php

namespace Modules\Sc\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class dispatch_to_branch_setoff extends Model
{
    use HasFactory,LogsActivity;

    protected $table = "dispatch_to_branch_setoffs";
    protected $primaryKey =  "dispatch_to_branch_setoffs_id";

    protected static $logOnlyDirty = true;
    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->log_name = "dispatch_to_branch_setoffs";
        $activity->description = $eventName;
        $activity->causer_id = Auth::user()->id;;
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }
    
}
