<?php

namespace Modules\Sd\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class sales_return_item extends Model
{
  use HasFactory, LogsActivity;
  protected $table = "sales_return_items";
  protected $primaryKey =  'sales_return_item_id';
  protected $fillable = [];
 
  protected static $logOnlyDirty = true;
  public function tapActivity(Activity $activity, string $eventName)
  {
      $activity->log_name = "sales_return_item_id";
      $activity->description = $eventName;
      $activity->causer_id = 1;
  }
  public function getActivitylogOptions(): LogOptions
  {
      return LogOptions::defaults()
          ->logOnly(['*']);
      // Chain fluent methods for configuration options
  }
}
