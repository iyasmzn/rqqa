<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostCardTest extends TestCase
{
    use RefreshDatabase;

    private const GRID_HEADING = ' class="font-bold text-base leading-snug';

    private const FEATURED_HEADING = ' class="text-xl sm:text-2xl font-extrabold';

    public function test_blog_page_uses_the_shared_cards_with_h2_headings(): void
    {
        Post::factory()->count(2)->create();

        $this->get(route('blog.index'))
            ->assertOk()
            ->assertSee('<h2'.self::FEATURED_HEADING, false)
            ->assertSee('<h2'.self::GRID_HEADING, false)
            ->assertSee('itemtype="https://schema.org/Article"', false);
    }

    public function test_home_page_uses_the_shared_cards_with_h3_headings(): void
    {
        Post::factory()->count(2)->create();

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('<h3'.self::FEATURED_HEADING, false)
            ->assertSee('<h3'.self::GRID_HEADING, false)
            ->assertSee('itemtype="https://schema.org/Article"', false);
    }

    public function test_featured_card_shows_reading_time_with_a_clock_icon(): void
    {
        Post::factory()->create(['published_at' => now()->subHour(), 'read_time' => 5]);
        Post::factory()->create(['published_at' => now()->subDays(2)]);

        // Dimulai dari tag artikel agar ikon jam di luar kartu (mis. toggle
        // urutan "Terbaru") tidak ikut terhitung.
        $sequence = ['itemtype="https://schema.org/Article"', 'M12 8v4l3 3', '5 mnt baca'];

        $this->get(route('blog.index'))->assertSeeInOrder($sequence, false);
        $this->get(route('home'))->assertSeeInOrder($sequence, false);
    }

    public function test_only_the_blog_page_labels_its_featured_card(): void
    {
        Post::factory()->count(2)->create();

        $this->get(route('blog.index'))->assertSee('✦ Unggulan');
        $this->get(route('home'))->assertDontSee('✦ Unggulan');
    }
}
