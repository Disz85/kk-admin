<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ArticleResource;
use App\Models\Article;
use OpenApi\Annotations as OA;

class ArticleStatusController extends Controller
{
    /**
     * Toggle the status of the specified article.
     *
     * @OA\Patch (
     *     tags={"Articles"},
     *     path="/admin/articles/{article}/status",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="article",
     *         in="path",
     *         required=true,
     *         description="Article ID",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Article status updated.",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error in fields."
     *     )
     * )
     *
     * @param Article $article
     * @return ArticleResource
     */
    public function __invoke(Article $article): ArticleResource
    {
        $article->is_active = ! $article->is_active;
        $article->save();

        return new ArticleResource($article->refresh());
    }
}
