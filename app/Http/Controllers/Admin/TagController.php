<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DeleteTagRequest;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Http\Resources\Admin\TagCollection;
use App\Http\Resources\Admin\TagResource;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
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
     *    @OA\Parameter(
     *      name="page",
     *      in="query",
     *      description="Page number",
     *      @OA\JsonContent(),
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
     *                 @OA\Property(
     *                     property="is_highlighted",
     *                     type="integer",
     *                     description="1|0",
     *                     example="0",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tag created.",
     *         @OA\JsonContent(),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error in fields."
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
     *      response="200",
     *      description="Display a selected Tag.",
     *      @OA\JsonContent(),
     *    ),
     *    @OA\Response(
     *        response=404,
     *        description="Tag Not Found.",
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
     *                 @OA\Property(
     *                     property="is_highlighted",
     *                     type="integer",
     *                     description="1|0",
     *                     example="0",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tag updated.",
     *         @OA\JsonContent(),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error in fields."
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
     *    @OA\Parameter(
     *      name="tag",
     *      in="path",
     *      description="integer",
     *      @OA\Schema(
     *          type="string"
     *      )
     *    ),
     *    @OA\RequestBody(
     *      required=false,
     *    ),
     *    @OA\Response(
     *      response=204,
     *      description="Tag deleted.",
     *      @OA\JsonContent(),
     *    ),
     *    @OA\Response(
     *      response=404,
     *      description="Tag not found."
     *    ),
     *    @OA\Response(
     *      response=422,
     *      description="Tag cannot be deleted due to existence of related resources."
     *    ),
     * )
     *
     * @param DeleteTagRequest $request
     * @param Tag $tag
     * @return JsonResponse
     * @throws Throwable
     */
    public function destroy(DeleteTagRequest $request, Tag $tag): JsonResponse
    {
        $tag->deleteOrFail();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
