<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class KassaComponent extends Model
{
    protected $table = 'kassa_componenten';

    protected $fillable = [
        'naam',
        'poorten_per_kassa',
        'poe_required',
        'is_actief',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'poe_required' => 'boolean',
            'is_actief'    => 'boolean',
        ];
    }

    public function scopeActief(Builder $query): Builder
    {
        return $query->where('is_actief', true);
    }
}
