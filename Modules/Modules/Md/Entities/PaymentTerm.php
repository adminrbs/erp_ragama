<?php

namespace Modules\Md\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class paymentTerm extends Model
{
    use HasFactory,LogsActivity;
    protected $table = "payment_terms";
    protected $primaryKey = "payment_term_id";
    protected $fillable = [
        
    ];


    protected static $logOnlyDirty = true;
    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->log_name = "payment_terms";
        $activity->description = $eventName;
        $activity->causer_id = Auth::user()->id;
    }

    public function ItemPaymentTerm(){
        return $this->hasMany(paymentTerm::class, 'payment_term_id', 'payment_term_id');
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }
    
   
}