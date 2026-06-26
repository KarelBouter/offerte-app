<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    protected $fillable = [
        'created_by_user_id',
        'assigned_to_user_id',
        'quote_id',
        'title',
        'description',
        'status',
        'due_date',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'due_date'     => 'date',
            'completed_at' => 'datetime',
        ];
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    public function mentions(): HasMany
    {
        return $this->hasMany(TaskMention::class);
    }

    public function extractMentions(string $description): array
    {
        preg_match_all('/@(\w+)/u', $description, $matches);
        $users = [];
        foreach ($matches[1] as $match) {
            $user = User::where('name', 'LIKE', $match . '%')
                ->where('is_active', true)
                ->first();
            if ($user && !in_array($user->id, array_column($users, 'id'))) {
                $users[] = $user;
            }
        }
        return $users;
    }

    public function complete(): void
    {
        $this->update([
            'status'       => 'afgerond',
            'completed_at' => now(),
        ]);
    }
}
