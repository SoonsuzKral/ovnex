<?php

namespace Tests\Unit;

use App\Models\NewsFeed;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsFeedTest extends TestCase
{
    use RefreshDatabase;

    public function test_model_can_create_news(): void
    {
        $news = NewsFeed::factory()->create();

        $this->assertDatabaseHas('news_feeds', ['id' => $news->id]);
        $this->assertNotNull($news->title);
    }

    public function test_news_categories(): void
    {
        $validCategories = ['earthquake', 'traffic', 'fire', 'general', 'weather'];

        foreach ($validCategories as $cat) {
            $news = NewsFeed::factory()->create(['category' => $cat]);
            $this->assertEquals($cat, $news->category);
        }
    }
}
