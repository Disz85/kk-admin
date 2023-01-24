<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Admin\ArticleResource;
use App\Models\Article;

class ArticleStatusController extends Controller
{
    /**
     * Toggle the status of the specified article.
     *
     * @OA\Patch (
     *     tags={"Articles"},
     *     path="/admin/articles/{article}/status",
     *     @OA\Parameter(
     *         name="article",
     *         in="path",
     *         required=true,
     *         description="Article ID",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Article status updated."
     *     ),
     *     @OA\Response(
     *         response=419,
     *         description="Error in fields"
     *     )
     * )
     *
     * @param Article $article
     * @return ArticleResource
     */
    public function __invoke(Article $article): ArticleResource
    {
        $article->active = ! $article->active;
        $article->save();

        return new ArticleResource($article->refresh());
    }
}
