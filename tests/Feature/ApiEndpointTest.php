<?php
/*
 * OVNEX — API uç noktası testleri
 * Tüm endpoint'lerin çalıştığını doğrular
 */
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiEndpointTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_stats_endpoint(): void
    {
        $response = $this->getJson('/api/stats');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'active_aircraft',
            'last_earthquake',
            'weather_temp',
            'active_traffic',
            'active_vessels',
            'total_news',
        ]);
    }

    public function test_aircraft_endpoint(): void
    {
        $response = $this->getJson('/api/aircraft');
        $response->assertStatus(200);
    }

    public function test_earthquakes_endpoint(): void
    {
        $response = $this->getJson('/api/earthquakes');
        $response->assertStatus(200);
    }

    public function test_earthquakes_recent_endpoint(): void
    {
        $response = $this->getJson('/api/earthquakes/recent');
        $response->assertStatus(200);
    }

    public function test_weather_current_endpoint(): void
    {
        $response = $this->getJson('/api/weather/current');
        $response->assertStatus(200);
    }

    public function test_weather_cities_endpoint(): void
    {
        $response = $this->getJson('/api/weather/cities');
        $response->assertStatus(200);
    }

    public function test_news_endpoint(): void
    {
        $response = $this->getJson('/api/news');
        $response->assertStatus(200);
    }

    public function test_news_latest_endpoint(): void
    {
        $response = $this->getJson('/api/news/latest');
        $response->assertStatus(200);
    }

    public function test_traffic_endpoint(): void
    {
        $response = $this->getJson('/api/traffic');
        $response->assertStatus(200);
    }

    public function test_vessels_endpoint(): void
    {
        $response = $this->getJson('/api/vessels');
        $response->assertStatus(200);
    }

    public function test_dashboard_page(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_admin_stats_page_redirects_when_guest(): void
    {
        $response = $this->get('/admin/stats');
        $response->assertStatus(302);
    }

    public function test_admin_stats_page_when_authenticated(): void
    {
        $user = \App\Models\User::factory()->create();
        $response = $this->actingAs($user)->get('/admin/stats');
        $response->assertStatus(200);
    }
}
