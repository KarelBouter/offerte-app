<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Quote extends Model
{
    protected $fillable = [
        'quote_number',
        'user_id',
        'customer_id',
        'installation_address',
        'status',
        'revision',
        'valid_until',
        'notes',
        'total_onetime_excl_vat',
        'total_yearly_excl_vat',
        'signed_at',
        'signed_by_name',
        'signature_path',
        'signed_ip',
        'sign_token',
        'sign_token_expires_at',
        'pdf_path',
    ];

    protected function casts(): array
    {
        return [
            'revision'               => 'integer',
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
                $latest = static::whereYear('created_at', now()->year)->count();
                $quote->quote_number = 'PI-' . now()->year . '-' .
                    str_pad($latest + 1, 4, '0', STR_PAD_LEFT);
            }

            if (empty($quote->valid_until)) {
                $days = (int) (Setting::where('key', 'quote_validity_days')->value('value') ?? 30);
                $quote->valid_until = now()->addDays($days)->toDateString();
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

    public function versions(): HasMany
    {
        return $this->hasMany(QuoteVersion::class)->orderByDesc('revision');
    }

    public function createVersion(?string $label = null): QuoteVersion
    {
        $version = QuoteVersion::create([
            'quote_id'       => $this->id,
            'created_by'     => Auth::id(),
            'revision'       => $this->revision,
            'label'          => $label ?? 'Opgeslagen als v' . $this->revision,
            'quote_snapshot' => $this->only([
                'quote_number', 'revision', 'status', 'valid_until', 'notes',
                'installation_address', 'total_onetime_excl_vat', 'total_yearly_excl_vat',
            ]),
            'items_snapshot' => $this->items->map(fn ($item) => [
                'product_id'          => $item->product_id,
                'product_name'        => $item->product->name ?? '(verwijderd)',
                'quantity'            => $item->quantity,
                'unit_price_snapshot' => $item->unit_price_snapshot,
                'is_auto_added'       => $item->is_auto_added,
                'sort_order'          => $item->sort_order,
            ])->values()->toArray(),
            'created_at' => now(),
        ]);

        $this->increment('revision');
        $this->refresh();

        return $version;
    }

    public function revisionLabel(): string
    {
        return 'v' . $this->revision;
    }
}
