<?php

namespace Modules\Sd\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class sales_order extends Model
{
    use HasFactory,LogsActivity;

    protected $primaryKey =  'sales_order_Id';
    protected $fillable = [];


    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->log_name = "sales_order";
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