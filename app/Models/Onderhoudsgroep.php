<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Onderhoudsgroep extends Model
{
    protected $table = 'onderhoudsgroepen';

    protected $fillable = ['naam', 'basisproduct_id', 'per_stuk_product_id', 'is_actief'];

    protected function casts(): array
    {
        return ['is_actief' => 'boolean'];
    }

    public function basisproduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'basisproduct_id');
    }

    public function perStukProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'per_stuk_product_id');
    }

    public function producten(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function scopeActief($query)
    {
        return $query->where('is_actief', true);
    }
}
