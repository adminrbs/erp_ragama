<?php

namespace Modules\Md\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class customer_delivery_points extends Model
{
    use HasFactory,LogsActivity;
    protected $primarykey = 'customer_delivery_id';
    protected $fillable = [

    ];

    protected static $logOnlyDirty = true;
    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->log_name = "customer_delivery_points";
        $activity->description = $eventName;
        $activity->causer_id = Auth::user()->id;
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
        
    }
}
