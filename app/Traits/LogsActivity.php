<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            self::logActivity('Menambahkan ' . class_basename($model) . ' baru.');
        });

        static::updated(function ($model) {
            self::logActivity('Memperbarui data ' . class_basename($model) . '.');
        });

        static::deleted(function ($model) {
            self::logActivity('Menghapus ' . class_basename($model) . '.');
        });
    }

    protected static function logActivity($activity)
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'activity' => $activity,
                'ip_address' => request()->ip(),
            ]);
        }
    }
}
