<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlainTextTest extends TestCase
{
    use RefreshDatabase;

    public function test_plain_text_strips_tags_decodes_entities_and_collapses_whitespace(): void
    {
        $this->assertSame(
            'Qurrota A\'yun & "Santri"',
            plain_text("<p>Qurrota  A&#039;yun</p>\n<p>&amp; &quot;Santri&quot;</p>"),
        );
        $this->assertSame('Spasi keras', plain_text('Spasi&nbsp;keras'));
        $this->assertSame('', plain_text(null));
    }

    public function test_blog_table_of_contents_shows_apostrophes_as_text(): void
    {
        $post = Post::factory()->create([
            'content' => '<h2>Apa itu Metode Talaqi?</h2><p>Isi.</p><h2>Sistem Hafalan di Qurrota A&#039;yun</h2><p>Isi.</p>',
        ]);

        $this->get(route('blog.show', $post->slug))
            ->assertOk()
            ->assertSeeInOrder(['Daftar Isi', 'Apa itu Metode Talaqi?', "Sistem Hafalan di Qurrota A'yun"])
            ->assertDontSee('A&amp;#039;yun', false);
    }

    public function test_meta_description_fallback_decodes_entities(): void
    {
        $post = Post::factory()->make([
            'excerpt' => null,
            'content' => '<p>Qurrota A&#039;yun &amp; santri</p>',
        ]);

        $this->assertSame("Qurrota A'yun & santri", $post->meta_description);
    }
}
