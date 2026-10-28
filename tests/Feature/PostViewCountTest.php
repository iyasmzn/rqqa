<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostViewCountTest extends TestCase
{
    use RefreshDatabase;

    private const BROWSER_USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36';

    public function test_new_post_starts_with_zero_views(): void
    {
        $post = Post::factory()->create();

        $this->assertSame(0, $post->fresh()->views_count);
    }

    public function test_visiting_a_post_increments_its_view_count(): void
    {
        $post = Post::factory()->create();

        $this->withHeader('User-Agent', self::BROWSER_USER_AGENT)
            ->get(route('blog.show', $post->slug))
            ->assertOk();

        $this->assertSame(1, $post->fresh()->views_count);
    }

    public function test_same_session_is_counted_only_once_per_post(): void
    {
        $post = Post::factory()->create();

        $this->withHeader('User-Agent', self::BROWSER_USER_AGENT);
        $this->get(route('blog.show', $post->slug))->assertOk();
        $this->get(route('blog.show', $post->slug))->assertOk();
        $this->get(route('blog.show', $post->slug))->assertOk();

        $this->assertSame(1, $post->fresh()->views_count);
    }

    public function test_a_new_session_is_counted_again(): void
    {
        $post = Post::factory()->create();

        $this->withHeader('User-Agent', self::BROWSER_USER_AGENT);
        $this->get(route('blog.show', $post->slug))->assertOk();
        $this->flushSession();
        $this->get(route('blog.show', $post->slug))->assertOk();

        $this->assertSame(2, $post->fresh()->views_count);
    }

    public function test_session_dedup_is_tracked_per_post(): void
    {
        [$first, $second] = Post::factory()->count(2)->create();

        $this->withHeader('User-Agent', self::BROWSER_USER_AGENT);
        $this->get(route('blog.show', $first->slug))->assertOk();
        $this->get(route('blog.show', $second->slug))->assertOk();

        $this->assertSame(1, $first->fresh()->views_count);
        $this->assertSame(1, $second->fresh()->views_count);
    }

    public function test_bots_and_link_previews_are_not_counted(): void
    {
        $post = Post::factory()->create();

        $userAgents = [
            'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
            'facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)',
            'WhatsApp/2.23.20.0',
            'TelegramBot (like TwitterBot)',
            'Mozilla/5.0 (X11; Linux x86_64) HeadlessChrome/130.0 Safari/537.36',
        ];

        foreach ($userAgents as $userAgent) {
            $this->withHeader('User-Agent', $userAgent)
                ->get(route('blog.show', $post->slug))
                ->assertOk();
        }

        $this->assertSame(0, $post->fresh()->views_count);
    }

    public function test_request_without_user_agent_is_not_counted(): void
    {
        $post = Post::factory()->create();

        $this->withHeader('User-Agent', '')
            ->get(route('blog.show', $post->slug))
            ->assertOk();

        $this->assertSame(0, $post->fresh()->views_count);
    }

    public function test_recording_a_view_does_not_touch_updated_at(): void
    {
        $post = Post::factory()->create();
        $post->forceFill(['updated_at' => now()->subWeek()])->saveQuietly();
        $originalUpdatedAt = $post->fresh()->updated_at;

        $this->withHeader('User-Agent', self::BROWSER_USER_AGENT)
            ->get(route('blog.show', $post->slug))
            ->assertOk();

        $post->refresh();
        $this->assertSame(1, $post->views_count);
        $this->assertTrue($post->updated_at->equalTo($originalUpdatedAt));
    }

    public function test_draft_post_is_not_found_and_not_counted(): void
    {
        $post = Post::factory()->draft()->create();

        $this->withHeader('User-Agent', self::BROWSER_USER_AGENT)
            ->get(route('blog.show', $post->slug))
            ->assertNotFound();

        $this->assertSame(0, $post->fresh()->views_count);
    }

    public function test_view_count_is_shown_on_post_and_blog_index(): void
    {
        $post = Post::factory()->create(['views_count' => 1233]);

        $this->withHeader('User-Agent', self::BROWSER_USER_AGENT)
            ->get(route('blog.show', $post->slug))
            ->assertOk()
            ->assertSee('1.234 kali dibaca')
            ->assertSee('"userInteractionCount": 1234', false);

        $this->get(route('blog.index'))
            ->assertOk()
            ->assertSee('1.234 kali dibaca');
    }
}
