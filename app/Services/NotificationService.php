<?php

namespace App\Services;

use App\Models\AppNotification;
use App\Models\Task;
use App\Models\User;

class NotificationService
{
    public function notifyTaskAssigned(Task $task): void
    {
        if (!$task->assigned_to_user_id || $task->assigned_to_user_id === $task->created_by_user_id) {
            return;
        }

        AppNotification::create([
            'user_id'         => $task->assigned_to_user_id,
            'type'            => 'task.assigned',
            'title'           => ($task->createdBy?->name ?? 'Iemand') . ' heeft je een taak toegewezen',
            'body'            => $task->title . ($task->quote ? ' — Offerte ' . $task->quote->quote_number : ''),
            'url'             => route('taken.show', $task),
            'related_task_id' => $task->id,
        ]);
    }

    public function notifyTaskMentioned(Task $task, User $mentionedUser): void
    {
        if ($mentionedUser->id === $task->created_by_user_id) {
            return;
        }

        AppNotification::create([
            'user_id'         => $mentionedUser->id,
            'type'            => 'task.mentioned',
            'title'           => ($task->createdBy?->name ?? 'Iemand') . ' heeft je getagd in een taak',
            'body'            => $task->title . ($task->quote ? ' — Offerte ' . $task->quote->quote_number : ''),
            'url'             => route('taken.show', $task),
            'related_task_id' => $task->id,
        ]);
    }

    public function notifyTaskCompleted(Task $task): void
    {
        if (!$task->created_by_user_id || auth()->id() === $task->created_by_user_id) {
            return;
        }

        AppNotification::create([
            'user_id'         => $task->created_by_user_id,
            'type'            => 'task.completed',
            'title'           => (auth()->user()?->name ?? 'Iemand') . ' heeft een taak afgerond',
            'body'            => $task->title,
            'url'             => route('taken.show', $task),
            'related_task_id' => $task->id,
        ]);
    }

    public function markAllRead(User $user): void
    {
        AppNotification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }
}
