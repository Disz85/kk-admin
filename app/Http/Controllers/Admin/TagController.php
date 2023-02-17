<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteTagRequest;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Http\Resources\Admin\TagCollection;
use App\Http\Resources\Admin\TagResource;
use App\Models\Tag;
use App\RequestMappers\TagRequestMapper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TagController extends Controller
{
    /**
     * @OA\Get(
     *    tags={"Tags"},
     *    path="/admin/tags",
     *    security={{"bearer":{}}},
     *    @OA\Parameter(
     *      name="page",
     *      in="query",
     *      description="Page number",
     *      @OA\Schema(type="integer"),
     *      allowEmptyValue="true",
     *    ),
     *    @OA\Parameter(
     *      name="name",
     *      in="query",
     *      description="Filter by name",
     *      @OA\Schema(type="string"),
     *    ),
     *    @OA\Response(
     *      response=200,
     *      description="Display a listing of tags.",
     *      @OA\JsonContent(ref="#/components/schemas/Tag"),
     *    ),
     *    @OA\Response(
     *      response=404,
     *      description="No tags.",
     *      @OA\JsonContent(),
     *    )
     * )
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
     *     security={{"bearer":{}}},
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
     *         response=201,
     *         description="Tag created.",
     *         @OA\JsonContent(ref="#/components/schemas/Tag"),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error in fields."
     *     ),
     * )
     *
     * @param StoreTagRequest $request
     * @param Tag $tag
     * @param TagRequestMapper $tagRequestMapper
     * @return TagResource
     */
    public function store(StoreTagRequest $request, Tag $tag, TagRequestMapper $tagRequestMapper): TagResource
    {
        return new TagResource($tagRequestMapper->map($tag, $request->validated()));
    }

    /**
     * Show a selected tag.
     *
     * @OA\Get(
     *    tags={"Tags"},
     *    path="/admin/tags/{tag}",
     *    security={{"bearer":{}}},
     *    @OA\Parameter(
     *        name="tag",
     *        in="path",
     *        required=true,
     *        description="Tag ID",
     *        @OA\Schema(type="integer"),
     *    ),
     *    @OA\Response(
     *        response=200,
     *        description="Display a selected tag.",
     *        @OA\JsonContent(ref="#/components/schemas/Tag"),
     *    ),
     *    @OA\Response(
     *        response=404,
     *        description="Tag not found.",
     *        @OA\JsonContent(),
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
     *    tags={"Tags"},
     *    path="/admin/tags/{tag}",
     *    security={{"bearer":{}}},
     *    @OA\Parameter(
     *      name="tag",
     *      in="path",
     *      required=true,
     *      description="integer",
     *      @OA\Schema(type="string"),
     *    ),
     *    @OA\RequestBody(
     *        required=true,
     *        @OA\MediaType(
     *            mediaType="application/x-www-form-urlencoded",
     *            @OA\Schema(
     *                required={"name"},
     *                @OA\Property(
     *                    property="name",
     *                    type="string",
     *                    description="Name.",
     *                ),
     *                @OA\Property(
     *                    property="description",
     *                    type="string",
     *                    nullable=true,
     *                    description="Desciption.",
     *                ),
     *                @OA\Property(
     *                    property="is_highlighted",
     *                    type="integer",
     *                    description="1|0",
     *                    example="0",
     *                ),
     *            )
     *        )
     *    ),
     *    @OA\Response(
     *        response=200,
     *        description="Tag updated.",
     *        @OA\JsonContent(ref="#/components/schemas/Tag"),
     *    ),
     *    @OA\Response(
     *        response=422,
     *        description="Error in fields."
     *    ),
     * )
     *
     * @param UpdateTagRequest $request
     * @param Tag $tag
     * @param TagRequestMapper $tagRequestMapper
     * @return TagResource
     */
    public function update(UpdateTagRequest $request, Tag $tag, TagRequestMapper $tagRequestMapper): TagResource
    {
        return new TagResource($tagRequestMapper->map($tag, $request->validated()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @OA\Delete (
     *     tags={"Tags"},
     *     path="/admin/tags/{tag}",
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(mediaType="application/x-www-form-urlencoded"),
     *     ),
     *    @OA\Parameter(
     *      name="tag",
     *      in="path",
     *      description="Tag ID",
     *      @OA\Schema(type="integer"),
     *    ),
     *    @OA\Response(
     *      response=204,
     *      description="Tag deleted.",
     *      @OA\JsonContent(),
     *    ),
     *    @OA\Response(
     *      response=404,
     *      description="Tag not found.",
     *      @OA\JsonContent(),
     *    ),
     *    @OA\Response(
     *      response=422,
     *      description="Tag cannot be deleted due to existence of related resources.",
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
