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
        'switch_ports_total',
        'switch_ports_poe',
        'poorten_benodigd',
        'installatie_instructie',
        'werkbon_zichtbaarheid',
        'vereist_servicecontract',
    ];

    protected function casts(): array
    {
        return [
            'unit_price'              => 'decimal:2',
            'price_per_meter'         => 'decimal:2',
            'is_price_on_quote'       => 'boolean',
            'is_active'               => 'boolean',
            'vereist_servicecontract' => 'boolean',
        ];
    }

    public function getSwitchPortsAvailableAttribute(): int
    {
        if (!$this->switch_ports_total) return 0;
        return $this->switch_ports_total - 1;
    }

    public function getSwitchPortsStandardAvailableAttribute(): int
    {
        if (!$this->switch_ports_total) return 0;
        $standardPorts = $this->switch_ports_total - ($this->switch_ports_poe ?? 0);
        return max(0, $standardPorts - 1);
    }

    public function getSwitchPortsPoeAvailableAttribute(): int
    {
        return $this->switch_ports_poe ?? 0;
    }

    public function dependencies(): HasMany
    {
        return $this->hasMany(ProductDependency::class, 'product_id');
    }
}
