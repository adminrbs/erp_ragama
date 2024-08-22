<?php

namespace Modules\Sc\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class reverse_devision_transfer extends Model
{
    use HasFactory,LogsActivity;

    protected $table = "reverse_devision_transfers";
    protected $primaryKey =  "reverse_devision_transfer_id";

    protected static $logOnlyDirty = true;
    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->log_name = "reverse_devision_transfer";
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
