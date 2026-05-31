<?php

namespace Database\Factories;

use App\Models\NewsFeed;
use Illuminate\Database\Eloquent\Factories\Factory;

class NewsFeedFactory extends Factory
{
    protected $model = NewsFeed::class;

    public function definition(): array
    {
        return [
            'external_url' => fake()->unique()->url(),
            'source_name' => fake()->randomElement(['AA', 'TRT', 'Hürriyet']),
            'source_type' => 'rss',
            'title' => fake()->sentence(8),
            'summary' => fake()->paragraph(2),
            'category' => fake()->randomElement(['earthquake', 'traffic', 'fire', 'general', 'weather']),
            'severity' => fake()->randomElement(['low', 'medium', 'high']),
            'latitude' => fake()->latitude(35, 42),
            'longitude' => fake()->longitude(25, 45),
            'province' => fake()->city(),
            'published_at' => now()->subHours(fake()->numberBetween(0, 48)),
        ];
    }
}
