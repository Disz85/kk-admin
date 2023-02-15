<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Tag;
use Database\Factories\ArticleFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use Illuminate\Support\Arr;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function it_can_list_articles(): void
    {
        $this->getJson(route('admin.articles.index'))
            ->assertOk();
    }

    /** @test */
    public function it_can_create_an_article(): void
    {
        [
            'article' => $article,
            'authors' => $authors,
            'tags' => $tags,
            'categories' => $categories,
        ] = $this->makeDummyRequestData();

        $response = $this->postJson(route('admin.articles.store'), $article);

        $response->assertCreated();

        $this->assertDatabaseHas(Article::class, Arr::only($article, ['id', 'title','active']));

        $authors->every(fn ($author) => $response->assertJsonFragment([
            'name' => $author->name,
            'id' => $author->id,
        ]));

        $tags->every(fn ($tag) => $response->assertJsonFragment([
            'name' => $tag->name,
            'id' => $tag->id,
        ]));

        $categories->every(fn ($category) => $response->assertJsonFragment([
            'name' => $category->name,
            'id' => $category->id,
        ]));
    }

    /** @test */
    public function it_can_update_an_article(): void
    {
        $article = $this->createArticleWithRelations();

        [
            'article' => $updatedArticle,
            'authors' => $updatedAuthors,
            'tags' => $updatedTags,
            'categories' => $updatedCategories,
        ] = $this->makeDummyRequestData();

        $response = $this->putJson(
            route('admin.articles.update', ['article' => $article->id]),
            $updatedArticle
        );

        $response->assertOk();

        $this->assertDatabaseHas(Article::class, Arr::only($updatedArticle, ['id', 'title','is_active']));

        $updatedAuthors->every(fn ($author) => $response->assertJsonFragment([
            'name' => $author->name,
            'id' => $author->id,
        ]));

        $updatedTags->every(fn ($tag) => $response->assertJsonFragment([
            'name' => $tag->name,
            'id' => $tag->id,
        ]));

        $updatedCategories->every(fn ($category) => $response->assertJsonFragment([
            'name' => $category->name,
            'id' => $category->id,
        ]));
    }

    /** @test */
    public function it_can_show_an_article(): void
    {
        $article = $this->createArticleWithRelations();

        $response = $this->getJson(route('admin.articles.show', ['article' => $article->id]));

        $response->assertOk();

        $article->authors->every(fn ($author) => $response->assertJsonFragment([
            'name' => $author->name,
            'id' => $author->id,
        ]));

        $article->tags->every(fn ($tag) => $response->assertJsonFragment([
            'name' => $tag->name,
            'id' => $tag->id,
        ]));

        $article->categories->every(fn ($category) => $response->assertJsonFragment([
            'name' => $category->name,
            'id' => $category->id,
        ]));
    }

    /** @test */
    public function it_can_remove_an_article(): void
    {
        $article = Article::factory()->create();
        $this->deleteJson(route('admin.articles.destroy', ['article' => $article->id]))
            ->assertNoContent();
        $this->assertNull(Article::find($article->id));
    }

    private function makeDummyRequestData(): array
    {
        [
            'authors' => $authors,
            'tags' => $tags,
            'categories' => $categories
        ] = $this->articleRelations();

        $article = ArticleFactory::new()->raw();

        $article['image']['id'] = $article['image_id'];
        unset($article['image_id']);
        $article['authors'] = $authors->pluck('id')->toArray();
        $article['categories'] = $categories->pluck('id')->toArray();
        $article['tags'] = $tags->pluck('id')->toArray();

        return [
            'article' => $article,
            'authors' => $authors,
            'tags' => $tags,
            'categories' => $categories,
        ];
    }

    private function createArticleWithRelations(): Article
    {
        [
            'authors' => $authors,
            'tags' => $tags,
            'categories' => $categories
        ] = $this->articleRelations();

        return Article::factory()
            ->withAuthors($authors)
            ->withTags($tags)
            ->withCategories($categories)
            ->create();
    }

    private function articleRelations()
    {
        return [
            'authors' => Author::factory()->count(3)->create(),
            'categories' => Category::factory()->count(3)->create(),
            'tags' => Tag::factory()->count(2)->create(),
        ];
    }
}
