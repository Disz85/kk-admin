<?php

namespace App\Http\Controllers\Api;

use App\Http\Actions\Api\Brands\FilterAction;
use App\Http\Requests\Api\BrandListRequest;
use App\Http\Resources\Api\BrandCollection;
use App\Http\Resources\Api\BrandResource;
use App\Models\Brand;
use Elastic\ScoutDriverPlus\Builders\SearchParametersBuilder;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Annotations as OA;

class BrandController extends BaseController
{
    /**
     * Display a listing of brands.
     *
     * @OA\Get(
     *    tags={"Brands API"},
     *    path="/api/brands",
     *    @OA\Parameter(
     *        name="page",
     *        in="query",
     *        description="Page number",
     *        @OA\Schema(type="integer"),
     *        allowEmptyValue="true",
     *    ),
     *    @OA\Parameter(
     *        name="per_page",
     *        in="query",
     *        description="Page size",
     *        @OA\Schema(type="integer"),
     *        allowEmptyValue="true",
     *    ),
     *    @OA\Parameter(
     *        name="sort",
     *        in="query",
     *        description="sort_by and sort order ('-' prefix means desc)",
     *        @OA\Schema(type="string"),
     *    ),
     *    @OA\Parameter(
     *        name="filter[name]",
     *        in="query",
     *        description="Title",
     *        @OA\Schema(type="string")
     *    ),
     *    @OA\Parameter (
     *         name="filter[abc][0]",
     *         in="query",
     *         description="abc 1",
     *         @OA\Schema(type="string"),
     *         allowEmptyValue="true"
     *    ),
     *    @OA\Parameter (
     *         name="filter[abc][1]",
     *         in="query",
     *         @OA\Schema(type="string"),
     *         description="abc 2",
     *         allowEmptyValue="true"
     *    ),
     *    @OA\Response(
     *        response=200,
     *        description="Display a listing of brands.",
     *        @OA\JsonContent(ref="#/components/schemas/Brand")
     *    ),
     *    @OA\Response(
     *        response=404,
     *        description="No brands.",
     *        @OA\JsonContent()
     *    )
     * )
     *
     * @param BrandListRequest $request
     * @return BrandCollection
     */
    public function index(BrandListRequest $request, FilterAction $filterAction): BrandCollection
    {
        if ($filters = $request->validated('filter')) {
            $filteredQuery = $filterAction($filters);
        }

        $query = Brand::searchQuery($filteredQuery ?? null)
            ->when(
                $request->has('sort'),
                fn (SearchParametersBuilder $builder) => $builder
                ->sort(
                    $request->getSortBy(),
                    $request->getSortDirection()
                )
            )
            ->load(['image', 'createdBy', 'updatedBy']);

        $paginated = $query->paginate(
            perPage: $request->validated('per_page', 20),
            page: $request->validated('page', 1),
        );

        // @phpstan-ignore-next-line
        $paginated->data = $paginated->onlyModels()->transform(function ($model) {
            return new BrandResource($model);
        });

        return new BrandCollection($paginated);
    }

    /**
     * Display the specified brand.
     *
     * @OA\Get(
     *     tags={"Brands API"},
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
        return new BrandResource($brand->load(['image', 'createdBy', 'updatedBy']));
    }
}
