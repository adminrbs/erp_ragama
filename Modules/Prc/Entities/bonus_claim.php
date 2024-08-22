<?php

namespace Modules\Prc\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class bonus_claim extends Model
{
    use HasFactory,LogsActivity;

    protected $table = "bonus_claims";
    protected $primaryKey =  'bonus_claim_Id';
    protected $fillable = [];
   
    protected static $logOnlyDirty = true;
    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->log_name = "bonus_claims";
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
}
