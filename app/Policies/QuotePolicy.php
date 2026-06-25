<?php

namespace App\Policies;

use App\Models\Quote;
use App\Models\User;

class QuotePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'verkoper']);
    }

    public function view(User $user, Quote $quote): bool
    {
        return in_array($user->role, ['admin', 'verkoper']);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'verkoper']);
    }

    public function update(User $user, Quote $quote): bool
    {
        return in_array($user->role, ['admin', 'verkoper'])
            && $quote->status === 'concept';
    }

    public function delete(User $user, Quote $quote): bool
    {
        return $user->role === 'admin';
    }

    public function generatePdf(User $user, Quote $quote): bool
    {
        return in_array($user->role, ['admin', 'verkoper']);
    }

    public function copy(User $user, Quote $quote): bool
    {
        return in_array($user->role, ['admin', 'verkoper']);
    }

    public function send(User $user, Quote $quote): bool
    {
        return in_array($user->role, ['admin', 'verkoper'])
            && in_array($quote->status, ['concept', 'verzonden']);
    }
}
