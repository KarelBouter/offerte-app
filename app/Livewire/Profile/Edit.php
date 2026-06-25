<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Edit extends Component
{
    public string $name = '';
    public string $email = '';
    public string $currentPassword = '';
    public string $newPassword = '';
    public string $newPasswordConfirmation = '';

    public function mount(): void
    {
        $user        = auth()->user();
        $this->name  = $user->name;
        $this->email = $user->email;
    }

    public function save(): void
    {
        $user = auth()->user();

        $this->validate([
            'name'  => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ], [
            'name.required'  => 'Naam is verplicht.',
            'email.required' => 'E-mailadres is verplicht.',
            'email.email'    => 'Voer een geldig e-mailadres in.',
            'email.unique'   => 'Dit e-mailadres is al in gebruik.',
        ]);

        if ($this->newPassword !== '') {
            $this->validate([
                'currentPassword'         => 'required',
                'newPassword'             => 'required|string|min:8|same:newPasswordConfirmation',
                'newPasswordConfirmation' => 'required|string',
            ], [
                'currentPassword.required'         => 'Huidig wachtwoord is verplicht.',
                'newPassword.required'             => 'Nieuw wachtwoord is verplicht.',
                'newPassword.min'                  => 'Nieuw wachtwoord moet minimaal 8 tekens bevatten.',
                'newPassword.same'                 => 'Wachtwoorden komen niet overeen.',
                'newPasswordConfirmation.required' => 'Wachtwoordbevestiging is verplicht.',
            ]);

            if (!Hash::check($this->currentPassword, $user->password)) {
                $this->addError('currentPassword', 'Het huidige wachtwoord is onjuist.');
                return;
            }
        }

        $data = ['name' => $this->name, 'email' => $this->email];
        if ($this->newPassword !== '') {
            $data['password'] = $this->newPassword;
        }

        $user->update($data);

        $this->currentPassword = '';
        $this->newPassword = '';
        $this->newPasswordConfirmation = '';

        session()->flash('success', 'Profiel bijgewerkt.');
    }

    public function render()
    {
        $layout = auth()->user()->role === 'admin' ? 'layouts.app-admin' : 'layouts.app-verkoper';

        return view('livewire.profile.edit')
            ->layout($layout, ['title' => 'Mijn profiel']);
    }
}
