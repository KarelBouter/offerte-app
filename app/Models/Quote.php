<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Quote extends Model
{
    protected $fillable = [
        'quote_number',
        'user_id',
        'customer_id',
        'installation_address',
        'status',
        'valid_until',
        'notes',
        'total_onetime_excl_vat',
        'total_yearly_excl_vat',
        'signed_at',
        'signed_by_name',
        'sign_token',
        'sign_token_expires_at',
        'pdf_path',
    ];

    protected function casts(): array
    {
        return [
            'valid_until'            => 'date',
            'signed_at'              => 'datetime',
            'sign_token_expires_at'  => 'datetime',
            'total_onetime_excl_vat' => 'decimal:2',
            'total_yearly_excl_vat'  => 'decimal:2',
        ];
    }

    public function generateSignToken(): string
    {
        $token = Str::random(64);
        $this->update([
            'sign_token'            => $token,
            'sign_token_expires_at' => now()->addDays(14),
        ]);
        return $token;
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Quote $quote) {
            if (empty($quote->quote_number)) {
                $year = now()->year;
                $count = static::whereYear('created_at', $year)->count() + 1;
                $quote->quote_number = sprintf('PI-%d-%04d', $year, $count);
            }

            if (empty($quote->valid_until)) {
                $quote->valid_until = now()->addDays(30)->toDateString();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuoteItem::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
