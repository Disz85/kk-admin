<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Http\Resources\Admin\TagCollection;
use App\Http\Resources\Admin\TagResource;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * @resource Tags
 * @package App\Http\Controllers
 */
class TagController extends Controller
{
    /**
     * List of Tags.
     * @OA\Get(
     *    tags={"Tags"},
     *    path="/admin/tags",
     *    @OA\Response(
     *      response="200",
     *      description="Display a listing of tags."
     *    ),
     *    @OA\MediaType(
     *      mediaType="application/json"
     *    ),
     *    @OA\Parameter(
     *      name="page",
     *      in="query",
     *      description="Page number",
     *      @OA\Schema(
     *          type="integer"
     *      )
     *    )
     * )
     *
     * Display a list of the resource.
     *
     * @param Request $request
     * @return TagCollection
     */
    public function index(Request $request): TagCollection
    {
        return new TagCollection(
            Tag::query()
                ->when(
                    $request->has('name'),
                    fn (Builder $query) => $query->where('name', 'like', '%' . $request->get('name') . '%')
                )
                ->orderByDesc('updated_at')
                ->paginate($request->get('size', 20))
        );
    }

    /**
     * Store a newly created Tag.
     *
     * @OA\Post (
     *     tags={"Tags"},
     *     path="/admin/tags",
     *     @OA\MediaType(
     *         mediaType="application/json"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name"},
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="Name.",
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     nullable=true,
     *                     description="Desciption.",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tag created."
     *     ),
     *     @OA\Response(
     *         response=419,
     *         description="Error in fields"
     *     ),
     * )
     *
     * @param StoreTagRequest $request
     * @return TagResource
     */
    public function store(StoreTagRequest $request): TagResource
    {
        $tag = Tag::create($request->validated());
        $tag->generateSlug();
        $tag->save();

        return new TagResource($tag);
    }

    /**
     * Show a selected Tag.
     * @OA\Get(
     *    tags={"Tags"},
     *    path="/admin/tags/{tag}",
     *    @OA\Response(
     *      response="200",
     *      description="Display a selected Tag."
     *    ),
     *    @OA\MediaType(
     *      mediaType="application/json"
     *    ),
     *    @OA\Parameter(
     *         name="tag",
     *         in="path",
     *         required=true,
     *         description="Tag ID",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *    ),
     *    @OA\Response(
     *        response=404,
     *        description="Tag Not Found."
     *    )
     * )
     * @param Tag $tag
     * @return TagResource
     */
    public function show(Tag $tag): TagResource
    {
        return new TagResource($tag);
    }

    /**
     * Update Tag.
     *
     * @OA\Put (
     *     tags={"Tags"},
     *     path="/admin/tags/{tag}",
     *     @OA\MediaType(
     *         mediaType="application/json"
     *     ),
     *    @OA\Parameter(
     *      name="tag",
     *      in="path",
     *      required=true,
     *      description="integer",
     *      @OA\Schema(
     *          type="string"
     *      )
     *    ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 required={"name"},
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="Name.",
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     nullable=true,
     *                     description="Desciption.",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tag updated."
     *     ),
     *     @OA\Response(
     *         response=419,
     *         description="Error in fields"
     *     ),
     * )
     *
     * @param UpdateTagRequest $request
     * @param Tag $tag
     * @return TagResource
     */
    public function update(UpdateTagRequest $request, Tag $tag): TagResource
    {
        $tag->fill($request->validated());
        $tag->save();

        return new TagResource($tag);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @OA\Delete (
     *     tags={"Tags"},
     *     path="/admin/tags/{tag}",
     *     @OA\MediaType(
     *         mediaType="application/json"
     *     ),
     *    @OA\Parameter(
     *      name="tag",
     *      in="path",
     *      description="integer",
     *      @OA\Schema(
     *          type="string"
     *      )
     *    ),
     *    @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *         )
     *    ),
     *    @OA\Response(
     *        response=204,
     *        description="No Content, Tag deleted."
     *    ),
     *    @OA\Response(
     *        response=404,
     *        description="Tag Not Found."
     *    ),
     * )
     *
     * @param Tag $tag
     * @return JsonResponse
     * @throws Throwable
     */
    public function destroy(Tag $tag): JsonResponse
    {
        $tag->deleteOrFail();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
