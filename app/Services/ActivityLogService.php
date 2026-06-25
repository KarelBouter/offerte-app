<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class ActivityLogService
{
    public function log(string $action, Model $subject, string $description): void
    {
        ActivityLog::create([
            'user_id'      => auth()->id(),
            'action'       => $action,
            'subject_type' => class_basename($subject),
            'subject_id'   => $subject->getKey(),
            'description'  => $description,
            'ip_address'   => Request::ip(),
            'created_at'   => now(),
        ]);
    }
}
