<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBrandChangeRequest;
use App\Http\Requests\UpdateBrandChangeRequest;
use App\Http\Resources\Admin\BrandChangeRequestCollection;
use App\Http\Resources\Admin\BrandChangeRequestResource;
use App\Http\Resources\Admin\BrandResource;
use App\Mail\BrandChangeRequestRejectionMail;
use App\Models\Brand;
use App\Models\BrandChangeRequest;
use App\Models\Media;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

class BrandChangeRequestController extends Controller
{
    /**
     * List of Brand change request.
     * @OA\Get(
     *    tags={"BrandChangeRequests"},
     *    path="/admin/brand-change-requests",
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
     *      description="Display a listing of brand change requests.",
     *      @OA\JsonContent(ref="#/components/schemas/BrandChangeRequest"),
     *    ),
     * )
     *
     * Display a list of the resource.
     *
     * @param Request $request
     * @return BrandChangeRequestCollection
     */
    public function index(Request $request): BrandChangeRequestCollection
    {
        return new BrandChangeRequestCollection(
            BrandChangeRequest::query()
                ->orderByDesc('created_at')
                ->paginate($request->get('size', 20))
        );
    }

    /**
     * Show a selected Brand change request.
     * @OA\Get(
     *    tags={"BrandChangeRequests"},
     *    path="/admin/brand-change-requests/{brand_change_request}",
     *    security={{"bearer":{}}},
     *    @OA\MediaType(mediaType="application/json"),
     *    @OA\Parameter(
     *      name="brand_change_request",
     *      in="path",
     *      required=true,
     *      description="Brand Change Request ID",
     *      @OA\Schema(type="integer"),
     *    ),
     *    @OA\Response(
     *      response=200,
     *      description="Display a selected Brand Change Request.",
     *      @OA\JsonContent(ref="#/components/schemas/BrandChangeRequest"),
     *    ),
     *    @OA\Response(
     *        response=404,
     *        description="Brand change request not found.",
     *        @OA\JsonContent(),
     *    ),
     * )
     * @param BrandChangeRequest $brandChangeRequest
     * @return BrandChangeRequestResource
     */
    public function show(BrandChangeRequest $brandChangeRequest): BrandChangeRequestResource
    {
        $brandChangeRequest->image = isset($brandChangeRequest->data['image']['id'])
            ? Media::find($brandChangeRequest->data['image']['id'])
            : null;
        $brandChangeRequest->load('brand');

        return new BrandChangeRequestResource($brandChangeRequest);
    }

    /**
     * Store a Brand change request.
     *
     * @OA\Post (
     *     tags={"BrandChangeRequests"},
     *     path="/admin/brand-change-requests",
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title", "created_by", "image[id]"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     description="Title",
     *                 ),
     *                 @OA\Property(
     *                     property="brand_id",
     *                     type="integer",
     *                     description="Brand Id.",
     *                 ),
     *                 @OA\Property(
     *                     property="url",
     *                     type="string",
     *                     description="URL of the brand",
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     description="Description of the brand",
     *                 ),
     *                 @OA\Property(
     *                     property="image[id]",
     *                     type="integer",
     *                     description="Image ID",
     *                 ),
     *                 @OA\Property(
     *                     property="where_to_find",
     *                     type="string",
     *                     description="Where to find",
     *                 ),
     *                 @OA\Property(
     *                     property="created_by",
     *                     type="integer",
     *                     description="Create user ID",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Brand change request created.",
     *         @OA\JsonContent(ref="#/components/schemas/BrandChangeRequest"),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error in fields.",
     *         @OA\JsonContent(),
     *     ),
     * )
     * @param StoreBrandChangeRequest $request
     * @return BrandChangeRequestResource
     */
    public function store(StoreBrandChangeRequest $request): BrandChangeRequestResource
    {
        $brandChangeRequest = BrandChangeRequest::create([
            'data' => $request->validated(),
            'brand_id' => $request->brand_id ?? null,
        ]);

        return new BrandChangeRequestResource($brandChangeRequest);
    }

    /**
     * Update a brand change request.
     *
     * @OA\Put (
     *     tags={"BrandChangeRequests"},
     *     path="/admin/brand-change-requests/{brand_change_request}",
     *     security={{"bearer":{}}},
     *    @OA\Parameter(
     *      name="brand_change_request",
     *      in="path",
     *      description="Brand change request id.",
     *      @OA\Schema(type="integer"),
     *    ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *            mediaType="application/x-www-form-urlencoded",
     *            @OA\Schema(
     *                 required={"title", "brand_id", "created_by", "image[id]"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     description="Title",
     *                 ),
     *                 @OA\Property(
     *                     property="brand_id",
     *                     type="integer",
     *                     description="brand_id",
     *                 ),
     *                 @OA\Property(
     *                     property="url",
     *                     type="string",
     *                     description="URL of the brand",
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     description="Description of the brand",
     *                 ),
     *                 @OA\Property(
     *                     property="image[id]",
     *                     type="integer",
     *                     description="Image ID",
     *                 ),
     *                 @OA\Property(
     *                     property="where_to_find",
     *                     type="string",
     *                     description="Where to find",
     *                 ),
     *                 @OA\Property(
     *                     property="created_by",
     *                     type="integer",
     *                     description="Create user ID",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Brand change request updated.",
     *         @OA\JsonContent(ref="#/components/schemas/BrandChangeRequest"),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error in fields.",
     *         @OA\JsonContent(),
     *     ),
     * )
     *
     * @param UpdateBrandChangeRequest $request
     * @param BrandChangeRequest $brandChangeRequest
     * @return BrandChangeRequestResource
     */
    public function update(UpdateBrandChangeRequest $request, BrandChangeRequest $brandChangeRequest): BrandChangeRequestResource
    {
        $brandChangeRequest->data = $request->validated();
        $brandChangeRequest->brand_id = $request->brand_id ?? null;
        $brandChangeRequest->save();

        return new BrandChangeRequestResource($brandChangeRequest);
    }

    /**
     * Approve a Brand change request.
     *
     * @OA\Post (
     *     tags={"BrandChangeRequests"},
     *     path="/admin/brand-change-requests/{brand_change_request}/approve",
     *     security={{"bearer":{}}},
     *     @OA\MediaType(mediaType="application/json"),
     *     @OA\Parameter(
     *       name="brand_change_request",
     *       in="path",
     *       required=true,
     *       description="integer",
     *       @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *       response=200,
     *       description="Brand change request approved.",
     *       @OA\JsonContent(),
     *     ),
     * )
     *
     * @param BrandChangeRequest $brandChangeRequest
     * @return BrandResource
     */
    public function approve(BrandChangeRequest $brandChangeRequest): BrandResource
    {
        $brand = DB::transaction(function () use ($brandChangeRequest) {
            $data = $brandChangeRequest->data;
            $data['image_id'] = $data['image']['id'];
            $brand = Brand::updateOrCreate(['id' => $brandChangeRequest->brand_id ?? null], $data);
            $brandChangeRequest->delete();

            return $brand;
        });

        return new BrandResource($brand);
    }

    /**
     * Reject a Brand change request.
     *
     * @OA\Post (
     *     tags={"BrandChangeRequests"},
     *     path="/admin/brand-change-requests/{brand_change_request}/reject",
     *     security={{"bearer":{}}},
     *     @OA\MediaType(mediaType="application/json"),
     *    @OA\Parameter(
     *      name="brand_change_request",
     *      in="path",
     *      required=true,
     *      description="integer",
     *      @OA\Schema(type="integer"),
     *    ),
     *     @OA\Response(
     *         response=200,
     *         description="Brand change request rejected",
     *         @OA\JsonContent(),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Brand Change Request not found.",
     *         @OA\JsonContent(),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="E-mail send failed.",
     *         @OA\JsonContent(),
     *     ),
     * )
     *
     * @param BrandChangeRequest $brandChangeRequest
     * @return JsonResponse
     */
    public function reject(BrandChangeRequest $brandChangeRequest): JsonResponse
    {
        $user = User::findOrFail($brandChangeRequest->data['created_by']);

        try {
            Mail::send(new BrandChangeRequestRejectionMail($brandChangeRequest, $user));
        } catch (\Exception $e) {
            return response()->json(['error' => 'E-mail küldés sikertelen'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        $brandChangeRequest->delete();

        return response()->json([], Response::HTTP_OK);
    }
}
