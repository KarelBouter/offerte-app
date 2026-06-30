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
        'discount_type',
        'discount_value',
        'onetime_subtotal_excl_vat',
        'total_onetime_excl_vat',
        'total_yearly_excl_vat',
        'signed_at',
        'signed_by_name',
        'signature_path',
        'signed_ip',
        'cosigned_at',
        'cosigned_by',
        'sign_token',
        'sign_token_expires_at',
        'pdf_path',
        'inclusief_overeenkomst',
        'werkbon_laatst_bewerkt_op',
        'werkbon_laatst_bewerkt_door',
        'werkbon_afgerond',
        'werkbon_afgerond_op',
        'werkbon_afgerond_door',
    ];

    protected function casts(): array
    {
        return [
            'revision'                    => 'integer',
            'inclusief_overeenkomst'      => 'boolean',
            'werkbon_afgerond'            => 'boolean',
            'werkbon_afgerond_op'         => 'date',
            'werkbon_laatst_bewerkt_op'   => 'datetime',
            'valid_until'                 => 'date',
            'signed_at'                 => 'datetime',
            'cosigned_at'               => 'datetime',
            'sign_token_expires_at'     => 'datetime',
            'discount_value'            => 'decimal:2',
            'onetime_subtotal_excl_vat' => 'decimal:2',
            'total_onetime_excl_vat'    => 'decimal:2',
            'total_yearly_excl_vat'     => 'decimal:2',
        ];
    }

    public function getDiscountAmount(float $onetimeSubtotal): float
    {
        if (!$this->discount_type || !$this->discount_value) {
            return 0.0;
        }
        if ($this->discount_type === 'percentage') {
            return round($onetimeSubtotal * ((float) $this->discount_value / 100), 2);
        }
        return min((float) $this->discount_value, $onetimeSubtotal);
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
            if (is_null($quote->revision)) {
                $quote->revision = 0;
            }

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

    public function werkbonBewerker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'werkbon_laatst_bewerkt_door');
    }

    public function versions(): HasMany
    {
        return $this->hasMany(QuoteVersion::class)->orderByDesc('revision');
    }

    public function createVersion(?string $label = null): QuoteVersion
    {
        $this->increment('revision');
        $this->refresh();

        return QuoteVersion::create([
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
    }

    public function revisionLabel(): string
    {
        return 'v' . $this->revision;
    }
}
