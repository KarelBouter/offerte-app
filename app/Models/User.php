<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'is_active'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isVerkoper(): bool
    {
        return $this->role === 'verkoper';
    }

    public function isSamensteller(): bool
    {
        return $this->role === 'samensteller';
    }

    public function isWerkvoorbereider(): bool
    {
        return $this->role === 'werkvoorbereider';
    }

    public function canEditWerkbon(): bool
    {
        return in_array($this->role, ['admin', 'verkoper', 'werkvoorbereider']);
    }

    public function canSendQuotes(): bool
    {
        return in_array($this->role, ['admin', 'verkoper']);
    }

    public function canGeneratePdf(): bool
    {
        return in_array($this->role, ['admin', 'verkoper']);
    }

    public function canChangeQuoteStatus(): bool
    {
        return in_array($this->role, ['admin', 'verkoper']);
    }
}
