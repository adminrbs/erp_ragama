<?php

namespace Modules\Sd\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class employee extends Model
{
    use HasFactory,LogsActivity;
    protected $primaryKey =  "employee_id";

    protected static $logOnlyDirty = true;
    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->log_name = "employees";
        $activity->description = $eventName;
        $activity->causer_id = Auth::user()->id;
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }
    protected $fillable = [
        'employee_code',
        'employee_name',
        'office_mobile',
        'Office_email',
        'persional_mobile',
        'persional_fixed',
        'persional_email',
        'address',
        'desgination_id',
        'report_to',
        'date_of_joined',
        'date_of_resign',
        'status_id',
        'note',
    ];


}
