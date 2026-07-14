<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuestionTest extends TestCase
{
    use RefreshDatabase;

    public function test_visitor_can_submit_question_when_post_allows_questions(): void
    {
        $post = Post::factory()->create(['allow_questions' => true]);

        $response = $this->post(route('questions.store'), [
            'post_id' => $post->id,
            'name' => 'Budi',
            'question' => 'Apa syarat pendaftaran?',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('questions', [
            'post_id' => $post->id,
            'name' => 'Budi',
            'question' => 'Apa syarat pendaftaran?',
        ]);
    }

    public function test_question_is_rejected_when_post_disallows_questions(): void
    {
        $post = Post::factory()->create(['allow_questions' => false]);

        $response = $this->post(route('questions.store'), [
            'post_id' => $post->id,
            'name' => 'Budi',
            'question' => 'Mencoba bertanya.',
        ]);

        $response->assertSessionHasErrors('question');
        $this->assertDatabaseCount('questions', 0);
    }

    public function test_general_question_without_post_is_still_accepted(): void
    {
        $response = $this->post(route('questions.store'), [
            'name' => 'Budi',
            'question' => 'Pertanyaan umum.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('questions', [
            'post_id' => null,
            'question' => 'Pertanyaan umum.',
        ]);
    }

    public function test_question_form_is_hidden_when_post_disallows_questions(): void
    {
        $post = Post::factory()->create(['allow_questions' => false]);

        $response = $this->get(route('blog.show', $post->slug));

        $response->assertStatus(200);
        $response->assertSee('Kolom tanya jawab untuk artikel ini ditutup.');
        $response->assertDontSee('Kirim Pertanyaan');
    }
}
