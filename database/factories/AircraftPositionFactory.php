<?php

namespace Database\Factories;

use App\Models\AircraftPosition;
use Illuminate\Database\Eloquent\Factories\Factory;

class AircraftPositionFactory extends Factory
{
    protected $model = AircraftPosition::class;

    public function definition(): array
    {
        return [
            'icao24' => strtoupper(dechex(fake()->unique()->numberBetween(100000, 16777215))),
            'callsign' => strtoupper(fake()->randomLetter() . fake()->randomLetter() . fake()->randomNumber(4)),
            'origin_country' => fake()->country(),
            'latitude' => fake()->latitude(35.5, 42.1),
            'longitude' => fake()->longitude(25.0, 44.8),
            'altitude_baro' => fake()->randomFloat(2, 3000, 13000),
            'velocity' => fake()->randomFloat(2, 150, 300),
            'heading' => fake()->randomFloat(1, 0, 360),
            'vertical_rate' => fake()->randomFloat(1, -10, 10),
            'on_ground' => false,
            'recorded_at' => now(),
        ];
    }
}
