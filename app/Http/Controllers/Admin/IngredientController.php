<?php

namespace App\Http\Controllers\Admin;

use App\Enum\IngredientEwgDataEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteIngredientRequest;
use App\Http\Requests\StoreIngredientRequest;
use App\Http\Requests\UpdateIngredientRequest;
use App\Http\Resources\Admin\IngredientCollection;
use App\Http\Resources\Admin\IngredientResource;
use App\Models\Ingredient;
use App\Repositories\CategoryRepository;
use App\RequestMappers\IngredientRequestMapper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class IngredientController extends Controller
{
    /**
     * @OA\Get(
     *    tags={"Ingredients"},
     *    path="/admin/ingredients",
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
     *    @OA\Response(
     *        response=200,
     *        description="Display a listing of ingredients.",
     *        @OA\JsonContent(ref="#/components/schemas/Ingredient"),
     *    ),
     *    @OA\Response(
     *        response=404,
     *        description="No ingredients.",
     *        @OA\JsonContent(),
     *    )
     * )
     *
     * @param Request $request
     * @return IngredientCollection
     */
    public function index(Request $request): IngredientCollection
    {
        return new IngredientCollection(
            Ingredient::query()
                ->when(
                    $request->has('name'),
                    fn (Builder $query) => $query->where('name', 'like', '%' . $request->get('name') . '%')
                )
                ->orderByDesc('updated_at')
                ->paginate($request->get('size', 20))
        );
    }

    /**
     * Store a newly created Ingredient.
     *
     *  @OA\Post (
     *     tags={"Ingredients"},
     *     path="/admin/ingredients",
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name"},
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="Name.",
     *                 ),
     *                 @OA\Property (
     *                     property="categories[0]",
     *                     type="integer",
     *                     description="category 1 id",
     *                 ),
     *                 @OA\Property (
     *                     property="categories[1]",
     *                     type="integer",
     *                     description="category 2 id",
     *                 ),
     *                 @OA\Property(
     *                     property="ewg_data",
     *                     type="string",
     *                     description="EWG data.",
     *                     enum={"None", "Fair", "Good", "Limited", "Robust"},
     *                 ),
     *                 @OA\Property(
     *                     property="ewg_score",
     *                     type="integer",
     *                     minimum=0,
     *                     maximum=10,
     *                     description="EWG score: 1-10",
     *                 ),
     *                 @OA\Property(
     *                     property="ewg_score_max",
     *                     type="integer",
     *                     minimum=0,
     *                     maximum=10,
     *                     description="Ewg_score_max: 1-10 (bigger or equal than ewg_score).",
     *                 ),
     *                 @OA\Property(
     *                     property="comedogen_index",
     *                     type="integer",
     *                     minimum=0,
     *                     maximum=5,
     *                     description="Comedogenic index: 0-5.",
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     description="Desciption.",
     *                 ),
     *                 @OA\Property(
     *                     property="published_at",
     *                     type="datetime",
     *                  @OA\Schema(
     *                      type="string",
     *                      format="date-time",
     *                  ),
     *                     description="Format: Y-m-d H:i:s",
     *                 ),
     *                 @OA\Property(
     *                     property="created_by",
     *                     type="integer",
     *                     description="created_by",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Ingredient created.",
     *         @OA\JsonContent(ref="#/components/schemas/Ingredient")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error in fields.",
     *     ),
     * )
     *
     * @param StoreIngredientRequest $request
     * @param Ingredient $ingredient
     * @param IngredientRequestMapper $ingredientRequestMapper
     * @return IngredientResource
     */
    public function store(StoreIngredientRequest $request, Ingredient $ingredient, IngredientRequestMapper $ingredientRequestMapper): IngredientResource
    {
        return new IngredientResource($ingredientRequestMapper->map($ingredient, $request->validated()));
    }

    /**
     * Show a selected Ingredient.
     *
     * @OA\Get(
     *    tags={"Ingredients"},
     *    path="/admin/ingredients/{ingredient}",
     *    security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="ingredient",
     *         in="path",
     *         required=true,
     *         description="Ingredient ID",
     *         @OA\Schema(type="integer"),
     *     ),
     *    @OA\Response(
     *        response=200,
     *        description="Display a selected Ingredient.",
     *        @OA\JsonContent(ref="#/components/schemas/Ingredient"),
     *    ),
     *    @OA\Response(
     *        response=404,
     *        description="Ingredient not found.",
     *        @OA\JsonContent(),
     *    )
     * )
     * @param Ingredient $ingredient
     * @return IngredientResource
     */
    public function show(Ingredient $ingredient): IngredientResource
    {
        $ingredient->load('categories');

        return new IngredientResource($ingredient);
    }

    /**
     * Update ingredient.
     *
     * @OA\Put (
     *     tags={"Ingredients"},
     *     path="/admin/ingredients/{ingredient}",
     *     security={{"bearer":{}}},
     *    @OA\Parameter(
     *      name="ingredient",
     *      in="path",
     *      required=true,
     *      description="Ingredient ID",
     *      @OA\Schema(type="integer"),
     *    ),
     *    @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 required={"name"},
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="Name.",
     *                 ),
     *                 @OA\Property (
     *                     property="categories[0]",
     *                     type="integer",
     *                     description="category 1 id",
     *                 ),
     *                 @OA\Property (
     *                     property="categories[1]",
     *                     type="integer",
     *                     description="category 2 id",
     *                 ),
     *                 @OA\Property(
     *                     property="ewg_data",
     *                     type="string",
     *                     description="EWG data.",
     *                     enum={"None", "Fair", "Good", "Limited", "Robust"},
     *                 ),
     *                 @OA\Property(
     *                     property="ewg_score",
     *                     type="integer",
     *                     minimum=0,
     *                     maximum=10,
     *                     description="EWG score: 1-10.",
     *                 ),
     *                 @OA\Property(
     *                     property="ewg_score_max",
     *                     type="integer",
     *                     minimum=0,
     *                     maximum=10,
     *                     description="Ewg_score_max: 1-10 (bigger or equal than ewg_score).",
     *                 ),
     *                 @OA\Property(
     *                     property="comedogen_index",
     *                     type="integer",
     *                     minimum=0,
     *                     maximum=5,
     *                     description="Comedogenic index: 0-5.",
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     description="Desciption.",
     *                 ),
     *                 @OA\Property(
     *                     property="published_at",
     *                     type="datetime",
     *                  @OA\Schema(
     *                      type="string",
     *                      format="date-time",
     *                  ),
     *                     description="Format: Y-m-d H:i:s",
     *                 ),
     *                 @OA\Property(
     *                     property="created_by",
     *                     type="integer",
     *                     description="created_by",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ingredient updated.",
     *         @OA\JsonContent(ref="#/components/schemas/Ingredient"),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error in fields.",
     *     ),
     * )
     *
     * @param UpdateIngredientRequest $request
     * @param Ingredient $ingredient
     * @param IngredientRequestMapper $ingredientRequestMapper
     * @return IngredientResource
     */
    public function update(UpdateIngredientRequest $request, Ingredient $ingredient, IngredientRequestMapper $ingredientRequestMapper): IngredientResource
    {
        return new IngredientResource($ingredientRequestMapper->map($ingredient, $request->validated()));
    }

    /**
     * Remove an Ingredient.
     *
     * @OA\Delete (
     *     tags={"Ingredients"},
     *     path="/admin/ingredients/{ingredient}",
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(mediaType="application/x-www-form-urlencoded"),
     *     ),
     *    @OA\Parameter(
     *      name="ingredient",
     *      in="path",
     *      description="Ingredient ID",
     *      @OA\Schema(type="integer"),
     *    ),
     *    @OA\Response(
     *      response=204,
     *      description="Ingredient deleted.",
     *      @OA\JsonContent(),
     *    ),
     *    @OA\Response(
     *      response=404,
     *      description="Ingredient not found.",
     *      @OA\JsonContent(),
     *    ),
     *    @OA\Response(
     *      response=422,
     *      description="Ingredient cannot be deleted due to existence of related resources.",
     *    ),
     * )
     *
     * @param DeleteIngredientRequest $request
     * @param Ingredient $ingredient
     * @return JsonResponse
     * @throws Throwable
     */
    public function destroy(DeleteIngredientRequest $request, Ingredient $ingredient): JsonResponse
    {
        $ingredient->deleteOrFail();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @OA\Get(
     *    tags={"Ingredients"},
     *    path="/admin/ingredients/get-ewg-data-types",
     *    security={{"bearer":{}}},
     *    @OA\MediaType(mediaType="application/json"),
     *    @OA\Response(
     *      response=200,
     *      description="Display a listing of ingredient ewg_data types.",
     *    ),
     * )
     *
     * @return array<int, IngredientEwgDataEnum>
     */
    public function getEwgDataTypes(): array
    {
        return IngredientEwgDataEnum::cases();
    }

    /**
     * @OA\Get(
     *    tags={"Ingredients"},
     *    path="/admin/ingredients/get-categories",
     *    security={{"bearer":{}}},
     *    @OA\MediaType(mediaType="application/json"),
     *    @OA\Response(
     *      response=200,
     *      description="Display a listing of ingredient categories.",
     *    ),
     * )
     *
     * @return object|null
     */
    public function getCategories(): ?object
    {
        return CategoryRepository::getCategoriesByType('ingredient');
    }
}
