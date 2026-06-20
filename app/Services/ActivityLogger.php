<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

class ActivityLogger
{
    public static function log(
        string $action,
        ?Model $loggable = null,
        ?Model $causer = null,
        array $payload = []
    ): void {
        ActivityLog::create([
            'action' => $action,
            'loggable_type' => $loggable ? get_class($loggable) : null,
            'loggable_id' => $loggable?->getKey(),
            'causer_type' => $causer ? get_class($causer) : null,
            'causer_id' => $causer?->getKey(),
            'payload' => $payload ?: null,
        ]);
    }
}