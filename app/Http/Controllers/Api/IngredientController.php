<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\IngredientResource;
use App\Http\Resources\Api\IngredientCollection;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Spatie\QueryBuilder\QueryBuilder;

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
     * @param Request $request
     * @return IngredientCollection
     */
    public function index(Request $request): IngredientCollection
    {
        return new IngredientCollection(
            QueryBuilder::for(Ingredient::class)
                ->allowedFields([ 'uuid', 'name' ])
                ->allowedFilters([ 'name' ])
                ->allowedIncludes([ 'products', 'categories' ])
                ->defaultSort('name')
                ->allowedSorts([ 'name' ])
                ->paginate($request->get('per_page', 20))
                ->appends($request->query())
        );
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
