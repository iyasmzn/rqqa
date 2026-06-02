<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Media extends Model
{
    protected $fillable = [
        'name',
        'alt',
        'description',
        'show_in_gallery',
        'album',
        'path',
        'disk',
        'mime_type',
        'size',
        'uploaded_by',
    ];

    protected $casts = [
        'show_in_gallery' => 'boolean',
    ];

    // ── Accessors ─────────────────────────────────────────────────────

    /**
     * Public URL to access the file.
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/'.$this->path);
    }

    /**
     * Human-readable file size (e.g. "1.4 MB").
     */
    public function getSizeFormattedAttribute(): string
    {
        $bytes = (int) $this->size;

        return match (true) {
            $bytes >= 1_048_576 => round($bytes / 1_048_576, 1).' MB',
            $bytes >= 1_024 => round($bytes / 1_024, 1).' KB',
            default => $bytes.' B',
        };
    }

    /**
     * Whether the file is an image.
     */
    public function getIsImageAttribute(): bool
    {
        return str_starts_with($this->mime_type ?? '', 'image/');
    }

    /**
     * Short type label for the file (Image, PDF, etc.).
     */
    public function getTypeLabel(): string
    {
        return match (true) {
            str_starts_with($this->mime_type ?? '', 'image/') => 'Gambar',
            $this->mime_type === 'application/pdf' => 'PDF',
            str_starts_with($this->mime_type ?? '', 'video/') => 'Video',
            default => 'File',
        };
    }

    // ── Relationships ─────────────────────────────────────────────────

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
