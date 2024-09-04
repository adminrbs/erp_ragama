<?php

namespace Modules\Sd\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class sales_order_item_draft extends Model
{
    use HasFactory,LogsActivity;
    protected $table = 'sales_order_item_drafts';
    protected $primaryKey =  'sales_order_item_id';
    protected $fillable = [];


    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->log_name = "sales_order_item_drafts";
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
