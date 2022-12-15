<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Http\Resources\Admin\TagCollection;
use App\Http\Resources\Admin\TagResource;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
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
     *                 required={"name","slug"},
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="Name.",
     *                 ),
     *                 @OA\Property(
     *                     property="slug",
     *                     type="string",
     *                     description="Slug.",
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     description="Desciption.",
     *                 ),
     *             )
     *         )
     *     ),*
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
        $tag = new Tag($request->validated());
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
     *     @OA\Parameter(
     *         name="tag",
     *         in="path",
     *         required=true,
     *         description="Tag ID",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
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
     *                 required={"name","slug"},
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="Name.",
     *                 ),
     *                 @OA\Property(
     *                     property="slug",
     *                     type="string",
     *                     description="Slug.",
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     description="Desciption.",
     *                 ),
     *             )
     *         )
     *     ),*
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
     * @param UpdateTagRequest $tag
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
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *         )
     *     ),*
     *     @OA\Response(
     *         response=200,
     *         description="Tag deleted."
     *     ),
     * )
     *
     * @param Tag $tag
     * @return void
     * @throws Throwable
     */
    public function destroy(Tag $tag): void
    {
        $tag->deleteOrFail();
    }
}
