<?php

namespace Modules\Sl\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class creditor_credit_note extends Model
{
    use HasFactory,LogsActivity;
    protected $primaryKey =  'creditor_credit_notes_id';

    protected $table =  'creditor_credit_note';
    protected $fillable = [];

    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->log_name = "creditor_credit_note";
        $activity->description = $eventName;
        $activity->causer_id = Auth::user()->id;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
       
    } 
}
