<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;

class Index extends Component
{
    public string $search = '';

    public function toggleActive(int $userId): void
    {
        if ($userId === auth()->id()) {
            session()->flash('error', 'Je kunt je eigen account niet deactiveren.');
            return;
        }

        $user = User::findOrFail($userId);
        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'geactiveerd' : 'gedeactiveerd';
        session()->flash('success', "Gebruiker {$user->name} is $status.");
    }

    public function render()
    {
        $users = User::when($this->search, fn ($q) => $q->where(function ($q) {
            $q->where('name', 'like', '%'.$this->search.'%')
              ->orWhere('email', 'like', '%'.$this->search.'%');
        }))->orderBy('name')->get();

        return view('livewire.admin.users.index', compact('users'))
            ->layout('layouts.app-admin', ['title' => 'Gebruikers']);
    }
}
