<?php

namespace Modules\Md\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class ItemPaymentTerm extends Model
{
    use HasFactory, LogsActivity;
    protected $primaryKey =  'item_payment_terms_id';
    protected $fillable = [];
   
    protected static $logOnlyDirty = true;
    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->log_name = "ItemPaymentTerms";
        $activity->description = $eventName;
        $activity->causer_id = Auth::user()->id;
    }

    //one to many
    public function item(){
        return $this->belongsTo(item::class,'item_id','item_id');
    }
    public function paymentTerm(){
        return $this->belongsTo(paymentTerm::class,'payment_term_id','payment_term_id');
    }
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }
}
