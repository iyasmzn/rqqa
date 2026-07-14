<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $replied = fake()->boolean(40);

        return [
            'post_id' => Post::factory(),
            'user_id' => User::factory(),
            'body' => fake()->paragraph(),
            'admin_reply' => $replied ? fake()->paragraph() : null,
            'replied_at' => $replied ? fake()->dateTimeBetween('-3 months', 'now') : null,
            'is_approved' => true,
        ];
    }

    public function replied(): static
    {
        return $this->state([
            'admin_reply' => fake()->paragraph(),
            'replied_at' => now(),
        ]);
    }

    public function hidden(): static
    {
        return $this->state(['is_approved' => false]);
    }
}
