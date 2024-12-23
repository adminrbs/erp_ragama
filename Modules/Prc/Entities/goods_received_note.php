<?php

namespace Modules\Prc\Entities;

use App\Traits\LedgerActionListener;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class goods_received_note extends Model
{
    use HasFactory,LogsActivity,LedgerActionListener;

    protected $table = "goods_received_notes";
    protected $primaryKey =  'goods_received_Id';
    protected $fillable = [];
   
    protected static $logOnlyDirty = true;
    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->log_name = "goods_received_notes";
        $activity->description = $eventName;
        $activity->causer_id = Auth::user()->id;
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }
    
   /*  protected static function newFactory()
    {
        return \Modules\Prc\Database\factories\GoodsReceivedNoteFactory::new();
    } */

   /*  public function save(array $options = [])
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
