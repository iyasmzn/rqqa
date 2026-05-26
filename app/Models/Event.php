<?php

namespace App\Models;

use Database\Factories\EventFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /** @use HasFactory<EventFactory> */
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'excerpt', 'content', 'image',
        'category', 'location', 'starts_at', 'ends_at',
        'is_published', 'sort_order',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    // ── Scopes ──────────────────────────────────────────────

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('starts_at', '>=', now());
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('starts_at');
    }

    // ── Computed Attributes ──────────────────────────────────

    public function getThumbnailUrlAttribute(): string
    {
        return $this->image
            ? asset('storage/'.$this->image)
            : "https://picsum.photos/seed/event-{$this->id}/800/500";
    }

    public function getFormattedDateAttribute(): string
    {
        $months = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
        ];
        $d = $this->starts_at;

        return $d->day.' '.$months[$d->month].' '.$d->year;
    }

    public function getIsPastAttribute(): bool
    {
        return $this->starts_at->isPast();
    }
}
