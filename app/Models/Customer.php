<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'company_name',
        'address',
        'kvk_number',
        'contact_name',
        'contact_email',
        'contact_phone',
        'website',
        'internal_notes',
    ];

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class)->orderByDesc('created_at');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(CustomerNote::class)->orderByDesc('created_at');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class)->orderByDesc('created_at');
    }

    public function activeConfiguration(): ?Quote
    {
        return $this->quotes()
            ->where('status', 'ondertekend')
            ->with('items.product')
            ->first();
    }
}
