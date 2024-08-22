<?php

namespace Modules\Md\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class employee_Status extends Model
{
    use HasFactory,LogsActivity;

    protected $primaryKey = "employee_status_id";
    protected $table = "employee_statuses";

    protected $fillable = [
        'employee_status',
        'locked',
        'status_id',
    ];


    protected static $logOnlyDirty = true;
    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->log_name = "employee_status_id";
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
