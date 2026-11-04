<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EbookController extends Controller
{
    private const PER_PAGE = 12;

    /**
     * Pilihan urutan di halaman daftar e-book.
     *
     * @var array<string, string>
     */
    public const SORTS = [
        'default' => 'Urutan Default',
        'newest' => 'Terbaru',
        'title_asc' => 'Judul A–Z',
        'year_desc' => 'Tahun Terbit Terbaru',
    ];

    public function index(Request $request): View
    {
        $q = $this->stringQuery($request, 'q');
        $category = $this->stringQuery($request, 'category');
        $format = $this->stringQuery($request, 'format');
        $sort = $this->stringQuery($request, 'sort') ?: 'default';

        if (! array_key_exists($format, Ebook::formatOptions())) {
            $format = '';
        }

        if (! array_key_exists($sort, self::SORTS)) {
            $sort = 'default';
        }

        $ebooks = Ebook::published()
            ->when($q !== '', fn ($query) => $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('author', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            }))
            ->when($category !== '', fn ($query) => $query->where('category', $category))
            ->when($format !== '', fn ($query) => $query->where('source_type', $format))
            ->when($sort === 'newest', fn ($query) => $query->latest())
            ->when($sort === 'title_asc', fn ($query) => $query->orderBy('title'))
            ->when($sort === 'year_desc', fn ($query) => $query->orderByDesc('published_year')->orderBy('title'))
            ->when($sort === 'default', fn ($query) => $query->ordered())
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        $categories = Ebook::published()
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $activeFilters = collect([$q !== '', $category !== '', $format !== ''])->filter()->count();

        $siteName = setting('site_name', config('app.name'));

        $seo = [
            'title' => "E-Book | {$siteName}",
            'description' => "Baca dan unduh e-book pilihan dari {$siteName}: buku pelajaran, modul, kitab, dan bacaan bermanfaat lainnya.",
            'canonical' => route('ebooks.index'),
        ];

        return view('ebooks.index', [
            'ebooks' => $ebooks,
            'categories' => $categories,
            'formats' => Ebook::formatOptions(),
            'sorts' => self::SORTS,
            'q' => $q,
            'category' => $category,
            'format' => $format,
            'sort' => $sort,
            'activeFilters' => $activeFilters,
            'seo' => $seo,
        ]);
    }

    public function show(Ebook $ebook): View
    {
        abort_unless($ebook->is_published, 404);

        $related = Ebook::published()
            ->whereKeyNot($ebook->getKey())
            ->where('category', $ebook->category)
            ->ordered()
            ->limit(4)
            ->get();

        $siteName = setting('site_name', config('app.name'));

        $seo = [
            'title' => "{$ebook->title} | {$siteName}",
            'description' => $ebook->description
                ? Str::limit(plain_text($ebook->description), 155)
                : "Baca e-book {$ebook->title} di {$siteName}.",
            'canonical' => route('ebooks.show', $ebook),
            'og_type' => 'book',
            'og_image' => $ebook->cover_url,
        ];

        return view('ebooks.show', compact('ebook', 'related', 'seo'));
    }

    /**
     * A trimmed query-string value, or an empty string when the parameter is
     * missing or was sent as an array (`?q[]=…`).
     */
    private function stringQuery(Request $request, string $key): string
    {
        $value = $request->query($key);

        return is_string($value) ? trim($value) : '';
    }
}
