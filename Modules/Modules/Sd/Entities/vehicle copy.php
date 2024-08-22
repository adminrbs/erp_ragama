<?php

namespace Modules\Sd\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class vehicle extends Model
{
    use HasFactory,LogsActivity;
    protected $table = "vehicles";
    protected $primaryKey = "vehicle_id";
    protected $fillable = [
        'vehicle_no',
        'vehicle_name',
        'description',
        'vehicle_type_id',
        'licence_expire_date',
        'insurance_expire_date',
        'remarks',
        'status_id',

    ];


    protected static $logOnlyDirty = true;
    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->log_name = "vehicles";
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
