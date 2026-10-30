<?php

namespace App\Traits;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

trait BaseLogsActivity
{
    use LogsActivity;

    /**
     * Get the activity log options for the model.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logExcept(['password', 'remember_token'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName(class_basename($this))
            ->setDescriptionForEvent(function (string $eventName) {
                return match ($eventName) {
                    'created' => 'Created ' . class_basename($this),
                    'updated' => 'Updated ' . class_basename($this),
                    'deleted' => 'Deleted ' . class_basename($this),
                    default => ucfirst($eventName) . ' ' . class_basename($this),
                };
            });
    }
}
