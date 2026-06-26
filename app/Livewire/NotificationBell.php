<?php

namespace App\Livewire;

use App\Models\AppNotification;
use App\Services\NotificationService;
use Livewire\Attributes\Poll;
use Livewire\Component;

#[Poll('30s')]
class NotificationBell extends Component
{
    public function markRead(int $id): void
    {
        $notification = AppNotification::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if ($notification) {
            $notification->update(['read_at' => now()]);
            if ($notification->url) {
                $this->redirect($notification->url);
            }
        }
    }

    public function markAllRead(): void
    {
        app(NotificationService::class)->markAllRead(auth()->user());
    }

    public function render()
    {
        $notifications = AppNotification::where('user_id', auth()->id())
            ->orderByRaw('read_at IS NULL DESC')
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        $unreadCount = AppNotification::where('user_id', auth()->id())
            ->unread()
            ->count();

        return view('livewire.notification-bell', compact('notifications', 'unreadCount'));
    }
}
