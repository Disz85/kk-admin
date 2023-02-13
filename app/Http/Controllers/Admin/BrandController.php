<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteBrandRequest;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Http\Resources\Admin\BrandCollection;
use App\Http\Resources\Admin\BrandResource;
use App\Models\Brand;
use App\RequestMappers\BrandRequestMapper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class BrandController extends Controller
{
    /**
     * Display a listing of the brands.
     *
     * @OA\Get(
     *    tags={"Brands"},
     *    path="/admin/brands",
     *    @OA\Parameter(
     *       name="page",
     *       in="query",
     *       description="Page number",
     *       @OA\Schema(type="integer"),
     *       allowEmptyValue="true",
     *    ),
     *    @OA\Parameter(
     *      name="title",
     *      in="query",
     *      description="Filter by title",
     *      @OA\Schema(type="string"),
     *    ),
     *    @OA\Response(
     *        response=200,
     *        description="Display a listing of brands.",
     *        @OA\JsonContent(ref="#/components/schemas/Brand"),
     *    ),
     *    @OA\Response(
     *        response=404,
     *        description="No brands.",
     *        @OA\JsonContent(),
     *    )
     * )
     *
     * @param Request $request
     * @return BrandCollection
     */
    public function index(Request $request): BrandCollection
    {
        return new BrandCollection(
            Brand::query()
                ->when(
                    $request->has('title'),
                    fn (Builder $query) => $query->where('title', 'like', '%' . $request->get('title') . '%')
                )
                ->orderByDesc('updated_at')
                ->paginate($request->get('size', 20))
        );
    }

    /**
     * Store a newly created brand.
     *
     * @OA\Post (
     *     tags={"Brands"},
     *     path="/admin/brands",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 required={"title", "image_id"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     description="Title",
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     description="Description of the brand",
     *                 ),
     *                 @OA\Property(
     *                     property="url",
     *                     type="string",
     *                     description="URL of the brand",
     *                 ),
     *                 @OA\Property(
     *                     property="image_id",
     *                     type="integer",
     *                     description="Image ID",
     *                 ),
     *                 @OA\Property(
     *                     property="where_to_find",
     *                     type="string",
     *                     description="Where to find",
     *                 ),
     *                 @OA\Property(
     *                     property="created_by",
     *                     type="integer",
     *                     description="User ID",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Brand created.",
     *         @OA\JsonContent(ref="#/components/schemas/Brand")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error in fields."
     *     ),
     * )
     *
     * @param StoreBrandRequest $request
     * @param Brand $brand
     * @param BrandRequestMapper $brandRequestMapper
     * @return BrandResource
     */
    public function store(StoreBrandRequest $request, Brand $brand, BrandRequestMapper $brandRequestMapper): BrandResource
    {
        return new BrandResource($brandRequestMapper->map($brand, $request->validated()));
    }

    /**
     * Show a selected brand.
     * @OA\Get(
     *    tags={"Brands"},
     *    path="/admin/brands/{brand}",
     *     @OA\Parameter(
     *         name="brand",
     *         in="path",
     *         required=true,
     *         description="Brand ID",
     *         @OA\Schema(type="integer"),
     *     ),
     *    @OA\Response(
     *        response=200,
     *        description="Display a selected Brand.",
     *        @OA\JsonContent(ref="#/components/schemas/Brand"),
     *    ),
     *    @OA\Response(
     *        response=404,
     *        description="Ingredient not found.",
     *        @OA\JsonContent(),
     *    )
     * )
     * @param Brand $brand
     * @return BrandResource
     */
    public function show(Brand $brand): BrandResource
    {
        $brand->load(['tags']);

        return new BrandResource($brand);
    }

    /**
     * Update a brand.
     *
     * @OA\Put (
     *     tags={"Brands"},
     *     path="/admin/brands/{brand}",
     *    @OA\Parameter(
     *      name="brand",
     *      in="path",
     *      required=true,
     *      description="integer",
     *      @OA\Schema(type="string"),
     *    ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 required={"title", "image_id"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     description="Title",
     *                 ),
     *                 @OA\Property(
     *                     property="url",
     *                     type="string",
     *                     description="URL of the brand",
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     description="Description of the brand",
     *                 ),
     *                 @OA\Property(
     *                     property="image_id",
     *                     type="integer",
     *                     description="Image ID",
     *                 ),
     *                 @OA\Property(
     *                     property="where_to_find",
     *                     type="string",
     *                     description="Where to find",
     *                 ),
     *                 @OA\Property(
     *                     property="updated_by",
     *                     type="integer",
     *                     description="Update user ID",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Brand updated.",
     *         @OA\JsonContent(ref="#/components/schemas/Brand")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error in fields.",
     *     ),
     * )
     *
     * @param UpdateBrandRequest $request
     * @param Brand $brand
     * @param BrandRequestMapper $brandRequestMapper
     * @return BrandResource
     */
    public function update(UpdateBrandRequest $request, Brand $brand, BrandRequestMapper $brandRequestMapper): BrandResource
    {
        return new BrandResource($brandRequestMapper->map($brand, $request->validated()));
    }

    /**
     * Remove a brand.
     *
     * @OA\Delete (
     *     tags={"Brands"},
     *     path="/admin/brands/{brand}",
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(mediaType="application/x-www-form-urlencoded"),
     *     ),
     *    @OA\Parameter(
     *      name="brand",
     *      in="path",
     *      description="Brand ID",
     *      @OA\Schema(type="integer"),
     *    ),
     *    @OA\Response(
     *      response=204,
     *      description="Brand deleted.",
     *      @OA\JsonContent(),
     *    ),
     *    @OA\Response(
     *      response=404,
     *      description="Brand not found.",
     *      @OA\JsonContent(),
     *    ),
     *    @OA\Response(
     *      response=422,
     *      description="Brand cannot be deleted due to existence of related resources.",
     *    ),
     * )
     *
     * @param DeleteBrandRequest $request
     * @param Brand $brand
     * @return JsonResponse
     * @throws Throwable
     */
    public function destroy(DeleteBrandRequest $request, Brand $brand): JsonResponse
    {
        $brand->deleteOrFail();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
