<?php

namespace App\Traits;

use App\Models\user_activity;
use App\Models\user_activity_log;
use Illuminate\Support\Facades\Auth;

trait UserActivityLog
{
    public static function bootLogsUserActivity()
    {
        static::created(function ($model) {
            self::logActivity($model, 'created');
        });

        static::updated(function ($model) {
            self::logActivity($model, 'updated');
        });

        static::deleted(function ($model) {
            self::logActivity($model, 'deleted');
        });
    }

    protected static function logActivity($model, $action)
    {
        // Save to UserActivity
        $activity = user_activity::create([
            'user_activity' => $action,
            'model_type' => self::formatClassName(class_basename($model)), // Call formatClassName statically
            'model_id' => $model->{$model->getKeyName()},
        ]);

        // Save to UserActivityLog
        user_activity_log::create([
            'user_activity_id' => $activity->user_activity_id,
            'user_id' => Auth::user()->id,
            'activity_date_time' => now(), // use now() helper instead of date() for cleaner code
            'data' => json_encode($model->toArray()),
        ]);
    }

    public static function formatClassName($className)
    {
        // Convert snake_case (e.g., sales_order) to spaced words (e.g., Sales Order)
        $formatted = preg_replace('/([a-z0-9])([A-Z])/', '$1 $2', $className); // PascalCase to spaced words
        $formatted = preg_replace('/[_-]/', ' ', $formatted); // snake_case or hyphenated to space-separated
        $formatted = ucwords(strtolower($formatted)); // Capitalize first letter of each word

        return $formatted;
    }
}
