<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Http\Resources\Admin\AuthorCollection;
use App\Http\Resources\Admin\AuthorResource;
use App\Models\Author;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * List of Authors.
     * @OA\Get(
     *    tags={"Authors"},
     *    path="/admin/authors",
     *    @OA\Response(
     *      response="200",
     *      description="Display a listing of authors."
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
     * @return AuthorCollection
     */
    public function index(Request $request): AuthorCollection
    {
        return new AuthorCollection(
            Author::query()
                ->when(
                    $request->has('name'),
                    fn (Builder $query) => $query->where('name', 'like', '%' . $request->get('name') . '%')
                )
                ->orderByDesc('updated_at')
                ->paginate($request->get('size', 20))
        );
    }

    /**
     * Store a newly created Author.
     *
     * @OA\Post (
     *     tags={"Authors"},
     *     path="/admin/authors",
     *     @OA\MediaType(
     *         mediaType="application/json"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title","name","email","slug"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     description="Title.",
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="Name.",
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     description="Email",
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
     *                 @OA\Property(
     *                     property="image_id",
     *                     type="integer",
     *                     description="Image ID.",
     *                 ),
     *             )
     *         )
     *     ),*
     *     @OA\Response(
     *         response=200,
     *         description="Author created."
     *     ),
     *     @OA\Response(
     *         response=419,
     *         description="Error in fields"
     *     ),
     * )
     *
     * @param StoreAuthorRequest $request
     * @return AuthorResource
     */
    public function store(StoreAuthorRequest $request): AuthorResource
    {
        $author = new Author($request->validated());
        $author->save();

        return new AuthorResource($author);
    }

    /**
     * Show a selected Author.
     * @OA\Get(
     *    tags={"Authors"},
     *    path="/admin/authors/{author}",
     *    @OA\Response(
     *      response="200",
     *      description="Display a listing of authors."
     *    ),
     *    @OA\MediaType(
     *      mediaType="application/json"
     *    ),
     *     @OA\Parameter(
     *         name="author",
     *         in="path",
     *         required=true,
     *         description="Author ID",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     * )
     * @param Author $author
     * @return AuthorResource
     */
    public function show(Author $author): AuthorResource
    {
        return new AuthorResource($author);
    }

    /**
     * Update Author.
     *
     * @OA\Put (
     *     tags={"Authors"},
     *     path="/admin/authors/{author}",
     *     @OA\MediaType(
     *         mediaType="application/json"
     *     ),
     *    @OA\Parameter(
     *      name="author",
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
     *                 required={"title","name","email","slug"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     description="Title.",
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="Name.",
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     description="Email",
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
     *                 @OA\Property(
     *                     property="image_id",
     *                     type="integer",
     *                     description="Image ID.",
     *                 ),
     *             )
     *         )
     *     ),*
     *     @OA\Response(
     *         response=200,
     *         description="Author updated."
     *     ),
     *     @OA\Response(
     *         response=419,
     *         description="Error in fields"
     *     ),
     * )
     *
     * @param UpdateAuthorRequest $request
     * @param Author $author
     * @return AuthorResource
     */
    public function update(UpdateAuthorRequest $request, Author $author)
    {
        $author->fill($request->validated());
        $author->save();

        return new AuthorResource($author);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @OA\Delete (
     *     tags={"Authors"},
     *     path="/admin/authors/{author}",
     *     @OA\MediaType(
     *         mediaType="application/json"
     *     ),
     *    @OA\Parameter(
     *      name="author",
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
     *         description="Author deleted."
     *     ),
     * )
     *
     * @param Author $author
     * @return void
     */
    public function destroy(Author $author)
    {
        $author->deleteOrFail();
    }
}
