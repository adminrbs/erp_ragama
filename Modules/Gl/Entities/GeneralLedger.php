<?php

namespace Modules\Gl\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class GeneralLedger extends Model
{
    use HasFactory,LogsActivity;

    protected $fillable = [];

    protected $table = "general_ledger";
    protected $primaryKey = "general_ledger_id";

    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->log_name = "credit_notes";
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
