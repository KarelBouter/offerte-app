<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductDependency extends Model
{
    protected $fillable = [
        'product_id',
        'depends_on_product_id',
        'rule_type',
        'trigger_quantity_min',
        'trigger_quantity_max',
        'resulting_quantity',
        'resulting_quantity_formula',
        'replaces_product_id',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function dependsOnProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'depends_on_product_id');
    }

    public function replacesProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'replaces_product_id');
    }
}
