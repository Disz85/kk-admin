<?php

namespace App\Http\Controllers\Api;

use App\Enum\CategoryTypeEnum;
use App\Filters\ParentIdFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CategoryResource;
use App\Http\Resources\Api\CategoryCollection;
use App\Models\Category;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     *
     * @OA\Get(
     *    tags={"Categories API"},
     *    path="/api/categories",
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
     *        name="filter[name]",
     *        in="query",
     *        description="Name",
     *        @OA\Schema(type="string")
     *    ),
     *    @OA\Response(
     *        response=200,
     *        description="Display a listing of categories.",
     *        @OA\JsonContent(ref="#/components/schemas/Category")
     *    ),
     *    @OA\Response(
     *        response=404,
     *        description="No categories.",
     *        @OA\JsonContent()
     *    )
     * )
     *
     * @param Request $request
     * @return CategoryCollection
     */
    public function index(Request $request): CategoryCollection
    {
        // GET /categories?filter[name]=john
        // GET /categories?filter[name]=john&include=children
        // GET /categories?filter[name]=john&include=children.children
        // GET /categories?filter[name]=john&include=children&sort=-created_at
        // GET /categories?filter[name]=john&include=children&sort=-created_at&filter[parent_id]=null
        // GET /categories?filter[name]=john&include=children&sort=-created_at&filter[parent_id]=91eefdf0-0f10-420a-bd31-2f6d3c2eb0a5
        return new CategoryCollection(
            QueryBuilder::for(Category::class)
                ->where('type', '=', CategoryTypeEnum::Article)
                ->allowedFields([ 'uuid', 'name', 'slug' ])
                ->allowedFilters([
                    'name',
                    AllowedFilter::custom('parent_id', new ParentIdFilter()),
                ])
                ->allowedIncludes([ 'children.children' ])
                ->defaultSort('-updated_at')
                ->allowedSorts([ 'created_at', 'updated_at' ])
                ->paginate($request->get('per_page', 20))
                ->appends($request->query())
        );
    }

    /**
     * Display the specified category.
     *
     * @OA\Get(
     *     tags={"Categories API"},
     *     path="/api/categories/{category}",
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         required=true,
     *         description="Category UUID",
     *         @OA\Schema(type="string"),
     *     ),
     *    @OA\Response(
     *        response=200,
     *        description="Display a selected category.",
     *        @OA\JsonContent(ref="#/components/schemas/Category"),
     *    ),
     *    @OA\Response(
     *        response=404,
     *        description="Category not found.",
     *        @OA\JsonContent(),
     *    )
     * )
     *
     * @param Category $category
     * @return CategoryResource
     */
    public function show(Category $category): CategoryResource
    {
        return new CategoryResource($category);
    }
}
