<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ArticleResource;
use App\Http\Resources\Api\ArticleCollection;
use App\Models\Article;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Spatie\QueryBuilder\QueryBuilder;

class ArticleController extends Controller
{
    /**
     * Display a listing of articles.
     *
     * @OA\Get (
     *    tags={"Articles API"},
     *    path="/api/articles",
     *    @OA\Parameter (
     *        name="page",
     *        in="query",
     *        description="Page number",
     *        @OA\Schema(type="integer"),
     *        allowEmptyValue="true",
     *    ),
     *    @OA\Parameter (
     *        name="per_page",
     *        in="query",
     *        description="Page size",
     *        @OA\Schema(type="integer"),
     *        allowEmptyValue="true",
     *    ),
     *    @OA\Parameter (
     *        name="filter[title]",
     *        in="query",
     *        description="Title",
     *        @OA\Schema(type="string"),
     *    ),
     *    @OA\Response (
     *        response=200,
     *        description="Display a listing of articles.",
     *        @OA\JsonContent(ref="#/components/schemas/Article"),
     *    ),
     *    @OA\Response (
     *        response=404,
     *        description="No articles",
     *        @OA\JsonContent(),
     *    )
     * )
     *
     * @param Request $request
     * @return ArticleCollection
     */
    public function index(Request $request): ArticleCollection
    {
        $articles = Article::query()
            ->where('is_active', '=', 1);

        return new ArticleCollection(
            QueryBuilder::for($articles)
                ->allowedFilters('title')
                ->defaultSort('-published_at')
                ->allowedSorts([
                    'published_at',
                    'created_at',
                    'updated_at',
                    'title',
                ])
                ->paginate($request->get('per_page', 20))
                ->appends($request->query())
        );
    }

    /**
     * Display the specified article.
     *
     * @OA\Get (
     *     tags={"Articles API"},
     *     path="/api/articles/{article}",
     *     @OA\Parameter (
     *         name="article",
     *         in="path",
     *         required=true,
     *         description="Article UUID",
     *         @OA\Schema(type="string"),
     *     ),
     *    @OA\Response (
     *        response=200,
     *        description="Display a selected Article.",
     *        @OA\JsonContent(ref="#/components/schemas/Article"),
     *    ),
     *    @OA\Response (
     *        response=404,
     *        description="Article not found.",
     *        @OA\JsonContent(),
     *    )
     * )
     *
     * @param Article $article
     * @return ArticleResource
     */
    public function show(Article $article): ArticleResource
    {
        return new ArticleResource($article);
    }
}
