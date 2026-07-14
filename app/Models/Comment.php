<?php

namespace App\Models;

use Database\Factories\CommentFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    /** @use HasFactory<CommentFactory> */
    use HasFactory;

    protected $fillable = [
        'post_id', 'user_id', 'body',
        'admin_reply', 'replied_at', 'is_approved',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'replied_at' => 'datetime',
    ];

    // ── Relations ───────────────────────────────────────────

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Scopes ──────────────────────────────────────────────

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('is_approved', true);
    }
}
