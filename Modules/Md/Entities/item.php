<?php

namespace Modules\Md\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class item extends Model
{
    use HasFactory, LogsActivity;
    protected $primaryKey =  'item_id';
    protected $fillable = [];
   
    protected static $logOnlyDirty = true;
    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->log_name = "Items";
        $activity->description = $eventName;
        $activity->causer_id = Auth::user()->id;
    }

    //One to many
    public function ItemPaymentTerm(){
        return $this->hasMany(ItemPaymentTerm::class, 'item_id', 'item_id');
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }
}
