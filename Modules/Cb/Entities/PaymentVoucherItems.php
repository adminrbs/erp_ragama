<?php

namespace Modules\Cb\Entities;

use App\Traits\LedgerActionListener;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PaymentVoucherItems extends Model
{
    use HasFactory, LogsActivity,LedgerActionListener;
    protected $table = "payment_voucher_items";
    protected $primaryKey =  'payment_voucher_item_id';
    protected $fillable = [];

    protected static $logOnlyDirty = true;
    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->log_name = "payment_voucher_items";
        $activity->description = $eventName;
        $activity->causer_id = Auth::user()->id;;
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }
    
  /*   public function save(array $options = [])
    {
        $saved = parent::save($options);
        $header = PaymentVoucher::find($this->payment_voucher_id);

        if ($saved) {
            $this->saveLedger($header,$this);
        }
        return $saved;
    } */
    
}
