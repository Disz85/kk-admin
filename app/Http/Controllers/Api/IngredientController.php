<?php

namespace App\Http\Controllers\Api;

use App\Http\Actions\Api\Ingredients\FilterAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IngredientListRequest;
use App\Http\Resources\Api\IngredientCollection;
use App\Http\Resources\Api\IngredientResource;
use App\Models\Ingredient;
use Elastic\ScoutDriverPlus\Builders\SearchParametersBuilder;
use OpenApi\Annotations as OA;

class IngredientController extends Controller
{
    /**
     * Display a listing of ingredients.
     *
     * @OA\Get(
     *    tags={"Ingredients API"},
     *    path="/api/ingredients",
     *    @OA\Parameter(
     *        name="page",
     *        in="query",
     *        description="Page number",
     *        @OA\Schema(type="integer"),
     *        allowEmptyValue="true"
     *    ),
     *    @OA\Parameter(
     *        name="per_page",
     *        in="query",
     *        description="Page size",
     *        @OA\Schema(type="integer"),
     *        allowEmptyValue="true"
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
     *        description="Name",
     *        @OA\Schema(type="string"),
     *        allowEmptyValue="true"
     *    ),
     *    @OA\Parameter(
     *       name="filter[ewg_score]",
     *       in="query",
     *       description="EWG Score (0-9)",
     *       @OA\Schema(type="integer"),
     *       allowEmptyValue="true"
     *    ),
     *    @OA\Parameter (
     *         name="filter[categories][0]",
     *         in="query",
     *         description="category 1 uuid",
     *         @OA\Schema(type="string"),
     *         allowEmptyValue="true"
     *    ),
     *    @OA\Parameter (
     *        name="filter[categories][1]",
     *        in="query",
     *        description="category 2 uuid",
     *        @OA\Schema(type="string"),
     *        allowEmptyValue="true"
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
     *        description="Display a listing of ingredients.",
     *        @OA\JsonContent(ref="#/components/schemas/Ingredient")
     *    ),
     *    @OA\Response(
     *        response=404,
     *        description="No ingredients.",
     *        @OA\JsonContent()
     *    )
     * )
     *
     * @param IngredientListRequest $request
     * @return IngredientCollection
     */
    public function index(IngredientListRequest $request, FilterAction $filterAction): IngredientCollection
    {
        if ($filters = $request->validated('filter')) {
            $filteredQuery = $filterAction($filters);
        }

        $query = Ingredient::searchQuery($filteredQuery ?? null)
            ->when(
                $request->has('sort'),
                fn (SearchParametersBuilder $builder) => $builder
                ->sort(
                    $request->getSortBy(),
                    $request->getSortDirection()
                )
            )
            ->load(['categories']);

        $paginated = $query->paginate(
            perPage: $request->validated('per_page', 20),
            page: $request->validated('page', 1),
        );

        // @phpstan-ignore-next-line
        $paginated->data = $paginated->onlyModels()->transform(function ($model) {
            return new IngredientResource($model);
        });

        return new IngredientCollection($paginated);
    }

    /**
     * Display the specified ingredient.
     *
     * @OA\Get(
     *     tags={"Ingredients API"},
     *     path="/api/ingredients/{slug}",
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         required=true,
     *         description="Ingredient slug",
     *         @OA\Schema(type="string"),
     *     ),
     *    @OA\Response(
     *        response=200,
     *        description="Display a selected ingredient.",
     *        @OA\JsonContent(ref="#/components/schemas/Ingredient")
     *    ),
     *    @OA\Response(
     *        response=404,
     *        description="Ingredient not found.",
     *        @OA\JsonContent(ref="#/components/schemas/Ingredient")
     *    )
     * )
     *
     * @param Ingredient $ingredient
     * @return IngredientResource
     */
    public function show(Ingredient $ingredient): IngredientResource
    {
        return new IngredientResource($ingredient);
    }
}
