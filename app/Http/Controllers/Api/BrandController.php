<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\BrandCollection;
use App\Http\Resources\Api\BrandResource;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Spatie\QueryBuilder\QueryBuilder;

class BrandController extends BaseController
{
    public function index(Request $request): BrandCollection
    {
        return new BrandCollection(
            QueryBuilder::for(Brand::class)
                ->allowedFilters('title')
                ->defaultSort('title')
                ->allowedSorts('id', 'title')
                ->allowedIncludes(['image','createdBy','updatedBy'])
                ->paginate($request->get('per_page', 20))
                ->appends($request->query())
        );
    }

    /**
     * Display the specified brand.
     *
     * @OA\Get(
     *     tags={"Brand API"},
     *     path="/api/brands/{brand}",
     *     @OA\Parameter(
     *         name="brand",
     *         in="path",
     *         required=true,
     *         description="Brand UUID",
     *         @OA\Schema(type="string"),
     *     ),
     *    @OA\Response(
     *        response=200,
     *        description="Display a selected Brand.",
     *        @OA\JsonContent(ref="#/components/schemas/Brand")
     *    ),
     *    @OA\Response(
     *        response=404,
     *        description="Brand not found.",
     *        @OA\JsonContent(ref="#/components/schemas/Brand")
     *    )
     * )
     *
     * @param Brand $brand
     * @return BrandResource
     */
    public function show(Brand $brand): BrandResource
    {
        return new BrandResource($brand->load(['image','createdBy','updatedBy']));
    }
}
