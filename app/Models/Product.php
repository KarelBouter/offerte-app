<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'category',
        'description',
        'unit_price',
        'unit',
        'min_quantity',
        'max_quantity',
        'image_path',
        'is_price_on_quote',
        'is_active',
        'sort_order',
        'poe_wattage_output',
        'poe_wattage_input',
        'price_per_meter',
    ];

    protected function casts(): array
    {
        return [
            'unit_price'      => 'decimal:2',
            'price_per_meter' => 'decimal:2',
            'is_price_on_quote' => 'boolean',
            'is_active'         => 'boolean',
        ];
    }

    public function dependencies(): HasMany
    {
        return $this->hasMany(ProductDependency::class, 'product_id');
    }
}
