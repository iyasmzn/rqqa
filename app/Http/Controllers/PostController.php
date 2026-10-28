<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    private const PER_PAGE = 9;

    /** Nilai query ?sort= untuk urutan pembaca terbanyak; selain itu = terbaru. */
    private const SORT_POPULAR = 'popular';

    private const VIEWED_SESSION_KEY = 'viewed_posts';

    /** User-agent crawler / link preview yang tidak dihitung sebagai pembaca. */
    private const BOT_PATTERN = '/bot|crawl|spider|slurp|facebookexternalhit|whatsapp|telegram|preview|headless|lighthouse/i';

    public function index(Request $request): View
    {
        $category = $request->query('category');
        $sort = $request->query('sort') === self::SORT_POPULAR ? self::SORT_POPULAR : null;

        $posts = Post::published()
            ->when($category, fn ($q) => $q->byCategory($category))
            ->when($sort === self::SORT_POPULAR, fn ($q) => $q->popular())
            ->latest('published_at')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        $categories = Post::published()
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $appName = config('app.name');

        $seo = [
            'title' => $category
                ? "{$category} — Blog | {$appName}"
                : "Blog & Berita Sekolah | {$appName}",
            'description' => $category
                ? "Baca artikel dan berita terbaru kategori {$category} dari {$appName}."
                : "Temukan berita, artikel, dan informasi terkini dari {$appName}. Prestasi siswa, kegiatan sekolah, dan pengumuman resmi.",
            'canonical' => route('blog.index', $category ? ['category' => $category] : []),
            'og_image' => asset('images/og-blog.jpg'),
        ];

        return view('blog.index', compact('posts', 'categories', 'category', 'sort', 'seo'));
    }

    public function show(Request $request, string $slug): View
    {
        $post = Post::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $this->trackView($request, $post);

        $related = Post::published()
            ->where('id', '!=', $post->id)
            ->where('category', $post->category)
            ->latest('published_at')
            ->limit(3)
            ->get();

        $seo = [
            'title' => "{$post->title} | ".config('app.name'),
            'description' => $post->meta_description,
            'canonical' => $post->canonical_url,
            'og_image' => $post->thumbnail_url,
            'og_type' => 'article',
            'published' => $post->published_at?->toIso8601String(),
            'author' => $post->author,
            'category' => $post->category,
        ];

        $postQuestions = Question::published()->answered()
            ->where('post_id', $post->id)
            ->orderByDesc('answered_at')
            ->get();

        $comments = $post->comments()
            ->approved()
            ->with('user')
            ->latest()
            ->get();

        return view('blog.show', compact('post', 'related', 'seo', 'postQuestions', 'comments'));
    }

    /**
     * Hitung satu pembaca per sesi per artikel; abaikan bot & link preview.
     */
    private function trackView(Request $request, Post $post): void
    {
        $userAgent = (string) $request->userAgent();

        if ($userAgent === '' || preg_match(self::BOT_PATTERN, $userAgent)) {
            return;
        }

        $viewedPostIds = $request->session()->get(self::VIEWED_SESSION_KEY, []);

        if (in_array($post->id, $viewedPostIds, true)) {
            return;
        }

        $post->recordView();
        $request->session()->push(self::VIEWED_SESSION_KEY, $post->id);
    }
}
