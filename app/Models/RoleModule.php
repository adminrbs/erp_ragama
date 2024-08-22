<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;
/* use Spatie\Activitylog\Traits\LogsActivity; */
class RoleModule extends Model
{
    use HasFactory/* ,LogsActivity */;
    protected $primaryKey = "role_id";
    protected $fillable = [];
    protected static $logAttributes = [
        'role_id',
        'module_id',
    ];
  /*   protected static $logOnlyDirty = true;
    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->log_name = "role_modules";
        $activity->description = $eventName;
        $activity->causer_id = Auth::user()->id;
    } */
    /* protected static function newFactory()
    {
        return \Modules\St\Database\factories\RoleModuleFactory::new();
    } */
}
