<?php

namespace Modules\Prc\Entities;

use App\Traits\LedgerActionListener;
use App\Traits\UserActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;



class purchase_order_note extends Model
{
    use HasFactory,LogsActivity,LedgerActionListener,UserActivityLog;

    protected $table = "purchase_order_notes";
    protected $primaryKey =  'purchase_order_Id';
    protected $fillable = [];
   
    protected static $logOnlyDirty = true;
    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->log_name = "purchase_order_notes";
        $activity->description = $eventName;
        $activity->causer_id = Auth::user()->id;
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            // Log activity after the sales order is created
            $model->logActivity($model,'created');
        });

        static::updated(function ($model) {
            // Log activity after the sales order is updated
            $model->logActivity($model,'updated');
        });

        static::deleted(function ($model) {
            // Log activity after the sales order is deleted
            $model->logActivity($model,'deleted');
        });

      
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }
    
   
}
