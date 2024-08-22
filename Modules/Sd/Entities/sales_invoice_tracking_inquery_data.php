<?php

namespace Modules\Sd\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class sales_invoice_tracking_inquery_data extends Model
{
    use HasFactory, LogsActivity;


    protected $table = "sales_invoice_tracking_inquery_datas";
    protected $primaryKey =  'sales_invoice_tracking_inquery_data_id';
    protected $fillable = ['sales_invoice_tracking_inquery_data_id','inquery_person_statment','created_at'];
   
    protected static $logOnlyDirty = true;
    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->log_name = "sales_invoice_tracking_inquery_datas";
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
