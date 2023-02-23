<?php

namespace App\Http\Controllers\Admin;

use App\Enum\CategoryTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteCategoryRequest;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\Admin\CategoryCollection;
use App\Http\Resources\Admin\CategoryResource;
use App\Models\Category;
use App\RequestMappers\CategoryRequestMapper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class CategoryController extends Controller
{
    /**
     * @OA\Get(
     *    tags={"Categories"},
     *    path="/admin/categories",
     *    security={{"bearer":{}}},
     *    @OA\Parameter(
     *        name="page",
     *        in="query",
     *        description="Page number",
     *        @OA\Schema(type="integer"),
     *        allowEmptyValue="true",
     *    ),
     *    @OA\Parameter(
     *      name="name",
     *      in="query",
     *      description="Filter by name",
     *      @OA\Schema(type="string"),
     *    ),
     *    @OA\Parameter(
     *      name="type",
     *      in="query",
     *      description="Filter by type: article|product|skintype|skinconcern|ingredient",
     *      @OA\Schema(type="string"),
     *    ),
     *    @OA\Response(
     *        response=200,
     *        description="Display a listing of categories.",
     *        @OA\JsonContent(ref="#/components/schemas/Category"),
     *    ),
     *    @OA\Response(
     *        response=404,
     *        description="No categories.",
     *        @OA\JsonContent(),
     *    )
     * )
     *
     * @param Request $request
     * @return CategoryCollection
     */
    public function index(Request $request): CategoryCollection
    {
        return new CategoryCollection(
            Category::query()
                ->when(
                    $request->has('name'),
                    fn (Builder $query) => $query->where('name', 'like', '%' . $request->get('name') . '%')
                )
                ->when(
                    $request->has('type'),
                    fn (Builder $query) => $query->where('type', '=', $request->get('type'))
                )
                ->orderByDesc('updated_at')
                ->paginate($request->get('size', 20))
        );
    }

    /**
     * Store a newly created Category.
     *
     * @OA\Post (
     *     tags={"Categories"},
     *     path="/admin/categories",
     *     security={{"bearer":{}}},
     *     @OA\MediaType(mediaType="application/json"),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 required={"name","type"},
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="Name.",
     *                 ),
     *                 @OA\Property(
     *                     property="type",
     *                     type="string",
     *                     description="article|product|skintype|skinconcern|hairproblem|ingredient",
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     description="Desciption.",
     *                 ),
     *                 @OA\Property(
     *                     property="parent[id]",
     *                     type="integer",
     *                     description="Parent ID.",
     *                 ),
     *                 @OA\Property(
     *                     property="parent[type]",
     *                     type="string",
     *                     description="article|product|skintype|skinconcern|hairproblem|ingredient",
     *                 ),
     *                 @OA\Property(
     *                     property="is_archived",
     *                     type="integer",
     *                     description="1|0",
     *                     example="0",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Category created.",
     *         @OA\JsonContent(ref="#/components/schemas/Category")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error in fields.",
     *     ),
     * )
     *
     * @param StoreCategoryRequest $request
     * @param Category $category
     * @param CategoryRequestMapper $categoryRequestMapper
     * @return CategoryResource
     */
    public function store(StoreCategoryRequest $request, Category $category, CategoryRequestMapper $categoryRequestMapper): CategoryResource
    {
        return new CategoryResource($categoryRequestMapper->map($category, $request->validated()));
    }

    /**
     * Show a selected Category.
     * @OA\Get(
     *    tags={"Categories"},
     *    path="/admin/categories/{category}",
     *    security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         required=true,
     *         description="Category ID",
     *         @OA\Schema(type="integer"),
     *     ),
     *    @OA\Response(
     *        response=200,
     *        description="Display a selected Category.",
     *        @OA\JsonContent(ref="#/components/schemas/Category"),
     *    ),
     *    @OA\Response(
     *        response=404,
     *        description="Category not found.",
     *        @OA\JsonContent(),
     *    )
     * )
     * @param Category $category
     * @return CategoryResource
     */
    public function show(Category $category): CategoryResource
    {
        return new CategoryResource($category);
    }

    /**
     * Update a category.
     *
     * @OA\Put (
     *     tags={"Categories"},
     *     path="/admin/categories/{category}",
     *     security={{"bearer":{}}},
     *     @OA\MediaType(mediaType="application/json"),
     *    @OA\Parameter(
     *      name="category",
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
     *                 required={"name","type"},
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="Name.",
     *                 ),
     *                 @OA\Property(
     *                     property="type",
     *                     type="string",
     *                     description="article|product|skintype|skinconcern|hairproblem|ingredient",
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     description="Desciption.",
     *                 ),
     *                 @OA\Property(
     *                     property="parent[id]",
     *                     type="integer",
     *                     description="Parent ID.",
     *                 ),
     *                 @OA\Property(
     *                     property="parent[type]",
     *                     type="string",
     *                     description="article|product|skintype|skinconcern|hairproblem|ingredient",
     *                 ),
     *                 @OA\Property(
     *                     property="is_archived",
     *                     type="integer",
     *                     description="1|0",
     *                     example="0",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category updated.",
     *         @OA\JsonContent(ref="#/components/schemas/Category"),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error in fields.",
     *     ),
     * )
     *
     * @param UpdateCategoryRequest $request
     * @param Category $category
     * @param CategoryRequestMapper $categoryRequestMapper
     * @return CategoryResource
     */
    public function update(UpdateCategoryRequest $request, Category $category, CategoryRequestMapper $categoryRequestMapper): CategoryResource
    {
        return new CategoryResource($categoryRequestMapper->map($category, $request->validated()));
    }

    /**
     * Remove a category.
     *
     * @OA\Delete (
     *     tags={"Categories"},
     *     path="/admin/categories/{category}",
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(mediaType="application/x-www-form-urlencoded"),
     *     ),
     *    @OA\Parameter(
     *      name="category",
     *      in="path",
     *      description="Category ID",
     *      @OA\Schema(type="integer"),
     *    ),
     *    @OA\Response(
     *      response=204,
     *      description="Category deleted.",
     *      @OA\JsonContent(),
     *    ),
     *    @OA\Response(
     *      response=404,
     *      description="Category not found.",
     *      @OA\JsonContent(),
     *    ),
     *    @OA\Response(
     *      response=422,
     *      description="Category cannot be deleted due to existence of related resources.",
     *    ),
     * )
     *
     * @param DeleteCategoryRequest $request
     * @param Category $category
     * @return JsonResponse
     * @throws Throwable
     */
    public function destroy(DeleteCategoryRequest $request, Category $category): JsonResponse
    {
        $category->deleteOrFail();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @OA\Get(
     *    tags={"Categories"},
     *    path="/admin/categories/get-types",
     *    security={{"bearer":{}}},
     *    @OA\MediaType(mediaType="application/json"),
     *    @OA\Response(
     *      response=200,
     *      description="Display a listing of category types."
     *    ),
     * )
     *
     * @return array<int, CategoryTypeEnum>
     */
    public function getTypes(): array
    {
        return CategoryTypeEnum::cases();
    }
}
