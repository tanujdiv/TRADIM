<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    protected $fillable = [
        'user_id',
        'target_type',
        'target_id',
        'target_title',
        'reason',
        'description',
        'status',
        'reviewed_by',
        'admin_note',
        'action_taken',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'reviewed_by'
        );
    }

    public function getReasonLabelAttribute(): string
    {
        return match ($this->reason) {
            'spam' => 'Spam',
            'harassment' => 'Harassment',
            'hate_speech' => 'Hate speech',
            'violence' => 'Violence',
            'sexual_content' => 'Sexual content',
            'misinformation' => 'Misinformation',
            'copyright' => 'Copyright',
            default => 'Other',
        };
    }
}