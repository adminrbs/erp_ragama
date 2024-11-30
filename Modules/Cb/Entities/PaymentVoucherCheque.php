<?php

namespace Modules\Cb\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PaymentVoucherCheque extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    use HasFactory, LogsActivity;
    protected $table = "payment_voucher_cheques";
    protected $primaryKey =  'payment_voucher_cheque_id';
   
    protected static $logOnlyDirty = true;
    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->log_name = "payment_voucher_cheques";
        $activity->description = $eventName;
        $activity->causer_id = Auth::user()->id;
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
       
    }
}
