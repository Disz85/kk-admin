<?php

namespace App\Http\Controllers\Api;

use App\Http\Actions\Api\Ingredients\FilterAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IngredientListRequest;
use App\Http\Resources\Api\IngredientCollection;
use App\Http\Resources\Api\IngredientResource;
use App\Models\Ingredient;
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
     *        name="filter[name]",
     *        in="query",
     *        description="Name",
     *        @OA\Schema(type="string")
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
            ->sort($request->getSortBy(), $request->getSortDirection())
            ->load(['categories']);

        $paginated = $query->paginate(
            perPage: $request->validated('per_page', 20),
            page: $request->validated('page', 1),
        );

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
     *     path="/api/ingredients/{ingredient}",
     *     @OA\Parameter(
     *         name="ingredient",
     *         in="path",
     *         required=true,
     *         description="Ingredient UUID",
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
