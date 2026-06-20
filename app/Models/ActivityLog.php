<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = ['action', 'loggable_type', 'loggable_id', 'causer_type', 'causer_id', 'payload'];

    protected $casts = [
        'payload' => 'array',
    ];

    public function loggable()
    {
        return $this->morphTo();
    }

    public function causer()
    {
        return $this->morphTo();
    }
}