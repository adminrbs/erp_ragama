<?php

namespace Modules\Sl\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class creditor_debit_note extends Model
{
    use HasFactory,LogsActivity;
    protected $primaryKey =  'creditor_debit_notes_id';
    protected $fillable = [];

    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->log_name = "creditor_debit_note";
        $activity->description = $eventName;
        $activity->causer_id = 1;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
       
    }
    
   
}
