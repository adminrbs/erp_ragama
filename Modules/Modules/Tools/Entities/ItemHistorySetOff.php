<?php

namespace Modules\Tools\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class ItemHistorySetOff extends Model
{
    use HasFactory;
    protected $table = "item_history_set_offs";
    protected $primaryKey =  'item_history_setoff_id';
    protected $fillable = [];


    protected static $logOnlyDirty = true;
    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->log_name = "sales_invoice_item_setoffs";
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