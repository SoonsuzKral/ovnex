<?php

namespace Database\Factories;

use App\Models\Earthquake;
use Illuminate\Database\Eloquent\Factories\Factory;

class EarthquakeFactory extends Factory
{
    protected $model = Earthquake::class;

    public function definition(): array
    {
        return [
            'external_id' => 'afad-' . fake()->unique()->uuid(),
            'source' => fake()->randomElement(['AFAD', 'KANDILLI']),
            'latitude' => fake()->latitude(35, 42),
            'longitude' => fake()->longitude(25, 45),
            'depth_km' => fake()->randomFloat(2, 2, 30),
            'magnitude' => fake()->randomFloat(1, 1.5, 6.5),
            'magnitude_type' => 'ML',
            'location_name' => fake()->city() . ' Açıkları',
            'province' => fake()->city(),
            'occurred_at' => now()->subHours(fake()->numberBetween(0, 24)),
        ];
    }
}
