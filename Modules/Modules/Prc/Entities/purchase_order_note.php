<?php

namespace Modules\Prc\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;



class purchase_order_note extends Model
{
    use HasFactory,LogsActivity;

    protected $table = "purchase_order_notes";
    protected $primaryKey =  'purchase_order_Id';
    protected $fillable = [];
   
    protected static $logOnlyDirty = true;
    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->log_name = "purchase_order_notes";
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