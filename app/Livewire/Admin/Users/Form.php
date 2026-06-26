<?php

namespace App\Livewire\Admin\Users;

use App\Mail\WelcomeUserMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Form extends Component
{
    public ?int $userId = null;
    public string $name = '';
    public string $email = '';
    public string $role = 'verkoper';
    public bool $isActive = true;
    public string $password = '';
    public string $passwordConfirmation = '';

    public function mount(?User $user = null): void
    {
        if ($user?->exists) {
            $this->userId    = $user->id;
            $this->name      = $user->name;
            $this->email     = $user->email;
            $this->role      = $user->role;
            $this->isActive  = $user->is_active;
        }
    }

    public function save(): void
    {
        $isNew = $this->userId === null;

        $rules = [
            'name'  => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->userId)],
            'role'  => 'required|in:admin,verkoper',
        ];

        if ($isNew || $this->password !== '') {
            $rules['password']             = 'required|string|min:8|same:passwordConfirmation';
            $rules['passwordConfirmation'] = 'required|string';
        }

        $this->validate($rules, [
            'name.required'                 => 'Naam is verplicht.',
            'email.required'                => 'E-mailadres is verplicht.',
            'email.email'                   => 'Voer een geldig e-mailadres in.',
            'email.unique'                  => 'Dit e-mailadres is al in gebruik.',
            'role.required'                 => 'Rol is verplicht.',
            'password.required'             => 'Wachtwoord is verplicht.',
            'password.min'                  => 'Wachtwoord moet minimaal 8 tekens bevatten.',
            'password.same'                 => 'Wachtwoorden komen niet overeen.',
            'passwordConfirmation.required' => 'Wachtwoordbevestiging is verplicht.',
        ]);

        if ($isNew) {
            $user = User::create([
                'name'      => $this->name,
                'email'     => $this->email,
                'role'      => $this->role,
                'is_active' => $this->isActive,
                'password'  => $this->password,
            ]);

            Mail::to($user->email)->send(new WelcomeUserMail($user, $this->password));

            session()->flash('success', "Gebruiker {$user->name} aangemaakt. Welkomstmail verstuurd.");
        } else {
            $user = User::findOrFail($this->userId);
            $data = [
                'name'      => $this->name,
                'email'     => $this->email,
                'role'      => $this->role,
                'is_active' => $this->isActive,
            ];
            if ($this->password !== '') {
                $data['password'] = $this->password;
            }
            $user->update($data);

            session()->flash('success', "Gebruiker {$user->name} bijgewerkt.");
        }

        $this->redirect(route('beheer.gebruikers.index'));
    }

    public function render()
    {
        $title = $this->userId ? 'Gebruiker bewerken' : 'Nieuwe gebruiker';
        return view('livewire.admin.users.form')
            ->layout('layouts.app-admin', ['title' => $title]);
    }
}
