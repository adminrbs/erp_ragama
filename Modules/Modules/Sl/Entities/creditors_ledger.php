<?php

namespace Modules\Sl\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class creditors_ledger extends Model
{
    use HasFactory,LogsActivity;
    protected $primaryKey =  'creditors_ledger_id';
    protected $table = 'creditors_ledger';
    protected $fillable = [];

    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->log_name = "creditors_ledger";
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
