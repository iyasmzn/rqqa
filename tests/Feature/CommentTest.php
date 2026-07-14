<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_post_comment_and_is_redirected_to_login(): void
    {
        $post = Post::factory()->create(['allow_comments' => true]);

        $response = $this->post(route('comments.store', $post), [
            'body' => 'Komentar dari tamu.',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseCount('comments', 0);
    }

    public function test_authenticated_user_can_post_comment(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['allow_comments' => true]);

        $response = $this->actingAs($user)->post(route('comments.store', $post), [
            'body' => 'Artikel yang bermanfaat, terima kasih.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('comment_success');

        $this->assertDatabaseHas('comments', [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'body' => 'Artikel yang bermanfaat, terima kasih.',
            'is_approved' => true,
        ]);
    }

    public function test_comment_is_rejected_when_post_disallows_comments(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['allow_comments' => false]);

        $response = $this->actingAs($user)->post(route('comments.store', $post), [
            'body' => 'Mencoba berkomentar.',
        ]);

        $response->assertSessionHasErrors('body');
        $this->assertDatabaseCount('comments', 0);
    }

    public function test_comment_body_is_required(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['allow_comments' => true]);

        $response = $this->actingAs($user)->post(route('comments.store', $post), [
            'body' => '',
        ]);

        $response->assertSessionHasErrors('body');
        $this->assertDatabaseCount('comments', 0);
    }

    public function test_approved_comments_are_shown_on_the_article_page(): void
    {
        $post = Post::factory()->create(['allow_comments' => true]);
        $visible = Comment::factory()->create(['post_id' => $post->id, 'body' => 'Komentar tampil']);
        $hidden = Comment::factory()->hidden()->create(['post_id' => $post->id, 'body' => 'Komentar tersembunyi']);

        $response = $this->get(route('blog.show', $post->slug));

        $response->assertStatus(200);
        $response->assertSee('Komentar tampil');
        $response->assertDontSee('Komentar tersembunyi');
    }
}
