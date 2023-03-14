<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\Admin\ArticleCollection;
use App\Http\Resources\Admin\ArticleResource;
use App\Models\Article;
use App\RequestMappers\ArticleRequestMapper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ArticleController extends Controller
{
    /**
     * Display a listing of articles.
     *
     * @OA\Get(
     *    tags={"Articles"},
     *    path="/admin/articles",
     *    security={{"bearer":{}}},
     *    @OA\Parameter(
     *        name="page",
     *        in="query",
     *        description="Page number",
     *        @OA\Schema(type="integer"),
     *        allowEmptyValue="true"
     *    ),
     *    @OA\Parameter(
     *        name="per_page",
     *        in="query",
     *        description="Page size",
     *        @OA\Schema(type="integer"),
     *        allowEmptyValue="true"
     *    ),
     *    @OA\Parameter(
     *        name="title",
     *        in="query",
     *        description="Title",
     *        @OA\Schema(type="string")
     *    ),
     *    @OA\Response(
     *        response=200,
     *        description="Display a listing of articles.",
     *        @OA\JsonContent(ref="#/components/schemas/Article")
     *    ),
     *    @OA\Response(
     *        response=404,
     *        description="No articles",
     *        @OA\JsonContent()
     *    )
     * )
     *
     * @param Request $request
     * @return ArticleCollection
     */
    public function index(Request $request): ArticleCollection
    {
        return new ArticleCollection(
            Article::query()
                ->when(
                    $request->has('title'),
                    fn (Builder $query) => $query->where('title', 'like', '%' . $request->get('title') . '%')
                )
                ->orderByDesc('published_at')
                ->paginate($request->get('size', 20))
        );
    }

    /**
     * Store a newly created article.
     *
     * @OA\Post (
     *     tags={"Articles"},
     *     path="/admin/articles",
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title", "body", "image[id]"},
     *                 @OA\Property (
     *                     property="title",
     *                     type="string",
     *                     description="title"
     *                 ),
     *                 @OA\Property (
     *                     property="lead",
     *                     type="string",
     *                     description="Lead"
     *                 ),
     *                 @OA\Property (
     *                     property="body",
     *                     type="string",
     *                     description="Body"
     *                 ),
     *                 @OA\Property (
     *                     property="is_active",
     *                     type="integer",
     *                     description="Is active? 1|0",
     *                     example="0"
     *                 ),
     *                 @OA\Property (
     *                     property="is_sponsored",
     *                     type="integer",
     *                     description="Is sponsored? 1|0",
     *                     example="0"
     *                 ),
     *                 @OA\Property (
     *                     property="is_18_plus",
     *                     type="integer",
     *                     description="Is 18 plus content? 1|0",
     *                     example="0"
     *                 ),
     *                 @OA\Property (
     *                     property="image[id]",
     *                     type="integer",
     *                     description="Image ID"
     *                 ),
     *                 @OA\Property (
     *                     property="authors[0]",
     *                     type="integer",
     *                     description="Author #1 id"
     *                 ),
     *                 @OA\Property (
     *                     property="authors[1]",
     *                     type="integer",
     *                     description="Author #2 id"
     *                 ),
     *                 @OA\Property (
     *                     property="categories[0]",
     *                     type="integer",
     *                     description="Category #1 id"
     *                 ),
     *                 @OA\Property (
     *                     property="categories[1]",
     *                     type="integer",
     *                     description="Category #2 id"
     *                 ),
     *                 @OA\Property (
     *                     property="tags[0]",
     *                     type="integer",
     *                     description="Tag #1 id"
     *                 ),
     *                 @OA\Property (
     *                     property="tags[1]",
     *                     type="integer",
     *                     description="Tag #2 id"
     *                 ),
     *                 @OA\Property (
     *                     property="published_at",
     *                     type="datetime",
     *                     @OA\Schema(
     *                         type="string",
     *                         format="date-time",
     *                     ),
     *                     description="Format: Y-m-d H:i:s",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Article created.",
     *         @OA\JsonContent(ref="#/components/schemas/Article")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error in fields.",
     *     )
     * )
     *
     *
     * @param StoreArticleRequest $request
     * @param Article $article
     * @param ArticleRequestMapper $articleRequestMapper
     * @return ArticleResource
     */
    public function store(StoreArticleRequest $request, Article $article, ArticleRequestMapper $articleRequestMapper): ArticleResource
    {
        return new ArticleResource($articleRequestMapper->map($article, $request->validated()));
    }

    /**
     * Display the specified article.
     *
     * @OA\Get(
     *     tags={"Articles"},
     *     path="/admin/articles/{article}",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="article",
     *         in="path",
     *         required=true,
     *         description="Article ID",
     *         @OA\Schema(type="integer"),
     *     ),
     *    @OA\Response(
     *        response=200,
     *        description="Display a selected Article.",
     *        @OA\JsonContent(ref="#/components/schemas/Article")
     *    ),
     *    @OA\Response(
     *        response=404,
     *        description="Article not found.",
     *        @OA\JsonContent()
     *    )
     * )
     *
     * @param Article $article
     * @return ArticleResource
     */
    public function show(Article $article): ArticleResource
    {
        $article->load(['authors', 'tags', 'categories']);

        return new ArticleResource($article);
    }

    /**
     * Update the specified article.
     *
     * @OA\Put (
     *     tags={"Articles"},
     *     path="/admin/articles/{article}",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="article",
     *         in="path",
     *         required=true,
     *         description="Article ID",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 required={"title", "body", "image[id]"},
     *                 @OA\Property (
     *                     property="title",
     *                     type="string",
     *                     description="Title"
     *                 ),
     *                 @OA\Property (
     *                     property="lead",
     *                     type="string",
     *                     description="Lead"
     *                 ),
     *                 @OA\Property (
     *                     property="body",
     *                     type="string",
     *                     description="Body"
     *                 ),
     *                 @OA\Property (
     *                     property="is_active",
     *                     type="integer",
     *                     description="Is active? 1|0",
     *                     example="0"
     *                 ),
     *                 @OA\Property (
     *                     property="is_sponsored",
     *                     type="integer",
     *                     description="Is sponsored? 1|0",
     *                     example="0"
     *                 ),
     *                 @OA\Property (
     *                     property="is_18_plus",
     *                     type="integer",
     *                     description="Is 18 plus content? 1|0",
     *                     example="0"
     *                 ),
     *                 @OA\Property (
     *                     property="image[id]",
     *                     type="integer",
     *                     description="Image ID"
     *                 ),
     *                 @OA\Property (
     *                     property="authors[0]",
     *                     type="integer",
     *                     description="Author #1 id"
     *                 ),
     *                 @OA\Property (
     *                     property="authors[1]",
     *                     type="integer",
     *                     description="Author #2 id"
     *                 ),
     *                 @OA\Property (
     *                     property="categories[0]",
     *                     type="integer",
     *                     description="Category #1 id"
     *                 ),
     *                 @OA\Property (
     *                     property="categories[1]",
     *                     type="integer",
     *                     description="Category #2 id"
     *                 ),
     *                 @OA\Property (
     *                     property="tags[0]",
     *                     type="integer",
     *                     description="Tag #1 id"
     *                 ),
     *                 @OA\Property (
     *                     property="tags[1]",
     *                     type="integer",
     *                     description="Tag #2 id"
     *                 ),
     *                 @OA\Property (
     *                     property="published_at",
     *                     type="datetime",
     *                     @OA\Schema(
     *                         type="string",
     *                         format ="date-time",
     *                     ),
     *                     description="Format: Y-m-d H:i:s",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Article updated.",
     *         @OA\JsonContent(ref="#/components/schemas/Article"),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error in fields."
     *     )
     * )
     *
     * @param UpdateArticleRequest $request
     * @param Article $article
     * @param ArticleRequestMapper $articleRequestMapper
     * @return ArticleResource
     */
    public function update(UpdateArticleRequest $request, Article $article, ArticleRequestMapper $articleRequestMapper): ArticleResource
    {
        return new ArticleResource($articleRequestMapper->map($article, $request->validated()));
    }

    /**
     * Remove the specified article.
     *
     * @OA\Delete (
     *     tags={"Articles"},
     *     path="/admin/articles/{article}",
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(mediaType="application/x-www-form-urlencoded")
     *     ),
     *     @OA\Parameter(
     *         name="article",
     *         in="path",
     *         description="Article ID",
     *         @OA\Schema(type="string")
     *    ),
     *    @OA\Response(
     *        response=204,
     *        description="Article deleted."
     *    ),
     *    @OA\Response(
     *        response=404,
     *        description="Article not found.",
     *        @OA\JsonContent()
     *    )
     * )
     *
     * @param Article $article
     * @return JsonResponse
     * @throws Throwable
     */
    public function destroy(Article $article): JsonResponse
    {
        $article->deleteOrFail();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
