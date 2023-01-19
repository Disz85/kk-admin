<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DeleteBrandRequest;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Http\Resources\Admin\BrandCollection;
use App\Http\Resources\Admin\BrandResource;
use App\Models\Brand;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
     *    @OA\Response(
     *      response="200",
     *      description="Display a listing of brands.",
     *      @OA\JsonContent()
     *    ),
     *    @OA\Parameter(
     *      name="page",
     *      in="query",
     *      description="Page number",
     *      @OA\Schema(
     *          type="integer"
     *      )
     *    ),
     *    @OA\Parameter(
     *      name="title",
     *      in="query",
     *      description="Filter by title",
     *      @OA\Schema(
     *          type="string"
     *      )
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title"},
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
     *                     property="created_by",
     *                     type="integer",
     *                     description="Create user ID",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Brand created.",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Input validation errors."
     *     ),
     * )
     *
     * @param  \App\Http\Requests\StoreBrandRequest  $request
     * @return BrandResource
     */
    public function store(StoreBrandRequest $request): BrandResource
    {
        $brand = Brand::create($request->validated());
        $brand->generateSlug();
        $brand->save();

        return new BrandResource($brand);
    }

    /**
     * Show a selected brand.
     * @OA\Get(
     *    tags={"Brands"},
     *    path="/admin/brands/{brand}",
     *    @OA\Response(
     *      response="200",
     *      description="Display a listing of brand.",
 *          @OA\JsonContent()
     *    ),
     *    @OA\Parameter(
     *        name="brand",
     *        in="path",
     *        required=true,
     *        description="Brand ID",
     *        @OA\Schema(
     *            type="integer"
     *        ),
     *    ),
     * )
     * @param Brand $brand
     * @return BrandResource
     */
    public function show(Brand $brand): BrandResource
    {
        return new BrandResource($brand);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Brand $brands
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brands)
    {
        //
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
     *      @OA\Schema(
     *          type="string"
     *      )
     *    ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 required={"title"},
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
     *         response=200,
     *         description="Brand updated.",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Input validation errors."
     *     ),
     * )
     *
     * @param UpdateBrandRequest $request
     * @param Brand $brand
     * @return BrandResource
     */
    public function update(UpdateBrandRequest $request, Brand $brand): BrandResource
    {
        $brand->fill($request->validated())->save();

        return new BrandResource($brand);
    }

    /**
     * Remove a brand.
     *
     * @OA\Delete (
     *     tags={"Brands"},
     *     path="/admin/brands/{brand}",
     *    @OA\Parameter(
     *      name="brand",
     *      in="path",
     *      description="integer",
     *      @OA\Schema(
     *          type="string"
     *      )
     *    ),
     *     @OA\RequestBody(
     *         required=false,
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Brand deleted.",
     *         @OA\JsonContent(),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Brand not found."
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Brand cannot be deleted due to existence of related resources."
     *     ),
     * )
     *
     * @param DeleteBrandRequest $request
     * @param Brand $brand
     * @return JsonResponse
     * @throws Throwable
     */
    public function destroy(DeleteBrandRequest $request, Brand $brand): JsonResponse
    {
        $brand->image()->delete();
        $brand->deleteOrFail();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
