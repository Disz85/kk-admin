<?php

namespace Tests\Feature\Api;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticleApiTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        Article::factory(10)
            ->create();
    }

    /** @test */
    public function it_can_list_articles(): void
    {
        $this->getJson(route('api.articles.index'))
            ->assertOk();
    }

    /** @test */
    public function it_can_list_the_latest_3_articles_in_specific_order(): void
    {
        $latestArticles = Article::query()
            ->select('uuid')
            ->where('active', '=', 1)
            ->where('hidden', '=', 0)
            ->orderByDesc('updated_at')
            ->limit(3)
            ->pluck('uuid')
            ->toArray();

        $articleResponse = $this->getJson(route('api.articles.index', [
            'sort' => '-updated_at',
            'page' => 1,
            'per_page' => 3,
        ]));

        $articleResponse->assertOk()
            ->assertSeeInOrder($latestArticles);
    }

    // TODO: Add article relations, test accordingly.
}
