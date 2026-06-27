<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuoteVersion extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'quote_id',
        'created_by',
        'revision',
        'label',
        'quote_snapshot',
        'items_snapshot',
        'created_at',
    ];

    protected $casts = [
        'quote_snapshot' => 'array',
        'items_snapshot' => 'array',
        'created_at'     => 'datetime',
    ];

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
