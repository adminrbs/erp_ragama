<?php

namespace Modules\Sd\Entities;

use App\Traits\LedgerActionListener;
use App\Traits\UserActivityLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class sales_order extends Model
{
    use HasFactory,LogsActivity,UserActivityLog;

    protected $primaryKey =  'sales_order_Id';
    protected $fillable = [];


    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->log_name = "sales_order";
        $activity->description = $eventName;
        $activity->causer_id = Auth::user()->id;
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
        // Chain fluent methods for configuration options
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

    /* public function save(array $options = [])
    {
        $saved = parent::save($options);
        if ($saved) {
            $this->saveLedger();
        }
        return $saved;
    }


    public function delete(array $options = [])
    {

        $deleted = parent::delete($options);

        if ($deleted) {
            $this->deleteLedger();
        }

        return $deleted;
    } */
}
