<?php

namespace App\Models;

use Database\Factories\EbookFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Ebook extends Model
{
    /** @use HasFactory<EbookFactory> */
    use HasFactory;

    /** Isi berupa berkas PDF yang diunggah ke server — dibaca langsung di halaman. */
    public const SOURCE_PDF = 'pdf';

    /** Isi berada di situs lain; tombol baca membuka tautannya di tab baru. */
    public const SOURCE_LINK = 'link';

    /** Isi ditampilkan lewat penampil situs lain yang disematkan (iframe). */
    public const SOURCE_EMBED = 'embed';

    /** Batas panjang kolom `external_url` & `embed_url`. */
    public const URL_MAX_LENGTH = 1000;

    /** Folder PDF di disk public — satu-satunya tempat yang boleh dihapus model ini. */
    public const FILE_DIRECTORY = 'ebooks/files';

    protected $fillable = [
        'title', 'slug', 'author', 'publisher', 'published_year', 'isbn', 'language',
        'pages', 'category', 'description', 'cover_image',
        'source_type', 'file_path', 'original_filename', 'file_size',
        'external_url', 'embed_url', 'allow_download',
        'is_featured', 'is_published', 'sort_order',
    ];

    protected $casts = [
        'published_year' => 'integer',
        'pages' => 'integer',
        'file_size' => 'integer',
        'allow_download' => 'boolean',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        // PDF bisa berukuran puluhan MB — berkas lama dibuang begitu diganti,
        // dikosongkan (sumber isi beralih ke tautan/embed), atau e-book dihapus.
        static::updated(function (Ebook $ebook): void {
            if ($ebook->wasChanged('file_path')) {
                self::deleteStoredFile($ebook->getOriginal('file_path'));
            }
        });

        static::deleted(fn (Ebook $ebook) => self::deleteStoredFile($ebook->file_path));
    }

    // ── Sumber Isi ───────────────────────────────────────────

    /**
     * Pilihan sumber isi untuk form admin.
     *
     * @return array<string, string>
     */
    public static function sourceTypeOptions(): array
    {
        return [
            self::SOURCE_PDF => 'File PDF',
            self::SOURCE_LINK => 'Tautan',
            self::SOURCE_EMBED => 'Sematkan (Embed)',
        ];
    }

    /**
     * Nama format seperti yang dilihat pengunjung — badge kartu & filter
     * halaman e-book.
     *
     * @return array<string, string>
     */
    public static function formatOptions(): array
    {
        return [
            self::SOURCE_PDF => 'PDF',
            self::SOURCE_EMBED => 'Baca Online',
            self::SOURCE_LINK => 'Tautan Eksternal',
        ];
    }

    public function isPdf(): bool
    {
        return $this->source_type === self::SOURCE_PDF;
    }

    public function isLink(): bool
    {
        return $this->source_type === self::SOURCE_LINK;
    }

    public function isEmbed(): bool
    {
        return $this->source_type === self::SOURCE_EMBED;
    }

    /**
     * Whether the e-book can be read inside its own page: an uploaded PDF or an
     * embeddable viewer. Linked e-books are read on the other site instead.
     */
    public function hasReader(): bool
    {
        return ($this->isPdf() && filled($this->file_path))
            || ($this->isEmbed() && filled($this->embed_url));
    }

    public function canBeDownloaded(): bool
    {
        return $this->isPdf() && $this->allow_download && filled($this->file_path);
    }

    /**
     * Normalise what an admin pastes into the embed field into an iframe `src`:
     * a full `<iframe>` snippet is reduced to its `src`, and Google Drive /
     * Docs sharing links become their embeddable `/preview` URL. Returns null
     * for anything that is not an http(s) URL.
     */
    public static function normalizeEmbedUrl(?string $input): ?string
    {
        $url = trim((string) $input);

        if (preg_match('/<iframe\b[^>]*?\bsrc\s*=\s*(["\'])(.*?)\1/is', $url, $matches)) {
            $url = trim(html_entity_decode($matches[2], ENT_QUOTES | ENT_HTML5));
        }

        if (str_starts_with($url, '//')) {
            $url = 'https:'.$url;
        }

        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));

        if (! filter_var($url, FILTER_VALIDATE_URL) || ! in_array($scheme, ['http', 'https'], true)) {
            return null;
        }

        return self::googlePreviewUrl($url) ?? $url;
    }

    /**
     * The `/preview` URL for a Google Drive file or Google Docs/Slides/Sheets
     * sharing link, or null for any other URL. Docs "publish to web" links
     * (`/d/e/…`) are already embeddable and are left alone.
     */
    private static function googlePreviewUrl(string $url): ?string
    {
        $host = strtolower((string) parse_url($url, PHP_URL_HOST));
        $path = (string) parse_url($url, PHP_URL_PATH);
        parse_str((string) parse_url($url, PHP_URL_QUERY), $query);

        if ($host === 'drive.google.com') {
            $id = preg_match('~/file/d/([\w-]+)~', $path, $matches) ? $matches[1] : ($query['id'] ?? null);

            if (! is_string($id) || $id === '') {
                return null;
            }

            $resourceKey = is_string($query['resourcekey'] ?? null)
                ? '?resourcekey='.urlencode($query['resourcekey'])
                : '';

            return "https://drive.google.com/file/d/{$id}/preview{$resourceKey}";
        }

        if ($host === 'docs.google.com' && preg_match('~^/(document|presentation|spreadsheets)/d/(?!e/)([\w-]+)~', $path, $matches)) {
            return "https://docs.google.com/{$matches[1]}/d/{$matches[2]}/preview";
        }

        return null;
    }

    /**
     * Delete a stored PDF — only inside the e-book folder, so a stray or
     * tampered path can never take another feature's file with it.
     */
    private static function deleteStoredFile(?string $path): void
    {
        if (blank($path) || ! str_starts_with($path, self::FILE_DIRECTORY.'/') || str_contains($path, '..')) {
            return;
        }

        Storage::disk('public')->delete($path);
    }

    // ── Mutators ─────────────────────────────────────────────

    protected function embedUrl(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value): ?string => blank($value) ? null : self::normalizeEmbedUrl($value),
        );
    }

    // ── Scopes ──────────────────────────────────────────────

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }

    /**
     * E-book untuk seksi halaman depan: yang ditandai unggulan, atau — selama
     * belum ada yang ditandai — e-book terbaru, agar seksinya tidak kosong.
     *
     * @return Collection<int, Ebook>
     */
    public static function featuredOrLatest(int $limit): Collection
    {
        $featured = self::published()->featured()->ordered()->limit($limit)->get();

        return $featured->isNotEmpty()
            ? $featured
            : self::published()->latest()->limit($limit)->get();
    }

    // ── Computed Attributes ──────────────────────────────────

    /**
     * Cover image URL, or null when none was uploaded — views then draw a
     * generated cover from the title instead.
     */
    public function getCoverUrlAttribute(): ?string
    {
        return $this->cover_image ? asset('storage/'.$this->cover_image) : null;
    }

    public function getFileUrlAttribute(): ?string
    {
        return $this->file_path ? asset('storage/'.$this->file_path) : null;
    }

    /**
     * Where the main "read" button points: the PDF itself, the linked site, or
     * the embedded viewer opened on its own.
     */
    public function getReadUrlAttribute(): ?string
    {
        return match ($this->source_type) {
            self::SOURCE_LINK => $this->external_url,
            self::SOURCE_EMBED => $this->embed_url,
            default => $this->file_url,
        };
    }

    public function getFormatLabelAttribute(): string
    {
        return self::formatOptions()[$this->source_type] ?? 'E-Book';
    }

    /**
     * Host name of the linked site (e.g. "drive.google.com"), shown next to
     * the button so visitors know they are leaving the website.
     */
    public function getExternalHostAttribute(): ?string
    {
        $host = parse_url((string) $this->read_url, PHP_URL_HOST);

        return is_string($host) ? preg_replace('/^www\./', '', $host) : null;
    }

    /**
     * Human-readable PDF size (e.g. "1.2 MB"), or null when unknown.
     */
    public function getFileSizeLabelAttribute(): ?string
    {
        $bytes = $this->file_size;

        if (! $bytes) {
            return null;
        }

        if ($bytes < 1_048_576) {
            return max(1, round($bytes / 1024)).' KB';
        }

        return round($bytes / 1_048_576, 1).' MB';
    }

    public function getDownloadFilenameAttribute(): string
    {
        return $this->original_filename ?: $this->slug.'.pdf';
    }
}
