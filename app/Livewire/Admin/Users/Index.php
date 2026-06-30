<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;

class Index extends Component
{
    public string $search = '';

    public ?int $confirmingId       = null;
    public string $confirmingName   = '';
    public bool $confirmingIsActive = false;

    public function prepareConfirmToggle(int $id, string $name, bool $isActive): void
    {
        $this->confirmingId       = $id;
        $this->confirmingName     = $name;
        $this->confirmingIsActive = $isActive;
        $this->dispatch('open-modal', 'confirm-user');
    }

    public function toggleActive(int $userId): void
    {
        if ($userId === auth()->id()) {
            session()->flash('error', 'Je kunt je eigen account niet deactiveren.');
            $this->dispatch('close-modal', 'confirm-user');
            return;
        }

        $user = User::findOrFail($userId);
        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'geactiveerd' : 'gedeactiveerd';
        session()->flash('success', "Gebruiker {$user->name} is $status.");
        $this->dispatch('close-modal', 'confirm-user');
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
