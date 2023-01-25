<?php

namespace App\Http\Controllers\Admin;

use App\Enum\CategoryTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\Admin\CategoryCollection;
use App\Http\Resources\Admin\CategoryResource;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Throwable;

class CategoryController extends Controller
{
    /**
     * @OA\Get(
     *    tags={"Categories"},
     *    path="/admin/categories",
     *    @OA\Response(
     *      response="200",
     *      description="Display a listing of categories."
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
     *     @OA\MediaType(
     *         mediaType="application/json"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
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
     *                     description="article|product|skintype|skinconcern|ingredient",
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
     *                 @OA\Property(
     *                     property="parent_id",
     *                     type="integer",
     *                     description="Parent ID.",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category created."
     *     ),
     *     @OA\Response(
     *         response=419,
     *         description="Error in fields"
     *     ),
     * )
     *
     * @param StoreCategoryRequest $request
     * @return CategoryResource
     */
    public function store(StoreCategoryRequest $request): CategoryResource
    {
        $category = Category::create($request->validated());
        $category->generateSlug();
        $category->save();

        return new CategoryResource($category);
    }

    /**
     * Show a selected Category.
     * @OA\Get(
     *    tags={"Categories"},
     *    path="/admin/categories/{category}",
     *    @OA\Response(
     *      response="200",
     *      description="Display a listing of categories."
     *    ),
     *    @OA\MediaType(
     *      mediaType="application/json"
     *    ),
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         required=true,
     *         description="Category ID",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
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
     *     @OA\MediaType(
     *         mediaType="application/json"
     *     ),
     *    @OA\Parameter(
     *      name="category",
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
     *                 required={"name","type"},
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="Name.",
     *                 ),
     *                 @OA\Property(
     *                     property="type",
     *                     type="string",
     *                     description="article|product|skintype|skinconcern|ingredient",
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
     *                 @OA\Property(
     *                     property="parent_id",
     *                     type="integer",
     *                     description="Parent ID.",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category created."
     *     ),
     *     @OA\Response(
     *         response=419,
     *         description="Error in fields"
     *     ),
     * )
     *
     * @param UpdateCategoryRequest $request
     * @param Category $category
     * @return CategoryResource
     */
    public function update(UpdateCategoryRequest $request, Category $category): CategoryResource
    {
        $category->fill($request->validated())->save();

        return new CategoryResource($category);
    }

    /**
     * Remove a category.
     *
     * @OA\Delete (
     *     tags={"Categories"},
     *     path="/admin/categories/{category}",
     *     @OA\MediaType(
     *         mediaType="application/json"
     *     ),
     *    @OA\Parameter(
     *      name="category",
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
     *         description="Category deleted."
     *     ),
     * )
     *
     * @param Category $category
     * @return void
     * @throws Throwable
     */
    public function destroy(Category $category): void
    {
        $category->deleteOrFail();
    }

    /**
     * @OA\Get(
     *    tags={"Categories"},
     *    path="/admin/categories/get-types",
     *    @OA\Response(
     *      response="200",
     *      description="Display a listing of category types."
     *    ),
     *    @OA\MediaType(
     *      mediaType="application/json"
     *    ),
     * )
     *
     * @param Request $request
     * @return CategoryCollection
     */
    public function getTypes(): array
    {
        return CategoryTypeEnum::cases();
    }
}
