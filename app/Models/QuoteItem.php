<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuoteItem extends Model
{
    protected $fillable = [
        'quote_id',
        'product_id',
        'quantity',
        'unit_price_snapshot',
        'is_auto_added',
        'auto_added_reason',
        'cable_runs',
        'installatie_notitie',
        'werkbon_aantekening',
        'is_optional_declined',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'unit_price_snapshot' => 'decimal:2',
            'is_auto_added' => 'boolean',
            'is_optional_declined' => 'boolean',
            'cable_runs' => 'array',
        ];
    }

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
