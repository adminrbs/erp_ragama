<?php

namespace Modules\Cb\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class sfa_receipt extends Model
{
    use HasFactory, LogsActivity;
    protected $primaryKey =  'customer_receipt_id';
    protected $fillable = [];
    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->log_name = "sfa_receipts";
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
