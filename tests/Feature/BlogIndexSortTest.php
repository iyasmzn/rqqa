<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class BlogIndexSortTest extends TestCase
{
    use RefreshDatabase;

    public function test_blog_is_sorted_by_latest_by_default(): void
    {
        $oldPopular = Post::factory()->create(['published_at' => now()->subDays(10), 'views_count' => 500]);
        $newQuiet = Post::factory()->create(['published_at' => now()->subDay(), 'views_count' => 3]);

        $this->get(route('blog.index'))
            ->assertOk()
            ->assertViewHas('sort', null)
            ->assertViewHas('posts', $this->idsInOrder([$newQuiet, $oldPopular]))
            ->assertSee('✦ Unggulan');
    }

    public function test_popular_sort_orders_by_views_then_latest(): void
    {
        $quiet = Post::factory()->create(['published_at' => now()->subDay(), 'views_count' => 3]);
        $popular = Post::factory()->create(['published_at' => now()->subDays(10), 'views_count' => 500]);
        $tiedOlder = Post::factory()->create(['published_at' => now()->subDays(5), 'views_count' => 40]);
        $tiedNewer = Post::factory()->create(['published_at' => now()->subDays(2), 'views_count' => 40]);

        $this->get(route('blog.index', ['sort' => 'popular']))
            ->assertOk()
            ->assertViewHas('sort', 'popular')
            ->assertViewHas('posts', $this->idsInOrder([$popular, $tiedNewer, $tiedOlder, $quiet]))
            ->assertSee('🔥 Terpopuler');
    }

    public function test_popular_sort_respects_category_filter(): void
    {
        $news = Post::factory()->create(['category' => 'Berita', 'views_count' => 10]);
        $popularNews = Post::factory()->create(['category' => 'Berita', 'views_count' => 90]);
        Post::factory()->create(['category' => 'Prestasi', 'views_count' => 999]);

        $this->get(route('blog.index', ['category' => 'Berita', 'sort' => 'popular']))
            ->assertOk()
            ->assertViewHas('posts', $this->idsInOrder([$popularNews, $news]));
    }

    public function test_popular_sort_excludes_unpublished_posts(): void
    {
        $published = Post::factory()->create(['views_count' => 5]);
        Post::factory()->draft()->create(['views_count' => 1000]);
        Post::factory()->create(['published_at' => now()->addWeek(), 'views_count' => 1000]);

        $this->get(route('blog.index', ['sort' => 'popular']))
            ->assertOk()
            ->assertViewHas('posts', $this->idsInOrder([$published]));
    }

    public function test_unknown_sort_value_falls_back_to_latest(): void
    {
        $oldPopular = Post::factory()->create(['published_at' => now()->subDays(10), 'views_count' => 500]);
        $newQuiet = Post::factory()->create(['published_at' => now()->subDay(), 'views_count' => 3]);

        $this->get(route('blog.index', ['sort' => 'random']))
            ->assertOk()
            ->assertViewHas('sort', null)
            ->assertViewHas('posts', $this->idsInOrder([$newQuiet, $oldPopular]));
    }

    public function test_array_sort_value_falls_back_to_latest(): void
    {
        Post::factory()->create();

        $this->get('/blog?sort[]=popular')
            ->assertOk()
            ->assertViewHas('sort', null);
    }

    public function test_category_and_sort_links_preserve_each_other(): void
    {
        Post::factory()->create(['category' => 'Berita']);

        $this->get(route('blog.index', ['category' => 'Berita', 'sort' => 'popular']))
            ->assertOk()
            // Pill "Semua" tetap membawa urutan populer.
            ->assertSee($this->href(['sort' => 'popular']), false)
            // Toggle urutan tetap membawa kategori aktif; "Terpopuler" yang aktif.
            ->assertSeeInOrder([
                'aria-label="Urutkan artikel"',
                $this->href(['category' => 'Berita']),
                'Terbaru',
                $this->href(['category' => 'Berita', 'sort' => 'popular']),
                'aria-current="true"',
                'Terpopuler',
            ], false);
    }

    public function test_featured_label_is_only_popular_on_the_first_page(): void
    {
        foreach (range(1, 10) as $views) {
            Post::factory()->create(['views_count' => $views]);
        }

        $this->get(route('blog.index', ['sort' => 'popular', 'page' => 2]))
            ->assertOk()
            ->assertSee('✦ Unggulan')
            ->assertDontSee('🔥 Terpopuler');
    }

    public function test_pagination_links_keep_the_sort(): void
    {
        foreach (range(1, 10) as $views) {
            Post::factory()->create(['views_count' => $views]);
        }

        $this->get(route('blog.index', ['sort' => 'popular']))
            ->assertOk()
            ->assertSee(e(route('blog.index', ['sort' => 'popular', 'page' => 2])), false);
    }

    /**
     * @param  array<int, Post>  $expected
     */
    private function idsInOrder(array $expected): \Closure
    {
        $expectedIds = array_map(fn (Post $post): int => $post->id, $expected);

        return fn (LengthAwarePaginator $posts): bool => $posts->pluck('id')->all() === $expectedIds;
    }

    /**
     * @param  array<string, string>  $query
     */
    private function href(array $query): string
    {
        return 'href="'.e(route('blog.index', $query)).'"';
    }
}
