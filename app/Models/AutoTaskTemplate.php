<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AutoTaskTemplate extends Model
{
    protected $fillable = [
        'name',
        'trigger_status',
        'title_template',
        'description_template',
        'assign_to_user_id',
        'due_days',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'due_days'  => 'integer',
        ];
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assign_to_user_id');
    }
}
