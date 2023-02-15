<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteAuthorRequest;
use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Http\Resources\Admin\AuthorCollection;
use App\Http\Resources\Admin\AuthorResource;
use App\Models\Author;
use App\RequestMappers\AuthorRequestMapper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AuthorController extends Controller
{
    /**
     * List of Authors.
     * @OA\Get(
     *    tags={"Authors"},
     *    path="/admin/authors",
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
     *      description="Display a listing of authors.",
     *      @OA\JsonContent(),
     *    ),
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
                ->when(
                    $request->has('email'),
                    fn (Builder $query) => $query->where('email', 'like', '%' . $request->get('email') . '%')
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
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name","email"},
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
     *                     property="title",
     *                     type="string",
     *                     description="Title.",
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     description="Desciption.",
     *                 ),
     *                 @OA\Property(
     *                     property="image[id]",
     *                     type="integer",
     *                     description="Image ID.",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Author created.",
     *         @OA\JsonContent(ref="#/components/schemas/Author")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error in fields.",
     *     ),
     * )
     *
     * @param StoreAuthorRequest $request
     * @param Author $author
     * @param AuthorRequestMapper $authorRequestMapper
     * @return AuthorResource
     */
    public function store(StoreAuthorRequest $request, Author $author, AuthorRequestMapper $authorRequestMapper): AuthorResource
    {
        return new AuthorResource($authorRequestMapper->map($author, $request->validated()));
    }

    /**
     * Show a selected Author.
     * @OA\Get(
     *    tags={"Authors"},
     *    path="/admin/authors/{author}",
     *    @OA\Parameter(
     *        name="author",
     *        in="path",
     *        required=true,
     *        description="Author ID",
     *        @OA\Schema(type="integer"),
     *    ),
     *    @OA\Response(
     *      response=200,
     *      description="Show a selected author.",
     *      @OA\JsonContent(ref="#/components/schemas/Author"),
     *    ),
     *    @OA\Response(
     *      response=404,
     *      description="Author not found.",
     *      @OA\JsonContent(),
     *    ),
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
     *         mediaType="application/json",
     *     ),
     *    @OA\Parameter(
     *      name="author",
     *      in="path",
     *      required=true,
     *      description="integer",
     *      @OA\Schema(type="string"),
     *    ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name","email"},
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
     *                     property="title",
     *                     type="string",
     *                     description="Title.",
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     description="Desciption.",
     *                 ),
     *                 @OA\Property(
     *                     property="image[id]",
     *                     type="integer",
     *                     description="Image ID.",
     *                 ),
     *             )
     *         )
     *     ),*
     *     @OA\Response(
     *         response=200,
     *         description="Author updated.",
     *         @OA\JsonContent(ref="#/components/schemas/Author")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Field validation error.",
     *     ),
     * )
     *
     * @param UpdateAuthorRequest $request
     * @param Author $author
     * @param AuthorRequestMapper $authorRequestMapper
     * @return AuthorResource
     */
    public function update(UpdateAuthorRequest $request, Author $author, AuthorRequestMapper $authorRequestMapper)
    {
        return new AuthorResource($authorRequestMapper->map($author, $request->validated()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @OA\Delete (
     *     tags={"Authors"},
     *     path="/admin/authors/{author}",
     *     @OA\Parameter(
     *       name="author",
     *       in="path",
     *       description="integer",
     *       @OA\Schema(type="string"),
     *     ),
     *     @OA\RequestBody(
     *       required=false,
     *       @OA\MediaType(mediaType="application/x-www-form-urlencoded")
     *     ),
     *     @OA\Response(
     *       response=204,
     *       description="Author deleted",
     *       @OA\JsonContent(),
     *     ),
     *     @OA\Response(
     *       response=404,
     *       description="Author not found",
     *     ),
     *    @OA\Response(
     *       response=422,
     *       description="Author cannot be deleted due to existence of related resources.",
     *    ),
     * )
     *
     * @param DeleteAuthorRequest $request
     * @param Author $author
     * @return JsonResponse
     * @throws Throwable
     */
    public function destroy(DeleteAuthorRequest $request, Author $author): JsonResponse
    {
        $author->deleteOrFail();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
