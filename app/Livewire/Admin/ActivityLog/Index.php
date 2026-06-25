<?php

namespace App\Livewire\Admin\ActivityLog;

use App\Models\ActivityLog;
use App\Models\User;
use Livewire\Component;

class Index extends Component
{
    public string $userFilter   = '';
    public string $actionFilter = '';

    public function render()
    {
        $logs = ActivityLog::with('user')
            ->when($this->userFilter, fn ($q) => $q->where('user_id', $this->userFilter))
            ->when($this->actionFilter, fn ($q) => $q->where('action', 'like', $this->actionFilter.'%'))
            ->orderByDesc('created_at')
            ->limit(200)
            ->get();

        $users = User::orderBy('name')->get(['id', 'name']);

        $actionPrefixes = [
            'quote'   => 'Offertes',
            'product' => 'Producten',
            'user'    => 'Gebruikers',
        ];

        return view('livewire.admin.activity-log.index', compact('logs', 'users', 'actionPrefixes'))
            ->layout('layouts.app-admin', ['title' => 'Activiteitenlog']);
    }
}
