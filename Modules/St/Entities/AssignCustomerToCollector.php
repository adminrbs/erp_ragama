<?php

namespace Modules\St\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class AssignCustomerToCollector extends Model
{
    use HasFactory,LogsActivity;
    protected $table = "customer_collectors";
    protected $primaryKey =  "customer_collector_id";
    protected $fillable = [];


    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->log_name = "assign_customer_to_collectors";
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