<?php

namespace Modules\Md\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Auth;

class AccountGroupLevelFour extends Model
{
    use HasFactory,LogsActivity;
    protected $table = "account_group_level_fours";
    protected $primaryKey = "account_group_level_four_id";
    protected $fillable = [];
    
    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->log_name = "account_group_level_fours";
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