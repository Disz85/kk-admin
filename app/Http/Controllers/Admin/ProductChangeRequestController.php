<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProductChangeRequest;
use App\Http\Resources\Admin\ProductChangeRequestCollection;
use App\Http\Resources\Admin\ProductChangeRequestResource;
use App\Http\Resources\Admin\ProductResource;
use App\Mail\ProductChangeRequestRejectionMail;
use App\Models\Ingredient;
use App\Models\Media;
use App\Models\Product;
use App\Models\ProductChangeRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

class ProductChangeRequestController extends Controller
{
    /**
     * List of Product change request.
     *
     * @OA\Get(
     *    tags={"ProductChangeRequests"},
     *    path="/admin/product-change-requests",
     *    security={{"bearer":{}}},
     *    @OA\MediaType(mediaType="application/json"),
     *    @OA\Parameter(
     *      name="page",
     *      in="query",
     *      description="Page number",
     *      @OA\Schema(type="integer"),
     *    ),
     *    @OA\Response(
     *      response=200,
     *      description="Display a listing of prdouct change requests.",
     *      @OA\JsonContent(ref="#/components/schemas/ProductChangeRequest"),
     *    ),
     * )
     *
     * Display a list of the resource.
     *
     * @param Request $request
     * @return ProductChangeRequestCollection
     */
    public function index(Request $request): ProductChangeRequestCollection
    {
        return new ProductChangeRequestCollection(
            ProductChangeRequest::query()
                ->orderByDesc('created_at')
                ->paginate($request->get('size', 20))
        );
    }

    /**
     * Show a selected Product change request.
     * @OA\Get(
     *    tags={"ProductChangeRequests"},
     *    path="/admin/product-change-requests/{product_change_request}",
     *    security={{"bearer":{}}},
     *    @OA\Parameter(
     *      name="product_change_request",
     *      in="path",
     *      required=true,
     *      description="Product Change Request ID",
     *      @OA\Schema(type="integer"),
     *    ),
     *    @OA\Response(
     *      response=200,
     *      description="Display a selected Product Change Request.",
     *      @OA\JsonContent(ref="#/components/schemas/ProductChangeRequest"),
     *    ),
     *    @OA\Response(
     *      response=404,
     *      description="Product change request not found.",
     *      @OA\JsonContent(),
     *    )
     * )
     * @param ProductChangeRequest $productChangeRequest
     * @return ProductChangeRequestResource
     */
    public function show(ProductChangeRequest $productChangeRequest): ProductChangeRequestResource
    {
        $productChangeRequest->image = isset($productChangeRequest->data['image']['id'])
            ? Media::find($productChangeRequest->data['image']['id'])
            : null;
        $productChangeRequest->load(['product']);

        return new ProductChangeRequestResource($productChangeRequest);
    }

    /**
     * Approve a Product change request.
     *
     * @OA\Post (
     *     tags={"ProductChangeRequests"},
     *     path="/admin/product-change-requests/{product_change_request}/approve",
     *     security={{"bearer":{}}},
     *     @OA\MediaType(mediaType="application/json"),
     *    @OA\Parameter(
     *      name="product_change_request",
     *      in="path",
     *      required=true,
     *      description="integer",
     *      @OA\Schema(type="integer"),
     *    ),
     *     @OA\Response(
     *         response=200,
     *         description="Product change request approved",
     *         @OA\JsonContent()
     *     ),
     * )
     *
     * @param ProductChangeRequest $productChangeRequest
     * @return ProductResource
     */
    public function approve(ProductChangeRequest $productChangeRequest): ProductResource
    {
        $product = DB::transaction(function () use ($productChangeRequest) {
            $data = $productChangeRequest->data;
            if (data_get($data, "brand.id")) {
                $data['brand_id'] = data_get($data, "brand.id");
            }
            if (data_get($data, "image.id")) {
                $data['image_id'] = data_get($data, "image.id");
            }

            $product = Product::updateOrCreate(['id' => $productChangeRequest->product->id ?? null], $data);

            $ingredients = [];
            foreach ($productChangeRequest->data['ingredients'] ?? [] as $newIngredientName) {
                $ingredient = Ingredient::firstOrCreate(
                    ['name' => $newIngredientName]
                );
                $ingredients[] = $ingredient->id;
            }

            if ($ingredients) {
                $product->ingredients()->sync($ingredients);
            }
            if (! $productChangeRequest->product) {
                // new product
                $categories = [];
                if ($productCategory = data_get($data, 'category.id')) {
                    $categories[] = $productCategory;
                }

                if ($skinTypes = data_get($data, 'skin_types.*.id')) {
                    $categories = array_merge($categories, $skinTypes);
                }

                if ($skinConcerns = data_get($data, 'skin_concerns.*.id')) {
                    $categories = array_merge($categories, $skinConcerns);
                }

                if ($hairProblems = data_get($data, 'hair_problems.*.id')) {
                    $categories = array_merge($categories, $hairProblems);
                }
                $product->categories()->sync($categories);
            }
            $productChangeRequest->delete();

            return $product->refresh();
        });

        return new ProductResource($product->loadMissing(['tags', 'categories', 'ingredients']));
    }

    /**
     * Reject a Product change request.
     *
     * @OA\Post (
     *     tags={"ProductChangeRequests"},
     *     path="/admin/product-change-requests/{product_change_request}/reject",
     *     security={{"bearer":{}}},
     *     @OA\MediaType(mediaType="application/json"),
     *    @OA\Parameter(
     *      name="product_change_request",
     *      in="path",
     *      required=true,
     *      description="integer",
     *      @OA\Schema(type="integer"),
     *    ),
     *     @OA\Response(
     *         response=200,
     *         description="Product change request rejected",
     *         @OA\JsonContent()
     *     ),
     * )
     *
     * @param ProductChangeRequest $productChangeRequest
     * @return JsonResponse
     */
    public function reject(ProductChangeRequest $productChangeRequest): JsonResponse
    {
        $productChangeRequest->load('user');

        try {
            Mail::send(new ProductChangeRequestRejectionMail($productChangeRequest));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Email küldés sikertelen'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        $productChangeRequest->delete();

        return response()->json([], Response::HTTP_OK);
    }

    /**
     * Update a product change request.
     *
     * @OA\Put (
     *    tags={"ProductChangeRequests"},
     *    path="/admin/product-change-requests/{product_change_request}",
     *    security={{"bearer":{}}},
     *    @OA\Parameter(
     *      name="product_change_request",
     *      in="path",
     *      description="integer",
     *      @OA\Schema(type="string"),
     *    ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="name",
     *                 ),
     *                 @OA\Property(
     *                     property="brand[id]",
     *                     type="integer",
     *                     description="brand[id]",
     *                 ),
     *                 @OA\Property(
     *                     property="price",
     *                     type="string",
     *                     description="price",
     *                 ),
     *                 @OA\Property(
     *                     property="size",
     *                     type="string",
     *                     description="size",
     *                 ),
     *                 @OA\Property(
     *                     property="where_to_find",
     *                     type="string",
     *                     description="where_to_find",
     *                 ),
     *                 @OA\Property(
     *                     property="description[0]",
     *                     type="string",
     *                     description="Desciption",
     *                 ),
     *                 @OA\Property (
     *                     property="image[id]",
     *                     type="integer",
     *                     description="Image ID",
     *                 ),
     *                 @OA\Property (
     *                     property="category[id]",
     *                     type="integer",
     *                     description="category id",
     *                 ),
     *                 @OA\Property (
     *                     property="ingredients[0]",
     *                     type="integer",
     *                     description="ingredient 1 name",
     *                 ),
     *                 @OA\Property (
     *                     property="ingredients[1]",
     *                     type="integer",
     *                     description="ingredient 2 name",
     *                 ),
     *                 @OA\Property (
     *                     property="skin_types[0][id]",
     *                     type="integer",
     *                     description="Skin type 1 id",
     *                 ),
     *                 @OA\Property (
     *                     property="skin_types[1][id]",
     *                     type="integer",
     *                     description="Skin type 2 id",
     *                 ),
     *                 @OA\Property (
     *                     property="skin_concerns[0][id]",
     *                     type="integer",
     *                     description="Skin concerns 1 id",
     *                 ),
     *                 @OA\Property (
     *                     property="skin_concerns[1][id]",
     *                     type="integer",
     *                     description="Skin concerns 2 id",
     *                 ),
     *                 @OA\Property (
     *                     property="hair_problems[0][id]",
     *                     type="integer",
     *                     description="Hair problems 1 id",
     *                 ),
     *                 @OA\Property (
     *                     property="hair_problems[1][id]",
     *                     type="integer",
     *                     description="Hair problems 2 id",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product change request created.",
     *         @OA\JsonContent(ref="#/components/schemas/ProductChangeRequest"),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error in fields.",
     *         @OA\JsonContent(),
     *     ),
     * )
     *
     * @param UpdateProductChangeRequest $request
     * @param ProductChangeRequest $productChangeRequest
     * @return ProductChangeRequestResource
     */
    public function update(UpdateProductChangeRequest $request, ProductChangeRequest $productChangeRequest): ProductChangeRequestResource
    {
        $originalData = $productChangeRequest->data;
        $productChangeRequest->data = array_merge(
            $request->validated(),
            data_get($originalData, 'created_by') ? ['created_by' => $originalData['created_by']] : [],
            data_get($originalData, 'ingredients_by') ? ['ingredients_by' => $originalData['ingredients_by']] : [],
        );
        $productChangeRequest->save();

        return new ProductChangeRequestResource($productChangeRequest);
    }
}
