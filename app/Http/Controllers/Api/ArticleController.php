<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\ArticleCollection;
use App\Models\Article;

class ArticleController extends Controller
{
    /**
     * Display a listing of the brands.
     *
     * @OA\Get(
     *    tags={"Articles Api"},
     *    path="/api/articles/get-header-articles",
     *    @OA\Response(
     *      response="200",
     *      description="Display the last 3 published articles.",
     *      @OA\JsonContent()
     *    ),
     * )
     *
     * @return ArticleCollection
     */
    public function getHeaderArticles(): ArticleCollection
    {
        $articles = Article::query()
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        return new ArticleCollection($articles);
    }
}
