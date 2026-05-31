<?php

namespace Tests\Unit;

use App\Models\AircraftPosition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AircraftPositionTest extends TestCase
{
    use RefreshDatabase;

    public function test_model_can_create_aircraft(): void
    {
        $ac = AircraftPosition::factory()->create();

        $this->assertDatabaseHas('aircraft_positions', [
            'id' => $ac->id,
            'icao24' => $ac->icao24,
        ]);
    }

    public function test_active_aircraft_scope(): void
    {
        AircraftPosition::factory()->create(['recorded_at' => now()->subSeconds(30)]);
        AircraftPosition::factory()->create(['recorded_at' => now()->subMinutes(5)]);

        $active = AircraftPosition::where('recorded_at', '>=', now()->subMinute())->count();

        $this->assertEquals(1, $active);
    }
}
