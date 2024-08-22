<?php

namespace Modules\Prc\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class purchase_request_other_draft extends Model
{
    use HasFactory,LogsActivity;

    protected $fillable = [];
    protected $primaryKey =  'purchase_request_other_id';
    protected static $logOnlyDirty = true;

    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->log_name = "purchase_request_other_drafts";
        $activity->description = $eventName;
        $activity->causer_id = 1;
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }
    
    /* protected static function newFactory()
    {
        return \Modules\Prc\Database\factories\PurchaseRequestOtherFactory::new();
    } */
}
