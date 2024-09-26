<?php

namespace Modules\Sl\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class supplierPaymentMethod extends Model
{
    use HasFactory,LogsActivity;
    
    /* protected static function newFactory()
    {
        return \Modules\Md\Database\factories\SupplierGroupFactory::new();
    } */

    use HasFactory, LogsActivity;
    protected $table = 'supplier_payment_methods';
    protected $primaryKey =  'supplier_payment_method_id';
    protected $fillable = [];
   
    protected static $logOnlyDirty = true;
    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->log_name = "supplier_payment_methods";
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
