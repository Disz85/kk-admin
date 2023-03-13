<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\DeleteProductChangeRequestRequest;
use App\Http\Requests\Api\ShowProductChangeRequestRequest;
use App\Http\Requests\Api\StoreProductChangeRequest;
use App\Http\Requests\Api\StoreProductPatchRequest;
use App\Http\Requests\Api\UpdateProductChangeRequestRequest;
use App\Http\Resources\Api\ProductChangeRequestCollection;
use App\Http\Resources\Api\ProductChangeRequestResource;
use App\Models\ProductChangeRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ProductChangeRequestController extends Controller
{
    /**
     * Store a product change request.
     *
     * @OA\Post (
     *     tags={"ProductChangeRequests API"},
     *     path="/api/product-change-requests",
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name", "brand[id]", "category[id]", "description[0]"},
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
     * @param StoreProductChangeRequest $request
     * @return ProductChangeRequestResource
     */
    public function store(StoreProductChangeRequest $request): ProductChangeRequestResource
    {
        $productChangeRequest = ProductChangeRequest::create([
            'data' => array_merge(
                $request->validated(),
                ['created_by' => Auth::user()->id ],
                data_get($request->validated(), 'ingredients')
                    ? ['ingredients_by' => Auth::user()->id]
                    : [],
            ),
            'user_id' => Auth::user()->id,
        ]);

        return new ProductChangeRequestResource($productChangeRequest);
    }

    /**
     * Store a product change request.
     *
     * @OA\Patch (
     *     tags={"ProductChangeRequests API"},
     *     path="/api/product-change-requests/store-patch",
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 required={"product_id"},
     *                 @OA\Property(
     *                     property="product_id",
     *                     type="int",
     *                     description="product_id",
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
     * @param StoreProductPatchRequest $request
     * @return ProductChangeRequestResource
     */
    public function storePatch(StoreProductPatchRequest $request): ProductChangeRequestResource
    {
        $productChangeRequest = ProductChangeRequest::create([
            'data' => array_merge(
                $request->validated(),
                data_get($request->validated(), 'ingredients')
                    ? ['ingredients_by' => Auth::user()->id]
                    : [],
            ),
            'product_id' => $request->product_id,
            'user_id' => Auth::user()->id,
        ]);

        return new ProductChangeRequestResource($productChangeRequest);
    }

    /**
     * List of Product change request.
     *
     * @OA\Get(
     *    tags={"ProductChangeRequests API"},
     *    path="/api/product-change-requests",
     *    security={{"bearer":{}}},
     *    @OA\MediaType(mediaType="application/json"),
     *    @OA\Response(
     *      response=200,
     *      description="Display a listing of product change requests.",
     *      @OA\JsonContent(ref="#/components/schemas/ProductChangeRequest"),
     *    ),
     * )
     *
     * Display a list of the resource.
     *
     * @return ProductChangeRequestCollection
     */
    public function index()
    {
        $user = Auth::user()->load('productChangeRequests');

        return new ProductChangeRequestCollection($user->productChangeRequests);
    }

    /**
     * Show a selected Product Change Request.
     *
     * @OA\Get(
     *    tags={"ProductChangeRequests API"},
     *    path="/api/product-change-requests/{product_change_request}",
     *    security={{"bearer":{}}},
     *    @OA\Parameter(
     *        name="product_change_request",
     *        in="path",
     *        required=true,
     *        description="Product Change Request ID",
     *        @OA\Schema(type="integer"),
     *    ),
     *    @OA\Response(
     *        response=200,
     *        description="Display a selected Product Change Request.",
     *        @OA\JsonContent(ref="#/components/schemas/ProductChangeRequest"),
     *    ),
     *    @OA\Response(
     *        response=404,
     *        description="Product Change Request not found.",
     *        @OA\JsonContent(),
     *    ),
     *    @OA\Response(
     *        response=403,
     *        description="Unauthorized, not your Resource",
     *        @OA\JsonContent(),
     *    )
     * )
     * @param ShowProductChangeRequestRequest $productChangeRequestRequest
     * @param ProductChangeRequest $productChangeRequest
     * @return ProductChangeRequestResource
     */
    public function show(ShowProductChangeRequestRequest $productChangeRequestRequest, ProductChangeRequest $productChangeRequest): ProductChangeRequestResource
    {
        return new ProductChangeRequestResource($productChangeRequest->load('product'));
    }

    /**
     * Update a product change request.
     *
     * @OA\Put (
     *     tags={"ProductChangeRequests API"},
     *     path="/api/product-change-requests/{product_change_request}",
     *     security={{"bearer":{}}},
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
     *                 required={},
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
     *         response=200,
     *         description="Product change request changed.",
     *         @OA\JsonContent(ref="#/components/schemas/ProductChangeRequest"),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error in fields.",
     *         @OA\JsonContent(),
     *     ),
     * )
     *
     * @param UpdateProductChangeRequestRequest $request
     * @param ProductChangeRequest $productChangeRequest
     * @return ProductChangeRequestResource
     */
    public function update(UpdateProductChangeRequestRequest $request, ProductChangeRequest $productChangeRequest): ProductChangeRequestResource
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

    /**
     * Remove the specified resource from storage.
     *
     * @OA\Delete (
     *     tags={"ProductChangeRequests API"},
     *     path="/api/product-change-requests/{product_change_request}",
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(mediaType="application/x-www-form-urlencoded"),
     *     ),
     *    @OA\Parameter(
     *      name="product_change_request",
     *      in="path",
     *      description="Product Change Request ID",
     *      @OA\Schema(type="integer"),
     *    ),
     *    @OA\Response(
     *      response=204,
     *      description="Product Change Request deleted.",
     *      @OA\JsonContent(),
     *    ),
     *    @OA\Response(
     *      response=404,
     *      description="Product Change Request not found.",
     *      @OA\JsonContent(),
     *     ),
     *    @OA\Response(
     *        response=403,
     *        description="Unauthorized, not your Resource",
     *        @OA\JsonContent(),
     *    ),
     * )
     *
     * @param DeleteProductChangeRequestRequest $request
     * @param ProductChangeRequest $productChangeRequest
     * @return JsonResponse
     * @throws \Throwable
     */
    public function destroy(DeleteProductChangeRequestRequest $request, ProductChangeRequest $productChangeRequest): JsonResponse
    {
        $productChangeRequest->deleteOrFail();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
