<?php

namespace Tests\Unit;

use App\Models\Earthquake;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EarthquakeTest extends TestCase
{
    use RefreshDatabase;

    public function test_model_can_create_earthquake(): void
    {
        $eq = Earthquake::factory()->create();

        $this->assertDatabaseHas('earthquakes', ['id' => $eq->id]);
        $this->assertNotNull($eq->magnitude);
    }

    public function test_unique_external_id(): void
    {
        $eq1 = Earthquake::factory()->create(['external_id' => 'test-001']);
        $this->assertNotNull($eq1);

        $this->expectException(\Illuminate\Database\QueryException::class);
        Earthquake::factory()->create(['external_id' => 'test-001']);
    }
}
