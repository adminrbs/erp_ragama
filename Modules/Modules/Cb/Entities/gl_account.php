<?php

namespace Modules\Cb\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class gl_account extends Model
{
    use HasFactory;
    protected $table = "gl_accounts";
    protected $primaryKey = "account_id";

    protected $fillable = [];

    protected static $logOnlyDirty = true;
    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->log_name = "gl_accounts";
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
