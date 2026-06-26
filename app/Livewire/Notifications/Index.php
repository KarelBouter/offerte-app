<?php

namespace App\Livewire\Notifications;

use App\Models\AppNotification;
use App\Services\NotificationService;
use Livewire\Component;

class Index extends Component
{
    public function markAllRead(): void
    {
        app(NotificationService::class)->markAllRead(auth()->user());
    }

    public function markRead(int $id): void
    {
        $n = AppNotification::where('id', $id)->where('user_id', auth()->id())->first();
        if ($n) {
            $n->update(['read_at' => now()]);
            if ($n->url) {
                $this->redirect($n->url);
            }
        }
    }

    public function render()
    {
        $notifications = AppNotification::where('user_id', auth()->id())
            ->orderByRaw('read_at IS NULL DESC')
            ->orderByDesc('created_at')
            ->get();

        $isAdmin = auth()->user()->role === 'admin';
        $layout  = $isAdmin ? 'layouts.app-admin' : 'layouts.app-verkoper';

        return view('livewire.notifications.index', compact('notifications'))
            ->layout($layout, ['title' => 'Notificaties']);
    }
}
